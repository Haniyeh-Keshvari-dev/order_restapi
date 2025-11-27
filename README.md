# Orders REST API — Laravel

این پروژه یک RESTful API کامل برای مدیریت محصولات (Products)، مشتریان (Customers) و سفارش‌ها (Orders) است.  
این API شامل CRUD کامل برای سه ریسورس، مدیریت آیتم‌های سفارش، ولیدیشن حرفه‌ای با Form Request،  
middleware اختصاصی برای احراز هویت با(X-API-KEY) و منطق محاسبه‌ی سفارش با Transaction است.

## امکانات اصلی پروژه

### Products (CRUD کامل)

- ایجاد، ویرایش، حذف، نمایش
- فیلتر بر اساس `name` و `sku`
- sorting و pagination

### Customers (CRUD کامل)

- ایجاد، ویرایش، حذف
- فیلتر بر اساس `name` و `email`
- pagination

### Orders (CRUD + منطق سفارش)

- ایجاد سفارش با:
    - ولیدیشن کامل آیتم‌ها
    - محاسبه total_amount
    - ذخیره order + order_items
    - اجرا داخل Transaction
- نمایش سفارش
- فیلتر customer_id
- pagination
- حذف سفارش

### Middleware: X-API-KEY

تمام روت‌ها با هدر زیر محافظت می‌شوند:

```

X-API-KEY: 123456789

````

---

## پیش‌نیازها

- PHP 8.1+
- Composer
- Laravel 12
- MySQL
- Postman

---

## نصب و راه‌اندازی

### 1. کلون پروژه

```bash
git clone https://github.com/Haniyeh-Keshvari-dev/order_restapi.git
cd order_restapi

````

### 2. نصب پکیج‌ها

```bash
composer install
```

### 3. ساخت فایل env

```bash
cp .env.example .env
```

### 4. مقداردهی `.env`

```
DB_DATABASE=order_restapi
DB_USERNAME=root
DB_PASSWORD=

API_KEY=123456789
```

### 5. ساخت کلید اپ

```bash
php artisan key:generate
```

### 6. ساخت جداول + seed

```bash
php artisan migrate --seed
```

### 7. اجرا

```bash
php artisan serve
```

---

## روت‌ها (Endpoints)

تمام درخواست‌ها باید هدر زیر را داشته باشند:

```
X-API-KEY: 123456789
```

---

# Products

### لیست محصولات

```
GET /api/products
```

فیلتر:

```
GET /api/products?name=harum
GET /api/products?sku=2312
GET /api/products?per_page=10
```

### ایجاد محصول

```
POST /api/products
```

Body:

```json
{
    "name": "T-Shirt",
    "sku": "TSH-001",
    "price": 25.50,
    "stock_quantity": 100
}
```

### نمایش محصول

```
GET /api/products/{product}
```

### آپدیت محصول

```
PUT /api/products/{product}
```

### حذف محصول

```
DELETE /api/products/{product}
```

---

# Customers

### لیست

```
GET /api/customers?name=ali&email=test
```

### ایجاد

```
POST /api/customers
```

### نمایش

```
GET /api/customers/{customer}
```

### آپدیت

```
PUT /api/customers/{customer}
```

### حذف

```
DELETE /api/customers/{customer}
```

---

# Orders

### ایجاد سفارش

```
POST /api/orders
```

Body:

```json
{
    "customer_id": 1,
    "items": [
        {
            "product_id": 2,
            "quantity": 3
        },
        {
            "product_id": 5,
            "quantity": 1
        }
    ]
}
```

### لیست سفارش‌ها

```
GET /api/orders?customer_id=1&per_page=10
```

### نمایش سفارش

```
GET /api/orders/{order}
```

### حذف سفارش

```
DELETE /api/orders/{order}
```

---

## توضیحات

* استفاده از **Transaction** برای جلوگیری از ذخیره ناقص
* ذخیره `unit_price` داخل order_items (برای ثابت ماندن قیمت تاریخی)
* استفاده از **Form Request** برای تمیز شدن کنترلرها
* استفاده از middleware اختصاصی `X-API-KEY`
* pagination استاندارد
* بارگذاری روابط با `with()` در index و show

---

##  تست API

publish document by postman:
(customer) : https://documenter.getpostman.com/view/45954196/2sB3dK1YHQ
(product) : https://documenter.getpostman.com/view/45954196/2sB3dK1YHT
(order) : https://documenter.getpostman.com/view/45954196/2sB3dK1YHW


## لیست روت ها 


```bash
php artisan route:list
```

ریست دیتابیس:

```bash
php artisan migrate:fresh --seed
```

---
