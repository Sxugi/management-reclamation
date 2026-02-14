# Management Reclamation System

A Laravel-based web application for managing land reclamation progress tracking, monitoring environmental restoration activities, and maintaining compliance with regulatory requirements.

## Features

- **Admin Dashboard**: Centralized control for user management and master data configuration (Tree Types & Budget Categories).
- **Role-Based Access Control (RBAC)**: Granular permission system with Admin, Owner, Editor, and Viewer roles.
- **Land & Team Management**: Assign specific teams to reclamation sites with defined access levels.
- **Plot Management**: Track multiple reclamation plots with detailed progress monitoring.
- **Progress Tracking**: Record and monitor various reclamation activities with dynamic field definitions.
- **Target Management**: Set and track progress against specific environmental restoration targets.
- **Documentation System**: Upload and manage photographic evidence and documentation.
- **Progress Analytics**: Calculate overall progress percentages and generate trend analysis.
- **Historical Snapshots**: Maintain timeline accuracy with historical progress data.

## Roles & Permissions

### System Roles
- **Super Admin**: 
  - Full access to the system.
  - **Exclusive right to create new "Lahan" (Sites).**
  - Manages Users (Create, Edit, Suspend).
  - Manages Master Data (Tree Species, Budget Categories).
  - Can view and manage all Lahan.

### Lahan (Site) Team Roles
Users can be assigned to specific "Lahan" with the following roles:
- **Owner**: Full control over the specific Lahan, including team management (add/remove members).
- **Editor**: Can input progress, upload documentation, and edit data within the assigned Lahan.
- **Viewer**: Read-only access to the Lahan's data and reports.

## Simplified Route Overview

**Admin Panel (New)**
- **Dashboard**: Overview of system stats (Total Users, Active Users, Admins).
- **User Management**: Add new users, manage roles, suspend/activate accounts.
- **Master Data**: 
  - **Jenis Pohon**: Add/Edit tree species available for input by users.
  - **Kategori Anggaran**: Add/Edit budget categories for financial planning.

**Authentication & User**
- Login, password reset, email verification, logout
- Profile view/update/delete

**Land & Plot Management**
- **Create Lahan**: Restricted to Admin only.
- **Team Management**: Manage members and assign roles (Owner/Editor/Viewer) for specific Lahan.
- Manage associated plots (blocks).
- Set plot-specific targets.

**Progress Entries**
- Create, edit, update, delete reclamation progress records (Editors & Owners).
- Attach/remove documentation files.
- Dynamic form based on selected activity type.

**Dashboard & Analytics**
- Fetch consolidated stats (totals, daily/weekly changes).
- Retrieve progress per block.
- Map data (plots + progress).
- Historical progress series.
- Indicator data and summary metrics.

**Planning & Budget**
- Manage reclamation plans and cost plans.
- Track reclamation budget based on Admin-defined categories.
- Generate recapitulation reports.

**Success Criteria & Documentation**
- View & update success criteria sections.
- General documentation and file management.

**Biological & Inventory**
- Tree/species records (based on Admin-defined species).
- Warehouse/inventory records CRUD.

## Requirements

- PHP 8.3 or higher
- Composer 2.0+
- PostgreSQL 17+ & PostGIS
  - **Note:** PostGIS must be installed as an extension for PostgreSQL before running this web application
- Node.js 18+ and npm
- Laravel 12.x

## Setup Instructions

1. **Install PostgreSQL with PostGIS**

Before proceeding with application setup, ensure PostgreSQL 17+ is installed with the PostGIS extension enabled:

```bash
# macOS (using Homebrew)
brew install postgresql postgis

# Ubuntu/Debian
sudo apt-get install postgresql postgresql-contrib postgis

# Windows
# Download from https://www.postgresql.org/download/windows/
# Select PostGIS during installation
```
 
2. **Enable PostGIS Extension**

Connect to your PostgreSQL database and run:

```sql
CREATE EXTENSION postgis;
```

3. **Clone the repository**
```bash
git clone https://github.com/Sxugi/management-reclamation.git
cd management-reclamation
```

4. **Install PHP dependencies**
```bash
composer install
```

5. **Install Node.js dependencies**
```bash
npm install
```

6. **Environment configuration**
```bash
cp .env.example .env
php artisan key:generate
```

7. **Configure database**
Edit your `.env` file with database credentials:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=reclamation_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

8. **Run database migrations**
```bash
php artisan migrate
```

9. **Seed the database (optional)**
```bash
php artisan db:seed
```

10. **Build frontend assets**
```bash
npm run build
# or for development
npm run dev
```

11. **Create storage symlink**
```bash
php artisan storage:link
```

## Configuration

### Indicators Configuration

Configure your environmental indicators in `config/indicators.php`:

```php
return [
    'targets' => [
        'pohon_ditanam' => [
            'label' => 'Pohon Ditanam',
            'satuan' => 'batang',
            'summary_type' => 'sum'
        ],
        'luas_revegetasi' => [
            'label' => 'Luas Revegetasi',
            'satuan' => 'ha',
            'summary_type' => 'sum'
        ],
        // Add more indicators as needed
    ]
];
```

## Architecture

- Controllers: HTTP handling (dashboard, progress, planning, files, criteria, etc.)
- Services: Business logic (ProgresReklamasiService, DashboardService)
- Models: Plot, ProgresReklamasi, TargetProgresReklamasi, ProgresSnapshot, FieldDefinition, ProgresFieldValue, etc.
- Policies: Authorization logic for Lahan access (Owner/Editor/Viewer)
- Storage: Public disk for documentation
- Snapshots: Maintain historical integrity & trend analysis

## Usage

### Running the Application

**Development:**
```bash
composer run dev
```

**Production:**
```bash
# Set up your web server to point to the public directory
# Build production assets
npm run build
```

### Basic Operations

1. **Managing Plots**: Create and configure reclamation plots with basic information
2. **Setting Targets**: Define target values for various environmental indicators per plot
3. **Recording Progress**: Add progress entries with dynamic fields based on activity type
4. **Uploading Documentation**: Attach photos and documents to progress entries
5. **Monitoring Progress**: View real-time progress percentages and historical trends

## Security

- All file uploads are validated and stored securely
- Progress data is validated before database insertion
- User authentication and authorization implemented
- CSRF protection enabled for all forms
- SQL injection prevention through Eloquent ORM