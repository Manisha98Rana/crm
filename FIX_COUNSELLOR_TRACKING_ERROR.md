# 🚨 URGENT: Run Counsellor Tracking Migration

## ❌ Error:
```
Table 'counsellor_college_sales' doesn't exist
Table 'counsellor_exam_pitches' doesn't exist
```

## ✅ Solution:

### **Step 1: Run the Migration**

Open your browser and navigate to:
```
http://localhost/crm/database/run_counsellor_tracking_migration.php
```

### **Step 2: Verify Success**

You should see:
- ✅ Created table: **counsellor_college_sales**
- ✅ Created table: **counsellor_exam_pitches**
- ✅ Inserted sample data
- 🎉 Migration completed successfully!

### **Step 3: Test the Pages**

After migration, these pages will work:
- `counsellor/my_sales.php` - View college form sales
- `counsellor/my_pitches.php` - View exam pitches
- `counsellor/colleges.php` - Track new sales
- `counsellor/entrance_exams.php` - Track new pitches

---

## 📋 What Tables Will Be Created:

### **1. counsellor_college_sales**
Tracks college form sales by counsellors:
- counsellor_id
- student_id
- college_id
- form_type (Application Form, Admission Form, etc.)
- sale_date
- amount
- status (pitched, sold, applied, rejected, admitted)
- notes

### **2. counsellor_exam_pitches**
Tracks exam pitches by counsellors:
- counsellor_id
- student_id
- exam_id
- pitch_date
- pitch_status (pitched, interested, registered, not_interested, appeared)
- follow_up_date
- notes

---

## 🎯 Quick Fix:

**Just open this URL in your browser:**
```
http://localhost/crm/database/run_counsellor_tracking_migration.php
```

That's it! The migration will run automatically and create all necessary tables.

---

**Status:** Migration file ready ✅  
**Action Required:** Run the migration URL  
**Time Required:** < 1 minute
