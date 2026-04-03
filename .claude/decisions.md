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
