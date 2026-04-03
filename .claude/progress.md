# Progress Log

## Poslednja sesija: 2026-04-03
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
| Recurring transakcije   | ❌ Nije početo       |
| Dashboard / Grafovi     | ❌ Nije početo       |
| Export (CSV/PDF)        | ❌ Nije početo       |

---

## Poslednji fajlovi koje smo dirali (Faza 2)

- `app/Services/BalanceService.php`
- `app/Http/Requests/StoreCategoryRequest.php`
- `app/Http/Requests/UpdateCategoryRequest.php`
- `app/Http/Requests/StoreTransactionRequest.php`
- `app/Http/Requests/UpdateTransactionRequest.php`
- `app/Http/Controllers/CategoryController.php`
- `app/Http/Controllers/TransactionController.php`
- `app/Http/Controllers/Auth/RegisteredUserController.php`
- `routes/web.php`
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/navigation.blade.php`
- `resources/views/categories/index.blade.php`
- `resources/views/categories/create.blade.php`
- `resources/views/categories/edit.blade.php`
- `resources/views/transactions/index.blade.php`
- `resources/views/transactions/create.blade.php`
- `resources/views/transactions/edit.blade.php`
- `resources/views/dashboard.blade.php`

---

## Poznati problemi / Tech debt

- `current_balance` se ne rekalkuliše retroaktivno — vrednost u bazi mora biti konzistentna sa stvarnim stanjem transakcija
- Nema admin sekcije za upravljanje default kategorijama

---

## Sledeća sesija treba da počne sa:

**Faza 3 — Recurring (Planned) Transakcije**

Prompt za ovu fazu se nalazi u:
`docs/Personal-finance-tracker.md` → sekcija **FAZA 3 PROMPT**
