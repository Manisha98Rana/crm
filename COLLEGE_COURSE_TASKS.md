# Task Checklist: College/Course & Counsellor Tracking System

## Phase 1: Database & Migration ⏳
- [ ] Create `colleges` table
- [ ] Create `courses` table  
- [ ] Create `college_courses` junction table
- [ ] Create `counsellor_college_sales` tracking table
- [ ] Create `counsellor_exam_pitches` tracking table
- [ ] Add sample data for testing
- [ ] Test migration script

## Phase 2: Admin - College Management ⏳
- [ ] Create `admin/colleges.php` - List all colleges
- [ ] Create `admin/college_form.php` - Add/Edit college form
- [ ] Create `admin/api_colleges.php` - CRUD API
- [ ] Add "Colleges" link to admin sidebar
- [ ] Test college CRUD operations

## Phase 3: Admin - Course Management ⏳
- [ ] Create `admin/courses.php` - List all courses
- [ ] Create `admin/course_form.php` - Add/Edit course form
- [ ] Create `admin/api_courses.php` - CRUD API
- [ ] Add "Courses" link to admin sidebar
- [ ] Test course CRUD operations

## Phase 4: Student Module ⏳
- [ ] Check if `student/colleges.php` exists (create if not)
- [ ] Check if `student/courses.php` exists (create if not)
- [ ] Verify `student/entrance_exams.php` works ✓
- [ ] Add links to student sidebar
- [ ] Test all pages

## Phase 5: Parent Module ⏳
- [ ] Create `parent/colleges.php`
- [ ] Create `parent/courses.php`
- [ ] Create `parent/entrance_exams.php`
- [ ] Add links to parent sidebar/header
- [ ] Test all pages

## Phase 6: Counsellor Module - View Pages ⏳
- [ ] Create `counsellor/colleges.php`
- [ ] Create `counsellor/courses.php`
- [ ] Create `counsellor/entrance_exams.php`
- [ ] Add links to counsellor sidebar
- [ ] Test all pages

## Phase 7: Counsellor Module - Tracking ⏳
- [ ] Create `counsellor/my_sales.php` - College form sales tracker
- [ ] Create `counsellor/my_pitches.php` - Exam pitch tracker
- [ ] Create `counsellor/api_track_sale.php` - API to log sales
- [ ] Create `counsellor/api_track_pitch.php` - API to log pitches
- [ ] Add "Track Sale" buttons on college pages
- [ ] Add "Track Pitch" buttons on exam pages
- [ ] Test tracking functionality

## Phase 8: Counsellor Dashboard ⏳
- [ ] Create sales metrics widget
- [ ] Create pitch conversion widget
- [ ] Create recent activities list
- [ ] Create pending follow-ups list
- [ ] Add to counsellor dashboard
- [ ] Test dashboard

## Phase 9: Integration & Polish ⏳
- [ ] Update all sidebars with new links
- [ ] Add search functionality to listings
- [ ] Add filter functionality
- [ ] Test mobile responsiveness
- [ ] Test all user flows
- [ ] Fix any bugs

## Phase 10: Documentation ⏳
- [ ] Create setup guide
- [ ] Create user guide for counsellors
- [ ] Update README
- [ ] Create API documentation
- [ ] Add inline code comments

---

## Current Status: Planning Phase ✅
**Next Step:** Start with Phase 1 (Database Migration)

---

## Notes:
- Follow existing patterns from entrance exams system
- Use prepared statements for all database queries
- Maintain consistent UI across all modules
- Add proper error handling
- Include loading states
- Mobile-first design
