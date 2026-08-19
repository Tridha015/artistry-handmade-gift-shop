# Artistry of Tridha - Handmade Gift Shop

A web-based e-commerce management system designed for handmade craft customization, inventory handling, and multi-role operations.


## 👥 Project Roles & User Panels

1. **Admin (Lead Artisan):** Custom order approval, pricing estimation, order reviews, and delivery rider assignment.
2. **Customer:** Browse craft categories, submit personalized custom order forms, and track order progress.
3. **Seller (Artisan):** Upload new craft products/albums, manage inventory, and handle stock items.
4. **Delivery Agent:** View assigned pickup/delivery parcels and update live delivery status.



## 🛠️ Technology Stack & Architecture

* **Backend:** PHP
* **Frontend:** HTML, CSS, JavaScript
* **Database:** MySQL
* **Design Pattern:** Model-View-Controller (MVC) with database connections inside the Model layer.



## 📂 Directory Structure

```text
artistry/
├── assets/
│   ├── css/          # Global styles (style.css)
│   ├── js/           # Form validations
│   └── images/       # Store assets and sample reference photos
├── config/           # App configuration files
├── Controller/       # Business logic controllers
├── Model/            # Database queries and connection files
├── View/             # Role-based user interfaces
│   ├── admin/        # Admin panel views
│   ├── customer/     # Customer catalog & custom request views
│   ├── delivery/     # Delivery agent views
│   ├── layouts/      # Shared headers, footers, navigation
│   └── seller/       # Seller inventory & upload forms
├── index.php         # Public storefront landing page
└── README.md         # Project documentation