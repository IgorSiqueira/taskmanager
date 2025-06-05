<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\FluentPDO;

use Envms\FluentPDO\Query as QueryBuilder;
use PDO;
use PDOException;

class ConnectionProvider
{
    /*TODO
    Injetar e pegar os dados via env
    */
    private string $host = 'mysql';
    private string $dbName = 'task_manager_db';
    private string $user = 'taskmanager_user';
    private string $password = 'userpassword';
    private string $charset = 'utf8mb4';

    private ?QueryBuilder $fluentPdoInstance = null;

    public function __construct()
    {
    }
    public function getFluentPDO(): QueryBuilder
    {
        if ($this->fluentPdoInstance === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset={$this->charset}";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                $pdo = new PDO($dsn, $this->user, $this->password, $options);
                $this->fluentPdoInstance = new QueryBuilder($pdo);
            } catch (PDOException $e) {
                error_log('FluentPDO Connection Error: ' . $e->getMessage());
                throw $e;
            }
        }

        return $this->fluentPdoInstance;
    }
}