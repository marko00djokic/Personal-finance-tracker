# Task Backlog

## FAZA 1 — Setup & Auth ✅ ZAVRŠENO
- [x] Instalacija Laravel 11 projekta u root direktorijum
- [x] Instalacija i konfiguracija Laravel Breeze (Blade + Alpine.js stack)
- [x] Konfiguracija TailwindCSS i Vite (`npm install && npm run build`)
- [x] Kreiranje migracije za `categories` tabelu
- [x] Kreiranje migracije za `transactions` tabelu
- [x] Kreiranje migracije za `planned_transactions` tabelu
- [x] Proširivanje `users` tabele sa `current_balance` kolonom
- [x] Kreiranje Eloquent modela: Category, Transaction, PlannedTransaction
- [x] Definisanje relacija na svim modelima
- [x] Kreiranje Factory klasa za sve modele
- [x] Kreiranje CategorySeeder sa podrazumevanim kategorijama
- [x] `php artisan migrate:fresh --seed` mora proći bez grešaka
- [x] Testiranje register/login/logout flow-a (Breeze spreman)

## FAZA 2 — Transakcije & Kategorije ✅ ZAVRŠENO
- [x] Kopiranje default kategorija pri registraciji novog korisnika
- [x] CategoryController sa svim CRUD metodama
- [x] Blade view-ovi za kategorije (index, create, edit)
- [x] StoreCategoryRequest i UpdateCategoryRequest validacija
- [x] Zaštita od brisanja kategorije koja ima transakcije
- [x] TransactionController sa svim CRUD metodama
- [x] Blade view-ovi za transakcije (index, create, edit)
- [x] StoreTransactionRequest i UpdateTransactionRequest validacija
- [x] Filter transakcija po datumu, kategoriji, tipu
- [x] Paginacija na listi transakcija (20 po stranici)
- [x] BalanceService — logika korekcije balansa pri CRUD operacijama
- [x] Prikaz tekućeg balansa u glavnoj navigaciji
- [x] Flash poruke (success/error) na svim operacijama
- [x] Potvrda pre brisanja (browser confirm putem Alpine.js)

## FAZA 3 — Recurring transakcije ✅ ZAVRŠENO
- [x] PlannedTransactionController sa svim CRUD metodama
- [x] Blade view-ovi za planned transactions (index, create, edit)
- [x] StorePlannedTransactionRequest i UpdatePlannedTransactionRequest
- [x] PlannedTransactionService::calculateNextDueDate() logika
- [x] Artisan komanda `planned-transactions:process`
- [x] Scheduled job (svaki dan u ponoć) za procesiranje dospelih transakcija
- [x] Akcija "Potvrdi" — kreira Transaction, pomera next_due_date
- [x] Akcija "Preskoči" — samo pomera next_due_date
- [x] Badge u navigaciji sa brojem dospelih planned transakcija
- [x] Sekcija "Dospele danas" na planned-transactions/index

## FAZA 4 — Dashboard & Grafovi
- [ ] DashboardController sa svim potrebnim agregatima
- [ ] Summary cards (balans, prihodi, rashodi, neto mesec)
- [ ] Lista poslednjih 10 transakcija na dashboardu
- [ ] Widget za upcoming planned transactions (7 dana)
- [ ] Instalacija Chart.js
- [ ] Line chart: prihodi vs rashodi po danima (tekući mesec)
- [ ] Pie/Donut chart: rashodi po kategorijama (tekući mesec)
- [ ] Bar chart: prihodi vs rashodi po mesecima (poslednjih 6)
- [ ] Dodavanje `monthly_limit` kolone u categories tabelu (nova migracija)
- [ ] Progress bar indikatori budžetskih limita po kategoriji
- [ ] Period switcher (ovaj mesec / prošli mesec / posled. 3 meseca)

## FAZA 5 — Polish & Export
- [ ] ExportController sa CSV i PDF akcijama
- [ ] Instalacija maatwebsite/excel ili plain CSV export
- [ ] Instalacija barryvdh/laravel-dompdf
- [ ] Blade template za PDF export
- [ ] Responsive dizajn audit (mobile 375px + tablet 768px)
- [ ] Hamburger navigacija za mobile
- [ ] Kreiranje 404.blade.php i 500.blade.php
- [ ] Try/catch u svim Controller metodama koje pišu u bazu
- [ ] Rate limiting (throttle) na store/update/destroy route-ove
- [ ] Kompletiranje docs/technical/technical_manual.md
- [ ] Kompletiranje docs/user/user_manual.md
- [ ] Kreiranje docs/test/E2E_test_plan_v1.md
- [ ] Finalna revizija svih FormRequest validacija
- [ ] Provera N+1 query problema (Laravel Debugbar)
- [ ] `php artisan optimize` i cache konfiguracija

## Backlog / Buduće ideje
- [ ] Viševalutna podrška (praćenje u više valuta)
- [ ] Import transakcija iz CSV fajla
- [ ] Email notifikacije za dospele planirane transakcije
- [ ] Mobile app (PWA ili React Native)
- [ ] API endpoints za mobilni klijent
- [ ] Deljeni budžet između korisnika (npr. porodični račun)
- [ ] Integracija sa bankarskim API-jem (automatski import)
- [ ] AI asistent za analizu potrošnje i preporuke uštedine
