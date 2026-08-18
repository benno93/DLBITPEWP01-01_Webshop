# =============================================================================
# Dockerfile für den Webshop (Laravel 13 / PHP 8.3 / Inertia + Vue 3 / Vite)
#
# Lernversion: Ein einzelner Container, basierend auf Alpine Linux, der den
# kompletten Code per "git clone" holt, alle Abhängigkeiten installiert,
# die Frontend-Assets baut und die Anwendung über den eingebauten
# Laravel-Entwicklungsserver startet.
#
# WICHTIG: "php artisan serve" ist laut Laravel-Doku NICHT für echten
# Produktivbetrieb gedacht, sondern nur für lokales Testen/Entwickeln.
# Für eine "richtige" Auslieferung würde man später auf PHP-FPM + Nginx
# in getrennten Containern umsteigen (siehe die andere Skizze weiter oben
# im Chatverlauf).
# =============================================================================


# -----------------------------------------------------------------------------
# SCHRITT 1: Basis-Image
# -----------------------------------------------------------------------------
# Wir pinnen eine konkrete Alpine-Version statt "alpine:latest", damit der
# Build immer reproduzierbar ist (siehe Erklärung weiter oben im Chat: ohne
# Tag würde automatisch ":latest" angenommen, das sich unbemerkt ändern kann).
FROM alpine:3.20


# -----------------------------------------------------------------------------
# SCHRITT 2 + 3: Paketindex aktualisieren und benötigte Software installieren
# -----------------------------------------------------------------------------
# Achtung: Alpine nutzt "apk" als Paketmanager, NICHT "apt"/"apt-get" (das ist
# Debian/Ubuntu-spezifisch). "apk update" aktualisiert nur den Paketindex,
# nicht die bereits installierten Pakete (kein "apk upgrade" nötig/üblich in
# einem Dockerfile, siehe Erklärung im Chat: Reproduzierbarkeit).
#
# Installiert werden:
#   - git                für "git clone" des Repos
#   - php83 + Erweiterungen, die Laravel laut composer.json/Standardbetrieb
#                        braucht (pdo_sqlite, weil DB_CONNECTION=sqlite in
#                        der .env.example steht; die anderen sind Laravel-
#                        Kernanforderungen)
#   - composer           PHP-Abhängigkeitsmanager
#   - nodejs, npm         für den Vite-Build der Frontend-Assets (Vue,
#                        Tailwind/daisyUI)
#
# "--no-cache" sorgt dafür, dass apk keinen lokalen Paket-Cache im Image-Layer
# hinterlässt -> kleineres Image, kein separates "apk update" nötig davor.
RUN apk update && apk add --no-cache \
        git \
        php83 \
        php83-pdo \
        php83-pdo_sqlite \
        php83-sqlite3 \
        php83-mbstring \
        php83-tokenizer \
        php83-xml \
        php83-dom \
        php83-simplexml \
        php83-xmlwriter \
        php83-ctype \
        php83-fileinfo \
        php83-bcmath \
        php83-session \
        php83-openssl \
        php83-phar \
        php83-curl \
        composer \
        nodejs \
        npm

# Damit der Befehl im Container einfach "php" statt "php83" heißt
# (Alpine installiert den Befehl unter dem Namen "php83").
RUN ln -s /usr/bin/php83 /usr/bin/php


# -----------------------------------------------------------------------------
# Arbeitsverzeichnis im Container festlegen
# -----------------------------------------------------------------------------
# Alle folgenden Befehle (RUN, CMD, ...) beziehen sich ab jetzt auf diesen
# Ordner im Container.
WORKDIR /app


# -----------------------------------------------------------------------------
# SCHRITT 4: Repo per git clone holen
# -----------------------------------------------------------------------------
# WICHTIG: URL unten durch die echte GitHub-URL des Webshop-Repos ersetzen.
# Falls das Repo PRIVAT ist, funktioniert ein einfacher "git clone" mit
# https:// so nicht -> dann bräuchtest du entweder ein Personal Access
# Token in der URL (Sicherheitsrisiko, wenn das Image irgendwo landet!)
# oder einen SSH-Key, der ins Image eingebaut wird. Für ein privates Repo
# ist es meist einfacher, wie im Chat erwähnt, den Code stattdessen per
# "COPY . ." aus dem bereits lokal ausgecheckten Ordner zu übernehmen.
RUN git clone https://github.com/benno93/DLBITPEWP01-01_Webshop .


# -----------------------------------------------------------------------------
# SCHRITT 5: Build-Prozess
# -----------------------------------------------------------------------------
# 5a) PHP-Abhängigkeiten installieren (liest composer.json/composer.lock)
RUN composer install --no-interaction --optimize-autoloader

# 5b) Node-Abhängigkeiten installieren (liest package.json/package-lock.json)
RUN npm install

# 5c) Frontend-Assets bauen (Vite kompiliert Vue-Komponenten, Tailwind/daisyUI
#     CSS etc. einmalig in den Ordner public/build)
RUN npm run build

# 5d) Laravel-Grundkonfiguration:
#     - .env existiert nach "git clone" NICHT, da sie üblicherweise in
#       .gitignore steht und nie mit ins Repo committet wird -> aus der
#       mitgelieferten .env.example erzeugen
#     - APP_KEY generieren (Laravel startet ohne gültigen Key nicht)
#     - SQLite-Datenbankdatei anlegen, da DB_CONNECTION=sqlite Standard ist
#     - Migrationen einmalig beim Image-Bau ausführen, damit die Datenbank
#       direkt mit den nötigen Tabellen im Image steckt
RUN cp .env.example .env \
    && php artisan key:generate \
    && touch database/database.sqlite \
    && php artisan migrate --force \
    && php artisan db:seed

# 5e) Symlink für öffentlich erreichbaren Storage-Ordner (z. B. hochgeladene
#     Bilder), falls die Anwendung das nutzt.
RUN php artisan storage:link


# -----------------------------------------------------------------------------
# SCHRITT 6 (Teil 1 von 2 - Vorbereitung): Port dokumentieren
# -----------------------------------------------------------------------------
# EXPOSE gibt NUR an, auf welchem Port die Anwendung im Container lauscht.
# Das ist reine Dokumentation und öffnet noch KEINEN Port nach außen!
# Die tatsächliche Freigabe passiert erst beim Start des Containers,
# siehe Hinweis ganz unten.
EXPOSE 8000


# -----------------------------------------------------------------------------
# SCHRITT 6 (Teil 2 von 2): Startbefehl beim Containerstart
# -----------------------------------------------------------------------------
# --host=0.0.0.0 ist entscheidend: ohne dieses Flag bindet "artisan serve"
# nur an 127.0.0.1 und wäre selbst mit freigegebenem Port von außerhalb
# des Containers NICHT erreichbar (siehe Erklärung im Chatverlauf).
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]


# =============================================================================
# So baust und startest du den Container:
#
#   docker build -t webshop .
#   docker run -p 8000:8000 webshop
#
# Der Parameter "-p 8000:8000" ist der Schritt, der den Port TATSÄCHLICH nach
# außen freigibt (Host-Port:Container-Port). Danach ist die Seite über
# http://<IP-des-Host-Rechners>:8000 erreichbar, z. B. http://localhost:8000
# auf demselben Rechner, oder über die tatsächliche Netzwerk-IP des Rechners
# von anderen Geräten im selben Netzwerk aus.
# =============================================================================
