# Progress Log

## Poslednja sesija: 2026-04-04
### Šta je urađeno (Faza 5):
- Instaliran `barryvdh/laravel-dompdf` (^3.1)
- Kreiran `ExportController` sa metodama `exportCsv()` i `exportPdf()`
  - Oba eksporta prihvataju iste filtere kao `TransactionController@index`
  - CSV: plain PHP fwrite sa UTF-8 BOM, separator `;`, Excel-kompatibilan
  - PDF: dompdf, A4 portrait, summary kartice + tabela transakcija
- Kreiran `resources/views/exports/transactions-pdf.blade.php`
- Dodata export dugmad (CSV + PDF) na `transactions/index` header
- Dodate export rute (`GET /export/csv`, `GET /export/pdf`) u `routes/web.php`
- Dodato rate limiting (`throttle:60,1`) na sve store/update/destroy route-ove za sve 3 resursa
- Kreirana `resources/views/errors/404.blade.php`
- Kreirana `resources/views/errors/500.blade.php`
- Responsive audit:
  - Navigacija: hamburger meni već postoji (Breeze default), dodat prikaz balansa u mobile meniju
  - Tabele: `overflow-hidden` → `overflow-x-auto` na `transactions/index` i `planned-transactions/index`
- `php artisan optimize` — sve 4 cache sekcije završene uspešno
- Kreiran `docs/test/E2E_test_plan_v1.md` sa 59 test scenarija u 9 sekcija
- Kompletiran `docs/technical/technical_manual.md` (sekcije 6–11: Export, Rate Limiting, Error stranice, Testing, Deployment, Troubleshooting)
- Kompletiran `docs/user/user_manual.md` (sekcija 7: Export, sekcija 8: FAQ)

---

## Prethodna sesija: 2026-04-04
### Šta je urađeno (Faza 4):
- Nova migracija `add_monthly_limit_to_categories_table` — dodaje nullable decimal kolonu `monthly_limit` u tabelu categories
- Ažuriran `Category` model: `monthly_limit` dodat u `$fillable` i `$casts`
- Ažurirani `StoreCategoryRequest` i `UpdateCategoryRequest`: dodato pravilo `nullable|numeric|min:0` za `monthly_limit`
- Ažurirani Blade view-ovi `categories/create.blade.php` i `categories/edit.blade.php`: novo polje za unos mesečnog limita
- Kreiran `DashboardController` sa sledećim agregatima:
  - Prihodi/rashodi/neto za izabrani period
  - Poslednjih 10 transakcija
  - Predstojeće planirane transakcije (narednih 7 dana)
  - Dospele planirane transakcije
  - Dnevni podaci za line chart (prihodi vs rashodi po danima u periodu)
  - Rashodi po kategorijama za donut chart
  - Mesečni agregat za bar chart (poslednjih 6 meseci)
  - Budžetski progres po kategorijama sa limitom
- Period switcher: Ovaj mesec / Prošli mesec / Poslednja 3 meseca (GET param `period`)
- Ažuriran `routes/web.php`: dashboard ruta sada koristi `DashboardController@index`
- Kompletno prepisan `dashboard.blade.php`:
  - 4 summary kartice (balans, prihodi, rashodi, neto)
  - Alert sekcija za dospele planirane transakcije sa Potvrdi/Preskoči akcijama
  - Line chart: dnevni prihodi vs rashodi (Chart.js via CDN)
  - Donut chart: rashodi po kategorijama
  - Bar chart: poređenje prihoda i rashoda po mesecima (poslednjih 6)
  - Budget progress bars sa zelena/žuta/crvena logikom
  - Lista poslednjih 10 transakcija
  - Lista predstojećih planiranih transakcija (7 dana)

---

## Prethodna sesija: 2026-04-04
### Šta je urađeno (Faza 3):
- Kreiran `PlannedTransactionService` sa metodom `calculateNextDueDate()`:
  - none → vraća null (transakcija se deaktivira)
  - daily → +1 dan
  - weekly → +7 dana
  - monthly → recurrence_day tog meseca (ili poslednji dan ako mesec kraći)
  - yearly → +1 godina
- Kreiran `StorePlannedTransactionRequest` i `UpdatePlannedTransactionRequest`
- Kreiran `PlannedTransactionController` sa:
  - CRUD (index, create, store, edit, update, destroy)
  - `confirm` akcija: kreira Transaction, primenjuje balans, pomera next_due_date
  - `skip` akcija: samo pomera next_due_date (ili deaktivira ako none)
  - `advanceDueDate()` helper: za `none` deaktivira, za ostale pomera datum
- Kreiran Artisan command `planned-transactions:process` (logiraj dospele/nadolazeće)
- Dodata schedule u `routes/console.php`: svaki dan u ponoć (00:00)
- Ažuriran `routes/web.php`: resource route + confirm/skip POST route-ovi
- Kreirani Blade view-ovi:
  - `planned-transactions/index` — 3 sekcije: Dospele (crvena), Predstojeće, Neaktivne
  - `planned-transactions/create` — form sa Alpine.js conditional za recurrence_day
  - `planned-transactions/edit` — isti form popunjen existing podacima
  - `planned-transactions/_recurrence_badge` — partial za badge tipa ponavljanja
- Ažurirana `navigation.blade.php`: link "Planirane" + crveni badge ako ima dospelih
- Ažuriran `dashboard.blade.php`: 4. kartica za planirane + sekcija dospelih sa Potvrdi/Preskoči

---

## Prethodna sesija: 2026-04-03
### Šta je urađeno (Faza 2):
- Kreiran `BalanceService` sa metodama apply/reverse/reapply
- Kreiran `StoreCategoryRequest` i `UpdateCategoryRequest`
- Kreiran `StoreTransactionRequest` i `UpdateTransactionRequest`
- Kreiran `CategoryController` (index, create, store, edit, update, destroy)
  - Zaštita od brisanja ako kategorija ima transakcije
  - Auth check (abort 403 ako nije vlasnik)
- Kreiran `TransactionController` (index, create, store, edit, update, destroy)
  - Filter po datumu (from/to), kategoriji, tipu
  - Paginacija 20 po stranici
  - Balance logika: store += amount, destroy reverz, update reapply
- Kreiran `CategoryController` sa resource routes `/categories`
- Kreiran `TransactionController` sa resource routes `/transactions`
- Ažuriran `routes/web.php` — dodati resource route-ovi za kategorije i transakcije
- Ažuriran `RegisteredUserController` — kopira default kategorije novom korisniku pri registraciji
- Ažuriran `layouts/navigation.blade.php` — dodati linkovi (Transakcije, Kategorije) i prikaz balansa
- Ažuriran `layouts/app.blade.php` — dodati flash poruke (success/error, auto-hide sa Alpine.js)
- Kreirani Blade view-ovi za kategorije: `index`, `create`, `edit`
- Kreirani Blade view-ovi za transakcije: `index`, `create`, `edit`
- Ažuriran `dashboard.blade.php` — prikaz tekućeg balansa i quick linkovi

---

## Prethodna sesija: 2026-04-03
### Šta je urađeno (Faza 1):
- Instaliran Laravel 11 (v11.51.0) u root projektnog direktorijuma
- Instaliran Laravel Breeze (v2.4.1) sa Blade + Alpine.js stack-om
- Vite build uspešno pokrenut — assets generisani u `public/build/`
- `.env.example` ažuriran: APP_NAME, MySQL konekcija (personal_finance_tracker)
- Kreirana MySQL baza `personal_finance_tracker`
- Kreirana migracija: `add_current_balance_to_users_table` (decimal 15,2, default 0.00)
- Kreirana migracija: `create_categories_table`
- Kreirana migracija: `create_transactions_table`
- Kreirana migracija: `create_planned_transactions_table`
- Kreiran model `Category` sa $fillable, $casts, relacijama i Factory
- Kreiran model `Transaction` sa $fillable, $casts, relacijama i Factory
- Kreiran model `PlannedTransaction` sa $fillable, $casts, relacijama i Factory
- Dopunjen model `User` sa `current_balance`, $fillable, $casts, relacijama
- Kreiran `CategorySeeder` sa 13 podrazumevanih kategorija (4 prihoda, 9 rashoda)
- `DatabaseSeeder` poziva `CategorySeeder`
- `php artisan migrate:fresh --seed` prolazi bez grešaka ✅
- Auth flow (Register/Login/Logout) spreman putem Breeze-a ✅

---

## Trenutno stanje koda

| Komponenta              | Status               |
|-------------------------|----------------------|
| Projektna dokumentacija | ✅ Kreirana          |
| Laravel instalacija     | ✅ Završeno          |
| Auth (Breeze)           | ✅ Završeno          |
| Baza / Migracije        | ✅ Završeno          |
| Kategorije CRUD         | ✅ Završeno          |
| Transakcije CRUD        | ✅ Završeno          |
| Recurring transakcije   | ✅ Završeno          |
| Dashboard / Grafovi     | ✅ Završeno          |
| Export (CSV/PDF)        | ✅ Završeno          |

---

## Poslednji fajlovi koje smo dirali (Faza 3)

- `app/Services/PlannedTransactionService.php`
- `app/Http/Requests/StorePlannedTransactionRequest.php`
- `app/Http/Requests/UpdatePlannedTransactionRequest.php`
- `app/Http/Controllers/PlannedTransactionController.php`
- `app/Console/Commands/ProcessPlannedTransactions.php`
- `routes/console.php`
- `routes/web.php`
- `resources/views/planned-transactions/index.blade.php`
- `resources/views/planned-transactions/create.blade.php`
- `resources/views/planned-transactions/edit.blade.php`
- `resources/views/planned-transactions/_recurrence_badge.blade.php`
- `resources/views/layouts/navigation.blade.php`
- `resources/views/dashboard.blade.php`

---

## Poznati problemi / Tech debt

- `current_balance` se ne rekalkuliše retroaktivno — vrednost u bazi mora biti konzistentna sa stvarnim stanjem transakcija
- Nema admin sekcije za upravljanje default kategorijama
- `planned-transactions:process` command samo loguje — ne šalje email/push notifikacije (planirano za kasniju fazu)

---

## Poslednji fajlovi koje smo dirali (Faza 4)

- `database/migrations/2026_04_03_221241_add_monthly_limit_to_categories_table.php`
- `app/Models/Category.php`
- `app/Http/Requests/StoreCategoryRequest.php`
- `app/Http/Requests/UpdateCategoryRequest.php`
- `app/Http/Controllers/DashboardController.php`
- `routes/web.php`
- `resources/views/dashboard.blade.php`
- `resources/views/categories/create.blade.php`
- `resources/views/categories/edit.blade.php`

---

## Sledeća sesija treba da počne sa:

Sve faze završene. Projekat je spreman za produkciju.

Eventualni backlog zadaci su opisani u `.claude/tasks.md` → sekcija **Backlog / Buduće ideje**.
