# 📝 PHP To-Do List Application

A web-based To-Do List application built with PHP, featuring user authentication, task management, profile configuration, and an interactive dashboard.

---

## 🚀 How to Run the Project

1. Open your terminal or command prompt and navigate to the project root directory:

   ```bash
   php -S localhost:8000
   ```

📄 Pages & Features Overview
🔑 1. Sign Up (signup.php)

    Purpose: Allows new users to register for an account.

    Key Features:

        User registration form (Full Name, Email, Password, Confirm Password).

        Email uniqueness validation against the database.

        Secure password encryption using password_hash().

        Automated redirect to login page after successful registration.

🔓 2. Login (login.php)

    Purpose: Authenticates existing users to access their task dashboard.

    Key Features:

        Secure email and password verification via password_verify().

        User session initialisation ($_SESSION).

        Display of error messages for invalid credentials.

📊 3. Overview (overview.php)

    Purpose: A summary dashboard offering high-level task metrics and activity.

    Key Features:

        Real-time metrics counters (Total Tasks, Pending Tasks, Completed Tasks).

        Progress rate indicators and visual completion percentage.

        Quick-access preview of recently added tasks.

📋 4. Tasks (tasks.php)

    Purpose: The main management hub for creating, updating, and organizing tasks.

    Key Features:

        Add Task: Create tasks with title, description, due date and tast status.

👤 5. Profile (profile.php)

    Purpose: Manage personal account details and security settings.

    Key Features:

        View personal details (Name, Email).

        Password change form requiring security verification.

🚪 6. Logout (logout.php)

    Purpose: Terminate the active user session securely.

    Key Features:

        Session data destruction (session_destroy()).

        Session cookie cleanup.

        Automatic redirect back to login.php.

