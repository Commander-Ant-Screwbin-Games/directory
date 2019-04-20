<?php
declare(strict_types=1);
/**
 * Kooser Directory - Manage your database with complete ease.
 * 
 * @package Kooser\Directory.
 */

namespace Kooser\Directory;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Create a new database connection.
 */
class ConnectionManager implements ConnectionManagerInterface
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
     * @return \Kooser\Directory\ConnectionManagerInterface Returns the connection manager.
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
     * @return \Kooser\Directory\ConnectionManagerInterface Returns the connection manager.
     */
    public function setExceptions(bool $exceptions = \true): ConnectionManagerInterface
    {
        $this->exceptions = $exceptions;
        return $this;
    }

    /**
     * Establish a new database connection.
     *
     * @throws ConnectionFailedException If the connection manager could not open the connection.
     *
     * @return void Returns nothing.
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
     * Get the connection string.
     *
     * @throws Exception\ConnectionClosedException If the connection manager has a closed connection.
     *
     * @return mixed The connection string.
     */
    public function getConnectionString()
    {
        if (\is_null($this->connectionString)) {
            if ($this->exceptions) {
                throw new Exception\ConnectionClosedException('The connection manager has a closed connection.');
            } else {
                \trigger_error('The connection manager has a closed connection.', \E_USER_ERROR);
            }
        }
        return $this->connectionString;
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
     * Configure the hasher options.
     *
     * @param OptionsResolver The symfony options resolver.
     *
     * @return void Returns nothing.
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'database_port' => 3306,
            'database_user' => '',
            'database_pass' => '',
            'database_options' => [],
        ]);
        $resolver->setRequired('database_dsn');
        $resolver->setRequired('database_user');
        $resolver->setAllowedTypes('database_dsn', 'string');
        $resolver->setAllowedTypes('database_user', 'string');
        $resolver->setAllowedTypes('database_pass', 'string');
        $resolver->setAllowedTypes('database_options', 'array');
        $resolver->setAllowedTypes('database_port', 'int');
    }
}
