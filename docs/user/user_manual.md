# Korisnički priručnik — Personal Finance Tracker

> Ovaj dokument se dopunjava nakon svake faze razvoja.
> Poslednje ažuriranje: 2026-04-04 (Faza 5)

---

## 1. Uvod u aplikaciju

Personal Finance Tracker je web aplikacija koja ti pomaže da:
- Pratiš svoje prihode i rashode
- Planiš buduće troškove i uplate
- Vizuelno analiziraš svoju potrošnju kroz grafike i izveštaje

---

## 2. Registracija i prijava

### Registracija

1. Otvori aplikaciju na http://localhost:8000
2. Klikni na "Register"
3. Unesi ime, email adresu i lozinku
4. Klikni "Create Account"
5. Automatski si prijavljen i preusmeren na Dashboard

### Prijava

1. Klikni na "Log In"
2. Unesi email i lozinku
3. Opciono: označi "Remember me" za ostanak prijavljenim
4. Klikni "Log In"

### Odjava

- Klikni na svoje ime u gornjem desnom uglu
- Izaberi "Log Out"

---

## 3. Dashboard

Dashboard je početna stranica nakon prijave. Prikazuje pregled tvojih finansija na jednom mestu.

### Period switcher

U gornjem desnom uglu možeš izabrati period za koji se prikazuju podaci:
- **Ovaj mesec** (podrazumevano)
- **Prošli mesec**
- **Poslednja 3 meseca**

### Summary kartice

Na vrhu su 4 kartice:
| Kartica | Opis |
|---------|------|
| **Tekući balans** | Tvoj ukupni balans u aplikaciji (pravi zbir svih transakcija) |
| **Prihodi** | Suma prihoda u izabranom periodu |
| **Rashodi** | Suma rashoda u izabranom periodu |
| **Neto perioda** | Prihodi minus rashodi za izabrani period |

### Dospele planirane transakcije

Ako imaš planirane transakcije čiji je datum dospeća prošao, prikazuju se kao crveni alert sa dugmićima **Potvrdi** i **Preskoči** direktno iz dashboarda.

### Grafici

- **Prihodi vs Rashodi (dnevni)** — line chart koji prikazuje kretanje prihoda i rashoda po danima u izabranom periodu
- **Rashodi po kategorijama** — donut chart koji vizuelno prikazuje udeo svake kategorije rashoda
- **Mesečno poređenje** — bar chart koji poredi prihode i rashode po mesecima za poslednjih 6 meseci

### Budžetski limiti

Ako si nekim kategorijama rashoda postavio mesečni limit, ovde vidiš koliko si iskoristio:
- Zelena traka → ispod 80% limita
- Žuta traka → između 80% i 100% (blizu limita)
- Crvena traka → prekoračen limit

### Poslednje transakcije i predstojeće planirane

Na dnu dashboarda su dva panela:
- **Poslednje transakcije** — 10 najnovijih unosa
- **Predstojeće u narednih 7 dana** — planirane transakcije koje dospevaju uskoro

---

## 4. Upravljanje kategorijama

Nakon registracije, aplikacija automatski kreira 13 podrazumevanih kategorija (4 prihoda, 9 rashoda) na tvom nalogu. Možeš ih menjati ili dodavati nove.

### Pregled kategorija

- Klikni na **Kategorije** u navigaciji
- Kategorije su grupisane u dve kolone: Prihodi i Rashodi
- Svaka kategorija ima boju i naziv

### Dodavanje kategorije

1. Na stranici Kategorije, klikni **+ Nova kategorija**
2. Unesi naziv (maks. 100 karaktera)
3. Izaberi tip: **Prihod** ili **Rashod**
4. Izaberi boju
5. Opcionalno unesi **mesečni limit (RSD)** — samo za rashode, prikazuje se kao progress bar na dashboardu
6. Klikni **Sačuvaj**

### Izmena kategorije

1. Pored kategorije klikni **Izmeni**
2. Promeni željene podatke
3. Klikni **Sačuvaj izmene**

### Brisanje kategorije

- Klikni **Obriši** pored kategorije i potvrdi dijalog
- Kategorija se **ne može obrisati** ako ima vezanih transakcija — moraš prvo obrisati ili promeniti kategoriju tim transakcijama

---

## 5. Unos transakcija

### Pregled transakcija

- Klikni na **Transakcije** u navigaciji
- Lista je sortirana od najnovijeg ka starijem
- Prikazuje se 20 transakcija po stranici

### Filtriranje

Na vrhu liste možeš filtrirati po:
- **Od datuma / Do datuma** — vremenski opseg
- **Kategorija** — konkretna kategorija
- **Tip** — Prihod ili Rashod

Klikni **Filtriraj** za primenu, **Reset** za uklanjanje filtera.

### Dodavanje transakcije

1. Klikni **+ Nova transakcija**
2. Izaberi tip: **Prihod** ili **Rashod**
3. Unesi iznos u RSD
4. Izaberi kategoriju (grupisano po tipu)
5. Unesi datum (podrazumevano: danas)
6. Opcionalno unesi opis
7. Klikni **Dodaj transakciju**

Tekući balans se automatski ažurira (prihod ga povećava, rashod smanjuje).

### Izmena transakcije

1. Klikni **Izmeni** pored transakcije
2. Promeni željene podatke
3. Klikni **Sačuvaj izmene**

Balans se automatski koriguje (stari efekat se poništava, novi primenjuje).

### Brisanje transakcije

- Klikni **Obriši** pored transakcije i potvrdi dijalog
- Efekat transakcije na balans se automatski poništava

---

## 6. Planirane transakcije

Planirane transakcije su buduće uplate ili rashodi koji se ponavljaju po određenom rasporedu — npr. mesečna kirija, pretplata, plata.

### Pregled planiranih transakcija

Klikni na **Planirane** u navigaciji. Stranica je podeljena u tri sekcije:

1. **Dospele danas ili zakasnele** (crvena sekcija) — zahtevaju akciju
2. **Predstojeće (aktivne)** — buduće planirane transakcije
3. **Neaktivne** — jednokratne transakcije koje su već obrađene

Ako ima dospelih transakcija, u navigaciji se prikazuje **crveni badge** sa brojem.

### Dodavanje planirane transakcije

1. Klikni **+ Nova planirana transakcija**
2. Izaberi tip: **Prihod** ili **Rashod**
3. Unesi iznos u RSD
4. Opcionalno izaberi kategoriju i opis
5. Unesi datum prvog dospeća
6. Izaberi tip ponavljanja:
   - **Jednokratno** — neće se ponavljati (deaktivira se nakon potvrde)
   - **Dnevno** — svaki dan
   - **Nedeljno** — svake 7 dana
   - **Mesečno** — isti dan u mesecu (možeš zadati dan 1–31)
   - **Godišnje** — isti datum svake godine
7. Klikni **Kreiraj**

### Potvrda dospele transakcije

Kada transakcija dospeva (datum dospeća je danas ili ranije):

- **Potvrdi** — kreira stvarnu transakciju u evidenciji i ažurira tekući balans. Datum dospeća se automatski pomera na sledeći period.
- **Preskoči** — samo pomera datum dospeća na sledeći period, bez kreiranja transakcije.

### Izmena i brisanje

- Klikni **Izmeni** pored planirane transakcije da promeniš podatke ili datum dospeća.
- Klikni **Obriši** i potvrdi dijalog da trajno obrišeš planiranu transakciju.

---

## 7. Export podataka

Na stranici **Transakcije** nalaze se dva dugmeta za preuzimanje podataka u gornjem desnom uglu:

### CSV export

- Klikni dugme **CSV** pored "+ Nova transakcija"
- Preuzima se fajl `transakcije_YYYY-MM-DD.csv`
- Separator kolona: `;` (semicolon) — kompatibilan sa Microsoft Excel
- Kolone: Datum; Tip; Kategorija; Opis; Iznos (RSD)
- Fajl je UTF-8 enkodovan (sa BOM) — srpski karakteri su ispravno prikazani

### PDF export

- Klikni dugme **PDF**
- Preuzima se fajl `transakcije_YYYY-MM-DD.pdf`
- PDF uključuje:
  - Zaglavlje sa tvojim imenom, periodom i datumom generisanja
  - Summary kartice: Prihodi, Rashodi, Neto, Broj transakcija
  - Kompletnu tabelu transakcija

### Filtriran export

Export uvek odražava **aktivne filtere** na stranici. Na primer:
- Postavi filter "Od: 01.01.2026 Do: 31.01.2026" i klikni CSV → dobijaš samo januarske transakcije
- Filteri se automatski prenose u URL export dugmića

---

## 8. Česta pitanja (FAQ)

**Zašto se rashod koji sam unio ne vidi na grafikonu?**  
Proveri da li je datum transakcije u okviru izabranog perioda na dashboardu (period switcher u gornjem desnom uglu).

**Zaboravio/la sam da potvrdim dospelu planiranu transakciju. Šta se dešava?**  
Transakcija ostaje u "Dospele danas ili zakasnele" sekciji sve dok je ne potvrdićeš ili preskočiš. Nema automatskog odbacivanja.

**Mogu li da promenim valutu?**  
Aplikacija trenutno podržava samo RSD. Viševalutna podrška je planirana za buduće verzije.

**Zašto ne mogu da obrišem kategoriju?**  
Kategorija ima vezane transakcije. Promeni kategoriju tim transakcijama (ili ih obriši), pa pokušaj ponovo.

**Kako da resetujem balans?**  
Balans se automatski izračunava iz svih transakcija. Nema ručnog resetovanja — ako je netačan, proveri da li postoje transakcije s greškom u tipu ili iznosu.
