<?php
namespace DelhiArpitPatel\LargeMysqlMigrator;

/**
 * Main entry point for MySQL Database Migration
 * Provides simplified API for common operations
 */
class MySQLMigrator
{
    private DatabaseConnection $db;
    private LoggerInterface $logger;
    private TempFileManager $tempManager;

    public function __construct(
        string $host, 
        string $username, 
        string $password, 
        string $database = '',
        LoggerInterface $logger = null
    ) {
        $this->db = new DatabaseConnection($host, $username, $password, $database);
        $this->logger = $logger ?? new SilentLogger();
        $this->tempManager = new TempFileManager();
    }

    /**
     * Backup a database
     */
    public function backup(string $database, string $outputPath, array $skipTables = []): bool
    {
        $backupManager = new BackupManager($this->db, $this->logger, $this->tempManager);
        return $backupManager->backupDatabase($database, $outputPath, $skipTables);
    }

    /**
     * Restore a database
     */
    public function restore(string $backupPath, string $database): bool
    {
        $restoreManager = new RestoreManager($this->db, $this->logger, $this->tempManager);
        return $restoreManager->restoreDatabase($backupPath, $database);
    }

    /**
     * Static helper for quick backup
     */
    public static function quickBackup(
        string $host, 
        string $username, 
        string $password, 
        string $database, 
        string $outputPath,
        array $skipTables = [],
        LoggerInterface $logger = null
    ): bool {
        $migrator = new self($host, $username, $password, $database, $logger);
        return $migrator->backup($database, $outputPath, $skipTables);
    }

    /**
     * Static helper for quick restore
     */
    public static function quickRestore(
        string $host, 
        string $username, 
        string $password, 
        string $database, 
        string $backupPath,
        LoggerInterface $logger = null
    ): bool {
        $migrator = new self($host, $username, $password, $database, $logger);
        return $migrator->restore($backupPath, $database);
    }

    public function __destruct()
    {
        $this->tempManager->cleanup();
    }
}
