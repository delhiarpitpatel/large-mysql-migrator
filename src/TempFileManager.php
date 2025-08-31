<?php
namespace DelhiArpitPatel\LargeMysqlMigrator;

class TempFileManager
{
    private string $tempDir;
    private array $createdFiles = [];
    private array $createdDirs = [];

    public function __construct(string $baseDir = null)
    {
        $this->tempDir = $baseDir ?? sys_get_temp_dir() . '/mysql_migrator_' . uniqid();
        if (!is_dir($this->tempDir)) {
            mkdir($this->tempDir, 0755, true);
            $this->createdDirs[] = $this->tempDir;
        }
    }

    public function createTempFile(string $filename = null): string
    {
        $filename = $filename ?? uniqid('temp_') . '.tmp';
        $filepath = $this->tempDir . '/' . $filename;
        touch($filepath);
        $this->createdFiles[] = $filepath;
        return $filepath;
    }

    public function createTempDir(string $dirname = null): string
    {
        $dirname = $dirname ?? uniqid('temp_dir_');
        $dirpath = $this->tempDir . '/' . $dirname;
        mkdir($dirpath, 0755, true);
        $this->createdDirs[] = $dirpath;
        return $dirpath;
    }

    public function getTempDir(): string
    {
        return $this->tempDir;
    }

    public function cleanup(): void
    {
        // Remove files first
        foreach ($this->createdFiles as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Remove directories (in reverse order)
        foreach (array_reverse($this->createdDirs) as $dir) {
            if (is_dir($dir)) {
                $this->removeDirectory($dir);
            }
        }

        $this->createdFiles = [];
        $this->createdDirs = [];
    }

    private function removeDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }
        return rmdir($dir);
    }

    public function __destruct()
    {
        $this->cleanup();
    }
}
