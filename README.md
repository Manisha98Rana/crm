# FormsAdda CRM & Career Guide Platform

An all-in-one Career Guidance and CRM platform connecting Students, Parents, and Counsellors. This application facilitates college discovery, entrance exam tracking, and direct consultation with expert counsellors via chat and video calls.

## 🌟 Key Features

### 🎓 Student Module
- **Dashboard**: Track sessions, wallet balance, and college applications.
- **College Discovery**: Browse top-ranked colleges with detailed info and comparisons.
- **Entrance Exams**: Stay constantly updated with upcoming exam dates and deadlines.
- **Consultation**: Book Chat or Voice/Video sessions with counsellors using Agora.
- **Wallet System**: Recharge wallet for paid consultation sessions.
- **College Predictor**: AI-based tools to predict college admission chances.
- **PWA Support**: Installable as a native-like app on mobile devices.

### 👨‍👩‍👧 Parent Module
- **Child Monitoring**: Track student's academic profile and session history.
- **Shared Wallet**: Manage wallet balance for the student's sessions.
- **Unified Login**: Seamless switching between Student and Parent accounts.

### 🧑‍🏫 Counsellor Module
- **Availability Management**: Set weekly schedules for consultations.
- **Session Handling**: Accept/Reject session requests and conduct chats/calls.
- **Earnings Dashboard**: Track revenue and payout history.
- **Lead Management**: View and follow up with student enquiries.

### 🛠 Admin Module
- **User Management**: Manage Students, Parents, and Counsellors.
- **Content Management**: Update Colleges, Courses, and Entrance Exams.
- **Reports**: Generate detailed financial and activity reports.
- **System Settings**: Configure global settings, payment gateways, and PWA options.

---

## 🚀 Technology Stack

- **Backend**: PHP (Vanilla 7.4/8.0+)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript (jQuery)
- **Styling**: Bootstrap 5, FontAwesome
- **Real-time Communication**: Agora SDK (Video/Voice/Chat)
- **Payments**: Razorpay / PayU Integration
- **PWA**: Service Workers, Web App Manifest

---

## 🛠 Installation & Setup

### Prerequisites
- PHP >= 7.4
- MySQL / MariaDB
- Composer (optional, for dependencies)
- A local server environment (e.g., XAMPP, WAMP, Laragon)

### Steps

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/Manisha98Rana/crm.git
    cd formsadda-crm
    ```

2.  **Database Setup**
    - Create a new MySQL database (e.g., `formsadda_crm`).
    - Import the provided SQL file from `database/schema.sql` (if available) or `sql/` folder.
    - *Note: If no SQL dump is present, check `admin/setup_*.php` files for schema creation.*

3.  **Configuration**
    - Duplicate the example configuration files:
      ```bash
      cp db_conn.example.php db_conn.php
      cp config_payment.example.php config_payment.php
      cp agora_config.example.php agora_config.php
      ```
    - Edit `db_conn.php` with your database credentials:
      ```php
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "formsadda_crm";
      ```
    - Edit `config_payment.php` with your Razorpay/PayU keys.
    - Edit `agora_config.php` with your Agora App ID and Certificate.

4.  **Run the Application**
    - Place the project folder in your server's root directory (e.g., `htdocs` or `www`).
    - Access via browser: `http://localhost/formsadda_crm/`

---

## 📂 Project Structure

```
formsadda_crm/
├── admin/          # Admin Dashboard & Management
├── counsellor/     # Counsellor Portal
├── parent/         # Parent Portal
├── student/        # Student Portal & Main App
├── image/          # Public Assets & Images
├── includes/       # Shared PHP functions/templates
├── css/            # Global Stylesheets
├── js/             # Global Scripts
├── db_conn.php     # Database Connection (Ignored)
└── service-worker.js # PWA Service Worker
```

---

## 🛡 Security & Configuration

This project contains sensitive configuration files that are excluded from version control via `.gitignore`.
- **`db_conn.php`**: Database connection details.
- **`config_payment.php`**: Payment gateway API keys.
- **`agora_config.php`**: Agora Real-time Engagement keys.

Always use the provided `*.example.php` files as a reference when setting up a new environment.

---

## 📱 PWA Features

This application is fully optimized as a Progressive Web App (PWA).
- **Offline Support**: Caches core assets for faster load times.
- **Installable**: "Add to Home Screen" prompt for mobile users.
- **Auto-Updates**: Service worker handles background updates.

---

## 🤝 Contributing

1.  Fork the repository.
2.  Create a feature branch (`git checkout -b feature/NewFeature`).
3.  Commit your changes (`git commit -m 'Add NewFeature'`).
4.  Push to the branch (`git push origin feature/NewFeature`).
5.  Open a Pull Request.

---

## 📄 License

This project is proprietary software. All rights reserved.
