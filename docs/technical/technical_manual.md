# Technical Manual — Personal Finance Tracker

> Ovaj dokument se dopunjava nakon svake faze razvoja.
> Poslednje ažuriranje: 2026-04-04 (Faza 4)

---

## 1. Instalacija i pokretanje projekta

### Preduslovi
- PHP 8.3+
- Composer 2.x
- Node.js 20+ i npm
- MySQL 8+
- Git

### Koraci instalacije

```bash
# 1. Kloniraj repozitorijum
git clone <repo-url> personal-finance-tracker
cd personal-finance-tracker

# 2. Instaliraj PHP zavisnosti
composer install

# 3. Instaliraj Node.js zavisnosti
npm install

# 4. Kopiraj .env fajl i podesi vrednosti
cp .env.example .env
php artisan key:generate

# 5. Pokreni migracije i seedere
php artisan migrate:fresh --seed

# 6. Kompajliraj assets
npm run build

# 7. Pokreni development server
php artisan serve
```

Aplikacija je dostupna na: http://localhost:8000

### Development mode (hot reload)

```bash
# Terminal 1 — PHP server
php artisan serve

# Terminal 2 — Vite dev server
npm run dev
```

---

## 2. Konfiguracija okruženja

### .env ključne varijable

```ini
APP_NAME="Personal Finance Tracker"
APP_ENV=local
APP_KEY=           # Generisano sa php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=personal_finance_tracker
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=log    # Za development, loguje u storage/logs/laravel.log
```

---

## 3. Struktura projekta

> Detaljna folder struktura i arhitektura su dokumentovane u `.claude/project.md`

---

## 4. Baza podataka

> Šema tabela i ERD dijagram su dokumentovani u `.claude/project.md`

### Pokretanje migracija

```bash
# Prva instalacija
php artisan migrate --seed

# Reset (GUBI SVE PODATKE)
php artisan migrate:fresh --seed

# Samo nova migracija
php artisan migrate
```

---

## 5. Ključne klase i servisni sloj

### BalanceService (`app/Services/BalanceService.php`)

Centralizuje svu logiku korekcije `users.current_balance`:

| Metoda | Opis |
|--------|------|
| `apply(User, type, amount)` | Primeni efekat transakcije (+income / -expense) |
| `reverse(User, type, amount)` | Poništi efekat transakcije |
| `reapply(User, oldTransaction, newType, newAmount)` | Reverz + primena — koristi se pri update-u |

### PlannedTransactionService (`app/Services/PlannedTransactionService.php`)

Izračunava sledeći datum dospeća:

| `recurrence_type` | Logika |
|-------------------|--------|
| `none` | Vraća `null` — transakcija se deaktivira (`is_active = false`) |
| `daily` | `+1 dan` |
| `weekly` | `+7 dana` |
| `monthly` | `recurrence_day` tog meseca (ili poslednji dan ako mesec kraći) |
| `yearly` | `+1 godina` |

### FormRequest klase (`app/Http/Requests/`)

| Klasa | Validira |
|-------|----------|
| `StoreCategoryRequest` | name, type, color (hex regex), icon, monthly_limit (nullable numeric) |
| `UpdateCategoryRequest` | Iste kao Store |
| `StoreTransactionRequest` | type, amount (0.01–9999999.99), category_id, transaction_date, description |
| `UpdateTransactionRequest` | Iste kao Store |
| `StorePlannedTransactionRequest` | type, amount, category_id (nullable), description, recurrence_type, recurrence_day (nullable), next_due_date, is_active |
| `UpdatePlannedTransactionRequest` | Iste kao Store |

### CategoryController — zaštita pri brisanju

Pre brisanja kategorije poziva `$category->transactions()->exists()`. Ako vraća `true`, brisanje se odbija sa flash `error` porukom — bez soft-delete, bez kaskade.

### TransactionController — filter logika

Query builder u `index()` metodi prihvata `date_from`, `date_to`, `category_id`, `type` iz GET parametara. Paginacija: `->paginate(20)->withQueryString()` (čuva filtere kroz stranice).

---

### PlannedTransactionController — akcije potvrde i preskakanja

| Akcija | Route | Opis |
|--------|-------|------|
| `confirm` | `POST /planned-transactions/{id}/confirm` | Kreira Transaction, primenjuje balans, pomera next_due_date |
| `skip` | `POST /planned-transactions/{id}/skip` | Samo pomera next_due_date (ili deaktivira za `none`) |

---

## 6. Artisan komande

### `planned-transactions:process`

```bash
php artisan planned-transactions:process
```

Loguje sve aktivne planirane transakcije čiji `next_due_date` je danas ili u naredna 3 dana.

**Schedule:** svaki dan u ponoć (00:00), konfigurisano u `routes/console.php`:

```php
Schedule::command('planned-transactions:process')->dailyAt('00:00');
```

Da bi schedule radio, mora biti aktiviran cron na serveru:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

### DashboardController (`app/Http/Controllers/DashboardController.php`)

Centralizuje sve agregatne upite za dashboard stranicu:

| Podatak | Opis |
|---------|------|
| `income`, `expenses`, `net` | Suma prihoda/rashoda/neto za izabrani period |
| `recentTransactions` | Poslednjih 10 transakcija (eager load category) |
| `upcomingPlanned` | Planirane transakcije u narednih 7 dana |
| `duePlanned` | Planirane transakcije čiji je datum dospeća prošao |
| `dailyData` | Dnevni podaci za line chart (labels, income[], expenses[]) |
| `categoryExpenses` | Rashodi grupisani po kategoriji za donut chart |
| `monthlyData` | Mesečni podaci za bar chart — poslednjih 6 meseci |
| `budgetCategories` | Kategorije sa limitom + spent + % + CSS klasa |

**Period switcher** — GET param `period`:
- `this_month` (default) — od prvog do poslednjeg dana tekućeg meseca
- `last_month` — prošli mesec
- `last_3_months` — od pre 3 meseca do kraja tekućeg meseca

**Budget progress logic:**
- `pct < 80%` → zelena (`bg-green-500`)
- `80% ≤ pct < 100%` → žuta (`bg-yellow-400`)
- `pct ≥ 100%` → crvena (`bg-red-500`)

---

### categories.monthly_limit kolona

Dodata migracija `add_monthly_limit_to_categories_table`:
```php
$table->decimal('monthly_limit', 15, 2)->nullable()->after('is_default');
```
Koristi se isključivo za rashode. Ako `null` ili `0`, kategorija nema limit i neće se prikazati u budget sekciji.

---

## 6. Testing

> Sekcija se popunjava nakon svake faze

---

## 7. Deployment

> Sekcija se popunjava u Fazi 5

---

## 8. Troubleshooting

> Sekcija se popunjava tokom razvoja kada se identifikuju česti problemi
