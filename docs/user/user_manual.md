# Korisnički priručnik — Personal Finance Tracker

> Ovaj dokument se dopunjava nakon svake faze razvoja.
> Poslednje ažuriranje: 2026-04-04 (Faza 3)

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

> Sekcija se popunjava u Fazi 4

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
5. Klikni **Sačuvaj**

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

> Sekcija se popunjava u Fazi 5

---

## 8. Česta pitanja (FAQ)

> Sekcija se popunjava tokom razvoja na osnovu korisničkih pitanja
