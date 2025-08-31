<?php
namespace DelhiArpitPatel\LargeMysqlMigrator;

class DatabaseConnection
{
    private \mysqli $connection;
    private string $host;
    private string $username;
    private string $password;
    private string $database;

    public function __construct(string $host, string $username, string $password, string $database = '')
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->connect();
    }

    private function connect(): void
    {
        $this->connection = new \mysqli($this->host, $this->username, $this->password, $this->database);
        
        if ($this->connection->connect_error) {
            throw new \Exception("Database connection failed: " . $this->connection->connect_error);
        }

        // Set UTF-8 encoding
        $this->connection->set_charset("utf8");
    }

    public function getConnection(): \mysqli
    {
        return $this->connection;
    }

    public function selectDatabase(string $database): bool
    {
        $this->database = $database;
        return $this->connection->select_db($database);
    }

    public function query(string $sql): \mysqli_result|bool
    {
        $result = $this->connection->query($sql);
        if (!$result) {
            throw new \Exception("Query failed: " . $this->connection->error);
        }
        return $result;
    }

    public function multiQuery(string $sql): bool
    {
        return $this->connection->multi_query($sql);
    }

    public function escapeString(string $string): string
    {
        return $this->connection->real_escape_string($string);
    }

    public function close(): void
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}
