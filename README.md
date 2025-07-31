🔐 Role-Based Authentication Web Application

This is a simple web application built using **PHP** and **MySQL** that demonstrates **Role-Based Authentication** for Admin and Student users. The app allows users to log in and see different dashboards based on their assigned roles.

📌 Features

- 🔐 Secure login system
- 👤 Role-based access (Admin & Student)
- ✨ Simple and clean UI
- 📂 Session handling
- 🛠️ Easy to extend for more roles

🧰 Tech Stack

- Frontend: HTML, CSS, JavaScript
- Backend: PHP
- Database: MySQL
- Tool: XAMPP (Apache + MySQL)

🚀 Getting Started

1. Clone the repo:

   git clone https://github.com/kumarpritam143/Role-Based-Auth-App.git

2. 📂 Move Project to XAMPP htdocs

Navigate to the cloned folder.
Move the entire folder Role-Based-Auth-App into your XAMPP htdocs directory:

C:/xampp/htdocs/

3. ▶️ Start Apache and MySQL
   Open the XAMPP Control Panel.

Click Start on both Apache and MySQL modules.

4. 🗄️ Import the Database
   Open http://localhost/phpmyadmin

Create a new database named: Role-Based Authentication Web Application

Click Import → Choose the users.sql file from the project → Click Go

5. 🛠️ Configure Database Connection
   Open the database.php file and make sure the following credentials are correct:

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'Role-Based Authentication Web Application';

6. 🌐 Run the Application
   Open your browser and go to:

http://localhost/Role-Based-Auth-App
