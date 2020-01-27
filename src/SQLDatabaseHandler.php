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
 * The sql database handler.
 */
final class SQLDatabaseHandler implements SQLDatabaseHandlerInterface
{

    /** @var mixed $connectionManager The connection manager. */
    protected $connectionManager = \null;

    /** @var mixed $connectionString The connection string. */
    protected $connectionString = \null;

    /**
     * {@inheritdoc}
     */
    public function __construct(ConnectionManagerInterface $connectionManager = \null)
    {
        if (!\is_null($connectionManager)) {
            $this->setConnectionManager($connectionManager);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setConnectionManager(ConnectionManagerInterface $connectionManager): SQLDatabaseHandlerInterface
    {
        $this->connectionManager = $connectionManager;
        $this->connectionManager->establishConnection();
        $this->connectionString = $this->connectionManager->getConnectionString();
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function select(string $sql, array $array = [], int $fetchMode = \PDO::FETCH_ASSOC): array
    {
        $sth = $this->connectionString->prepare($sql);
        foreach ($array as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->execute();
        return $sth->fetchAll($fetchMode);
    }

    /**
     * {@inheritdoc}
     */
    public function insert(string $table, array $data): void
    {
        \ksort($data);
        $fieldNames = \implode(', ', \array_keys($data));
        $fieldValues = ':' . \implode(', :', \array_keys($data));
        $sth = $this->connectionString->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");
        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function update(string $table, array $data, string $where, array $whereBindArray = array()): void
    {
        \ksort($data);
        $fieldDetails = "";
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key=:$key,";
        }
        $fieldDetails = \rtrim($fieldDetails, ',');
        $sth = $this->connectionString->prepare("UPDATE $table SET $fieldDetails WHERE $where");
        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        foreach ($whereBindArray as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->execute();
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function delete(string $table, string $where, array $bind = array(), int $limit = \null): void
    {
        $query = "DELETE FROM $table WHERE $where";
        if ($limit) {
            $query .= " LIMIT $limit";
        }
        $sth = $this->connectionString->prepare($query);
        foreach ($bind as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function __destruct()
    {
        $this->connectionManager->closeConnectionString();
        $this->connectionString = \null;
    }
}
