# ERP System

This project is an ERP System for managing items. It includes functionalities to add, edit, and view items.

## Assumptions

1. The project uses PHP and MySQL for the backend.
2. The project uses Bootstrap ,css , javascript for frontend styling.
3. The database connection details are stored in `db_connect.php`.
4. The project files are structured such that all PHP files are in the `php` directory, and CSS files are in the `css` directory.


### Steps

1. **Clone the Repository**

  
    git clone [https://github.com/yourusername/erp_system.git](https://github.com/DisaraMethmali/ERP-System)
    

2. **Move to Project Directory**

    
    cd erp_system
    

3. **Start XAMPP**

    Open XAMPP Control Panel and start the Apache and MySQL modules.

4. **Set Up the Database**

    - Open your web browser and go to `http://localhost/phpmyadmin`.
    - Create a new database named `assignment`.
    - Import the `assignment.sql` file from the `database` directory to set up the necessary tables and initial data.

5. **Configure Database Connection**

    - Open `php/db_connect.php`.
    - Update the database connection details (hostname, username, password, and database name) to match your local environment.

   ![image](https://github.com/user-attachments/assets/b4dc30e0-4959-4b3f-a7ed-f8ea9552e2ca)


6. **Access the Project**

    - Open your web browser and go to `http://localhost/erp_system/php/index.php` to view dashboard


