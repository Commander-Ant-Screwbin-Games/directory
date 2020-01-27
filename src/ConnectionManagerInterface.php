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
 * The connection manager interface.
 */
interface ConnectionManagerInterface
{

    /**
     * Construct a new database connection.
     *
     * @param array $options    The database connection options.
     * @param bool  $exceptions Should we utilize exceptions.
     *
     * @return void Returns nothing.
     */
    public function __construct(array $options = [], bool $exceptions = \true);

    /**
     * Set the database connection options.
     *
     * @param array $options The database connection options.
     *
     * @return \Directory\ConnectionManagerInterface Returns the connection manager.
     */
    public function setOptions(array $options = []): ConnectionManagerInterface;

    /**
     * Set the exceptions param.
     *
     * @param bool $exceptions Should we utilize exceptions.
     *
     * @return \Directory\ConnectionManagerInterface Returns the connection manager.
     */
    public function setExceptions(bool $exceptions = \true): ConnectionManagerInterface;
 
    /**
     * Establish a new database connection.
     *
     * @throws Exception\ConnectionFailedException If the connection manager could not open the connection.
     *
     * @return void Returns nothing.
     *
     * @codeCoverageIgnore
     */
    public function establishConnection(): void;

    /**
     * Return the database driver.
     *
     * @return string The database driver.
     */
    public function getDriver(): string;

    /**
     * Close the connection string.
     *
     * @return void Returns nothing.
     */
    public function closeConnectionString(): void;

    /**
     * Get the connection string.
     *
     * @return mixed Get the connection string.
     */
    public function getConnectionString();
}
