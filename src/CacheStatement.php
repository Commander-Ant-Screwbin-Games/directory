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
final class CacheStatement implements CacheStatementInterface
{

    /** @var \Symfony\Component\Cache\Adapter\AdapterInterface $cache The cache handler. */
    protected $cache;

    /** @var \Kooser\Framework\Directory $dbh The database handler. */
    protected $dbh;

    /**
     * Construct a new cache statement handler.
     *
     * @param \Symfony\Component\Cache\Adapter\AdapterInterface $cacheHandler The cache handler.
     * @param \Kooser\Framework\Directory                       $dbh          The database handler.
     *
     * @return void Returns nothing.
     */
    public function __construct(AdapterInterface $cacheHandler, SQLDatabaseHandler $dbh)
    {
        $this->cache = $cacheHandler;
        $this->dbh = $dbh;
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
    public function select(string $sql, array $array = array(), int $fetchMode = \PDO::FETCH_ASSOC)
    {
        $encodeParams = \json_encode($array);
        $statement = $this->cache->getItem("kooser-directory.{$sql}.{$encodeParams}.{$fetchMode}");
        if (!$statement->isHit()) {
            $res = $this->dbh->select($sql, $array, $fetchMode);
            $statement->set(\json_encode($res));
            $this->cache->save($statement);
            return $res;
        }
        $encodedRes = $productsCount->get();
        return \json_decode($encodedRes);
    }
}
