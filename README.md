# Management Reclamation System

A Laravel-based web application for managing land reclamation progress tracking, monitoring environmental restoration activities, and maintaining compliance with regulatory requirements.

## Features

- **Plot Management**: Track multiple reclamation plots with detailed progress monitoring
- **Progress Tracking**: Record and monitor various reclamation activities with dynamic field definitions
- **Target Management**: Set and track progress against specific environmental restoration targets
- **Documentation System**: Upload and manage photographic evidence and documentation
- **Progress Analytics**: Calculate overall progress percentages and generate trend analysis
- **Historical Snapshots**: Maintain timeline accuracy with historical progress data
- **Activity Categorization**: Organize activities by categories (Revegetasi, Monitoring, etc.)
- **Indicator-based Tracking**: Support for multiple environmental indicators with configurable aggregation

## Simplified Route Overview

**Authentication & User**
- Login, password reset, email verification, logout
- Profile view/update/delete

**Land & Plot Management**
- Manage lahan (sites) and associated plots (blocks)
- Set plot-specific targets
- View plot activity logs

**Progress Entries**
- Create, edit, update, delete reclamation progress records
- Attach/remove documentation files
- Dynamic form based on selected activity type

**Dashboard & Analytics**
- Fetch consolidated stats (totals, daily/weekly changes)
- Retrieve progress per block
- Map data (plots + progress)
- Historical progress series (overall & per block)
- Indicator data (all, specific, enhanced, block breakdowns)
- Summary metrics

**Planning & Budget**
- Manage reclamation plans and cost plans (with PDF exports)
- Track reclamation budget
- Generate recapitulation (progress & cost) reports (PDF)

**Success Criteria**
- View & update success criteria sections
- Export criteria report (PDF)

**Documentation & Files**
- General documentation CRUD
- Plan files list/upload/delete + preview
- Report files list/upload/delete + preview

**Biological & Inventory**
- Tree/species records (create, manage yearly data, remove by year)
- Warehouse/inventory records CRUD

## Requirements

- PHP 8.3 or higher
- Composer 2.0+
- PostgreSQL 17+
- Node.js 18+ and npm
- Laravel 12.x

## Installation

1. **Clone the repository**
```bash
git clone <repository-url>
cd management-reclamation
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node.js dependencies**
```bash
npm install
```

4. **Environment configuration**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure database**
Edit your `.env` file with database credentials:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reclamation_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. **Run database migrations**
```bash
php artisan migrate
```

7. **Seed the database (optional)**
```bash
php artisan db:seed
```

8. **Build frontend assets**
```bash
npm run build
# or for development
npm run dev
```

9. **Create storage symlink**
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
- Storage: Public disk for documentation (with cleanup procedures)
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