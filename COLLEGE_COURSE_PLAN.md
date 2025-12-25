# REVISED Implementation Plan: College/Course & Counsellor Tracking System

## 🔍 Current State Analysis

### ✅ What Already Exists in Admin:
1. **`view_college.php`** - Lists all colleges with search/filter
2. **`add_college.php`** - Add new college
3. **`edit_college.php`** - Edit existing college
4. **`delete_college.php`** - Delete college
5. **`college_details.php`** - View college details
6. **`course_list.php`** - List courses for a college
7. **`add_course.php`** - Add course to college
8. **`edit_course.php`** - Edit course
9. **`delete_course.php`** - Delete course
10. **`view_course_details.php`** - View course details

### ✅ Database Tables (Already Exist):
- `colleges` - College information
- `courses` - Course information (linked to colleges)
- `reviews` - Course reviews

---

## 🎯 What Needs to Be Built

### Phase 1: Student Module Pages ⏳
**Goal:** Allow students to browse colleges, courses, and entrance exams

Files to create:
1. `student/colleges.php` - Browse all colleges
2. `student/courses.php` - Browse all courses
3. `student/college_details.php` - View single college
4. `student/course_details.php` - View single course
5. ✅ `student/entrance_exams.php` - Already exists

### Phase 2: Parent Module Pages ⏳
**Goal:** Allow parents to browse colleges, courses, and entrance exams

Files to create:
6. `parent/colleges.php` - Browse all colleges
7. `parent/courses.php` - Browse all courses
8. `parent/college_details.php` - View single college
9. `parent/course_details.php` - View single course
10. `parent/entrance_exams.php` - View entrance exams

### Phase 3: Counsellor Module - View Pages ⏳
**Goal:** Allow counsellors to browse resources

Files to create:
11. `counsellor/colleges.php` - Browse all colleges
12. `counsellor/courses.php` - Browse all courses
13. `counsellor/college_details.php` - View single college
14. `counsellor/course_details.php` - View single course
15. `counsellor/entrance_exams.php` - View entrance exams

### Phase 4: Counsellor Tracking System ⏳
**Goal:** Track college form sales and exam pitches

#### Database Tables to Create:
```sql
CREATE TABLE counsellor_college_sales (
    id INT PRIMARY KEY AUTO_INCREMENT,
    counsellor_id INT NOT NULL,
    student_id INT NOT NULL,
    college_id INT NOT NULL,
    form_type VARCHAR(50),
    sale_date DATE NOT NULL,
    amount DECIMAL(10,2),
    status ENUM('pitched', 'sold', 'applied', 'rejected') DEFAULT 'pitched',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (counsellor_id) REFERENCES counsellors(id),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (college_id) REFERENCES colleges(id)
);

CREATE TABLE counsellor_exam_pitches (
    id INT PRIMARY KEY AUTO_INCREMENT,
    counsellor_id INT NOT NULL,
    student_id INT NOT NULL,
    parent_id INT NULL,
    exam_id INT NOT NULL,
    pitch_date DATE NOT NULL,
    pitch_status ENUM('pitched', 'interested', 'registered', 'not_interested') DEFAULT 'pitched',
    follow_up_date DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (counsellor_id) REFERENCES counsellors(id),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (exam_id) REFERENCES entrance_exams(id)
);
```

#### Files to Create:
16. `database/migrate_counsellor_tracking.sql` - Migration script
17. `counsellor/my_sales.php` - Track college form sales
18. `counsellor/my_pitches.php` - Track exam pitches
19. `counsellor/api_track_sale.php` - API to log sales
20. `counsellor/api_track_pitch.php` - API to log pitches
21. `counsellor/dashboard_widgets.php` - Performance metrics

### Phase 5: Integration ⏳
22. Add "Colleges" link to student sidebar
23. Add "Courses" link to student sidebar
24. Add "Colleges" link to parent header/sidebar
25. Add "Courses" link to parent header/sidebar
26. Add "Entrance Exams" link to parent header/sidebar
27. Add "Colleges" link to counsellor sidebar
28. Add "Courses" link to counsellor sidebar
29. Add "Entrance Exams" link to counsellor sidebar
30. Add "My Sales" link to counsellor sidebar
31. Add "My Pitches" link to counsellor sidebar

---

## 📋 Detailed Task Breakdown

### ✅ Phase 1: Student Module (5 files)
- [ ] Create `student/colleges.php` - Card layout with filters
- [ ] Create `student/courses.php` - Category-wise listing
- [ ] Create `student/college_details.php` - Full college info
- [ ] Create `student/course_details.php` - Full course info
- [ ] Update student sidebar with new links

### ✅ Phase 2: Parent Module (5 files)
- [ ] Create `parent/colleges.php` - Same as student view
- [ ] Create `parent/courses.php` - Same as student view
- [ ] Create `parent/college_details.php` - Same as student view
- [ ] Create `parent/course_details.php` - Same as student view
- [ ] Create `parent/entrance_exams.php` - Copy from student
- [ ] Update parent header/sidebar with new links

### ✅ Phase 3: Counsellor View Pages (5 files)
- [ ] Create `counsellor/colleges.php` - With "Track Sale" buttons
- [ ] Create `counsellor/courses.php` - Browse courses
- [ ] Create `counsellor/college_details.php` - With tracking options
- [ ] Create `counsellor/course_details.php` - Full course info
- [ ] Create `counsellor/entrance_exams.php` - With "Track Pitch" buttons
- [ ] Update counsellor sidebar with new links

### ✅ Phase 4: Counsellor Tracking (6 files)
- [ ] Create database migration script
- [ ] Run migration to create tracking tables
- [ ] Create `counsellor/my_sales.php` - List all sales
- [ ] Create `counsellor/my_pitches.php` - List all pitches
- [ ] Create `counsellor/api_track_sale.php` - Log sale API
- [ ] Create `counsellor/api_track_pitch.php` - Log pitch API
- [ ] Add tracking buttons to college/exam pages

### ✅ Phase 5: Dashboard & Reports (3 files)
- [ ] Create sales metrics widget
- [ ] Create pitch conversion widget
- [ ] Create pending follow-ups list
- [ ] Integrate into counsellor dashboard

---

## 🎨 UI/UX Specifications

### Student/Parent College Listing:
- Card-based grid layout
- Filters: Location, Type, Ranking
- Search by name
- "View Details" button
- "View Courses" button
- College logo display

### Counsellor College Listing (Additional):
- All student features +
- "Track Sale" button on each card
- Quick pitch modal

### Counsellor Tracking Pages:
- Table view with filters
- Status badges (Pitched/Sold/Applied/Rejected)
- Edit/Delete options
- Export to CSV
- Date range filters

---

## 🔄 Workflow Examples

### Counsellor Sells College Form:
1. Browse colleges at `counsellor/colleges.php`
2. Click "Track Sale" on a college card
3. Modal opens with form:
   - Select student
   - Form type (Application/Admission)
   - Amount
   - Status
   - Notes
4. Submit → Logged in `counsellor_college_sales`
5. Visible in `counsellor/my_sales.php`

### Counsellor Pitches Exam:
1. Browse exams at `counsellor/entrance_exams.php`
2. Click "Track Pitch" on an exam
3. Modal opens with form:
   - Select student
   - Pitch status
   - Follow-up date
   - Notes
4. Submit → Logged in `counsellor_exam_pitches`
5. Visible in `counsellor/my_pitches.php`

---

## ✅ Success Criteria

- [ ] Students can browse colleges, courses, exams
- [ ] Parents can browse colleges, courses, exams
- [ ] Counsellors can view all resources
- [ ] Counsellors can track sales with status
- [ ] Counsellors can track pitches with follow-ups
- [ ] All pages are mobile responsive
- [ ] Proper authentication on all pages
- [ ] Search and filter work correctly
- [ ] Dashboard shows accurate metrics

---

## 📝 Implementation Notes

- **Reuse existing admin pages as reference** for UI consistency
- **Use existing database tables** (colleges, courses, entrance_exams)
- **Only create NEW tracking tables** for counsellor data
- **Follow existing patterns** from entrance exams system
- **Maintain theme colors** (#008190 and #F38E3E)
- **Mobile-first design** for all pages

---

## 🚀 Estimated Timeline

- Phase 1 (Student): 2-3 hours
- Phase 2 (Parent): 1-2 hours (copy from student)
- Phase 3 (Counsellor Views): 2-3 hours
- Phase 4 (Tracking System): 3-4 hours
- Phase 5 (Dashboard): 1-2 hours
- **Total: 9-14 hours**

---

**Status:** Ready to implement
**Next Step:** Create database migration for tracking tables
