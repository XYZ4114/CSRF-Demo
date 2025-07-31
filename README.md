# 🔐 CSRF-Demo

A secure PHP-based leave management system demonstrating CSRF protection, authentication, admin review panel, and secure message handling. Designed to showcase secure session handling and request verification in a real-world application.

---

## 🚀 Features

### 👤 User Side (`/user`)

* Register, Login, Logout
* Submit leave/contact request with CSRF token
* View previous submissions and responses
* Session-based user dashboard

### 🛡️ CSRF Protection

* Token generation and validation using `csrf.php`
* Session-bound tokens
* One-time-use logic for tokens

### 🛠️ Admin Panel (`/admin`)

* Login as admin
* View all submitted requests
* Reply to user queries
* Session protection with `session_admin.php`

---

## 🧰 Tech Stack

* **Backend:** PHP (Vanilla, session-based)
* **Frontend:** HTML, CSS, Bootstrap
* **Database:** MySQL (`database/leave_portal.sql`)
* **Security:** Custom CSRF protection

---

## 🧑‍💻 Local Setup

1. **Clone the repository**

   ```bash
   git clone https://github.com/yourusername/CSRF-Demo.git
   ```

2. **Move the project to XAMPP’s `htdocs` directory**

   Example:
   `C:\xampp\htdocs\CSRF-Demo`

3. **Start Apache & MySQL via XAMPP**

4. **Import the database**

   * Open `phpMyAdmin`
   * Create a new database (e.g., `csrf_demo`)
   * Import the SQL file from `database/leave_portal.sql`

5. **Update database connection**

   Edit `includes/db.php`:

   ```php
   $host = 'localhost';
   $db   = 'csrf_demo';
   $user = 'root';
   $pass = '';
   ```

6. **Run the project**

   Visit in browser:

   ```bash
   http://localhost/CSRF-Demo/index.php
   ```

---

## ⚖️ License

This project is licensed under the MIT License.

---

## 🙌 Credits

Developed by [XYZ4114](https://github.com/XYZ4114). Contributions welcome!

