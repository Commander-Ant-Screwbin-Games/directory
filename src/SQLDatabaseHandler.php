<?php
declare(strict_types=1);
/**
 * Kooser Directory - A PDO wrapper for secure connections.
 * 
 * @package Kooser\Directory.
 */

namespace Kooser\Directory;

/**
 * The sql database handler.
 */
class SQLDatabaseHandler implements SQLDatabaseHandlerInterface
{

    /** @var mixed $connectionManager The connection manager. */
    protected $connectionManager = \null;

    /**
     * SQLDatabase handler constructor.
     *
     * @param \Kooser\Directory\ConnectionManagerInterface $connectionManager The connection manager.
     *
     * @return void Returns nothing.
     */
    public function __construct(ConnectionManagerInterface $connectionManager)
    {
        $this->setConnectionManager($connectionManager);
        $this->connectionManager->establishConnection();
    }

    /**
     * Set the connection manager.
     *
     * @param \Kooser\Directory\ConnectionManagerInterface $connectionManager The connection manager.
     *
     * @return \Kooser\Directory\SQLDatabaseHandlerInterface Return this class.
     */
    public function setConnectionManager(ConnectionManagerInterface $connectionManager): SQLDatabaseHandlerInterface
    {
        $this->connectionManager = $connectionManager;
        return $this;
    }

    /**
     * Select data.
     *
     * @param string $sql       An SQL string.
     * @param array  $array     Parameters to bind.
     * @param int    $fetchMode A PDO Fetch mode.
     *
     * @return array The selected data.
     */
    public function select(string $sql, array $array = array(), int $fetchMode = \PDO::FETCH_ASSOC): array
    {
        $sth = $this->connectionManager->connectionString->prepare($sql);
        foreach ($array as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->execute();
        return $sth->fetchAll($fetchMode);
    }

    /**
     * Insert data to database.
     *
     * @param string $table A name of table to insert into.
     * @param string $data  An associative array.
     *
     * @return void Returns nothing.
     */
    public function insert(string $table, array $data): void
    {
        \ksort($data);
        $fieldNames = \implode('`, `', \array_keys($data));
        $fieldValues = ':' . \implode(', :', \array_keys($data));
        $sth = $this->connectionManager->connectionString->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");
        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->execute();
    }

    /**
     * Update data.
     *
     * @param string $table          A name of table to insert into.
     * @param array  $data           An associative array where keys have the same name as database columns.
     * @param string $where          The WHERE query part.
     * @param array  $whereBindArray Parameters to bind to where part of query.
     *
     * @return void Returns nothing.
     */
    public function update(string $table, array $data, string $where, array $whereBindArray = array()): void
    {
        \ksort($data);
        $fieldDetails = \null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "`$key`=:$key,";
        }
        $fieldDetails = \rtrim($fieldDetails, ',');
        $sth = $this->connectionManager->connectionString->prepare("UPDATE $table SET $fieldDetails WHERE $where");
        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        foreach ($whereBindArray as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->execute();
    }

    /**
     * Delete data.
     *
     * @param string   $table The table.
     * @param string   $where The where statement.
     * @param array    $bind  The data to bind.
     * @param int|null $limit How much data.
     *
     * @return void Returns nothing.
     */
    public function delete(string $table, string $where, array $bind = array(), $limit = \null): void
    {
        $query = "DELETE FROM $table WHERE $where";
        if ($limit) {
            $query .= " LIMIT $limit";
        }
        $sth = $this->connectionManager->connectionString->prepare($query);
        foreach ($bind as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->execute();
    }

    /**
     * Close the connection after use.
     *
     * @return void Returns nothing.
     */
    public function __destruct()
    {
        $this->connectionManager->closeConnectionString();
    }
}
