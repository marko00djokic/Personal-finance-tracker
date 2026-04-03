# Personal Finance Tracker — Glavni projektni dokument

## 1. Ideja i svrha projekta

Personal Finance Tracker je web aplikacija za praćenje ličnih finansija namijenjena
pojedincima koji žele da imaju potpun pregled nad svojim prihodima, rashodima i finansijskim
planiranjem na jednom mestu.

### Osnovne mogućnosti:

- **Budžet i balans** — Unos početnog budžeta i praćenje tekućeg stanja u realnom vremenu.
  Svaka potvrđena transakcija automatski koriguje balans.
- **Transakcije** — Unos, editovanje i brisanje dnevnih prihoda i rashoda sa datumom,
  iznosom, kategorijom i napomenom.
- **Planirane transakcije** — Jednokratne ili recurring (periodične) transakcije poput
  računa (Infostan, telefon, internet, struja). Sistem podseća na neplanirane i omogućava
  potvrdu ili preskakanje.
- **Kategorije** — Korisnički definisane kategorije sa ikonama i bojama (CRUD).
  Podrazumevane kategorije se nude pri registraciji.
- **Dashboard** — Grafički prikaz potrošnje po danima, nedeljama i mesecima.
  Pregled bilansa, nedavnih transakcija, upozorenja za planirane uplate.

### Predložene dodatne funkcionalnosti:

- **Budžetski limiti po kategoriji** — Korisnik može postaviti mesečni limit za svaku
  kategoriju i dobiti vizuelno upozorenje kada se limit približava (80%) ili pređe (100%).
- **Export podataka** — Export transakcija u CSV ili PDF format za odabrani period.
- **Pretraga i filtriranje** — Napredni filter transakcija po datumu, kategoriji, tipu
  (prihod/rashod) i iznosu.
- **Viševalutna podrška (buduće)** — Osnova za praćenje u više valuta (van scope-a v1).

---

## 2. Plan razvoja po fazama

Projekat je organizovan u 5 faza. Redosled je određen principom "od jezgra prema periferiji":
najpre stabilna infrastruktura i auth, zatim core poslovni entiteti, pa složenije features,
i na kraju vizualizacija i polishing.

---

### FAZA 1 — Setup, Auth & Osnovna infrastruktura

**Cilj:** Funkcionalan Laravel projekat sa autentifikacijom i bazom.

**Taskovi:**
- Instalacija Laravel 11 i konfiguracija `.env`
- Instalacija Laravel Breeze (Blade stack)
- Konfiguracija TailwindCSS i Vite
- Kreiranje inicijalnih migracija:
  - `users` (Breeze default + polje `current_balance`)
  - `categories` (id, user_id, name, icon, color, type: income/expense, timestamps)
  - `transactions` (id, user_id, category_id, type, amount, description, date, timestamps)
  - `planned_transactions` (id, user_id, category_id, type, amount, description,
    recurrence_type: none/daily/weekly/monthly/yearly, recurrence_day, next_due_date, is_active, timestamps)
- Seederi za podrazumevane kategorije
- Testiranje register/login/logout flow-a

---

### FAZA 2 — CRUD Transakcije & Kategorije

**Cilj:** Korisnik može da upravlja kategorijama i unosi transakcije.

**Taskovi:**
- `CategoryController` — CRUD (lista, kreiranje, editovanje, brisanje)
- Blade view-ovi za kategorije (index, create, edit)
- `TransactionController` — CRUD (lista, kreiranje, editovanje, brisanje)
- Blade view-ovi za transakcije (index, create, edit)
- Automatska korekcija `current_balance` pri kreiranju/brisanju/editovanju transakcije
- Pretraga i filtriranje transakcija (datum, kategorija, tip)
- Validacija na server strani (FormRequest klase)
- Flash poruke za uspeh/grešku

---

### FAZA 3 — Planirane i Recurring transakcije

**Cilj:** Podrška za planirane jednokratne i periodične transakcije.

**Taskovi:**
- `PlannedTransactionController` — CRUD
- Blade view-ovi za planirane transakcije (index, create, edit)
- Logika za računanje `next_due_date` na osnovu `recurrence_type` i `recurrence_day`
- Artisan komanda ili Scheduled Job koji svaki dan proverava dospele planirane transakcije
- Prikaz notifikacija/upozorenja za transakcije koje dospevaju danas ili u narednih 3 dana
- Akcija "Potvrdi transakciju" — kreira stvarnu transakciju i pomera `next_due_date`
- Akcija "Preskoči" — samo pomera `next_due_date` bez kreiranja transakcije

---

### FAZA 4 — Dashboard & Grafovi

**Cilj:** Vizuelni pregled finansijskog stanja.

**Taskovi:**
- `DashboardController` sa agregacijom podataka
- Prikaz tekućeg balansa, mesečnih prihoda i rashoda (summary cards)
- Grafikon potrošnje po danima/nedeljama/mesecima (Chart.js ili Alpine.js + SVG)
- Pita grafikon po kategorijama (rashodi ovog meseca po kategoriji)
- Widget za upcoming planirane transakcije (narednih 7 dana)
- Lista poslednjih 5-10 transakcija na dashboardu
- Indikatori budžetskih limita po kategoriji (progress bar)

---

### FAZA 5 — Polish, Validacije & Export

**Cilj:** Produkcijska gotovost — UX polish, robustnost, export.

**Taskovi:**
- Export transakcija u CSV (odabrani period)
- Export u PDF (Laravel DomPDF ili Browsershot)
- Responsive dizajn audit (mobilni prikaz)
- Error handling i 404/500 stranice
- Rate limiting na form submit akcijama
- Kompletiranje `technical_manual.md` i `user_manual.md`
- E2E test plan dokument
- Finalna revizija svih validacija

---

## 3. Promptovi po fazama

---

## FAZA 1 PROMPT — Setup, Auth & Osnovna infrastruktura

```
Čitaj CLAUDE.md i .claude/progress.md pre nego što počneš.

## Zadatak — Faza 1: Laravel Setup, Auth & Inicijalne migracije

Radimo na projektu Personal Finance Tracker. Sve konvencije su u .claude/project.md.

### Šta treba uraditi:

1. **Laravel instalacija**
   - Instaliraj Laravel 11 u trenutni direktorijum (ne u poddirektorijum)
   - Podesi .env.example sa svim potrebnim varijablama (DB, APP_NAME itd.)
   - APP_NAME="Personal Finance Tracker"

2. **Laravel Breeze**
   - Instaliraj Breeze sa Blade + Alpine.js stack-om
   - Prilagodi login/register view-ove da koriste TailwindCSS

3. **TailwindCSS & Vite**
   - Potvrdi da Vite config uključuje Tailwind i Alpine.js
   - Pokreni `npm install` i `npm run build` da proveris da sve radi

4. **Migracije — kreiraj sledeće tabele:**

   Tabela `users` (proširena Breeze default):
   - Dodaj kolonu: `current_balance` decimal(15,2) default 0.00

   Nova tabela `categories`:
   - id, user_id (FK→users), name (string,100), icon (string,50 nullable),
     color (string,7 default '#6B7280'), type (enum: income,expense),
     is_default (bool default false), timestamps

   Nova tabela `transactions`:
   - id, user_id (FK→users), category_id (FK→categories nullable),
     type (enum: income,expense), amount (decimal 15,2),
     description (string,255 nullable), transaction_date (date),
     is_confirmed (bool default true), timestamps

   Nova tabela `planned_transactions`:
   - id, user_id (FK→users), category_id (FK→categories nullable),
     type (enum: income,expense), amount (decimal 15,2),
     description (string,255 nullable),
     recurrence_type (enum: none,daily,weekly,monthly,yearly),
     recurrence_day (int nullable — dan u mesecu za monthly),
     next_due_date (date), is_active (bool default true), timestamps

5. **Modeli**
   - User (dopuni postojeći), Category, Transaction, PlannedTransaction
   - Definiši $fillable, $casts i relacije na svim modelima
   - Svaki model mora imati Factory

6. **Seederi**
   - DatabaseSeeder poziva CategorySeeder
   - CategorySeeder kreira podrazumevane kategorije (is_default=true, user_id=null):
     Prihodi: Plata, Freelance, Pokloni, Ostali prihodi
     Rashodi: Hrana, Stan/Kirija, Transport, Zdravlje, Zabava,
              Računi, Odjeća, Obrazovanje, Ostali rashodi

7. **Verifikacija**
   - `php artisan migrate:fresh --seed` mora proći bez grešaka
   - Register → Login → Logout flow mora raditi
   - Potvrdi da se `current_balance` prikazuje na profilu ili nekom placeholder view-u

### Na kraju sesije:
- Ažuriraj .claude/progress.md
- Ažuriraj .claude/tasks.md (označi završene taskove)
- Napiši "✅ Context files ažurirani"
```

---

## FAZA 2 PROMPT — CRUD Transakcije & Kategorije

```
Čitaj CLAUDE.md i .claude/progress.md pre nego što počneš.

## Zadatak — Faza 2: CRUD Transakcije & Kategorije

### Šta treba uraditi:

1. **Kategorije**
   - CategoryController (CRUD) sa metodama: index, create, store, edit, update, destroy
   - Route resource: /categories
   - Blade view-ovi: categories/index.blade.php, categories/create.blade.php,
     categories/edit.blade.php
   - Pri registraciji korisnika: kopiraj podrazumevane kategorije (is_default=true)
     u korisnički nalog (is_default=false, user_id=auth()->id())
   - Brisanje kategorije: soft-check — ako ima transakcija, ne briši nego upozori

2. **Transakcije**
   - TransactionController (CRUD) sa metodama: index, create, store, edit, update, destroy
   - Route resource: /transactions
   - Blade view-ovi: transactions/index.blade.php, transactions/create.blade.php,
     transactions/edit.blade.php
   - Filter na index: po datumu (from/to), kategoriji, tipu (income/expense)
   - Paginacija: 20 po stranici

3. **Balans logika**
   - Pri store (income): current_balance += amount
   - Pri store (expense): current_balance -= amount
   - Pri destroy: reverz efekta
   - Pri update: reverz starog + primeni novi

4. **Validacija**
   - StoreCategoryRequest, UpdateCategoryRequest
   - StoreTransactionRequest, UpdateTransactionRequest

5. **UX**
   - Flash poruke (success/error) na svim operacijama
   - Potvrda pre brisanja (Alpine.js modal ili browser confirm)
   - Prikaz tekućeg balansa u navigaciji

### Na kraju sesije:
- Ažuriraj .claude/progress.md
- Ažuriraj .claude/tasks.md
- Napiši "✅ Context files ažurirani"
```

---

## FAZA 3 PROMPT — Planirane i Recurring transakcije

```
Čitaj CLAUDE.md i .claude/progress.md pre nego što počneš.

## Zadatak — Faza 3: Planirane i Recurring transakcije

### Šta treba uraditi:

1. **PlannedTransactionController** (CRUD)
   - Route resource: /planned-transactions
   - Blade view-ovi: planned-transactions/index, create, edit

2. **Logika next_due_date**
   - PlannedTransactionService::calculateNextDueDate($plannedTransaction): Carbon
   - none → next_due_date se ne menja (jednokratna)
   - daily → +1 dan
   - weekly → +7 dana
   - monthly → recurrence_day tog meseca (ili poslednji dan ako mesec kraći)
   - yearly → isti datum, +1 godina

3. **Scheduled Job**
   - Artisan komanda: `planned-transactions:process`
   - Pokreće se svaki dan u ponoć (app/Console/Kernel.php ili routes/console.php)
   - Kreira notifikacije (ili flash-style upozorenja) za transakcije koje dospevaju
     danas ili u narednih 3 dana

4. **Akcije na index strani**
   - "Potvrdi" dugme: kreira Transaction iz PlannedTransaction, pomera next_due_date
   - "Preskoči" dugme: samo pomera next_due_date, ne kreira transakciju

5. **Notifikacije/upozorenja**
   - Na dashboard-u (i u nav-u): badge sa brojem dospelih planned transakcija
   - Dedicated sekcija na planned-transactions/index za "Dospele danas"

### Na kraju sesije:
- Ažuriraj .claude/progress.md
- Ažuriraj .claude/tasks.md
- Ažuriraj docs/technical/technical_manual.md
- Ažuriraj docs/user/user_manual.md
- Napiši "✅ Context files ažurirani"
```

---

## FAZA 4 PROMPT — Dashboard & Grafovi

```
Čitaj CLAUDE.md i .claude/progress.md pre nego što počneš.

## Zadatak — Faza 4: Dashboard & Grafovi

### Šta treba uraditi:

1. **DashboardController**
   - Tekući balans korisnika
   - Ukupni prihodi ovog meseca
   - Ukupni rashodi ovog meseca
   - Neto ovog meseca (prihodi - rashodi)
   - Poslednjih 10 transakcija
   - Dospele planirane transakcije (narednih 7 dana)
   - Rashodi po kategorijama ovog meseca (za pie chart)
   - Dnevni/nedeljni/mesečni agregat za line chart (poslednja 4 nedelje / 6 meseci)

2. **Grafovi** (koristiti Chart.js via CDN ili npm)
   - Line chart: prihodi vs rashodi po danima (tekući mesec)
   - Pie/Donut chart: rashodi po kategorijama (tekući mesec)
   - Bar chart: poređenje prihoda i rashoda po mesecima (poslednjih 6)

3. **Summary cards**
   - Tekući balans (istaknuto)
   - Prihodi ovog meseca
   - Rashodi ovog meseca
   - Upozorenja (dospele planirane transakcije)

4. **Budžetski limiti po kategoriji**
   - Dodaj kolonu `monthly_limit` (decimal nullable) u tabelu categories
   - Na dashboard-u: progress bar za svaku kategoriju sa limitom
   - Boja: zelena <80%, žuta 80-100%, crvena >100%

5. **Period switcher**
   - Korisnik može da bira: Ovaj mesec / Prošli mesec / Poslednja 3 meseca

### Na kraju sesije:
- Ažuriraj .claude/progress.md
- Ažuriraj .claude/tasks.md
- Ažuriraj docs/technical/technical_manual.md
- Ažuriraj docs/user/user_manual.md
- Napiši "✅ Context files ažurirani"
```

---

## FAZA 5 PROMPT — Polish, Validacije & Export

```
Čitaj CLAUDE.md i .claude/progress.md pre nego što počneš.

## Zadatak — Faza 5: Polish, Export & Produkcijska gotovost

### Šta treba uraditi:

1. **Export**
   - ExportController sa akcijama: exportCsv($from, $to), exportPdf($from, $to)
   - CSV: koristiti Laravel Excel (maatwebsite/excel) ili plain PHP fwrite
   - PDF: koristiti barryvdh/laravel-dompdf
   - Blade template za PDF: resources/views/exports/transactions-pdf.blade.php

2. **Responsive dizajn**
   - Audit svih stranica na mobilnom (375px) i tablet (768px) prikazu
   - Navigacija: hamburger menu za mobile
   - Tabele: horizontal scroll ili card layout na mobilnom

3. **Error handling**
   - 404 stranica: resources/views/errors/404.blade.php
   - 500 stranica: resources/views/errors/500.blade.php
   - Try/catch blokovi u svim Controller metodama koje pišu u bazu

4. **Rate limiting**
   - Dodaj throttle middleware na store/update/destroy route-ove

5. **Dokumentacija**
   - Kompletiranje docs/technical/technical_manual.md
   - Kompletiranje docs/user/user_manual.md
   - Kreiranje docs/test/E2E_test_plan_v1.md sa listom test scenarija

6. **Finalna revizija**
   - Pregled svih FormRequest validacija
   - Provera svih foreign key constraint-ova
   - `php artisan optimize` i provera da nema N+1 query problema (Laravel Debugbar)

### Na kraju sesije:
- Ažuriraj .claude/progress.md
- Ažuriraj .claude/tasks.md
- Napiši "✅ Context files ažurirani — Faza 5 završena, projekat spreman za produkciju"
```
