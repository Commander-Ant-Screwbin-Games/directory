<?php
declare(strict_types=1);
/**
 * Kooser Directory - A PDO wrapper for secure connections.
 * 
 * @package Kooser\Framework\Directory.
 */

namespace Kooser\Framework\Directory;

use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Create a new database connection.
 */
interface CacheStatementInterface
{

    /**
     * Construct a new cache statement handler.
     *
     * @param \Symfony\Component\Cache\Adapter\AdapterInterface $cacheHandler The cache handler.
     * @param \Kooser\Framework\Directory\SQLDatabaseHandler    $dbh          The database handler.
     *
     * @return void Returns nothing.
     */
    public function __construct(AdapterInterface $cacheHandler, SQLDatabaseHandler $dbh);

    /**
     * Select data.
     *
     * @param string $sql       An SQL string.
     * @param array  $array     Parameters to bind.
     * @param int    $fetchMode A PDO Fetch mode.
     *
     * @return array The selected data.
     */
    public function select(string $sql, array $array = array(), int $fetchMode = \PDO::FETCH_ASSOC);
}
