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

/**
 * The sql database handler interface.
 */
interface SQLDatabaseHandlerInterface
{

    /**
     * SQLDatabase handler constructor.
     *
     * @param \Directory\ConnectionManagerInterface|null $connectionManager The connection manager.
     *
     * @return void Returns nothing.
     */
    public function __construct(ConnectionManagerInterface $connectionManager = \null);

    /**
     * Set the connection manager.
     *
     * @param \Directory\ConnectionManagerInterface $connectionManager The connection manager.
     *
     * @return \Directory\SQLDatabaseHandlerInterface Return this class.
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
