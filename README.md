<div align="center">

```
██╗███╗   ██╗██╗   ██╗███████╗███╗   ██╗████████╗ ██████╗ ██████╗ ██╗   ██╗
██║████╗  ██║██║   ██║██╔════╝████╗  ██║╚══██╔══╝██╔═══██╗██╔══██╗╚██╗ ██╔╝
██║██╔██╗ ██║██║   ██║█████╗  ██╔██╗ ██║   ██║   ██║   ██║██████╔╝ ╚████╔╝ 
██║██║╚██╗██║╚██╗ ██╔╝██╔══╝  ██║╚██╗██║   ██║   ██║   ██║██╔══██╗  ╚██╔╝  
██║██║ ╚████║ ╚████╔╝ ███████╗██║ ╚████║   ██║   ╚██████╔╝██║  ██║   ██║   
╚═╝╚═╝  ╚═══╝  ╚═══╝  ╚══════╝╚═╝  ╚═══╝   ╚═╝    ╚═════╝ ╚═╝  ╚═╝   ╚═╝  
```

**JM INTERNATIONAL — Production-ready Inventory & Point-of-Sale platform built on Laravel 10**

[![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![SQLite](https://img.shields.io/badge/SQLite-Default_DB-003B57?style=for-the-badge&logo=sqlite&logoColor=white)](https://sqlite.org)
[![Sanctum](https://img.shields.io/badge/Sanctum-API_Auth-F05340?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/docs/sanctum)
[![License](https://img.shields.io/badge/License-MIT-22c55e?style=for-the-badge)](LICENSE)

</div>

---

## 🗺️ System at a Glance

```
╔══════════════════════════════════════════════════════════════════════════╗
║                    INVENTORY MANAGEMENT SYSTEM                          ║
╠══════════════════╦═══════════════════╦════════════════╦═════════════════╣
║   👤 Auth        ║   📦 Inventory    ║   🛒 POS       ║   💰 Finance   ║
║   ─────────      ║   ─────────────   ║   ────────     ║   ──────────   ║
║   Login          ║   Products        ║   Quick Sale   ║   Due Collect  ║
║   Roles (RBAC)   ║   Categories      ║   Barcode Scan ║   EMI / Plans  ║
║   Login History  ║   FIFO Stock      ║   Multi-Pay    ║   Expenses     ║
║   Activity Log   ║   Stock Batches   ║   Invoice A4   ║   P&L Report   ║
║   Profile Mgmt   ║   Adjustments     ║   Thermal 80mm ║   Quotations   ║
╠══════════════════╬═══════════════════╬════════════════╬═════════════════╣
║   📊 Reports     ║   👥 CRM          ║   ⚙️ Admin     ║   🔌 API       ║
║   ─────────      ║   ────────────    ║   ──────────   ║   ──────────   ║
║   Sales & Profit ║   Customer Ledger ║   Multi-Branch ║   Products     ║
║   Daily Summary  ║   Supplier Ledger ║   SMS Gateway  ║   Categories   ║
║   Top Products   ║   Loyalty Points  ║   DB Backup    ║   Stock Check  ║
║   Stock Value    ║   Warranty Track  ║   Settings     ║   Sanctum Auth ║
║   Dashboard      ║   Purchase Hist.  ║   User Manual  ║   REST v1      ║
╚══════════════════╩═══════════════════╩════════════════╩═════════════════╝
```

---

## ⚡ Feature Highlights

### 🛒 Point of Sale
| | Feature | Details |
|---|---------|---------|
| ⚡ | **Instant Search** | Find products by name, SKU, barcode, or model |
| 📷 | **Barcode Scan** | EAN-13 / CODE128 support out of the box |
| 💳 | **Multi-Payment** | Cash · Card · Mobile Banking · Due |
| 👤 | **Quick Customer** | Register customer mid-sale without leaving POS |
| 🧾 | **Invoice (A4)** | Branded PDF with company logo, QR, terms |
| 🖨️ | **Thermal Receipt** | 58 mm & 80 mm POS printer optimized |
| 🔄 | **Auto Stock-Out** | FIFO deduction + warranty expiry auto-calculated |

### 📦 Inventory & Stock
| | Feature | Details |
|---|---------|---------|
| 📊 | **FIFO Costing** | Per-batch cost tracking — exact COGS on every sale |
| 📋 | **Movement Log** | Full audit trail: every stock-in, out, and adjustment |
| ⚠️ | **Low Stock Alerts** | Threshold-based dashboard warnings |
| 🔧 | **Manual Adjustments** | Add / subtract / set stock for corrections |
| 🏷️ | **Barcode Labels** | Auto-generate EAN-13 labels — A4 & thermal layouts |
| 📥 | **Bulk Import** | Spreadsheet upload for products & purchase orders |

### 💰 Finance & Accounting
| | Feature | Details |
|---|---------|---------|
| 💵 | **Due Collection** | Partial payments on sales and supplier purchases |
| 📅 | **Installment / EMI** | Monthly plans with due-date tracking |
| 🧾 | **Expense Tracking** | Categorized spend; create categories on the fly |
| 📊 | **Profit & Loss** | Revenue − COGS − Returns − Expenses = Net Profit |
| 💱 | **Quotations** | Printable price estimates before sale confirmation |
| 🏦 | **Cash Register** | Shift open / close with full balance accountability |

### 📈 Reports & Analytics
| | Feature | Details |
|---|---------|---------|
| 📊 | **Dashboard Charts** | 7-day sales trend line + category doughnut (Chart.js 4) |
| 💹 | **Sales & Profit** | Per-sale revenue, COGS, and margin % |
| 📅 | **Daily Summary** | Day-by-day revenue breakdown |
| 🏆 | **Top Products** | Best sellers ranked by qty & revenue |
| 📦 | **Stock Valuation** | Current stock value summed per FIFO batch |
| 📊 | **P&L Statement** | Complete printable profit & loss report |

### 👥 CRM & Contacts
| | Feature | Details |
|---|---------|---------|
| 📒 | **Customer Ledger** | Full purchase history · total · paid · outstanding |
| 📒 | **Supplier Ledger** | Full purchase history · total · paid · outstanding |
| ⭐ | **Loyalty Points** | Configurable points-per-purchase & redemption value |
| 🛡️ | **Warranty Tracking** | Auto expiry date per sale item — searchable |

### 📖 Accounting & Operations
| | Feature | Details |
|---|---------|---------|
| 📒 | **Day Book** | Chronological record of all financial transactions |
| ⚖️ | **Trial Balance** | Debit vs. credit totals verification |
| 📐 | **Units of Measure** | Manage measurement units (Pcs, Kg, Meters, etc.) |
| 📦 | **FIFO Batches** | View all stock batches with received date, unit cost, and remaining qty |

### 🔐 Security & Administration
| | Feature | Details |
|---|---------|---------|
| 🛡️ | **RBAC** | Spatie Permissions — 4 built-in roles, fully customizable |
| 📝 | **Activity Log** | Who · what · when · IP — every action recorded |
| 🔑 | **Login History** | User · timestamp · IP · browser for all logins |
| 🏢 | **Multi-Branch** | Add and switch between multiple store locations |
| 📱 | **SMS Gateway** | BulkSMSBD · SSL Wireless · Twilio · Custom HTTP |
| 💾 | **DB Backup** | One-click SQLite download from the dashboard |
| 📘 | **In-App Manual** | Scroll-spy user guide in English & Bangla · PDF export |

---

## 🔄 Data Flow

```
  ┌────────────┐     purchase      ┌────────────┐     stock-in      ┌─────────────┐
  │  Supplier  │ ─────────────────▶│  Purchase  │ ─────────────────▶│ Stock Batch │
  └────────────┘                   └────────────┘                   │  (FIFO)     │
                                                                     └──────┬──────┘
                                                                            │ deduct
  ┌────────────┐      sale         ┌────────────┐     stock-out             ▼
  │  Customer  │ ─────────────────▶│  POS Sale  │ ─────────────────▶┌─────────────┐
  └────────────┘                   └─────┬──────┘                   │  Movement   │
                                         │                           │    Log      │
                 ┌───────────────────────┼────────────────────┐     └─────────────┘
                 ▼                       ▼                    ▼
          ┌────────────┐        ┌────────────────┐   ┌──────────────┐
          │  Invoice   │        │ Warranty Set   │   │  Loyalty Pts │
          │  Receipt   │        │ (auto expiry)  │   │  Earned      │
          └────────────┘        └────────────────┘   └──────────────┘
                                         │
  ┌──────────────────────────────────────┘
  │
  ▼
  Revenue − COGS (FIFO) − Returns − Expenses  ═══▶  💰 Net Profit
```

---

## 💸 Money Flow

```
        💵 MONEY IN                              💸 MONEY OUT
     ─────────────────                        ─────────────────────
  ┌──────────────────────┐                 ┌───────────────────────┐
  │ 🛒  Sale Revenue      │◀── POS/Invoice  │ 📦  Purchase Cost     │──▶ Supplier
  ├──────────────────────┤                 ├───────────────────────┤
  │ 💳  Due Collection    │◀── Payments     │ 🏠  Operating Expenses│──▶ Rent/Bills
  ├──────────────────────┤                 ├───────────────────────┤
  │ 📅  EMI Installments  │◀── Schedules    │ ↩️  Customer Refunds  │──▶ Returns
  └──────────┬───────────┘                 └───────────┬───────────┘
             │                                         │
             └──────────────────┬────────────────────── ┘
                                ▼
                    ┌───────────────────────┐
                    │        📊 NET PROFIT   │
                    │                        │
                    │  Revenue               │
                    │  − Cost of Goods Sold  │
                    │  − Expenses            │
                    │  − Returns             │
                    │  ═══════════════════   │
                    │        = 💰 Profit     │
                    └───────────────────────┘
```

---

## 🏗️ Architecture

```
inventory-management/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController      ← KPIs, chart data
│   │   │   │   ├── PosController            ← Point of Sale
│   │   │   │   ├── ProductController        ← CRUD, bulk import, labels
│   │   │   │   ├── CategoryController       ← 3-level hierarchy
│   │   │   │   ├── PurchaseController       ← POs, bulk import
│   │   │   │   ├── SaleController           ← View, invoice, receipt
│   │   │   │   ├── SaleReturnController     ← Returns + stock restore
│   │   │   │   ├── StockController          ← View, adjust, movements
│   │   │   │   ├── PaymentController        ← Due collection
│   │   │   │   ├── InstallmentController    ← EMI plans
│   │   │   │   ├── ExpenseController        ← Categorized expenses
│   │   │   │   ├── QuotationController      ← Price estimates
│   │   │   │   ├── CashRegisterController   ← Shift open/close
│   │   │   │   ├── ReportController         ← 6 reports + P&L
│   │   │   │   ├── CustomerController       ← CRUD + ledger
│   │   │   │   ├── SupplierController       ← CRUD + ledger
│   │   │   │   ├── UserController           ← User management
│   │   │   │   ├── RoleController           ← Role management
│   │   │   │   ├── ProfileController        ← Profile & password
│   │   │   │   ├── SettingController        ← Business config, backup
│   │   │   │   ├── ActivityLogController    ← Audit trail
│   │   │   │   ├── LoginHistoryController   ← Login tracking
│   │   │   │   ├── BranchController         ← Multi-store
│   │   │   │   └── ManualController         ← In-app docs (EN + BN)
│   │   │   └── Api/
│   │   │       └── ProductApiController     ← REST API v1
│   │   └── Middleware/
│   └── Models/
│       ├── Product          ← Barcode, stockIn/stockOut (FIFO)
│       ├── Category         ← 3-level parent/child tree
│       ├── Sale             ← Header with totals, status
│       ├── SaleItem         ← warranty_expires, FIFO batch ref
│       ├── Purchase         ← Header with supplier
│       ├── PurchaseItem     ← Auto stock-in on save
│       ├── StockBatch       ← FIFO batches with unit cost
│       ├── StockMovement    ← Full movement audit log
│       ├── Payment          ← Polymorphic (sale / purchase)
│       ├── SaleReturn       ← Return header
│       ├── SaleReturnItem   ← Stock restore on save
│       ├── Expense          ← With ExpenseCategory
│       ├── Quotation        ← Price estimate with items
│       ├── InstallmentPlan  ← EMI schedule
│       ├── CashRegister     ← Shift records
│       ├── ActivityLog      ← Action tracking (who/what/when/IP)
│       ├── LoginHistory     ← Session / browser tracking
│       ├── LoyaltyTransaction ← Points earned & redeemed
│       ├── Branch           ← Multi-store locations
│       ├── Setting          ← Key-value app config
│       ├── Customer         ← With ledger totals
│       ├── Supplier         ← With ledger totals
│       └── User             ← Spatie HasRoles
├── routes/
│   ├── web.php              ← All admin panel routes
│   └── api.php              ← REST API v1 (Sanctum)
├── resources/views/         ← Blade templates (Bootstrap 5.3)
├── database/
│   ├── migrations/
│   └── seeders/             ← Demo data + default roles/users
└── public/
```

---

## 👤 Roles & Permissions

| Role | Dashboard | POS | Inventory | Finance | Reports | Users | Settings |
|------|:---------:|:---:|:---------:|:-------:|:-------:|:-----:|:--------:|
| 🌟 **Super Admin** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| 👔 **Manager** | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ |
| 📦 **Storekeeper** | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ |
| 🛒 **Salesperson** | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

> Roles and permissions are managed via [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) and are fully customizable from the admin panel.

---

## 🔌 REST API

All endpoints require a **Sanctum Bearer Token** in the `Authorization` header.

```
Base URL:  /api/v1

GET  /products                    List products (paginated, filterable)
GET  /products/{id}               Single product with category & stock
GET  /products/{id}/stock         Real-time stock availability
GET  /categories                  Full category tree (nested children)
```

**Example request:**
```bash
curl -H "Authorization: Bearer <token>" \
     https://your-domain.com/api/v1/products?search=laptop&category=5
```

**Example response:**
```json
{
  "data": [
    {
      "id": 12,
      "name": "Laptop Pro 15",
      "sku": "LP-2024-001",
      "barcode": "8901234567890",
      "price": 85000,
      "stock": 14,
      "category": { "id": 5, "name": "Laptops" }
    }
  ],
  "meta": { "current_page": 1, "total": 1 }
}
```

---

## 🛠️ Tech Stack

| Layer | Technology | Purpose |
|-------|-----------|---------|
| **Backend** | Laravel 10 · PHP 8.1+ | Application framework |
| **Frontend** | Bootstrap 5.3 · Bootstrap Icons 1.11 | UI components & icons |
| **Charts** | Chart.js 4 | Sales trend & category charts |
| **Barcodes** | JsBarcode | EAN-13 / CODE128 generation |
| **PDF** | mPDF | Invoice, receipt & report export |
| **Database** | SQLite (default) · MySQL compatible | Data persistence |
| **Auth** | Laravel Sanctum | Session + API token auth |
| **RBAC** | Spatie Laravel Permission | Role & permission management |
| **SMS** | BulkSMSBD · SSL Wireless · Twilio · Custom | Notification gateway |

---

## ⚡ Quick Start

### Requirements
```
PHP      ≥ 8.1
Composer ≥ 2.x
SQLite   (bundled with PHP — zero config)
```

### Install

```bash
# 1. Clone the repository
git clone <repo-url> inventory-management
cd inventory-management

# 2. Install PHP dependencies
composer install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Create the SQLite database file
touch database/database.sqlite          # Linux/macOS
# Windows: type nul > database\database.sqlite

# 5. Run migrations and seed demo data
php artisan migrate --seed

# 6. Link public storage
php artisan storage:link

# 7. Start the development server
php artisan serve
```

Open **http://localhost:8000** in your browser.

### Default Credentials

| Role | Email | Password |
|------|-------|----------|
| 🌟 Super Admin | `admin@example.com` | `password` |
| 🛒 Salesperson | `cashier@example.com` | `password` |

> ⚠️ **Change all default passwords immediately after your first login.**

---

## 🗃️ Database

The application ships with **SQLite** by default — no database server required. To switch to MySQL, update your `.env`:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory
DB_USERNAME=root
DB_PASSWORD=secret
```

Then run `php artisan migrate --seed`.

---

## 🧰 Artisan Commands

| Command | Purpose |
|---------|---------|
| `php artisan import:products` | Import products from `public/products.xlsx` |
| `php artisan seed:fifo-data` | Seed demo FIFO stock batches |
| `php artisan rebuild:stock-batches` | Recalculate all FIFO batches from purchase history |

---

## 📁 Key Environment Variables

```ini
APP_NAME="Inventory Management"
APP_URL=http://localhost

# Database — SQLite (default)
DB_CONNECTION=sqlite

# SMS Gateway (optional)
SMS_DRIVER=bulksmsbd          # bulksmsbd | ssl | twilio | custom
SMS_API_KEY=your_api_key

# Mail (optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
```

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'Add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

---

## 📄 License

Released under the **MIT License** — see [LICENSE](LICENSE) for details.

---

<div align="center">

Built with ❤️ using [Laravel](https://laravel.com) · [Bootstrap](https://getbootstrap.com) · [Chart.js](https://chartjs.org)

</div>
