# Hostel Management System

## 📌 Overview

This project is a **Hostel Management System** designed to demonstrate basic **DBMS operations and database connectivity** using MySQL and XAMPP. It manages core hostel-related data such as students, rooms, payments, complaints, and visitor logs through structured tables.

The focus of this project is on **CRUD operations (INSERT, SELECT, UPDATE, DELETE)** and how data is stored and manipulated in a relational database.

---

## ⚙️ Use of XAMPP

XAMPP is used to provide a **local server environment** for running the project.

### Components used:

* **Apache** → Executes PHP files
* **MySQL** → Stores and manages the database

### How it works:

1. XAMPP starts Apache and MySQL services
2. Project files are placed inside the `htdocs` directory
3. The application is accessed via:

   ```
   http://localhost/
   ```
4. PHP connects to MySQL using:

   ```php
   mysqli_connect("localhost", "root", "", "college_temp");
   ```
5. SQL queries are executed to perform database operations

---

## 🧠 Key Functionality

* **Insert**: Add new records (students, payments, etc.)
* **Update**: Modify existing data (room allocation, details)
* **Delete**: Remove records
* **Select**: Retrieve and display stored data

---

## 🎯 Conclusion

This project demonstrates how a database-driven system works by integrating **frontend input, backend processing, and MySQL database operations** using XAMPP as the local server environment.

