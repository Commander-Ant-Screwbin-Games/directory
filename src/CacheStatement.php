<?php declare(strict_types=1);
/**
 * Directory - A PDO wrapper for secure connections.
 *
 * @license MIT License. (https://github.com/Commander-Ant-Screwbin-Games/directory/blob/master/license)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * https://github.com/Commander-Ant-Screwbin-Games/firecms/tree/master/src/Core
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package Commander-Ant-Screwbin-Games/directory.
 */

namespace Directory;

use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Caches a dbh statement.
 */
final class CacheStatement implements CacheStatementInterface
{

    /** @var \Symfony\Component\Cache\Adapter\AdapterInterface|null $cache The cache handler. */
    protected $cache;

    /** @var \Directory\SQLDatabaseHandler|null $dbh The database handler. */
    protected $dbh;

    /**
     * {@inheritdoc}
     */
    public function __construct(AdapterInterface $cacheHandler, SQLDatabaseHandler $dbh)
    {
        $this->cache = $cacheHandler;
        $this->dbh = $dbh;
    }

    /**
     * {@inheritdoc}
     */
    public function select(string $sql, array $array = [], int $fetchMode = \PDO::FETCH_ASSOC)
    {
        $encodeParams = \json_encode($array);
        /** @psalm-suppress PossiblyNullReference **/
        $statement = $this->cache->getItem(\str_replace(["{", "}", ":"], '', 'kooser-directory.' . $sql . '.' . $encodeParams . '.' . $fetchMode));
        if (!$statement->isHit()) {
            /** @psalm-suppress PossiblyNullReference **/
            $res = $this->dbh->select($sql, $array, $fetchMode);
            $statement->set($res);
            $this->cache->save($statement);
            return $res;
        }
        return $statement->get();
    }
}
