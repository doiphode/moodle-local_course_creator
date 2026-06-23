# Course Creator (`local_course_creator`)

A Moodle local plugin that provides a guided, three-stage wizard for creating courses inside a category. Users can create a blank course, clone an existing course in the same category, or restore from a pre-configured school template — all without leaving the category context.

## Overview

Moodle's default course creation form is long and intimidating for new users, and it offers no way to base a new course on a template. This plugin solves both problems: it adds a **"Course creator"** link to the category settings navigation and walks the user through three clear stages — choose a creation type, configure the backup/restore settings, then finalise the course — resulting in a fully pre-populated new course.

Templates are grouped into **headings** (e.g. one heading per school or department) and managed by administrators from a dedicated template management page. Each heading holds one or more course IDs, which appear as options in the grouped template picker.

## Installation

### Via Moodle Plugin Installer

1. Download the plugin ZIP package.
2. Log in as a Moodle administrator.
3. Navigate to **Site administration → Plugins → Install plugins**.
4. Upload the ZIP file and follow the on-screen steps.
5. Complete the upgrade process.

### Manual Installation

1. Download and extract the plugin source code.
2. Copy the folder into your Moodle installation:
   ```
   /local/course_creator
   ```
3. Navigate to **Site administration → Notifications** and complete the installation.

## Features

### Three Course Creation Modes

| Mode | Description |
|------|-------------|
| **Blank course** | Skips the wizard and redirects directly to Moodle's standard course edit form. |
| **From a previous course** | Choose any visible course already in the current category and restore it as the basis for the new course. |
| **From a school template** | Pick from a grouped list of pre-configured template courses organised by heading (e.g. school or department). |

### Three-Stage Progress Wizard

The wizard guides users through three clearly labelled stages:

1. **Stage 1 — Course creator:** Choose the creation mode and source course or template.
2. **Stage 2 — Course setup:** Configure the Moodle backup settings (content, users, blocks, activities, etc.) for the source course.
3. **Stage 3 — Course information:** Supply the new course's full name, short name, and target category. The backup runs automatically and the restored course is created.

A progress indicator shows the current stage at every step.

### Template Management

- Administrators create named **headings** (groups) such as "English chapter" or "AI Learning".
- Each heading holds one or more courses, and the **Heading list** shows each heading's name and total course count.
- The **Course list** shows every assigned course across all headings — displaying the Course ID, course name, and which heading it belongs to.
- Headings and their courses appear as a grouped dropdown in the course creation wizard.
- A **"Manage templates"** shortcut link is shown directly on the Stage 1 creation form.
- Both list pages provide **Add heading** and **Add course** buttons in the top-right corner for quick access.

### Native Moodle Backup/Restore Engine

All content, settings, sections, and activities from the source course are fully preserved in the new course using Moodle's built-in backup and restore system — no proprietary copy mechanism.

### Category-Aware Entry Point

The **"Course creator"** link appears automatically in the category settings navigation. It is only visible to users who have permission to create courses in that category. The new course is always created in the current category, or a category selected during Stage 3.

### Privacy Compliant

The plugin stores no personal user data and is fully compliant with Moodle's privacy API.


## Configuration — Setting Up Templates

Before users can create courses from a school template, administrators must configure the headings and assign courses to them.

1. Navigate to **Site administration → Plugins → Local plugins → Course creator settings**.
2. Click **Add heading** to create a template group (e.g. "Science Faculty", "Business School").
3. In the **Heading list**, click the heading name to open its course list.
4. Click **Add course** and enter the **Course ID** of any existing Moodle course you want to use as a template.
5. Repeat for each template course within the heading.

Template headings and their assigned courses will appear as grouped options in the "From a school template" dropdown on the course creation form.

## Usage

### Creating a Course

1. Navigate to a course category (e.g. via **Site administration → Courses → Manage courses and categories**).
2. Open the category settings (click the cog/edit icon or use the category navigation block).
3. Click **Course creator** in the category settings navigation.
4. **Stage 1 — Select type:** Choose one of:
   - **Blank course** — goes directly to the standard Moodle course form.
   - **From a school template** — select a template from the grouped dropdown.
   - **From a previous course** — select any existing course in the current category.
5. Click **Create Course**.
6. **Stage 2 — Course setup:** Configure the Moodle backup settings for the source course (content, users, blocks, etc.).
7. **Stage 3 — Course information:** Enter the new course full name, short name, and target category. The backup runs automatically and the restore completes, redirecting you to the newly created course.

### Managing Templates (Administrators)

Navigate to **Site administration → Plugins → Local plugins → Course creator settings**, or click the **Manage templates** link on the Stage 1 creation form.

#### Heading list

Displays all template groups with their name and total course count. Actions available per row:

- **Edit** — rename the heading.
- **Delete** — remove the heading and all its course assignments.
- **View courses** — open the course list scoped to that heading.

Use the **Add heading** button (top right) to create a new group.

#### Course list

Displays every assigned course across all headings — showing Course ID, course name, and the heading it belongs to. Actions available per row:

- **Edit** — change which heading the course is assigned to.
- **Delete** — remove the course assignment.

Use the **Add course** button (top right) to assign a new course to a heading.

## Required Capabilities

| Capability                    | Required for                          |
|-------------------------------|---------------------------------------|
| `moodle/course:create`        | Seeing the "Course creator" link and accessing the wizard and all admin management pages. |
| `moodle/backup:backupcourse`  | Running the backup step on the source course (Stage 2). |

Both capabilities are needed to use the full wizard. By default, Editing Teachers and Managers hold these capabilities.

## Compatibility

| Moodle Version | Supported |
|----------------|-----------|
| Moodle 4.1.x   | Yes       |
| Moodle 4.x     | Yes       |
| Moodle 5.x     | Yes       |
| Moodle 5.1.x   | Yes       |
| Moodle 5.2.x   | Yes       |

**Current release:** 5.1 (Build 20260616)

## Author

Shubhendra Doiphode — [GitHub: doiphode](https://github.com/doiphode) | [Moodle Plugins Directory](https://moodle.org/plugins/local_course_creator)
