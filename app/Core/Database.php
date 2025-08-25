<?php

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
  /** @var string Database host */
  private static string $host;

  /** @var string Database name */
  private static string $dbName;

  /** @var string Database username */
  private static string $username;

  /** @var string Database password */
  private static string $password;

  /** @var int Database port */
  private static int $port = 3306;

  /** @var string Database charset */
  private static string $charset = 'utf8mb4';

  /** @var PDO|null Connection instance */
  private static ?PDO $connection = null;

  /** @var string|null Table name */
  private ?string $table = null;

  /**
   * Configure the database connection (static)
   * @param string $host
   * @param string $dbName
   * @param string $username
   * @param string $password
   * @param int $port
   * @param string $charset
   */
  public static function config(
    string $host,
    string $dbName,
    string $username,
    string $password,
    int $port = 3306,
    string $charset = 'utf8mb4'
  ): void {
    self::$host = $host;
    self::$dbName   = $dbName;
    self::$username = $username;
    self::$password = $password;
    self::$port     = $port;
    self::$charset  = $charset;
  }

  /**
   * Constructor: set table name
   */
  public function __construct(string $table)
  {
    $this->table = $table;
    $this->connect();
  }

  /**
   * Establish PDO connection (only once)
   * @return void
   */
  private function connect(): void
  {
    if (self::$connection === null) {
      $dsn = sprintf(
        'mysql:host=%s;dbname=%s;port=%d;charset=%s',
        self::$host,
        self::$dbName,
        self::$port,
        self::$charset
      );

      try {
        self::$connection = new PDO($dsn, self::$username, self::$password, [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::ATTR_EMULATE_PREPARES => false,
        ]);
      } catch (PDOException $e) {
        error_log('Database connection error: ' . $e->getMessage());
        throw new PDOException('Internal server error while connecting to the database.');
      }
    }
  }

  /**
   * Execute a query with parameters
   * @param string $query
   * @param array $params
   * @return PDOStatement
   */
  public function execute(string $query, array $params = []): PDOStatement
  {
    $stmt = self::$connection->prepare($query);
    $stmt->execute($params);
    return $stmt;
  }

  /**
   * Insert data into table
   * @param array $data
   * @return int
   * @throws PDOException
   */
  public function insert(array $data): int
  {
    $fields = array_keys($data);
    $placeholders = implode(',', array_fill(0, count($fields), '?'));

    $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $this->table, implode(',', $fields), $placeholders);

    $this->execute($sql, array_values($data));

    return (int) self::$connection->lastInsertId();
  }

  /**
   * Select data from table
   * @param string $where
   * @param array $params
   * @param string $fields
   * @param string $order
   * @param string $limit
   */
  public function select(
    ?string $where = null,
    array $params = [],
    string $fields = '*',
    ?string $order = null,
    ?string $limit = null
  ) {
    $sql = "SELECT $fields FROM {$this->table}";
    if ($where) $sql .= " WHERE $where";
    if ($order) $sql .= " ORDER BY $order";
    if ($limit) $sql .= " LIMIT $limit";

    return $this->execute($sql, $params);
  }

  /**
   * Update records in table
   * @param string $where
   * @param array $data
   * @param array $params
   * @return int
   */
  public function update(string $where, array $data, array $params = []): int
  {
    $fields = implode('=?,', array_keys($data)) . '=?';
    $sql = "UPDATE {$this->table} SET $fields WHERE $where";

    return $this->execute($sql, array_merge(array_values($data), $params))->rowCount();
  }

  /**
   * Delete records from table
   * @param string $where
   * @param array $params
   * @return int
   */
  public function delete(string $where, array $params = []): int
  {
    $sql = "DELETE FROM {$this->table} WHERE $where";
    return $this->execute($sql, $params)->rowCount();
  }
}
