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

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Create a new database connection.
 */
final class ConnectionManager implements ConnectionManagerInterface
{

    /** @var string $driver The database driver. */
    private $driver = '';

    /** @var array $options The database connection options. */
    private $options = [];

    /** @var bool $exceptions Should we utilize exceptions. */
    protected $exceptions = \true;

    /** @var mixed $connectionString The pdo connection string. */
    protected $connectionString = \null;

    /**
     * Construct a new database connection.
     *
     * @param array $options    The database connection options.
     * @param bool  $exceptions Should we utilize exceptions.
     *
     * @return void Returns nothing.
     */
    public function __construct(array $options = [], bool $exceptions = \true)
    {
        $this->setExceptions($exceptions);
        $this->setOptions($options);
    }

    /**
     * Set the database connection options.
     *
     * @param array $options The database connection options.
     *
     * @return \Directory\ConnectionManagerInterface Returns the connection manager.
     */
    public function setOptions(array $options = []): ConnectionManagerInterface
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
        return $this;
    }

    /**
     * Set the exceptions param.
     *
     * @param bool $exceptions Should we utilize exceptions.
     *
     * @return \Directory\ConnectionManagerInterface Returns the connection manager.
     */
    public function setExceptions(bool $exceptions = \true): ConnectionManagerInterface
    {
        $this->exceptions = $exceptions;
        return $this;
    }

    /**
     * Establish a new database connection.
     *
     * @throws Exception\ConnectionFailedException If the connection manager could not open the connection.
     *
     * @return void Returns nothing.
     *
     * @codeCoverageIgnore
     */
    public function establishConnection(): void
    {
        $dsn = $this->options['database_dsn'];
        if (\strpos($dsn, ':') !== \false) {
            $this->driver = \explode(':', $dsn)[0];
        }
        /** @var string $postQuery */
        $postQuery = '';
        switch ($this->driver) {
            case 'mysql':
                if (\strpos($dsn, ';charset=') === \false) {
                    $dsn .= ';charset=utf8mb4';
                }
                break;
            case 'pgsql':
                $postQuery = "SET NAMES 'UNICODE'";
                break;
        }
        try {
            $this->connectionString = new \PDO($this->options['database_dsn'],
                                               $this->options['database_user'],
                                               $this->options['database_pass'],
                                               $this->options['database_options']);
        } catch (\PDOException $e) {
            throw new Exception\ConnectionFailedException('The connection manager could not open the connection.');
        }
        if (!empty($postQuery)) {
            $this->connectionString->query($postQuery);
        }
    }

    /**
     * Return the database driver.
     *
     * @return string The database driver.
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Close the connection string.
     *
     * @return void Returns nothing.
     */
    public function closeConnectionString(): void
    {
        $this->connectionString = \null;
    }

    /**
     * Get the connection string.
     *
     * @return mixed Get the connection string.
     */
    public function getConnectionString()
    {
        return $this->connectionString;
    }

    /**
     * Configure the hasher options.
     *
     * @param OptionsResolver The symfony options resolver.
     *
     * @return void Returns nothing.
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'database_user' => '',
            'database_pass' => '',
            'database_options' => [],
        ]);
        $resolver->setRequired('database_dsn');
        $resolver->setAllowedTypes('database_dsn', 'string');
        $resolver->setAllowedTypes('database_user', 'string');
        $resolver->setAllowedTypes('database_pass', 'string');
        $resolver->setAllowedTypes('database_options', 'array');
    }
}
