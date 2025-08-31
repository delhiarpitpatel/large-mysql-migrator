<?php
namespace DelhiArpitPatel\LargeMysqlMigrator;

class ConsoleLogger implements LoggerInterface
{
    public function info(string $message): void
    {
        echo "[INFO] " . date('Y-m-d H:i:s') . " - " . $message . "\n";
    }

    public function error(string $message): void
    {
        echo "[ERROR] " . date('Y-m-d H:i:s') . " - " . $message . "\n";
    }

    public function debug(string $message): void
    {
        echo "[DEBUG] " . date('Y-m-d H:i:s') . " - " . $message . "\n";
    }
}
