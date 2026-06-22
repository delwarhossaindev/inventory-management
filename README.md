<p align="center">
  <img src="https://img.icons8.com/3d-fluency/94/box.png" width="70" alt="Logo">
</p>

<h1 align="center">Inventory Management System</h1>

<p align="center">
  A complete, production-ready inventory & POS solution built with Laravel 10
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 10">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.1+">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5">
  <img src="https://img.shields.io/badge/SQLite-Database-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite">
  <img src="https://img.shields.io/badge/Chart.js-4-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white" alt="Chart.js">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT">
</p>

---

## 🔥 System Workflow

```
┌─────────────────────────────────────────────────────────────────┐
│                     INVENTORY MANAGEMENT SYSTEM                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│   👤 Admin / Manager / Storekeeper / Salesperson                │
│       │                                                         │
│       ▼                                                         │
│   ┌──────┐    ┌──────────┐    ┌─────────┐    ┌──────────┐     │
│   │Login │───▶│Dashboard │───▶│  POS    │───▶│ Invoice  │     │
│   │      │    │& Charts  │    │  Sale   │    │ Receipt  │     │
│   └──────┘    └──────────┘    └─────────┘    └──────────┘     │
│                                    │                            │
│                    ┌───────────────┼───────────────┐            │
│                    ▼               ▼               ▼            │
│              ┌──────────┐   ┌──────────┐   ┌──────────┐       │
│              │  Stock   │   │ Payment  │   │ Warranty │       │
│              │  (FIFO)  │   │   Due    │   │ Tracking │       │
│              └──────────┘   └──────────┘   └──────────┘       │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

## 📊 Data Flow

```
 ┌──────────┐          ┌──────────┐          ┌──────────┐
 │ Supplier │────🔗───▶│ Purchase │────🔗───▶│  Stock   │
 │          │          │          │          │  (FIFO)  │
 └──────────┘          └──────────┘          └────┬─────┘
                                                  │
                            ┌─────────────────────┘
                            ▼
 ┌──────────┐          ┌──────────┐          ┌──────────┐
 │ Customer │────🔗───▶│ POS Sale │────🔗───▶│ Invoice  │
 │          │          │          │          │ Receipt  │
 └──────────┘          └─────┬────┘          └──────────┘
                             │
              ┌──────────────┼──────────────┐
              ▼              ▼              ▼
        ┌──────────┐  ┌──────────┐  ┌──────────┐
        │ Revenue  │  │ Warranty │  │ Loyalty  │
        │          │  │  Set     │  │ Points   │
        └─────┬────┘  └──────────┘  └──────────┘
              │
              ▼
 ┌──────────┐          ┌──────────┐          ┌──────────┐
 │ Expenses │────➕───▶│  COGS    │────🔗───▶│ Profit & │
 │          │          │ (FIFO)   │          │   Loss   │
 └──────────┘          └──────────┘          └──────────┘
```

## 💰 Money Flow

```
    💵 MONEY IN                          💸 MONEY OUT
  ─────────────                        ─────────────────
  ┌─────────────┐                      ┌─────────────────┐
  │ 🛒 Sales    │ ◀── POS/Invoice      │ 📦 Purchases    │ ──▶ Supplier
  │    Revenue  │                      │    Stock Cost    │
  ├─────────────┤                      ├─────────────────┤
  │ 💳 Due      │ ◀── Collection       │ 🏠 Expenses     │ ──▶ Rent/Bills
  │    Payments │                      │    Operating     │
  ├─────────────┤                      ├─────────────────┤
  │ 📅 EMI      │ ◀── Installments     │ ↩️ Refunds      │ ──▶ Returns
  │    Payments │                      │    Customer      │
  └──────┬──────┘                      └────────┬────────┘
         │                                      │
         └──────────────┐  ┌────────────────────┘
                        ▼  ▼
                 ┌──────────────┐
                 │   📊 NET     │
                 │   PROFIT     │
                 │ Revenue      │
                 │ - COGS       │
                 │ - Expenses   │
                 │ - Returns    │
                 │ ═══════════  │
                 │ = 💰 Profit  │
                 └──────────────┘
```

## ✨ Features

### 🛒 Point of Sale (POS)
| Feature | Description |
|---------|-------------|
| ⚡ Fast Sale | Barcode scan, search by name/SKU/model |
| 🧾 Invoice | A4 professional invoice with company logo |
| 🖨️ Thermal Receipt | 58mm / 80mm POS printer optimized |
| 👤 Quick Customer | Add customer instantly without leaving POS |
| 💳 Multi Payment | Cash, Card, Mobile, Due |
| 🔄 Auto Stock | FIFO stock-out on sale, warranty auto-set |

### 📦 Inventory & Stock
| Feature | Description |
|---------|-------------|
| 📊 FIFO Costing | First-In-First-Out accurate cost tracking |
| 📋 Stock Movements | Complete log of every stock change |
| ⚠️ Low Stock Alerts | Dashboard warnings when stock falls below threshold |
| 🔧 Manual Adjust | Add/subtract/set stock for corrections |
| 🏷️ Barcode Labels | EAN-13 auto-generated, A4 & thermal layouts |
| 📥 Bulk Import | Spreadsheet upload for products & purchases |

### 💰 Finance & Accounting
| Feature | Description |
|---------|-------------|
| 💵 Due Collection | Collect partial payments from sales & purchases |
| 📅 Installment/EMI | Monthly payment plans with due date tracking |
| 🧾 Expense Tracking | Categorized expenses with on-the-fly categories |
| 📊 Profit & Loss | Full P&L: Revenue - COGS - Returns - Expenses |
| 💱 Quotations | Price estimates before sale, printable |
| 🏦 Cash Register | Shift open/close with balance accountability |

### 📈 Reports & Analytics
| Feature | Description |
|---------|-------------|
| 📊 Dashboard Charts | 7-day sales trend line + category doughnut chart |
| 💹 Sales & Profit | Per-sale revenue, COGS, margin % |
| 📅 Daily Summary | Day-by-day breakdown |
| 🏆 Top Products | Best sellers by quantity & revenue |
| 📦 Stock Valuation | Current stock value (FIFO batch) |
| 📊 Profit & Loss | Complete P&L statement |

### 👥 Contacts & CRM
| Feature | Description |
|---------|-------------|
| 📒 Customer Ledger | Full purchase history, total/paid/due per customer |
| 📒 Supplier Ledger | Full purchase history, total/paid/due per supplier |
| ⭐ Loyalty Points | Configurable points-per-purchase & redeem value |
| 🛡️ Warranty Tracking | Auto warranty expiry calculation per sale item |

### 🔐 Security & Administration
| Feature | Description |
|---------|-------------|
| 👤 User Profile | Edit name, email, change password |
| 🛡️ Role-Based Access | Spatie permissions, 4 default roles |
| 📝 Activity Log | Who did what, when, from which IP |
| 🔑 Login History | User, time, IP, browser tracking |
| 💾 Database Backup | One-click SQLite download |
| 🏢 Multi-Branch | Add and manage multiple store locations |
| 📱 SMS Config | BulkSMSBD / SSL Wireless / Twilio / Custom |
| 🔌 E-commerce API | REST API with Sanctum authentication |

### 📖 Documentation
| Feature | Description |
|---------|-------------|
| 📘 User Manual | In-app interactive guide with scroll-spy navigation |
| 🔄 Workflow Diagrams | Animated visual sales/purchase/return/money flows |
| 📊 Data Flow Diagrams | System-wide data movement visualization |
| 📄 PDF Export | English & Bangla PDF download |
| ♾️ Infinite Scroll | All tables load on scroll, no pagination clicks |

---

## 🏗️ Architecture

```
app/
├── Http/Controllers/
│   ├── Admin/
│   │   ├── DashboardController     # Stats, charts data
│   │   ├── PosController           # Point of Sale
│   │   ├── ProductController       # CRUD, bulk import/pricing, labels
│   │   ├── CategoryController      # 3-level hierarchy
│   │   ├── PurchaseController      # Create, bulk import
│   │   ├── SaleController          # View, invoice, thermal receipt
│   │   ├── SaleReturnController    # Process returns
│   │   ├── StockController         # View, adjust, movements
│   │   ├── PaymentController       # Due collection
│   │   ├── InstallmentController   # EMI plans
│   │   ├── ExpenseController       # Track expenses
│   │   ├── QuotationController     # Price estimates
│   │   ├── CashRegisterController  # Shift open/close
│   │   ├── ReportController        # 6 reports + P&L
│   │   ├── CustomerController      # CRUD + ledger
│   │   ├── SupplierController      # CRUD + ledger
│   │   ├── UserController          # User management
│   │   ├── RoleController          # Role management
│   │   ├── ProfileController       # Profile & password
│   │   ├── SettingController       # Business settings + backup
│   │   ├── ActivityLogController   # Activity log
│   │   ├── LoginHistoryController  # Login tracking
│   │   ├── BranchController        # Multi-store
│   │   └── ManualController        # User manual (EN + BN)
│   └── Api/
│       └── ProductApiController    # REST API endpoints
│
├── Models/
│   ├── Product                     # Barcode, FIFO stockIn/stockOut
│   ├── Category                    # 3-level parent/child
│   ├── Sale / SaleItem             # With warranty_expires
│   ├── Purchase / PurchaseItem     # Auto stock-in
│   ├── StockMovement / StockBatch  # FIFO batches
│   ├── Payment                     # Polymorphic (sale/purchase)
│   ├── SaleReturn / SaleReturnItem # Returns with stock restore
│   ├── Expense / ExpenseCategory   # Categorized expenses
│   ├── Quotation / QuotationItem   # Price estimates
│   ├── InstallmentPlan / Payment   # EMI tracking
│   ├── CashRegister                # Shift management
│   ├── ActivityLog                 # Action tracking
│   ├── LoginHistory                # Login tracking
│   ├── LoyaltyTransaction          # Points tracking
│   ├── Branch                      # Multi-store
│   ├── Setting                     # Key-value config
│   ├── Customer / Supplier         # Contacts
│   └── User                        # Spatie HasRoles
```

---

## 👤 Default Roles

| Role | Access |
|------|--------|
| 🌟 **Super Admin** | Full access — all features |
| 👔 **Manager** | Everything except user management |
| 📦 **Storekeeper** | Products, stock, purchases, suppliers |
| 🛒 **Salesperson** | POS, view sales, customers only |

---

## ⚡ Quick Start

### Requirements
- PHP 8.1+
- Composer
- SQLite (default) or MySQL

### Installation

```bash
# Clone
git clone <repo-url> inventory-management
cd inventory-management

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Create SQLite database
touch database/database.sqlite

# Run migrations & seed
php artisan migrate --seed
php artisan storage:link

# Start server
php artisan serve
```

### Default Login
| | |
|---|---|
| **Admin** | `admin@example.com` / `password` |
| **Cashier** | `cashier@example.com` / `password` |

> ⚠️ Change default passwords immediately after first login!

---

## 🔌 API Endpoints

```
GET  /api/v1/products              # List products (paginated, filterable)
GET  /api/v1/products/{id}         # Product detail
GET  /api/v1/products/{id}/stock   # Stock availability check
GET  /api/v1/categories            # Category tree with children
```

Authentication: Laravel Sanctum Bearer Token

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 10, PHP 8.1+ |
| Frontend | Bootstrap 5.3, Bootstrap Icons 1.11 |
| Charts | Chart.js 4 |
| Barcodes | JsBarcode (EAN-13 / CODE128) |
| Database | SQLite (default), MySQL compatible |
| Auth | Laravel Sanctum + Spatie Permission |
| API | RESTful with Sanctum tokens |

---

## 📄 License

MIT License. See [LICENSE](LICENSE) for details.
