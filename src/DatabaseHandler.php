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
 * Create a new database handler.
 */
class DatabaseHandler implements ConnectionManagerInterface
{

    /** @var \Kooser\Directory\ConnectionManager $connectionManager The connection manager. */
    protected $connectionString = \null;

    /**
     * Construct a new database handler.
     *
     * @param \Kooser\Directory\ConnectionManager $connectionManager The connection manager.
     *
     * @return void Returns nothing.
     */
    public function __construct(ConnectionManager $connectionManager)
    {
        $this->connectionManager = $connectionManager;
        $this->connectionManager->establishConnection();
    }

    public function createDatabase(string $databaseName): void
    {
    }
}
