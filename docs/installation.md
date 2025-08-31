---
layout: with-sidebar
title: Installation
---

# Installation

## Requirements
- PHP 7.4 or higher
- MySQL/MySQLi extension
- ZIP extension

## Via Composer (Recommended)

```bash
composer require delhiarpitpatel/large-mysql-migrator
```

## Manual Installation

1. Clone the repository:
```bash
git clone https://github.com/delhiarpitpatel/large-mysql-migrator.git
```

2. Install dependencies:
```bash
cd large-mysql-migrator
composer install
```

## Verification

After installation, verify everything works:

```php
<?php
require_once 'vendor/autoload.php';

use DelhiArpitPatel\LargeMysqlMigrator\MySQLMigrator;

echo "Large MySQL Migrator loaded successfully!";
```

[Next: Usage →]({{ '/usage' | relative_url }})
