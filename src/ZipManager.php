<?php
namespace DelhiArpitPatel\LargeMysqlMigrator;

class ZipManager
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new SilentLogger();
    }

    public function createZip(string $sourceFile, string $outputPath): bool
    {
        $zip = new \ZipArchive();
        
        if ($zip->open($outputPath, \ZipArchive::CREATE) !== TRUE) {
            throw new \Exception("Could not create ZIP archive: " . $outputPath);
        }

        if (is_dir($sourceFile)) {
            $this->addDirectoryToZip($zip, $sourceFile);
        } else if (is_file($sourceFile)) {
            $zip->addFile($sourceFile, basename($sourceFile));
        } else {
            throw new \Exception("Source file/directory does not exist: " . $sourceFile);
        }

        $zip->close();
        $this->logger->info("ZIP archive created: " . $outputPath);
        return true;
    }

    public function extractZip(string $zipFile, string $extractPath): bool
    {
        $zip = new \ZipArchive();
        
        if ($zip->open($zipFile) !== TRUE) {
            throw new \Exception("Could not open ZIP archive: " . $zipFile);
        }

        if (!is_dir($extractPath)) {
            mkdir($extractPath, 0755, true);
        }

        $zip->extractTo($extractPath);
        $zip->close();
        
        $this->logger->info("ZIP archive extracted: " . $zipFile . " to " . $extractPath);
        return true;
    }

    private function addDirectoryToZip(\ZipArchive $zip, string $dir, string $base = ''): void
    {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;
            
            $filePath = $dir . '/' . $file;
            $zipPath = $base . $file;
            
            if (is_dir($filePath)) {
                $zip->addEmptyDir($zipPath);
                $this->addDirectoryToZip($zip, $filePath, $zipPath . '/');
            } else {
                $zip->addFile($filePath, $zipPath);
            }
        }
    }
}
