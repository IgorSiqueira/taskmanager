<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Medoo;

use Exception;
use Medoo\Medoo;

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
    private string $collation = 'utf8mb4_unicode_ci';

    private ?Medoo $medooInstance = null;

    public function __construct()
    {
    }

    public function getMedoo(): Medoo
    {
        if ($this->medooInstance === null) {
            try {
                $this->medooInstance = new Medoo([
                    'type' => 'mysql',
                    'host' => $this->host,
                    'database' => $this->dbName,
                    'username' => $this->user,
                    'password' => $this->password,
                    'charset' => $this->charset,
                    'collation' => $this->collation,
                    'port' => 3306,
                    'option' => [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                        \PDO::ATTR_EMULATE_PREPARES => false,
                    ],
                ]);
            } catch (Exception $e) {
                error_log('Medoo Connection Error: ' . $e->getMessage());
                throw $e;
            }
        }
        return $this->medooInstance;
    }
}
