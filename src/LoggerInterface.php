<?php
namespace DelhiArpitPatel\LargeMysqlMigrator;

interface LoggerInterface
{
    public function info(string $message): void;
    public function error(string $message): void;
    public function debug(string $message): void;
}
