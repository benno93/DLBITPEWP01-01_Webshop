<script setup>
import ShopLayout from '@/Layouts/ShopLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Impressum" />

    <ShopLayout>
        <div class="py-12 bg-base-200 min-h-screen">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-base-100 overflow-hidden shadow-xl sm:rounded-lg p-8">

                    <h1 class="text-3xl font-bold text-primary mb-6 border-b pb-4">
                        Impressum
                    </h1>

                    <div class="alert alert-info mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span><strong>Hinweis:</strong> Dieses Impressum dient ausschließlich zu Demonstrationszwecken im Rahmen eines Universitätsprojekts.</span>
                    </div>

                    <h2 class="text-xl font-semibold mt-6 mb-2">Angaben gemäß § 5 DDG</h2>
                    <p class="mb-4 leading-relaxed">
                        <strong>404 Not Found GmbH &amp; Co. KG</strong><br>
                        <span class="italic text-base-content/70">(Eine eingetragene Scheinfirma des Semesters)</span><br>
                        Code-Allee 101, Campus-Gebäude Z<br>
                        12345 Uni-Stadt
                    </p>

                    <h2 class="text-xl font-semibold mt-6 mb-2">Kontakt</h2>
                    <p class="mb-4 leading-relaxed">
                        <strong>Telefon:</strong> +49 (0) 40 123 456 78 <span class="italic text-base-content/70">(Erreichbar nur außerhalb der Vorlesungszeiten)</span><br>
                        <strong>E-Mail:</strong> <a href="mailto:support@kaffee-und-bugs.de" class="link link-primary">support@kaffee-und-bugs.de</a>
                    </p>

                    <h2 class="text-xl font-semibold mt-6 mb-2">Vertreten durch</h2>
                    <p class="mb-4 leading-relaxed">
                        <strong>Die Vorlesungs-Überlebenden:</strong><br>
                        Max Mustermann &amp; Student A
                    </p>

                    <h2 class="text-xl font-semibold mt-6 mb-2">Registereintrag</h2>
                    <p class="mb-4 leading-relaxed">
                        Eingetragen im Register der unbezahlten Überstunden.<br>
                        <strong>Registergericht:</strong> Amtsgericht der Prüfungskommission<br>
                        <strong>Registernummer:</strong> HRB 1.0 (Semester-Release)
                    </p>

                    <h2 class="text-xl font-semibold mt-6 mb-2">Umsatzsteuer-ID</h2>
                    <p class="mb-4 leading-relaxed">
                        Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz:<br>
                        <strong>DE 000 000 000</strong> <span class="italic text-base-content/70">(Wir zahlen in ECTS-Punkten)</span>
                    </p>

                    <h2 class="text-xl font-semibold mt-6 mb-2">EU-Streitschlichtung</h2>
                    <p class="mb-4 leading-relaxed">
                        Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit:<br>
                        <a href="https://ec.europa.eu/consumers/odr/" target="_blank" rel="noopener noreferrer" class="link link-primary break-all">https://ec.europa.eu/consumers/odr/</a><br>
                        Unsere E-Mail-Adresse finden Sie oben im Impressum.
                    </p>

                    <h2 class="text-xl font-semibold mt-6 mb-2">Haftungsausschluss für Code &amp; Noten</h2>
                    <ul class="list-disc list-inside space-y-2 mb-6 leading-relaxed">
                        <li><strong>Haftung für Inhalte:</strong> Die Inhalte unserer Seiten wurden mit größter Sorgfalt, viel Koffein und unter erheblichem Zeitdruck erstellt. Für die Richtigkeit, Vollständigkeit und Aktualität der Funktionen übernimmt der Entwickler vor der Bedenkzeit keine Gewähr.</li>
                        <li><strong>Haftung für Links:</strong> Unser Angebot enthält Links zu externen Websites Dritter (z. B. StackOverflow), auf deren Inhalte wir keinen Einfluss haben. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen oder durch ein <code class="bg-base-300 px-1 py-0.5 rounded text-sm">console.log('Oops')</code> ersetzen.</li>
                        <li><strong>Urheberrecht:</strong> Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Das Kopieren von StackOverflow ist gestattet, solange die Credits im Quellcode als Kommentar stehen bleiben.</li>
                    </ul>

                    <div class="mt-8 pt-4 border-t text-center text-sm text-base-content/60">
                        Erstellt für das Webshop-Projekt &bull; Uniprojekt
                    </div>

                </div>
            </div>
        </div>
    </ShopLayout>
</template>
