## MySoccer Project

MySoccer is a PHP-based web application for managing soccer-related data. It uses MySQL for database management and includes features such as authentication, password recovery, and more. This project is intended for local development and can be customized as needed.

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer (PHP dependency manager) 
- Local Server (XAMPP, WAMP, etc.)
- A web server (e.g., Apache, Nginx.)

### Installation

1. Clone the repository:
git clone git@github.com:minnocent12/Mysoccer1.git
cd MYSOCCER

2. Install Dependencies
Run the following command

composer install

to install PHP dependencies.

### Set Up the Database

1. Open your MySQL client or phpMyAdmin.
2. Create a new database, called  mysoccer.
3. Import the database schema:

#### Using phpMyAdmin:
1. Go to the Import tab in your database.
2. Select the MYSOCCER/database/mysoccer.sql file.
3. Click Go.

#### Using the Command Line:

mysql -u root -p mysoccer < database/mysoccer.sql

### Configure Database Connection

1. Navigate to the includes/ directory.
2. Open the db_connect.php file.
3. Update the database credentials to match your local MySQL setup:

$servername = "localhost";
$username = "root";
$password = ""; // Replace with your MySQL password
$dbname = "mysoccer";

### Run the Application

1. Start your web server or Local server
2. Access the project via your browser:
http://localhost/MYSOCCER/


### Features

Authentication: Secure login and password reset functionality.
Email Integration: Password reset emails using PHPMailer.
Responsive Design: CSS styles and layouts for an enhanced user experience.
Libraries and Tools
PHPMailer: For sending emails.
Composer: For dependency management.


### Troubleshooting

Common Issues:
Database Connection Error:

Ensure your MySQL server is running.
Verify the database credentials in db_connect.php.

Composer Not Found:

Install Composer globally on your system: Get Composer.

Email Sending Issues:

Verify SMTP settings in the email configuration section.


### License

This project is licensed under the INS License.








