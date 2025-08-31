---
layout: with-sidebar
title: API Reference
---

# API Documentation

## DelhiArpitPatel\\LargeMysqlMigrator\\MySQLMigrator

### Methods

#### `public static function quickBackup($host, $user, $pass, $database, $outputPath, array $skipTables = [], LoggerInterface $logger = null): bool`

One-liner to back up a complete database. Splits each table into individual SQL and compresses them into ZIPs.

**Parameters:** host, user, pass, database, outputPath, optional skipTables, optional logger.

**Returns:** bool

**Example:**
```php
use DelhiArpitPatel\LargeMysqlMigrator\MySQLMigrator;

MySQLMigrator::quickBackup("localhost", "user", "pass", "db", "/path/to/backup");
```

#### `public function backup(string $database, string $outputPath, array $skipTables = []): bool`

Instance method to back up the database with more control (e.g., skip tables).

**Parameters:**
- `$host` (string): Database host
- `$user` (string): Database username
- `$pass` (string): Database password  
- `$name` (string): Database name
- `$foldername` (string): Target folder path
- `$tables` (mixed): Table name(s) to backup

**Returns:** bool

#### `zipFile($output_path, $file_name)`

Creates a ZIP archive from a file.

**Parameters:**
- `$output_path` (string): Output ZIP file path
- `$file_name` (string): Source file to compress

**Returns:**
- void

---

#### `public static function quickRestore($host, $user, $pass, $database, $backupPath, LoggerInterface $logger = null): bool`

### Methods

One-liner to restore a database from backup ZIPs.

**Parameters:** host, user, pass, database, backupPath, optional logger.

**Returns:** bool

**Example:**
```php
use DelhiArpitPatel\LargeMysqlMigrator\MySQLMigrator;

MySQLMigrator::quickRestore("localhost", "user", "pass", "db", "/path/to/backup");
```

#### `public function restore(string $backupPath, string $database): bool`

Instance method to restore a database from a folder of backup ZIPs.

**Returns:** bool
