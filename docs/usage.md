---
layout: default
title: Usage
---

# Usage Guide

## Basic Backup

```php
<?php
require_once 'vendor/autoload.php';
use DelhiArpitPatel\LargeMysqlMigrator\MySQLMigrator;

// Quick backup for entire database
MySQLMigrator::quickBackup(
    "localhost",     // Host
    "username",      // Username
    "password",      // Password
    "database_name", // Database name
    "/backup/path"   // Backup folder
);
```

## Basic Restore

```php
<?php
require_once 'vendor/autoload.php';
use DelhiArpitPatel\LargeMysqlMigrator\MySQLMigrator;

// Quick restore from backup
MySQLMigrator::quickRestore(
    "localhost",     // Host
    "username",      // Username  
    "password",      // Password
    "database_name", // Database name
    "/backup/path"   // Backup folder path
);
```

## Advanced Usage

### Backup Specific Tables

```php
<?php
require_once 'vendor/autoload.php';
use DelhiArpitPatel\LargeMysqlMigrator\MySQLMigrator;

// Instance API for more control
$migrator = new MySQLMigrator("localhost", "username", "password");
$migrator->backup("database_name", "/backup/path", ["bkp_logs"]); // skip patterns/tables
// ... later
$migrator->restore("/backup/path", "database_name");
```

### Process Flow

1. **Backup Phase**: Each table is exported to individual SQL files
2. **Compression**: SQL files are compressed into ZIP archives
3. **Sequential Processing**: Large databases are handled one table at a time
4. **Restore Phase**: ZIP files are extracted and imported sequentially

This approach prevents:
- Memory limit exceeded errors
- Script timeout issues
- Server overload during migration

[Next: API Reference →](/api)
