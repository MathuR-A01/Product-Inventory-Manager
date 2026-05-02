# Product Inventory Manager — Laravel

A modern, single-page product inventory management application built with **Laravel 12**, **Bootstrap 5**, and **vanilla JavaScript** with AJAX.

## Features

- **Add Products** — Submit product name, quantity in stock, and price per item via AJAX
- **JSON Storage** — All data is persisted in a valid JSON file (`storage/app/private/products.json`)
- **Live Table** — Products are displayed in rows ordered by datetime submitted
- **Auto-Calculated Totals** — Total value = Quantity × Price, with a grand total row
- **Inline Editing** — Edit any product row directly in the table
- **Delete Products** — Remove entries with confirmation
- **Stats Dashboard** — Real-time stat cards showing total products, units, and value
- **Toast Notifications** — Elegant success/error feedback
- **Dark Glassmorphism UI** — Premium modern dark theme

## Tech Stack

| Layer       | Technology                          |
|-------------|-------------------------------------|
| Backend     | PHP 8.2+ / Laravel 12              |
| Frontend    | HTML5, CSS3, JavaScript (ES6+)      |
| UI Framework| Bootstrap 5.3 (CDN)                |
| Data Storage| JSON file (no database required)    |
| AJAX        | Fetch API with CSRF protection      |

## Setup Instructions

1. **Extract** the zip file into your web server directory
2. **Install dependencies**:
   ```bash
   composer install
   ```
3. **Copy environment file** (if `.env` is missing):
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. **Run the application**:
   ```bash
   php artisan serve
   ```
5. Open `http://127.0.0.1:8000` in your browser

> **Note:** No database setup is required. Data is stored in `storage/app/private/products.json`.

## File Structure

```
app/Http/Controllers/ProductController.php  — CRUD logic + JSON file I/O
routes/web.php                              — Route definitions
resources/views/products/index.blade.php    — Single-page UI (Bootstrap + JS)
storage/app/private/products.json           — Auto-created data file
```
