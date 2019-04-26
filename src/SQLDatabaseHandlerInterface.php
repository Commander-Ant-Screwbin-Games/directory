<?php
declare(strict_types=1);
/**
 * Kooser Directory - A PDO wrapper for secure connections.
 * 
 * @package Kooser\Directory.
 */
 
namespace Kooser\Directory;

/**
 * The sql database handler interface.
 */
interface SQLDatabaseHandlerInterface
{

    /**
     * SQLDatabase handler constructor.
     *
     * @param \Kooser\Directory\ConnectionManagerInterface|null $connectionManager The connection manager.
     *
     * @return void Returns nothing.
     */
    public function __construct(ConnectionManagerInterface $connectionManager = \null);

    /**
     * Set the connection manager.
     *
     * @param \Kooser\Directory\ConnectionManagerInterface $connectionManager The connection manager.
     *
     * @return \Kooser\Directory\SQLDatabaseHandlerInterface Return this class.
     */
    public function setConnectionManager(ConnectionManagerInterface $connectionManager): SQLDatabaseHandlerInterface;

    /**
     * Select data.
     *
     * @param string $sql       An SQL string.
     * @param array  $array     Parameters to bind.
     * @param int    $fetchMode A PDO Fetch mode.
     *
     * @return array The selected data.
     */
    public function select(string $sql, array $array = array(), int $fetchMode = \PDO::FETCH_ASSOC): array;

    /**
     * Insert data to database.
     *
     * @param string $table A name of table to insert into.
     * @param array  $data  An associative array.
     *
     * @return void Returns nothing.
     */
    public function insert(string $table, array $data): void;

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
    public function update(string $table, array $data, string $where, array $whereBindArray = array()): void;

    /**
     * Delete data.
     *
     * @param string   $table The table.
     * @param string   $where The where statement.
     * @param array    $bind  The data to bind.
     * @param int|null $limit How much data.
     *
     * @return void Returns nothing.
     *
     * @codeCoverageIgnore
     */
    public function delete(string $table, string $where, array $bind = array(), int $limit = \null): void;

    /**
     * Close the connection after use.
     *
     * @return void Returns nothing.
     */
    public function __destruct();
}
