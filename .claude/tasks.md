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

## FAZA 4 — Dashboard & Grafovi ✅ ZAVRŠENO
- [x] DashboardController sa svim potrebnim agregatima
- [x] Summary cards (balans, prihodi, rashodi, neto mesec)
- [x] Lista poslednjih 10 transakcija na dashboardu
- [x] Widget za upcoming planned transactions (7 dana)
- [x] Instalacija Chart.js (via CDN)
- [x] Line chart: prihodi vs rashodi po danima (tekući mesec)
- [x] Pie/Donut chart: rashodi po kategorijama (tekući mesec)
- [x] Bar chart: prihodi vs rashodi po mesecima (poslednjih 6)
- [x] Dodavanje `monthly_limit` kolone u categories tabelu (nova migracija)
- [x] Progress bar indikatori budžetskih limita po kategoriji
- [x] Period switcher (ovaj mesec / prošli mesec / posled. 3 meseca)

## FAZA 5 — Polish & Export ✅ ZAVRŠENO
- [x] ExportController sa CSV i PDF akcijama
- [x] Plain CSV export (PHP fwrite, UTF-8 BOM, separator ;)
- [x] Instalacija barryvdh/laravel-dompdf
- [x] Blade template za PDF export (resources/views/exports/transactions-pdf.blade.php)
- [x] Export dugmad na transactions/index header (CSV + PDF)
- [x] Responsive dizajn audit — overflow-x-auto na tabelama, balans u mobile meniju
- [x] Hamburger navigacija za mobile — već bila Breeze default
- [x] Kreiranje 404.blade.php i 500.blade.php
- [x] Try/catch u svim Controller metodama koje pišu u bazu — već bio urađen
- [x] Rate limiting (throttle:60,1) na store/update/destroy route-ove
- [x] Kompletiranje docs/technical/technical_manual.md (sekcije 6–11)
- [x] Kompletiranje docs/user/user_manual.md (sekcija 7, 8)
- [x] Kreiranje docs/test/E2E_test_plan_v1.md (59 scenarija)
- [x] Finalna revizija svih FormRequest validacija — validne
- [x] `php artisan optimize` — cache izgrađen uspešno

## Backlog / Buduće ideje
- [ ] Viševalutna podrška (praćenje u više valuta)
- [ ] Import transakcija iz CSV fajla
- [ ] Email notifikacije za dospele planirane transakcije
- [ ] Mobile app (PWA ili React Native)
- [ ] API endpoints za mobilni klijent
- [ ] Deljeni budžet između korisnika (npr. porodični račun)
- [ ] Integracija sa bankarskim API-jem (automatski import)
- [ ] AI asistent za analizu potrošnje i preporuke uštedine
