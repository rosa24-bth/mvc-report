![Banner Image](./assets/images/PHP_Symfony.png)

# mvc/report

Detta är information om report-sidan av mitt arbete i kursen mvc. Det finns en enkel webbplats med kort presentation av mig och kursen, samt redovisningstexter för de olika kursmomenten.

---

## Översikt

- **Innehåll:** Presentation, information om kursen, redovisningstexter och JSON API.
- **Tekniker som används:** Symfony, Twig, Encore, Git.

---

## Struktur för kursmoment 01-06

- public/ – Innehåller publika delar såsom bilder.
- src/ – Här ligger själva PHP-koden för controllers.
- templates/ – Twig-mallarna för layout.
- assets/ – CSS, JavaScript och bilder hanterade med Encore.

---

## Hur du kommer igång

### Klona projektet
```bash
git clone git@github.com:rosa24-bth/mvc-report.git
```

### Installera beroenden
```bash
composer install

npm install
```

### Starta lokal Symfony server
```bash
symfony server:start
```

Öppna sen https://127.0.0.1:8000 i din webbläsare.

### Badges från Scrutinizer

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rosa24-bth/mvc-report/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/rosa24-bth/mvc-report/?branch=main)

[![Code Coverage](https://scrutinizer-ci.com/g/rosa24-bth/mvc-report/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/rosa24-bth/mvc-report/?branch=main)

[![Build Status](https://scrutinizer-ci.com/g/rosa24-bth/mvc-report/badges/build.png?b=main)](https://scrutinizer-ci.com/g/rosa24-bth/mvc-report/build-status/main)

---

## Översikt projekt

- **Innehåll:**
  - Presentation, kursbeskrivning, redovisning för kmom01–kmom06
  - Ett avslutande projekt (kmom10) baserat på Agenda 2030
  - JSON API med data från SCB
- **Tekniker som används:** PHP, Symfony, Doctrine ORM, Twig, Webpack Encore, Git

---

## Projekt – Hållbar utveckling

Under `/proj` finns projektet som är del av kursmoment 10. Det fokuserar på att visualisera
indikatorer för mål 1 i Agenda 2030: **Ingen fattigdom**.

### Funktioner:

- Landningssida med sammanfattning av projektets syfte
- Visualiseringar i form av:
  - Tabeller: Pivotstruktur som jämför olika hushållstyper över tid
  - Grafer: Interaktiva linjediagram för två indikatorer
- JSON API med flera GET- och POST-endpoints
- Automatisk lista med tillgängliga grupper
- Reflekterande texter med analys av SCB:s data
- Separat layout/styling för proj-delen för att tydligt skilja den från report-sidan

### Indikatorer:

- Låg ekonomisk standard
- Långvarigt ekonomiskt bistånd

Källan till datan är SCB:s officiella indikatorer för hållbar utveckling:
https://www.scb.se/hallbar-utveckling/

---

## Struktur

- src/ – Innehåller kontroller, kommandoklasser m.m.
- templates/project/ – Twig-mallar för projektets sidor
- assets/styles/proj.css – Egen CSS för projektdelen
- public/api – JSON-respons via Symfony routes
- data/ – SCB-data importeras till SQLite via Doctrine
- tests/ – Enhetstester för projektets komponenter

---

## Så här kör du projektet

### Klona projektet
```bash
git clone git@github.com:rosa24-bth/mvc-report.git
```

### Installera beroenden
```bash
composer install

npm install
```

### Starta lokal Symfony server
```bash
symfony server:start
```
