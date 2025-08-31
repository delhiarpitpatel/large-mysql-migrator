---
layout: default
title: Home
---

# Large MySQL Migrator

## The Problem
Anyone who has tried to move a large, multi-gigabyte MySQL database knows it's a nightmare. Standard "export/import" fails due to server timeouts and memory limits.

## The Solution
A custom PHP tool that intelligently automates the process by exporting each table into its own compressed ZIP file. The corresponding restore script then imports each ZIP sequentially on the new server, completely bypassing server limitations and turning a painful manual task into a reliable, automated one.

## Features
- 🚀 **Automated Migration**: No manual intervention needed
- 💾 **Split Table Support**: Each table exported individually 
- 📦 **ZIP Compression**: Reduces file sizes and transfer time
- ⏱️ **Timeout Prevention**: Bypasses server limitations
- 🔄 **Sequential Import**: Reliable restore process

## Quick Start

### Installation
```bash
composer require delhiarpitpatel/large-mysql-migrator
```

### Basic Usage
```php
use DelhiArpitPatel\LargeMysqlMigrator\MySQLMigrator;

MySQLMigrator::quickBackup(
    "localhost", 
    "username", 
    "password", 
    "database_name", 
    "/backup/folder"
);
```

[Get Started →]({{ '/installation' | relative_url }}){: .btn .btn-primary}
[View Examples →]({{ '/examples' | relative_url }}){: .btn .btn-outline}
