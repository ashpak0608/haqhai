Clone it: git clone https://github.com/ashpak0608/haqhai.git
Install dependencies: composer install (This recreates the vendor folder you just deleted from GitHub).
Step 1: Set up your Environment File
The .env file contains your local database credentials and app settings. Since this file isn't on GitHub, you must create it from the template.

In your terminal (inside the project folder), run:


cp .env.example .env
Open the newly created .env file in your code editor (VS Code, etc.).

Update the Database section to match your local setup (usually MySQL/XAMPP):



DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
Step 2: Generate the Application Key
Laravel requires a unique encryption key. Without this, your sessions and encrypted data won't work, and you'll see a "No application encryption key has been specified" error.

Run this command:


php artisan key:generate
Step 3: Create and Migrate the Database
Open your database tool (like phpMyAdmin, TablePlus, or MySQL Workbench).

Create a new database with the exact name you typed in your .env file (DB_DATABASE).

Go back to your terminal and run the migrations to create the tables:

php artisan migrate
Step 4: Link Storage (If using images/files)
Laravel keeps public files in a private folder. To make them accessible via the web, you need to create a "symbolic link":


php artisan storage:link
Step 5: Start the Local Server
Now you are ready to view the site. Run the built-in PHP server:


php artisan serve
Step 6: Verify in your Browser
Once the server starts, it will give you a URL (usually http://127.0.0.1:8000).

Open your browser.

Type http://localhost:8000 or http://127.0.0.1:8000.
