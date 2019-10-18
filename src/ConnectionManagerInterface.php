<?php
declare(strict_types=1);
/**
 * Kooser Directory - A PDO wrapper for secure connections.
 * 
 * @package Kooser\Framework\Directory.
 */
 
namespace Kooser\Framework\Directory;

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
     * @return \Kooser\Framework\Directory\ConnectionManagerInterface Returns the connection manager.
     */
    public function setOptions(array $options = []): ConnectionManagerInterface;

    /**
     * Set the exceptions param.
     *
     * @param bool $exceptions Should we utilize exceptions.
     *
     * @return \Kooser\Framework\Directory\ConnectionManagerInterface Returns the connection manager.
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
