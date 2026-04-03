# Project Architecture

## Tech Stack

| Tehnologija      | Verzija | Razlog izbora                                                        |
|------------------|---------|----------------------------------------------------------------------|
| PHP              | 8.3     | LTS verzija, typed properties, enums, fibers                        |
| Laravel          | 11      | Aktuelna LTS, streamlined struktura, native enum podrška            |
| MySQL            | 8       | Pouzdana relacijska baza, dobra podrška za Laravel                  |
| Blade            | —       | Native Laravel template engine, bez overhead-a JS framework-a       |
| Alpine.js        | 3.x     | Lightweight reaktivnost za UI interakcije bez full SPA              |
| TailwindCSS      | 3.x     | Utility-first CSS, dobra integracija sa Vite                        |
| Vite             | 5.x     | Brz bundler, native Laravel integracija od v9                       |
| Laravel Breeze   | 2.x     | Minimal auth scaffolding, Blade stack, bez bloatware-a              |

---

## Arhitektura aplikacije (tekstualni dijagram)

```
Browser (Blade + Alpine.js)
        |
        | HTTP Request
        v
┌─────────────────────────────────────────────────────┐
│                  Laravel 11 App                      │
│                                                      │
│  routes/web.php                                      │
│       |                                              │
│       v                                              │
│  Middleware (Auth, Throttle, CSRF)                   │
│       |                                              │
│       v                                              │
│  Controllers/                                        │
│    ├── DashboardController                           │
│    ├── TransactionController                         │
│    ├── CategoryController                            │
│    ├── PlannedTransactionController                  │
│    └── ExportController                              │
│       |                                              │
│       v                                              │
│  Services/ (business logic)                          │
│    ├── BalanceService                                │
│    └── PlannedTransactionService                     │
│       |                                              │
│       v                                              │
│  Models/ (Eloquent ORM)                              │
│    ├── User                                          │
│    ├── Category                                      │
│    ├── Transaction                                   │
│    └── PlannedTransaction                            │
│       |                                              │
└───────|──────────────────────────────────────────────┘
        |
        v
    MySQL 8 Database
```

---

## Struktura baze podataka

### Tabela: `users`
```
id                  bigint PK auto_increment
name                varchar(255)
email               varchar(255) unique
email_verified_at   timestamp nullable
password            varchar(255)
current_balance     decimal(15,2) default 0.00
remember_token      varchar(100) nullable
created_at          timestamp
updated_at          timestamp
```

### Tabela: `categories`
```
id              bigint PK auto_increment
user_id         bigint FK→users nullable (null = default/sistemska kategorija)
name            varchar(100)
icon            varchar(50) nullable
color           varchar(7) default '#6B7280'
type            enum('income','expense')
is_default      tinyint(1) default 0
monthly_limit   decimal(15,2) nullable
created_at      timestamp
updated_at      timestamp

INDEX: (user_id, type)
```

### Tabela: `transactions`
```
id                  bigint PK auto_increment
user_id             bigint FK→users ON DELETE CASCADE
category_id         bigint FK→categories nullable ON DELETE SET NULL
type                enum('income','expense')
amount              decimal(15,2)
description         varchar(255) nullable
transaction_date    date
is_confirmed        tinyint(1) default 1
created_at          timestamp
updated_at          timestamp

INDEX: (user_id, transaction_date)
INDEX: (user_id, type)
```

### Tabela: `planned_transactions`
```
id                  bigint PK auto_increment
user_id             bigint FK→users ON DELETE CASCADE
category_id         bigint FK→categories nullable ON DELETE SET NULL
type                enum('income','expense')
amount              decimal(15,2)
description         varchar(255) nullable
recurrence_type     enum('none','daily','weekly','monthly','yearly')
recurrence_day      int nullable  (1-31, za monthly; dan u nedelji za weekly)
next_due_date       date
is_active           tinyint(1) default 1
created_at          timestamp
updated_at          timestamp

INDEX: (user_id, next_due_date)
INDEX: (user_id, is_active)
```

---

## Relacije između modela

```
User
  hasMany → Category (user_id)
  hasMany → Transaction (user_id)
  hasMany → PlannedTransaction (user_id)

Category
  belongsTo → User (nullable)
  hasMany → Transaction
  hasMany → PlannedTransaction

Transaction
  belongsTo → User
  belongsTo → Category (nullable)

PlannedTransaction
  belongsTo → User
  belongsTo → Category (nullable)
```

---

## Tok autentifikacije (Breeze flow)

```
/register → RegisteredUserController@store
              → Kreira User
              → Kopira default kategorije za novog korisnika
              → Login + redirect → /dashboard

/login → AuthenticatedSessionController@store
           → Validacija
           → Auth::login()
           → redirect → /dashboard

/logout → POST → AuthenticatedSessionController@destroy
            → Auth::logout()
            → redirect → /

Zaštićene rute: middleware('auth')
Gost rute: middleware('guest')
```

---

## Folder struktura Laravel projekta

```
app/
├── Console/
│   └── Commands/
│       └── ProcessPlannedTransactions.php
├── Http/
│   ├── Controllers/
│   │   ├── Auth/                    ← Breeze controllers
│   │   ├── CategoryController.php
│   │   ├── DashboardController.php
│   │   ├── ExportController.php
│   │   ├── PlannedTransactionController.php
│   │   └── TransactionController.php
│   └── Requests/
│       ├── StoreCategoryRequest.php
│       ├── UpdateCategoryRequest.php
│       ├── StorePlannedTransactionRequest.php
│       ├── UpdatePlannedTransactionRequest.php
│       ├── StoreTransactionRequest.php
│       └── UpdateTransactionRequest.php
├── Models/
│   ├── Category.php
│   ├── PlannedTransaction.php
│   ├── Transaction.php
│   └── User.php
└── Services/
    ├── BalanceService.php
    └── PlannedTransactionService.php

database/
├── factories/
│   ├── CategoryFactory.php
│   ├── PlannedTransactionFactory.php
│   └── TransactionFactory.php
├── migrations/
│   ├── xxxx_create_users_table.php         ← Breeze default + current_balance
│   ├── xxxx_create_categories_table.php
│   ├── xxxx_create_transactions_table.php
│   └── xxxx_create_planned_transactions_table.php
└── seeders/
    ├── CategorySeeder.php
    └── DatabaseSeeder.php

resources/
└── views/
    ├── auth/                    ← Breeze views
    ├── categories/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── components/              ← Reusable Blade components
    ├── dashboard.blade.php
    ├── exports/
    │   └── transactions-pdf.blade.php
    ├── layouts/
    │   ├── app.blade.php
    │   └── guest.blade.php
    ├── planned-transactions/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    └── transactions/
        ├── index.blade.php
        ├── create.blade.php
        └── edit.blade.php

routes/
├── auth.php                     ← Breeze routes
└── web.php                      ← Sve app routes
```

---

## Konvencije imenovanja

| Tip              | Format             | Primer                          |
|------------------|--------------------|---------------------------------|
| DB tabele        | snake_case plural  | `planned_transactions`          |
| Modeli           | PascalCase         | `PlannedTransaction`            |
| Controllers      | PascalCase + sufix | `PlannedTransactionController`  |
| FormRequests     | Verb+Model+Request | `StorePlannedTransactionRequest`|
| Services         | PascalCase+Service | `PlannedTransactionService`     |
| Routes (URL)     | kebab-case         | `/planned-transactions`         |
| Route names      | dot.notation       | `planned-transactions.index`    |
| Blade views      | kebab-case dirs    | `planned-transactions/index`    |
| JS variables     | camelCase          | `transactionAmount`             |
| CSS klase        | Tailwind utility   | `text-gray-700 font-semibold`   |
