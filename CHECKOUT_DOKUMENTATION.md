# Checkout.vue – Was wurde gebaut?

Diese Datei dokumentiert die neue `resources/js/Pages/Checkout.vue`, die als erster Baustein der Checkout-Integration erstellt wurde. Ziel war ausschließlich das Frontend-Formular (Eingabe aller nötigen Bestelldaten) – die serverseitige Verarbeitung der Bestellung folgt in einem späteren Schritt.

## 1. Layout

Zwei-Spalten-Grid (`grid grid-cols-1 lg:grid-cols-2`, ab `lg` zweispaltig, darunter einspaltig gestapelt):

- **Linke Spalte** – vier nummerierte Formular-Abschnitte (jeweils eigene Card mit Badge 1–4):
  1. **Kundendaten**: Vorname, Nachname, E-Mail, Telefon (optional)
  2. **Lieferadresse**: Straße, Hausnummer, PLZ, Stadt, Land (Auswahl: Deutschland/Österreich/Schweiz) + Checkbox „Rechnungsadresse entspricht der Lieferadresse“ (standardmäßig aktiv). Wird sie deaktiviert, blendet sich darunter ein zweiter Adressblock „Rechnungsadresse“ mit denselben Feldern ein (`v-if`).
  3. **Versandart**: auswählbare Karten (Radio-Buttons) – Standardversand (4,99 €, 3–5 Werktage), Expressversand (9,99 €, 1–2 Werktage), Abholung im Markt (kostenlos). Ausgewählte Option wird farblich hervorgehoben.
  4. **Zahlungsart**: auswählbare Karten – PayPal, Kreditkarte, Rechnungskauf, Nachnahme.

- **Rechte Spalte** – **Bestellübersicht** (sticky, bleibt beim Scrollen sichtbar):
  - Liste aller Warenkorb-Artikel (Bild, Name, Menge × Einzelpreis, Zeilensumme)
  - Zwischensumme, Versandkosten (abhängig von gewählter Versandart), Gesamtsumme (fett, farblich hervorgehoben)

- **Ganz unten, über die volle Breite**: großer, farblich abgesetzter Call-to-Action-Bereich mit
  - zwei Pflicht-Checkboxen (AGB akzeptieren, Widerrufsbelehrung zur Kenntnis genommen)
  - großem Bestellbutton, der die aktuelle Gesamtsumme anzeigt und **erst aktiv wird, wenn beide Checkboxen angehakt sind**

Bei leerem Warenkorb wird statt des Formulars ein Hinweis mit Link zurück zum Katalog angezeigt.

## 2. Technische Umsetzung

- **Formular-State**: `useForm()` von Inertia.js (gleiches Pattern wie in `Register.vue`), enthält alle Kunden-, Adress-, Versand-, Zahlungs- und Rechtliche-Felder in einem Objekt. Dadurch sind `form.errors.*` und `form.processing` bereits vorbereitet für die spätere Serveranbindung (Validierungsfehler würden automatisch unter den jeweiligen Feldern erscheinen).
- **Warenkorb-Daten**: werden nicht neu geladen, sondern aus den global per Inertia geteilten Props gelesen (`page.props.cart`, siehe `HandleInertiaRequests.php`) – exakt wie es `Navbar.vue` und `Cart.vue` bereits tun.
- **Preisberechnung**: rein clientseitig über `computed()`-Properties:
  - `subtotal` = Summe aus Menge × Preis aller Warenkorbartikel
  - `shippingCost` = Preis der aktuell gewählten Versandart
  - `total` = `subtotal + shippingCost`
  - Preisformatierung (`formatPrice`) folgt der bestehenden Konvention im Projekt (`toFixed(2) + ' €'`, wie in `Cart.vue`/`Navbar.vue`).
- **Validierung**: HTML5-native (`required`-Attribute auf allen Pflichtfeldern, inkl. Radio-Buttons und Checkboxen) – der Browser blockiert das Absenden, solange Pflichtfelder fehlen. Der Bestellbutton ist zusätzlich per `:disabled` an die beiden rechtlichen Checkboxen gekoppelt.
- **Styling**: konsequent an die bestehenden Shop-Seiten angelehnt (DaisyUI-Klassen wie `btn`, `input input-bordered`, `radio`, `checkbox`, `card`-artige `bg-white rounded-lg shadow p-6`-Blöcke), keine neuen Komponenten oder Bibliotheken eingeführt.

## 3. Absichtlich noch nicht enthalten

- Es gibt **noch keinen Server-Endpunkt** `checkout.store`. Der Submit-Handler prüft das (`route().has('checkout.store')`) und zeigt stattdessen einen Hinweis, dass die Serveranbindung im nächsten Schritt folgt – so bricht die Seite nicht, obwohl der Button schon vollständig funktionsfähig aussieht.
- Grund: Die Datenbank-Struktur (`orders`-Tabelle) verlangt aktuell zwingend einen eingeloggten `user_id` – Gast-Checkout ist im bestehenden Schema nicht vorgesehen. Das ist eine Design-Entscheidung, die vor der Backend-Anbindung noch getroffen werden sollte (z. B. Gastbestellungen erlauben oder Login vor Checkout erzwingen).

## 4. Durchgeführte Tests

- Vue-Template wurde über den Vite-Dev-Server kompiliert (dabei wurde ein Schreibfehler – ein versehentlich mitgeschriebenes `</content>`-Tag am Dateiende – gefunden und behoben).
- Seite wurde headless im echten Chrome-Browser (Playwright) aufgerufen:
  - Artikel wurde reell in den Warenkorb gelegt, Checkout-Seite geladen → Layout und Preisberechnung korrekt (Screenshot geprüft), keine Konsolen-Fehler.
  - Umschalten der „Rechnungsadresse entspricht der Lieferadresse“-Checkbox blendet den zweiten Adressblock korrekt ein/aus.
  - Wechsel der Versandart aktualisiert die Gesamtsumme in der Bestellübersicht live.
  - Bestellbutton bleibt deaktiviert, bis beide rechtlichen Checkboxen angehakt sind.

## 5. Nächster logischer Schritt

Serverseitige Anbindung: Route `POST /checkout` (`checkout.store`), Controller zum Validieren der Eingaben und Anlegen von `Address`-, `Order`-, `OrderItem`- und `Payment`-Datensätzen, Leeren des Warenkorbs sowie eine Bestätigungsseite. Vorher sollte geklärt werden, ob Gast-Bestellungen möglich sein sollen oder ein Login vorausgesetzt wird.
