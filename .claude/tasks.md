# Task Backlog

## FAZA 1 — Setup & Auth
- [ ] Instalacija Laravel 11 projekta u root direktorijum
- [ ] Instalacija i konfiguracija Laravel Breeze (Blade + Alpine.js stack)
- [ ] Konfiguracija TailwindCSS i Vite (`npm install && npm run build`)
- [ ] Kreiranje migracije za `categories` tabelu
- [ ] Kreiranje migracije za `transactions` tabelu
- [ ] Kreiranje migracije za `planned_transactions` tabelu
- [ ] Proširivanje `users` tabele sa `current_balance` kolonom
- [ ] Kreiranje Eloquent modela: Category, Transaction, PlannedTransaction
- [ ] Definisanje relacija na svim modelima
- [ ] Kreiranje Factory klasa za sve modele
- [ ] Kreiranje CategorySeeder sa podrazumevanim kategorijama
- [ ] Kopiranje default kategorija pri registraciji novog korisnika
- [ ] `php artisan migrate:fresh --seed` mora proći bez grešaka
- [ ] Testiranje register/login/logout flow-a

## FAZA 2 — Transakcije & Kategorije
- [ ] CategoryController sa svim CRUD metodama
- [ ] Blade view-ovi za kategorije (index, create, edit)
- [ ] StoreCategoryRequest i UpdateCategoryRequest validacija
- [ ] Zaštita od brisanja kategorije koja ima transakcije
- [ ] TransactionController sa svim CRUD metodama
- [ ] Blade view-ovi za transakcije (index, create, edit)
- [ ] StoreTransactionRequest i UpdateTransactionRequest validacija
- [ ] Filter transakcija po datumu, kategoriji, tipu
- [ ] Paginacija na listi transakcija (20 po stranici)
- [ ] BalanceService — logika korekcije balansa pri CRUD operacijama
- [ ] Prikaz tekućeg balansa u glavnoj navigaciji
- [ ] Flash poruke (success/error) na svim operacijama
- [ ] Potvrda pre brisanja (Alpine.js modal)

## FAZA 3 — Recurring transakcije
- [ ] PlannedTransactionController sa svim CRUD metodama
- [ ] Blade view-ovi za planned transactions (index, create, edit)
- [ ] StorePlannedTransactionRequest i UpdatePlannedTransactionRequest
- [ ] PlannedTransactionService::calculateNextDueDate() logika
- [ ] Artisan komanda `planned-transactions:process`
- [ ] Scheduled job (svaki dan u ponoć) za procesiranje dospelih transakcija
- [ ] Akcija "Potvrdi" — kreira Transaction, pomera next_due_date
- [ ] Akcija "Preskoči" — samo pomera next_due_date
- [ ] Badge u navigaciji sa brojem dospelih planned transakcija
- [ ] Sekcija "Dospele danas" na planned-transactions/index

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
