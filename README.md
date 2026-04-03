# 🏄‍♂️ Taghazout Surf Expo - Internal Management System

A professional web application designed to digitize the management of a surf school in Taghazout. This project follows the **MVC (Model-View-Controller)** architecture and uses **Object-Oriented Programming (OOP)** in native PHP.

## 🚀 Features
* **Role-Based Access Control:** Secure login system for Admins and Students.
* **Lesson Management:** Admin capabilities to Create, Read, Update, and Delete (CRUD) surf sessions.
* **Student Tracking:** Manage student profiles, including origin (Country) and skill levels (Beginner, Intermediate, Advanced).
* **Enrollment & Payments:** Register students for specific lessons and toggle payment status (Paid/Pending).
* **Student Agenda:** Personalized dashboard for students to view their upcoming registered lessons.

## 🛠 Technical Stack
* **Backend:** PHP 8.x (Native OOP & PDO)
* **Database:** MySQL
* **Frontend:** Bootstrap 5, FontAwesome, Custom CSS
* **Architecture:** MVC Pattern with strict separation of concerns (No SQL in Views).

## 📂 Project Structure
```text
TaghazoutSurfExpo/
├── config/
│   └── database.php      # Database connection (Singleton Pattern)
├── controllers/
│   ├── AdminController.php
│   ├── AuthController.php
│   ├── RollController.php
│   └── StudentController.php
├── model/
│   ├── Admin.php
│   ├── Database.php      # Base DB Access Class
│   ├── Lessons.php
│   ├── Roll.php
│   ├── Student.php
│   └── User.php
├── public/
│   ├── sty.css           # Custom styles
│   └── setup.php         # Connection test script
├── views/
│   ├── admin/            # Dashboard and Lesson management
│   ├── auth/             # Login and Registration
│   └── student/          # Agenda and Level updates
└── surfedb.sql           # Database Schema & Seeding script
