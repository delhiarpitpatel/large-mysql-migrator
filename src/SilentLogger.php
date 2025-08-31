<?php
namespace DelhiArpitPatel\LargeMysqlMigrator;

class SilentLogger implements LoggerInterface
{
    public function info(string $message): void {}
    public function error(string $message): void {}
    public function debug(string $message): void {}
}
