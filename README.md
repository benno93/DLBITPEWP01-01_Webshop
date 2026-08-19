# DLBITPEWP01-01 – Webshop

Laravel 13 (PHP 8.4) / Inertia.js + Vue 3 / Vite. Diese README beschreibt, wie die Anwendung lokal gestartet wird und wie die Docker-Variante gebaut, gestartet und bei Änderungen aktualisiert wird.

## Voraussetzungen

Für die lokale Entwicklung: PHP 8.4 oder neuer, Composer 2.x, Node.js (empfohlen Version 18 oder neuer) mit npm, sowie Git. Für die Docker-Variante zusätzlich Docker – entweder Docker Desktop oder, wie in diesem Projekt verwendet, die Docker-CLI per Homebrew zusammen mit [Colima](https://github.com/abiosoft/colima) als Container-Runtime unter macOS. Der Docker-Daemon (bei Colima per `colima start`) muss laufen, bevor `docker`-Befehle funktionieren.

## 1. Lokale Installation über das GitHub-Repository

1. Repository klonen und in den Projektordner wechseln:
   ```
   git clone https://github.com/benno93/DLBITPEWP01-01_Webshop.git
   cd DLBITPEWP01-01_Webshop
   ```

2. PHP-Abhängigkeiten installieren:
   ```
   composer install
   ```

3. Umgebungsdatei anlegen und Anwendungsschlüssel generieren:
   ```
   cp .env.example .env
   php artisan key:generate
   ```

4. SQLite-Datenbankdatei anlegen und Migrationen samt Beispieldaten einspielen:
   ```
   touch database/database.sqlite
   php artisan migrate --seed
   ```

5. Frontend-Abhängigkeiten installieren:
   ```
   npm install
   ```

6. Anwendung starten. Am einfachsten über den in `composer.json` hinterlegten Sammelbefehl, der PHP-Entwicklungsserver, Queue-Worker, Log-Ausgabe (Pail) und den Vite-Dev-Server gleichzeitig startet:
   ```
   composer run dev
   ```
   Alternativ einzeln in getrennten Terminals: `php artisan serve` und `npm run dev`.

7. Im Browser `http://localhost:8000` aufrufen.

## 2. Docker: Anwendung starten

Es gibt zwei Wege, die Anwendung per Docker zu starten:

**A) Fertiges Image von Docker Hub verwenden (schnellster Weg, kein lokaler Build nötig)**
```
docker pull henningsander/webshop:latest
docker run -p 8000:8000 henningsander/webshop:latest
```

**B) Image selbst aus dem Dockerfile bauen**
```
docker build -t webshop .
docker run -p 8000:8000 webshop
```
(Docker-Daemon muss laufen, siehe Voraussetzungen.)

In beiden Fällen ist die Anwendung danach unter `http://localhost:8000` erreichbar.

Der Container nutzt intern `php artisan serve` als Webserver – für lokales Ausprobieren/Testen völlig ausreichend, für echten Produktivbetrieb mit hoher Last wäre ein Setup mit separaten Containern für PHP-FPM, einen echten Webserver (Nginx) und eine externe Datenbank statt SQLite sinnvoller.

## 3. Änderungen einpflegen (Code ändern & Docker neu bauen)

Das `Dockerfile` holt den Anwendungscode per `git clone` direkt von GitHub, **nicht** aus dem lokalen Projektordner. Eine Code-Änderung muss also zuerst ganz normal committet und gepusht werden, bevor sie im nächsten Docker-Build ankommt:
```
git add .
git commit -m "Beschreibung der Änderung"
git push
```

Danach das Image neu bauen. Wichtig: Docker entscheidet anhand des *Texts* der Dockerfile-Befehle, ob ein Schritt aus dem Cache wiederverwendet wird – nicht danach, ob sich der Inhalt hinter einer URL (wie beim `git clone`) tatsächlich geändert hat. Ein einfaches `docker build -t webshop .` kann daher fälschlicherweise den alten, gecachten Repository-Stand wiederverwenden. Um das zu erzwingen, den Cache beim Neubau verwerfen:
```
docker build --no-cache -t webshop .
```

Falls die Änderung auch im auf Docker Hub veröffentlichten Image ankommen soll, muss das neu gebaute Image zusätzlich erneut hochgeladen werden:
```
docker login
docker tag webshop henningsander/webshop:latest
docker push henningsander/webshop:latest
```

### Kurzer Überblick über die Funktionsweise des Dockerfiles

Als Basis dient `alpine:3.22`, ein schlankes Linux-Image, auf dem per `apk` (Alpines Paketmanager) PHP 8.4 samt der von Laravel benötigten Erweiterungen, Node.js/npm, Git und curl installiert werden. Composer wird bewusst nicht über das Alpine-Paket installiert, sondern offiziell per Installer-Skript direkt für PHP 8.4, um Versionskonflikte zu vermeiden. Anschließend wird der Anwendungscode per `git clone` von GitHub geholt, mit `composer install` und `npm install` werden die Abhängigkeiten installiert, `npm run build` kompiliert die Vite/Vue/Tailwind-Assets. Danach läuft die Laravel-Grundkonfiguration einmalig beim Image-Bau (`.env` anlegen, Anwendungsschlüssel generieren, SQLite-Datenbank anlegen, Migrationen und Seeder ausführen). Zum Schluss dokumentiert `EXPOSE 8000` den verwendeten Port, und `CMD` startet beim Containerstart den Laravel-Entwicklungsserver mit `--host=0.0.0.0`, damit er auch von außerhalb des Containers erreichbar ist. Die tatsächliche Portfreigabe nach außen passiert erst beim `docker run` über den Parameter `-p 8000:8000`.

**Hinweis zu privaten Repositories**: Der `git clone`-Befehl im Dockerfile funktioniert nur, solange das GitHub-Repository öffentlich ist. Bei einem privaten Repo müsste stattdessen entweder ein Zugangstoken eingebunden werden (aus Sicherheitsgründen nicht empfohlen, falls das Image geteilt wird) oder der Code per `COPY . .` aus dem lokalen Build-Kontext übernommen werden.
