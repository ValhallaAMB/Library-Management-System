# Library Management System

## Steps to Run the Application

1. **Start the Server**:
   - Run **WampServer** or **XAMPP** to initialize the local server environment.

2. **Access phpMyAdmin**:
   - Open your preferred web browser and go to `http://localhost/phpmyadmin`.
   - Log in using your credentials:
     - **Username**: `root`
     - **Password**: Leave it blank (default) or enter your password if it was changed.

3. **Create Database**:
   - In phpMyAdmin, create a new database named **"library"** using the sidebar.

4. **Extract Project Files**:
   - Extract the **"library.rar"** file into the following directory based on your server:
     - For **WampServer**: `C:\wamp64\www`
     - For **XAMPP**: `C:\xampp\htdocs`

5. **Run the Application**:
   - Open your web browser and enter `http://localhost/library` to access the system.

## First-Time Login
Once you complete the steps above, you will be directed to the login page. At this point:
- Three tables (`librarians`, `authors`, and `books`) will be automatically created in the database.
- Use the following default credentials to log in as an admin:
  - **Email**: `admin@admin.com`
  - **Password**: `admin`
