# Personal Finance Tracker — Claude Onboarding

## QUICK START (čitaj ovo prvo)

Ovo je web aplikacija za praćenje ličnih finansija — budžet, transakcije, grafički izveštaji.

**Tech stack:** Laravel 11, PHP 8.3, MySQL 8, Blade + Alpine.js, Vite, TailwindCSS, Laravel Breeze

**Pokreni:** `php artisan serve` → http://localhost:8000  
**Assets:** `npm run dev` (development) ili `npm run build` (produkcija)

## TRENUTNI STATUS
→ Detalji u: `.claude/progress.md`  ← UVEK čitaj ovo pre nego što pitaš šta radimo

## ARHITEKTURA
→ Detalji u: `.claude/project.md`

## AKTIVNI TASKOVI
→ Detalji u: `.claude/tasks.md`

## PROJEKTNA DOKUMENTACIJA
→ Plan razvoja i promptovi po fazama: `docs/Personal-finance-tracker.md`

## VAZNE KONVENCIJE

- Routes su u `routes/web.php`, API routes u `routes/api.php`
- Controllers idu u `app/Http/Controllers/`, grupisani po domenu (npr. `TransactionController`)
- Blade views su u `resources/views/`, grupisani po sekcijama (npr. `transactions/`, `dashboard/`)
- FormRequest klase idu u `app/Http/Requests/`
- Service klase idu u `app/Services/`
- Ne menjaj `.env` direktno — koristi `.env.example` kao referencu
- Ne menjaj migracije koje su već pokrenute — dodaj novu migraciju
- Svaki novi model mora imati odgovarajući Factory i Seeder
- Svaki Controller metod koji piše u bazu mora imati try/catch

## Imenovanje

| Tip            | Konvencija         | Primer                        |
|----------------|--------------------|-------------------------------|
| Tabele         | snake_case, plural | `planned_transactions`        |
| Modeli         | PascalCase         | `PlannedTransaction`          |
| Controllers    | PascalCase + sufix | `TransactionController`       |
| Routes (web)   | kebab-case         | `/planned-transactions`       |
| Blade views    | kebab-case         | `planned-transactions/index`  |
| FormRequests   | Verb + Model + Request | `StoreTransactionRequest` |

## NE DIRAJ (bez eksplicitnog dogovora)

- `.env` fajl — nikada ga ne commituj
- `database/migrations/` — postojeće migracije se NE menjaju, samo dodaju nove
- `vendor/` direktorijum
- `public/` generisani asset fajlovi (build output)
- `storage/` direktorijum (osim ako ne dodajemo novi disk)

## KRAJ SVAKE SESIJE — OBAVEZNO

Pre nego što završimo razgovor, uvek uradi:
1. Ažuriraj `.claude/progress.md` sa onim što je urađeno u ovoj sesiji
2. Ažuriraj `.claude/tasks.md` — označi završene taskove, dodaj nove ako je potrebno
3. Ako je doneta neka arhitekturalna odluka, dodaj je u `.claude/decisions.md`
4. Napiši "✅ Context files ažurirani" kao poslednju poruku
