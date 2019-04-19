<?php
declare(strict_types=1);
/**
 * Kooser PasswordLock - Secure your password using password lock.
 * 
 * @package Kooser\PasswordLock.
 */

namespace Kooser\Directory;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Create a new database connection.
 */
class ConnectionManager implements ConnectionManagerInterface
{

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
        $this->exceptions = $exceptions;
        $this->setOptions($options);
    }

    /**
     * Set the database connection options.
     *
     * @param array $options The database connection options.
     *
     * @return void Returns nothing.
     */
    public function setOptions(array $options = []): void
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
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
        try {
            $this->connectionString = new \PDO($this->options['database_dsn'],
                                               $this->options['database_user'],
                                               $this->options['database_pass'],
                                               $this->options['database_options']);
        } catch (\PDOException $e) {
            throw new Exception\ConnectionFailedException('The connection manager could not open the connection.');
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
            'database_pass' => '',
            'database_options' => [],
        ]);
        $resolver->setRequired('database_dns');
        $resolver->setRequired('database_name');
        $resolver->setRequired('database_user');
        $resolver->setAllowedTypes('database_dns', 'string');
        $resolver->setAllowedTypes('database_name', 'string');
        $resolver->setAllowedTypes('database_user', 'string');
        $resolver->setAllowedTypes('database_pass', 'string');
        $resolver->setAllowedTypes('database_options', 'array');
        $resolver->setAllowedTypes('database_port', 'int');
    }
}
