<?php

class settingDB extends PDO
{
    private bool $debug = false;
    private string $logFile = 'db_errors.log';

    public function __construct(array $config)
    {
        $host    = $config['db']['host'];
        $dbname  = $config['db']['dbname'];
        $user    = $config['db']['user'];
        $pass    = $config['db']['pass'];
        $charset = $config['db']['charset'];

        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        parent::__construct($dsn, $user, $pass);

        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, ['DBStatement', []]);

        if (!empty($config['options']['debug'])) {
            $this->debug = $config['options']['debug'];
        }
        if (!empty($config['options']['logFile'])) {
            $this->logFile = $config['options']['logFile'];
        }
    }

    public function setDebug(bool $debug): void {
        $this->debug = $debug;
    }

    public function insert(string $sql, array $data = []): bool {
        return $this->executeQuery($sql, $data);
    }

    public function update(string $sql, array $data = []): bool {
        return $this->executeQuery($sql, $data);
    }

    public function delete(string $sql, array $data = []): bool {
        return $this->executeQuery($sql, $data);
    }

    public function select(string $sql, array $data = [], int $fetchMode = PDO::FETCH_ASSOC): array {
        try {
            if ($this->debug) { $this->debugPrint($sql, $data); }
            $stmt = $this->prepare($sql);
            $stmt->execute($data);
            return $stmt->fetchAll($fetchMode);
        } catch (PDOException $e) {
            $this->logError($e);
            return [];
        }
    }

    public function count(string $sql, array $data = []): int {
        try {
            if ($this->debug) { $this->debugPrint($sql, $data); }
            $stmt = $this->prepare($sql);
            $stmt->execute($data);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError($e);
            return 0;
        }
    }

    public function selectPage(string $sql, array $data, int $offset, int $limit, int $fetchMode = PDO::FETCH_ASSOC): array {
        $sql .= " LIMIT :offset, :limit";
        try {
            if ($this->debug) { $this->debugPrint($sql, $data + ['offset' => $offset, 'limit' => $limit]); }
            $stmt = $this->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(is_string($key) ? $key : ":$key", $value);
            }
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll($fetchMode);
        } catch (PDOException $e) {
            $this->logError($e);
            return [];
        }
    }

    public function begin(): bool {
        return $this->beginTransaction();
    }

    public function commit(): bool {
        return $this->commit();
    }

    public function rollback(): bool {
        return $this->rollBack();
    }

    public function lastId(): string {
        return $this->lastInsertId();
    }

    public function queryExecute(string $sql, array $data = []): ?PDOStatement {
        try {
            if ($this->debug) { $this->debugPrint($sql, $data); }
            $stmt = $this->prepare($sql);
            $stmt->execute($data);
            return $stmt;
        } catch (PDOException $e) {
            $this->logError($e);
            return null;
        }
    }

    public function getAllSettings(): array {
        return $this->select("SELECT * FROM settings");
    }

    public function getUserById(int $id): ?array {
        $result = $this->select("SELECT * FROM users WHERE id = :id", ['id' => $id]);
        return $result[0] ?? null;
    }

    private function executeQuery(string $sql, array $data = []): bool {
        try {
            if ($this->debug) { $this->debugPrint($sql, $data); }
            $stmt = $this->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    private function logError(PDOException $e): void {
        error_log(date('[Y-m-d H:i:s] ') . $e->getMessage() . PHP_EOL, 3, $this->logFile);
    }

    private function debugPrint(string $sql, array $data): void {
        echo "<pre>SQL: $sql\nDATA: "; print_r($data); echo "</pre>";
    }
}

class DBStatement extends PDOStatement {
    // opcional
}
?>
