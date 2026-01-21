# Technician Store Daily Issue Management Website

## 1) System Overview
এই প্রজেক্টটি একটি TV Channel Office-এর Technician Store দৈনন্দিন Issue ব্যবস্থাপনার জন্য তৈরি। এখানে **Admin** (Store In-charge/Technical Head) এবং **Technician/Reporter/Video Journalist**—দুটি Role থাকবে। Admin সবকিছু নিয়ন্ত্রণ করবে; Technician শুধুমাত্র নিজের Issue Entry ও History দেখবে।

**কী কী হবে:**
- Secure Login (Password Hashing + Session)
- Role-based Access Control
- Daily Issue Entry, Status Tracking (Pending/Approved/Returned)
- Admin Approval + Return Update
- Date/Technician-wise Report + Print View

## 2) Database Design (MySQL)
ডাটাবেজে ৫টি টেবিল থাকবে: `users`, `technicians`, `equipments`, `daily_issues`, `issue_return_log`।

### Table Structure (SQL)
`database/schema.sql` ফাইলে সম্পূর্ণ schema দেওয়া আছে।

- **users**: Login ও Role ব্যবস্থাপনা
- **technicians**: Technician/Reporter ইনফো
- **equipments**: Equipment ইনফো ও স্টক
- **daily_issues**: প্রতিদিনের Issue Entry
- **issue_return_log**: Return Update ইতিহাস

> টেবিলগুলো Prepared Statement দিয়ে Access করার জন্য Foreign Key ব্যবহার করা হয়েছে।

## 3) Folder Structure (MVC Pattern)
```
app/
  config/
  core/
  controllers/
  models/
  views/
    layouts/
    auth/
    technician/
    admin/
public/
  assets/
    css/
  index.php

database/
  schema.sql

README.md
```

## 4) PHP Code (OOP + MVC)
**Core Classes:**
- `Database.php` → PDO + Prepared Statement
- `Auth.php` → Session + Role Check
- `Router.php` → Route Dispatch

**Controllers:**
- `AuthController` (Login/Logout)
- `IssueController` (Technician Issue Entry)
- `AdminIssueController` (Approve/Reject/Return)
- `ReportController` (Report + Print View)

**Models:**
- `User`, `Technician`, `Equipment`, `DailyIssue`, `IssueReturnLog`

সব Model এ Prepared Statement ব্যবহার করা হয়েছে, যাতে SQL Injection প্রতিরোধ হয়।

## 5) UI Design (Bootstrap + Mobile First)
- Bootstrap 5 ব্যবহার করে Responsive layout করা হয়েছে
- Mobile First approach অনুসরণ করা হয়েছে
- Table + Cards ব্যবহার করে Admin/Technician ড্যাশবোর্ড তৈরি করা হয়েছে

---

# Step-by-step Setup (Beginner Friendly)
1. **Database তৈরি করুন**
   - `database/schema.sql` ফাইলটি MySQL এ রান করুন।

2. **Config সেট করুন**
   - `app/config/config.php` ফাইলে DB User/Password আপডেট করুন।

3. **Local Server চালু করুন**
   - প্রজেক্ট রুটে গিয়ে চালান:
     ```bash
     php -S localhost:8000 -t public
     ```

4. **Login করুন**
   - Admin: `admin@tvchannel.local`
   - Password: `password123`

---

# Security Highlights
- **Prepared Statement** (SQL Injection Protection)
- **Password Hashing** (bcrypt)
- **Role Checking** (Admin/Technician)
- **Session-based Auth**

---

# Next Steps (Optional Improvements)
- CSRF Protection যোগ করা
- Pagination যুক্ত করা
- PDF Export (mPDF/DOMPDF)
- Audit Log
