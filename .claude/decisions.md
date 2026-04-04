# Architecture Decision Records (ADR)

---

## ADR-001: Izbor Laravel Breeze umesto Jetstream/Fortify za auth

**Datum:** 2026-04-03
**Status:** Prihvaćeno

**Kontekst:**
Laravel nudi tri opcije za autentifikaciju: Breeze (minimalno), Jetstream (kompletno,
sa timovima i 2FA) i Fortify (headless auth backend). Trebalo je odabrati jednu za
Personal Finance Tracker.

**Odluka:**
Koristimo Laravel Breeze sa Blade + Alpine.js stack-om.

**Razlozi:**
- Breeze generiše čist, razumljiv kod koji je lako modifikovati
- Jetstream je previše heavyweight za single-user finance tracker (dodaje timove,
  API tokene, 2FA — što nije potrebno u v1)
- Fortify je headless i zahteva dodatni frontend setup koji nije u skladu sa Blade stack-om
- Breeze uključuje Blade view-ove koje možemo direktno stilizovati sa TailwindCSS

**Posledice:**
- Auth je ograničen na email/password (bez OAuth u v1)
- 2FA može biti dodata naknadno ako postoji potreba
- Kod ostaje minimalan i lak za razumevanje u budućim sesijama

---

## ADR-002: Izbor Blade + Alpine.js umesto SPA (React/Vue)

**Datum:** 2026-04-03
**Status:** Prihvaćeno

**Kontekst:**
Modern web app može biti građena kao SPA (React/Vue sa API-jem) ili kao server-rendered
Blade aplikacija sa malim količinama klijentske interaktivnosti.

**Odluka:**
Koristimo Blade templating sa Alpine.js za UI interaktivnost.

**Razlozi:**
- Finance tracker je CRUD-heavy aplikacija — server-rendered Blade je prirodan fit
- Alpine.js pruža dovoljno reaktivnosti za modalne prozore, dropdowne i dinamičke forme
  bez potrebe za punim SPA framework-om
- Nema potrebe za posebnim API-jem i JWT autentifikacijom u v1
- Manje kompleksnosti = brži razvoj i lakše održavanje
- Laravel Blade + Inertia.js migracija je uvek opcija za v2 ako se pojavi potreba

**Posledice:**
- Svaka navigacija je full page reload (prihvatljivo za v1)
- Za kompleksne interakcije koristimo Alpine.js komponente ili Livewire (ako zatreba)
- Chart.js se integriše direktno u Blade view-ove

---

## ADR-003: Redosled i granularnost razvojnih faza

**Datum:** 2026-04-03
**Status:** Prihvaćeno

**Kontekst:**
Projekat je podeljen u faze. Trebalo je odrediti redosled i granularnost.

**Odluka:**
5 faza: Setup → CRUD core → Recurring → Dashboard → Polish

**Razlozi:**
- **Faza 1 (Setup):** Bez stabilne infrastrukture i auth-a, ništa drugo ne može da se gradi.
  Migracije definišu celu DB strukturu unapred da se izbegnu breaking change-ovi u migracijama.
- **Faza 2 (CRUD):** Kategorije i transakcije su srž aplikacije. Sve ostalo zavisi od njih.
  Balans logika je ovde jer je vezana za svaki CRUD.
- **Faza 3 (Recurring):** Gradi se na vrhu postojećih transakcija. Zahteva scheduler i
  posebnu logiku, pa je odvojeno od osnovnog CRUD-a.
- **Faza 4 (Dashboard):** Vizualizacija ima smisla tek kada postoje podaci.
  Dashbord zahteva finalizovane relacije i agregacije, pa dolazi posle CRUD-a.
- **Faza 5 (Polish):** Export, responsive, error handling — sve što čini produkcijsku
  gotovost, ali nije bloker za core funkcionalnost.

**Posledice:**
- Na kraju Faze 2 aplikacija je već upotrebljiva za osnovno praćenje
- Faze se mogu prekinuti i app ostaje funkcionalna (svaka faza je vertikalni slice)
- Ako se scope proširi, nove faze se dodaju iza Faze 5

---

## ADR-004: Čuvanje balansa u users tabeli

**Datum:** 2026-04-03
**Status:** Prihvaćeno

**Kontekst:**
Tekući balans korisnika može biti računat (suma svih transakcija) ili čuvan kao
denormalizovana vrednost u users tabeli.

**Odluka:**
Čuvamo `current_balance` kao denormalizovanu vrednost u `users` tabeli.

**Razlozi:**
- Računanje balansa iz svih transakcija za svakog page load je skupo na velikom broju zapisa
- Denormalizacija je standardni pattern za "running balance" u fintech aplikacijama
- BalanceService enkapsulira logiku ažuriranja — koherentnost je osigurana servisnim slojem

**Posledice:**
- BalanceService mora biti pozvan pri svakom CRUD-u na transakcijama
- Ako se balans desinhronizuje, potrebna je repair komanda (može se dodati u Phase 5)
- Nije potreban kompleksan JOIN za prikaz balansa

---

## ADR-006: Upgrade Laravel 11 → 13

**Datum:** 2026-04-04
**Status:** Prihvaćeno

**Kontekst:**
Projekat je pokrenut na Laravel 11.51.0. Odlučeno je da se nadgradi na Laravel 13
kako bi aplikacija koristila najnovije sigurnosne zakrpe, novu PHP async infrastrukturu
i dugoročnu podršku.

**Odluka:**
Direktan upgrade na Laravel Framework v13.3.0, laravel/tinker v3.0, phpunit v12.

**Ključne promjene:**
- `laravel/framework`: `^11.31` → `^13.0` (instalirano v13.3.0)
- `laravel/tinker`: `^2.9` → `^3.0` (instalirano v3.0.0)
- `phpunit/phpunit`: `^11.0.1` → `^12.0` (instalirano v12.5.16)
- Carbon 3.x automatski povučen kao zavisnost (kompatibilno sa svim Carbon metodama u projektu)
- `config/cache.php`: dodata `serializable_classes` opcija (Laravel 13 security hardening)
- `bootstrap/app.php`: nije trebalo mijenjati — već u novom formatu (bez Kernel.php)
- CSRF middleware primejen: `VerifyCsrfToken` → `PreventRequestForgery` — nema uticaja
  na naš kod jer nismo direktno referencirali middleware u app/ fajlovima

**Paketi koji su ostali nepromijenjeni:**
- `barryvdh/laravel-dompdf ^3.1` — već podržava Laravel 13 (potvrđeno: `illuminate/support ^9|...|^13.0`)
- `laravel/breeze ^2.4` — kompatibilan sa Laravel 11|12|13
- `laravel/pail ^1.1` — kompatibilan sa Laravel 13

**Napomene o testovima:**
- Breeze-generated feature testovi su bili neispravni i PRIJE upgradeova (potvrđeno)
- Uzrok: testovi ne uključuju CSRF token u POST zahtjevima (pre-existing issue)
- Nije dio ove faze, ostavitiće se kao poznati tech debt

**Razlozi za direktan (ne postepeni) upgrade:**
- Aplikacija je relativno nova, bez legacy koda koji bi koristio deprecated API-je
- Nema app/Http/Kernel.php — već u Laravel 11+ formatu
- Nema korišćenja `HasUuids` traita, database Grammara direktno, ni Concurrency API-ja

**Posledice:**
- Aplikacija je na Laravel 13.3.0 sa PHP 8.3 (PHP verzija nije morala da se mijenja)
- `migrate:fresh --seed`, `route:list`, `config:cache`, `view:cache`, artisan komande — sve prolazi
- Svi ključni composer paketi kompatibilni sa Laravel 13

---

## ADR-005: Nullable category_id na transactions

**Datum:** 2026-04-03
**Status:** Prihvaćeno

**Kontekst:**
Ako korisnik obriše kategoriju, transakcije koje su koristile tu kategoriju ostaju bez nje.

**Odluka:**
`category_id` je nullable sa `ON DELETE SET NULL`.

**Razlozi:**
- Brisanje kategorije ne sme brisati finansijsku istoriju korisnika
- NULL category_id se prikazuje kao "Bez kategorije" u UI-u
- Alternativa (soft delete kategorija) uvodi dodatnu kompleksnost za v1

**Posledice:**
- Query-ji za kategoriju moraju handlovati NULL vrednosti
- "Bez kategorije" treba biti eksplicitno prikazano u transakcijama i grafikima
