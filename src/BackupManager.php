<?php
namespace DelhiArpitPatel\LargeMysqlMigrator;

class BackupManager
{
    private DatabaseConnection $db;
    private TempFileManager $tempManager;
    private ZipManager $zipManager;
    private LoggerInterface $logger;

    public function __construct(
        DatabaseConnection $db,
        LoggerInterface $logger = null,
        TempFileManager $tempManager = null
    ) {
        $this->db = $db;
        $this->logger = $logger ?? new SilentLogger();
        $this->tempManager = $tempManager ?? new TempFileManager();
        $this->zipManager = new ZipManager($this->logger);
    }

    public function backupDatabase(string $database, string $outputPath, array $skipTables = []): bool
    {
        $this->logger->info("Starting backup of database: " . $database);
        
        if (!$this->db->selectDatabase($database)) {
            throw new \Exception("Could not select database: " . $database);
        }

        // Create output directory
        if (!is_dir($outputPath)) {
            mkdir($outputPath, 0755, true);
        }

        // Get all tables
        $tables = $this->getTables($skipTables);
        $this->logger->info("Found " . count($tables) . " tables to backup");

        // Create temporary directory for SQL files
        $tempDir = $this->tempManager->createTempDir('backup_' . $database);

        // Backup each table
        foreach ($tables as $table) {
            $this->backupTable($table, $tempDir);
        }

        // Create final ZIP
        $finalZip = $outputPath . '/' . $database . '_backup_' . date('Y-m-d_H-i-s') . '.zip';
        $this->zipManager->createZip($tempDir, $finalZip);

        $this->logger->info("Database backup completed: " . $finalZip);
        return true;
    }

    private function getTables(array $skipTables = []): array
    {
        $result = $this->db->query("SHOW TABLES");
        $tables = [];
        
        while ($row = $result->fetch_row()) {
            if (!in_array($row[0], $skipTables) && strpos($row[0], 'bkp') === false) {
                $tables[] = $row[0];
            }
        }
        
        return $tables;
    }

    private function backupTable(string $table, string $outputDir): void
    {
        $this->logger->info("Backing up table: " . $table);
        
        // Generate SQL
        $sql = $this->generateTableSQL($table);
        
        // Write to temporary file
        $sqlFile = $outputDir . '/' . $table . '.sql';
        file_put_contents($sqlFile, $sql);
        
        // Create individual ZIP for this table
        $zipFile = $outputDir . '/' . $table . '.zip';
        $this->zipManager->createZip($sqlFile, $zipFile);
        
        // Remove SQL file (keep only ZIP)
        unlink($sqlFile);
        
        $this->logger->info("Table backup completed: " . $table);
    }

    private function generateTableSQL(string $table): string
    {
        $sql = "-- Backup for table: " . $table . "\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
        
        // Drop table if exists
        $sql .= "DROP TABLE IF EXISTS `{$table}`;\n\n";
        
        // Create table structure
        $result = $this->db->query("SHOW CREATE TABLE `{$table}`");
        $row = $result->fetch_row();
        $sql .= $row[1] . ";\n\n";
        
        // Insert data
        $dataResult = $this->db->query("SELECT * FROM `{$table}`");
        if ($dataResult->num_rows > 0) {
            $sql .= "-- Data for table: " . $table . "\n";
            while ($row = $dataResult->fetch_row()) {
                $sql .= "INSERT INTO `{$table}` VALUES (";
                $values = [];
                foreach ($row as $value) {
                    if ($value === null) {
                        $values[] = "NULL";
                    } else {
                        $values[] = "'" . $this->db->escapeString($value) . "'";
                    }
                }
                $sql .= implode(', ', $values) . ");\n";
            }
        }
        
        return $sql;
    }

    public function __destruct()
    {
        $this->tempManager->cleanup();
    }
}
