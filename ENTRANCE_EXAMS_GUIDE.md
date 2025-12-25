# Entrance Exams Management System

## 🎓 Overview
Dynamic entrance exam management system where admins can add, edit, and manage entrance exams that are displayed to students.

## 📁 Files Created

### Database
1. **migrate_entrance_exams.sql** - Database migration script

### Admin Panel
2. **entrance_exams.php** - List all entrance exams
3. **entrance_exam_form.php** - Add/Edit entrance exam form
4. **api_entrance_exams.php** - API for CRUD operations

### Student Side
- **entrance_exams.php** (already exists) - Displays exams to students

## 🚀 Setup Instructions

### Step 1: Run Database Migration

**Option 1: phpMyAdmin**
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Select your database: `u444558326_course_details`
3. Click SQL tab
4. Copy and paste content from `database/migrate_entrance_exams.sql`
5. Click Go

**Option 2: MySQL Command Line**
```bash
mysql -u root -p u444558326_course_details < database/migrate_entrance_exams.sql
```

### Step 2: Access Admin Panel

Go to: `http://localhost/crm/admin/entrance_exams.php`

## ✨ Features

### Admin Features
- ✅ Add new entrance exams
- ✅ Edit existing exams
- ✅ Delete exams
- ✅ Set exam status (Active/Inactive/Completed)
- ✅ Manage all exam details:
  - Exam name and category
  - Important dates (exam date, application deadlines)
  - Conducting body
  - Eligibility criteria
  - Exam pattern
  - Syllabus details
  - Official website link
  - Total marks and duration

### Student Features
- ✅ View all active entrance exams
- ✅ Filter by category
- ✅ See application status (Open/Closed)
- ✅ Access official website
- ✅ View exam details

## 📊 Exam Categories

- Engineering
- Medical
- Management
- Law
- Arts
- Science
- Commerce
- Other

## 🎯 Usage

### Adding a New Exam

1. Go to Admin → Student → Entrance Exams
2. Click "Add New Exam"
3. Fill in the details:
   - **Basic Info**: Name, Category, Conducting Body
   - **Dates**: Exam date, Application start/end dates
   - **Details**: Marks, Duration, Eligibility, Pattern
   - **Additional**: Syllabus, Website URL
4. Set Status to "Active"
5. Click "Create Exam"

### Editing an Exam

1. Go to entrance exams list
2. Click edit icon on the exam
3. Update details
4. Click "Update Exam"

### Deleting an Exam

1. Go to entrance exams list
2. Click delete icon
3. Confirm deletion

## 🎨 Status Types

- **Active** - Visible to students
- **Inactive** - Hidden from students
- **Completed** - Past exams (for reference)

## 📱 Student View

Students can access entrance exams at:
```
http://localhost/crm/student/entrance_exams.php
```

Features:
- Filter by category
- See application deadlines
- Check if applications are open/closed
- Visit official websites

## 🔒 Security

- ✅ Admin authentication required
- ✅ SQL injection protection (prepared statements)
- ✅ XSS protection (htmlspecialchars)
- ✅ Input validation

## 📝 Sample Data

The migration includes 3 sample exams:
1. JEE Main 2024 (Engineering)
2. NEET UG 2024 (Medical)
3. CAT 2024 (Management)

You can delete these and add your own exams.

## 🎉 Summary

**Admin Side:**
- Manage entrance exams from admin panel
- Full CRUD operations
- Easy-to-use interface

**Student Side:**
- View active exams
- Filter by category
- Access official websites

The system is fully functional and ready to use! 🚀
