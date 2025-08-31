---
layout: with-sidebar
title: Examples
---

# Code Examples

## Quick Start Examples

### Simple Backup
```php
<?php
require_once 'vendor/autoload.php';
use DelhiArpitPatel\LargeMysqlMigrator\MySQLMigrator;
use DelhiArpitPatel\LargeMysqlMigrator\ConsoleLogger;

// One-liner backup with logging
MySQLMigrator::quickBackup(
    "localhost", 
    "username", 
    "password", 
    "my_database", 
    "/backup/folder",
    [], // No tables to skip
    new ConsoleLogger()
);
```

### Simple Restore
```php
<?php
require_once 'vendor/autoload.php';
use DelhiArpitPatel\LargeMysqlMigrator\MySQLMigrator;
use DelhiArpitPatel\LargeMysqlMigrator\ConsoleLogger;

// One-liner restore with logging
MySQLMigrator::quickRestore(
    "localhost", 
    "username", 
    "password", 
    "my_database", 
    "/backup/folder",
    new ConsoleLogger()
);
```

## Advanced Examples

### Custom Backup with Skip Tables
```php
<?php
require_once 'vendor/autoload.php';
use DelhiArpitPatel\LargeMysqlMigrator\MySQLMigrator;
use DelhiArpitPatel\LargeMysqlMigrator\ConsoleLogger;

$migrator = new MySQLMigrator(
    "localhost", 
    "username", 
    "password", 
    "my_database",
    new ConsoleLogger()
);

// Backup excluding certain tables
$migrator->backup("my_database", "/backup/folder", [
    "temp_table", 
    "cache_table", 
    "session_table"
]);
```

### Silent Operation (No Logging)
```php
<?php
require_once 'vendor/autoload.php';
use DelhiArpitPatel\LargeMysqlMigrator\MySQLMigrator;

// Silent backup - no output
MySQLMigrator::quickBackup(
    "localhost", 
    "username", 
    "password", 
    "my_database", 
    "/backup/folder"
);
```

[← Back to Usage]({{ '/usage' | relative_url }}) | [API Reference →]({{ '/api' | relative_url }})
