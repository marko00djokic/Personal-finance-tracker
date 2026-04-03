# Progress Log

## Poslednja sesija: 2026-04-03
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
| Kategorije CRUD         | ❌ Nije početo       |
| Transakcije CRUD        | ❌ Nije početo       |
| Recurring transakcije   | ❌ Nije početo       |
| Dashboard / Grafovi     | ❌ Nije početo       |
| Export (CSV/PDF)        | ❌ Nije početo       |

---

## Poslednji fajlovi koje smo dirali

- `database/migrations/2026_04_03_194422_add_current_balance_to_users_table.php`
- `database/migrations/2026_04_03_194436_create_categories_table.php`
- `database/migrations/2026_04_03_194444_create_transactions_table.php`
- `database/migrations/2026_04_03_194452_create_planned_transactions_table.php`
- `app/Models/User.php`
- `app/Models/Category.php`
- `app/Models/Transaction.php`
- `app/Models/PlannedTransaction.php`
- `database/factories/CategoryFactory.php`
- `database/factories/TransactionFactory.php`
- `database/factories/PlannedTransactionFactory.php`
- `database/seeders/CategorySeeder.php`
- `database/seeders/DatabaseSeeder.php`
- `.env.example`

---

## Poznati problemi / Tech debt

- Registracijom se NE kopiraju default kategorije korisniku — treba dodati logiku u `RegisteredUserController` (Faza 2 task)
- `current_balance` nije prikazan nigde u UI — placeholder view nije kreiran

---

## Sledeća sesija treba da počne sa:

**Faza 2 — Kategorije i Transakcije CRUD**

Prompt za ovu fazu se nalazi u:
`docs/Personal-finance-tracker.md` → sekcija **FAZA 2 PROMPT**

Pre nego što počnemo Fazu 2, treba:
1. Dodati logiku u RegisteredUserController da kopira default kategorije novom korisniku
2. Kreirati placeholder dashboard view koji prikazuje `current_balance`
