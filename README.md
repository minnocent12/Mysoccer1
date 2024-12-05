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

folder permission error

Ensure that uploads/news, uploads/profile_pics, uploads/teams and uploads/tournaments have full permissions.
To setup permission run the following command.

Run this command (if you're on Linux and macOS):

chmod 777 uploads/news
chmod 777 uploads/profile_pics
chmod 777 uploads/teams
chmod 777 uploads/tournaments

On Windows, permissions are usually managed through a graphical interface rather than commands like in Linux or macOS.

Steps to Set Permissions on Windows:
Locate the Directory:

Navigate to the uploads/news, uploads/profile_pics, uploads/teams and uploads/tournaments folder in your project directory using File Explorer.
Open Folder Properties:

Right-click on the uploads/news, uploads/profile_pics, uploads/teams and uploads/tournaments folder and select Properties.
Go to the Security tab.
Edit Permissions:

Click on the Edit button under the Security tab.
In the new window, click Add to add a new user or group.
Add the IIS User:

In most cases, the web server on Windows is run by the IIS_IUSRS group or a similar user account used by the web server.
Type IIS_IUSRS in the box (or the appropriate user account if known) and click Check Names to verify.
Grant Write Permissions:

Once added, select the IIS_IUSRS group (or appropriate user) from the list.
Under the Permissions for IIS_IUSRS, check the Write permission checkbox.
Click OK to save changes.
Apply Changes:

Click Apply and OK to close all dialog boxes.



### License

This project is licensed under the INS License.








