# E2E Test Plan v1 — Personal Finance Tracker

> Verzija: 1.0  
> Datum: 2026-04-04  
> Okruženje: http://localhost:8000

---

## Preduslovi

- PHP server pokrenut: `php artisan serve`
- Assets kompajlirani: `npm run build`
- Baza resitovana: `php artisan migrate:fresh --seed`
- Browser: Chrome/Firefox na desktop (1280px) i mobile emulacija (375px)

---

## TS-01 — Autentifikacija

| ID | Scenario | Koraci | Očekivani rezultat |
|----|----------|--------|--------------------|
| TS-01-01 | Registracija novog korisnika | 1. Otvori /register<br>2. Popuni ime, email, lozinku<br>3. Klikni "Create Account" | Preusmerenje na /dashboard, kreirana je sesija, u bazi postoji novi user sa 13 kategorija |
| TS-01-02 | Registracija sa postojećim emailom | Pokušaj registracije sa emailom koji već postoji | Validaciona greška: "The email has already been taken" |
| TS-01-03 | Prijava sa ispravnim podacima | 1. Otvori /login<br>2. Unesi email i lozinku<br>3. Klikni "Log In" | Preusmerenje na /dashboard |
| TS-01-04 | Prijava sa pogrešnom lozinkom | Unesi ispravni email, pogrešnu lozinku | Greška: "These credentials do not match our records" |
| TS-01-05 | Odjava | Klikni na ime → Log Out | Preusmerenje na /, sesija uklonjena |
| TS-01-06 | Zaštita ruta bez prijave | Direktno pristupi /dashboard bez prijave | Preusmerenje na /login |

---

## TS-02 — Dashboard

| ID | Scenario | Koraci | Očekivani rezultat |
|----|----------|--------|--------------------|
| TS-02-01 | Period switcher — ovaj mesec | Izaberi "Ovaj mesec" | Kartice i grafici prikazuju podatke tekućeg meseca |
| TS-02-02 | Period switcher — prošli mesec | Izaberi "Prošli mesec" | Podaci se menjaju u skladu |
| TS-02-03 | Period switcher — poslednja 3 meseca | Izaberi "Poslednja 3 meseca" | Podaci pokrivaju 3-mesečni period |
| TS-02-04 | Prikaz dospelih planiranih | Postoji planirana transakcija sa next_due_date ≤ danas | Prikazuje se crveni alert sa dugmićima Potvrdi/Preskoči |
| TS-02-05 | Potvrdi dospelu sa dashboarda | Klikni Potvrdi na dospeloj | Flash "Transakcija je potvrđena", kartica balansa se ažurira |
| TS-02-06 | Line chart renderovanje | Postoji bar chart vidljiv na dashboardu | Grafik se prikazuje sa labelama dana |
| TS-02-07 | Donut chart renderovanje | Postoji barem jedna expense transakcija | Donut chart prikazuje kategorije |
| TS-02-08 | Budget progress bar | Kategorija ima monthly_limit i rashode | Progress bar sa ispravnom bojom (zelena/žuta/crvena) |

---

## TS-03 — Kategorije

| ID | Scenario | Koraci | Očekivani rezultat |
|----|----------|--------|--------------------|
| TS-03-01 | Pregled kategorija | Klikni Kategorije u navigaciji | Prikazane su dve kolone: Prihodi i Rashodi |
| TS-03-02 | Kreiranje kategorije | Klikni "+ Nova kategorija", popuni formu, Sačuvaj | Flash "Kategorija je uspešno kreirana", kategorija vidljiva u listi |
| TS-03-03 | Kreiranje bez naziva | Pokušaj sačuvati formu bez naziva | Validaciona greška na polju "name" |
| TS-03-04 | Kreiranje sa pogrešnim hex bojom | Unesi "red" umesto "#FF0000" | Validaciona greška na polju "color" |
| TS-03-05 | Izmena kategorije | Klikni Izmeni, promeni naziv, sačuvaj | Flash "Kategorija je uspešno ažurirana" |
| TS-03-06 | Brisanje kategorije bez transakcija | Klikni Obriši, potvrdi dijalog | Kategorija uklonjena iz liste |
| TS-03-07 | Brisanje kategorije sa transakcijama | Pokušaj obrisati kategoriju koja ima transakcije | Flash greška, kategorija ostaje |
| TS-03-08 | Unos mesečnog limita | Unesi 10000 u polje mesečni limit | Limit sačuvan, prikazuje se budget bar na dashboardu |

---

## TS-04 — Transakcije

| ID | Scenario | Koraci | Očekivani rezultat |
|----|----------|--------|--------------------|
| TS-04-01 | Pregled transakcija | Klikni Transakcije | Lista sa paginacijom, 20 po stranici |
| TS-04-02 | Dodavanje transakcije — prihod | Klikni "+ Nova transakcija", izaberi Prihod, unesi 5000 RSD | Balans povećan za 5000, flash success |
| TS-04-03 | Dodavanje transakcije — rashod | Klikni "+ Nova transakcija", izaberi Rashod, unesi 1500 RSD | Balans smanjen za 1500, flash success |
| TS-04-04 | Unos negativnog iznosa | Pokušaj uneti -100 | Validaciona greška |
| TS-04-05 | Unos bez kategorije | Pokušaj sačuvati bez kategorije | Validaciona greška na polju "category_id" |
| TS-04-06 | Izmena transakcije | Klikni Izmeni, promeni iznos, sačuvaj | Balans korigovan (stari efekat poništen, novi primenjen) |
| TS-04-07 | Brisanje transakcije | Klikni Obriši, potvrdi dijalog | Transakcija uklonjena, balans korigovan |
| TS-04-08 | Filter po datumu | Unesi opseg datuma, klikni Filtriraj | Lista prikazuje samo transakcije u opsegu |
| TS-04-09 | Filter po kategoriji | Izaberi kategoriju iz drop-down | Lista prikazuje samo tu kategoriju |
| TS-04-10 | Filter po tipu | Izaberi "Rashod" | Lista prikazuje samo expense transakcije |
| TS-04-11 | Reset filtera | Klikni Reset | Svi filteri uklonjeni, prikazuje se kompletna lista |
| TS-04-12 | Paginacija | Postoji >20 transakcija | Prikazani su linkovi za stranicu 2, 3, itd. |

---

## TS-05 — Planirane transakcije

| ID | Scenario | Koraci | Očekivani rezultat |
|----|----------|--------|--------------------|
| TS-05-01 | Kreiranje mesečne planirane | Tip: rashod, iznos 5000, recurrence: monthly, day: 1 | Planirana vidljiva u "Predstojeće" sekciji |
| TS-05-02 | Kreiranje jednokratne | Recurrence: none | Nakon potvrde → status neaktivno |
| TS-05-03 | Badge u navigaciji | Postoji dospela planirana transakcija | Crveni badge sa brojem u "Planirane" linku |
| TS-05-04 | Potvrda dospele | Klikni Potvrdi | Nova transakcija kreirana, balans ažuriran, next_due_date pomeren |
| TS-05-05 | Preskakanje dospele | Klikni Preskoči | next_due_date pomeren bez kreiranja transakcije |
| TS-05-06 | Izmena planirane | Klikni Izmeni, promeni iznos | Izmena sačuvana |
| TS-05-07 | Brisanje planirane | Klikni Obriši, potvrdi | Uklonjena iz svih sekcija |
| TS-05-08 | Neaktivne sekcija | Jednokratna planirana je potvrđena | Prikazuje se u "Neaktivne" sekciji |

---

## TS-06 — Export

| ID | Scenario | Koraci | Očekivani rezultat |
|----|----------|--------|--------------------|
| TS-06-01 | CSV export bez filtera | Na stranici Transakcije klikni "CSV" | Preuzimanje fajla `transakcije_YYYY-MM-DD.csv` |
| TS-06-02 | CSV export sa filterom | Postavi filter datuma, klikni "CSV" | CSV sadrži samo filtrirane transakcije |
| TS-06-03 | CSV sadržaj | Otvori CSV u text editoru | Zaglavlje: Datum;Tip;Kategorija;Opis;Iznos (RSD), UTF-8 BOM prisutan |
| TS-06-04 | PDF export bez filtera | Klikni "PDF" | Preuzimanje fajla `transakcije_YYYY-MM-DD.pdf` |
| TS-06-05 | PDF export sa filterom | Postavi filter, klikni "PDF" | PDF sadrži samo filtrirane transakcije i ispravne zbrojeve |
| TS-06-06 | PDF sadržaj | Otvori PDF | Prikazani: summary kartice (Prihodi/Rashodi/Neto/Broj), tabela transakcija |
| TS-06-07 | Export praznog skupa | Filter koji ne vraća nijednu transakciju → CSV/PDF | Fajl se preuzima sa zaglavljem ali bez redova (poruka "Nema transakcija") |

---

## TS-07 — Responsive dizajn

| ID | Scenario | Koraci | Očekivani rezultat |
|----|----------|--------|--------------------|
| TS-07-01 | Mobile navigacija (375px) | Chrome DevTools → iPhone SE emulacija | Hamburger ikona vidljiva, desktop meni skriven |
| TS-07-02 | Otvaranje mobile menija | Klikni hamburger ikonu | Meni se otvori, prikazuju se svi linkovi i balans |
| TS-07-03 | Tabela na mobile | Otvori /transactions na 375px | Horizontalni scroll dostupan, tabela čitljiva |
| TS-07-04 | Dashboard na tablet (768px) | Chrome DevTools → iPad emulacija | Summary kartice u 2 kolone, grafici vidljivi |
| TS-07-05 | Forme na mobile | Otvori /transactions/create na 375px | Sva polja vidljiva, forma submitabilna |

---

## TS-08 — Error stranice

| ID | Scenario | Koraci | Očekivani rezultat |
|----|----------|--------|--------------------|
| TS-08-01 | 404 stranica | Pristupi /nepostojeca-ruta | Prikazana 404 stranica sa linkovima "Nazad" i "Na Dashboard" |
| TS-08-02 | Autorizacija 403 | Pokušaj edita tuđe transakcije (direktan URL) | HTTP 403 ili preusmerenje |

---

## TS-09 — Sigurnost i rate limiting

| ID | Scenario | Koraci | Očekivani rezultat |
|----|----------|--------|--------------------|
| TS-09-01 | Rate limit na store | Pošalji >60 POST zahteva na /transactions u 1 minuti | HTTP 429 Too Many Requests |
| TS-09-02 | CSRF zaštita | Pošalji POST bez CSRF tokena | HTTP 419 Page Expired |
| TS-09-03 | Pristup tuđim podacima | Uloguj se kao korisnik A, pokušaj pristupiti /categories/{id} korisnika B | HTTP 403 Forbidden |

---

## Napomene

- Testiranje se vrši manuelno u browseru, osim TS-09-01 koji zahteva alat poput Postman/curl
- Za reset između test sekvenci: `php artisan migrate:fresh --seed`
- Logovi grešaka: `storage/logs/laravel.log`
