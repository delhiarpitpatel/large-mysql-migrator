<?php
namespace DelhiArpitPatel\LargeMysqlMigrator;

class RestoreManager
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

    public function restoreDatabase(string $backupPath, string $database): bool
    {
        $this->logger->info("Starting restore of database: " . $database);
        
        if (!$this->db->selectDatabase($database)) {
            throw new \Exception("Could not select database: " . $database);
        }

        if (!is_dir($backupPath)) {
            throw new \Exception("Backup path does not exist: " . $backupPath);
        }

        $tempDir = $this->tempManager->createTempDir('restore_' . $database);
        $this->processBackupFiles($backupPath, $tempDir);
        
        $this->logger->info("Database restore completed: " . $database);
        return true;
    }

    private function processBackupFiles(string $backupPath, string $tempDir): void
    {
        $files = scandir($backupPath);
        
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'zip' && !str_contains($file, 'imported_')) {
                $this->processZipFile($backupPath . '/' . $file, $tempDir);
                
                // Mark as imported
                $importedFile = $backupPath . '/imported_' . $file;
                rename($backupPath . '/' . $file, $importedFile);
            }
        }
    }

    private function processZipFile(string $zipFile, string $tempDir): void
    {
        $this->logger->info("Processing: " . basename($zipFile));
        
        // Extract ZIP
        $extractDir = $tempDir . '/' . pathinfo($zipFile, PATHINFO_FILENAME);
        $this->zipManager->extractZip($zipFile, $extractDir);
        
        // Import SQL files
        $sqlFiles = glob($extractDir . '/*.sql');
        foreach ($sqlFiles as $sqlFile) {
            $this->importSqlFile($sqlFile);
        }
    }

    private function importSqlFile(string $sqlFile): void
    {
        $this->logger->info("Importing SQL file: " . basename($sqlFile));
        
        $sql = file_get_contents($sqlFile);
        if (!$sql) {
            throw new \Exception("Could not read SQL file: " . $sqlFile);
        }

        if (!$this->db->multiQuery($sql)) {
            throw new \Exception("Failed to import SQL file: " . $sqlFile);
        }

        // Wait for all queries to complete
        do {
            if ($result = $this->db->getConnection()->store_result()) {
                $result->free();
            }
        } while ($this->db->getConnection()->next_result());

        $this->logger->info("SQL file imported successfully: " . basename($sqlFile));
    }

    public function __destruct()
    {
        $this->tempManager->cleanup();
    }
}
