# Large MySQL Migrator

A PHP library for migrating large MySQL databases by splitting tables into individual ZIP files, bypassing server timeouts and memory limits.

## The Problem
Anyone who has tried to move a large, multi-gigabyte MySQL database knows it's a nightmare. Standard "export/import" fails due to server timeouts and memory limits. I was facing this exact, frustrating problem.

## The Solution
I built a custom PHP tool to solve this. The script intelligently automates the process by exporting each table into its own compressed ZIP file. The corresponding restore script then imports each ZIP sequentially on the new server, completely bypassing server limitations and turning a painful manual task into a reliable, automated one.

## Features
- Backup and restore MySQL databases
- Support for splitting large tables
- Example scripts for backup and restore

## Requirements
- PHP 7.4 or higher
- MySQL server (tested with 5.7+)
- PHP extensions: mysqli, zip
- Sufficient disk space for backups
- Write permissions to backup/restore folders
## Installation
Install via Composer:

```bash
composer require delhiarpitpatel/large-mysql-migrator
```

Or clone the repository and run `composer install`:
cd large-mysql-migrator
composer install
```

## Usage
Simple one-liner for common operations:

### Quick Backup
```php
require_once 'vendor/autoload.php';
use DelhiArpitPatel\LargeMysqlMigrator\MySQLMigrator;

MySQLMigrator::quickBackup("localhost", "username", "password", "database_name", "/backup/folder");
```

### Quick Restore
```php
require_once 'vendor/autoload.php';
use DelhiArpitPatel\LargeMysqlMigrator\MySQLMigrator;

MySQLMigrator::quickRestore("localhost", "username", "password", "database_name", "/backup/folder");
```

### Advanced Usage
```php
use DelhiArpitPatel\LargeMysqlMigrator\MySQLMigrator;

$migrator = new MySQLMigrator("localhost", "username", "password");
$migrator->backup("database_name", "/backup/folder");
$migrator->restore("/backup/folder", "database_name");
```

## Documentation
Visit our [documentation site](https://delhiarpitpatel.github.io/large-mysql-migrator/) for detailed guides and examples.

## License
This project is licensed under the MIT License. See the LICENSE file for details.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## Author
Arpit Patel

## Troubleshooting & FAQ
- **Permission errors:** Ensure PHP has write access to the backup/restore folders.
- **Missing extensions:** Install `mysqli` and `zip` extensions for PHP.
- **Large tables fail:** Check disk space and increase PHP memory/time limits if needed.
- **Restore fails:** Check MySQL user permissions and error logs for details.
- **Backup/restore is slow:** Use SSD storage and run on the database server for best performance.
