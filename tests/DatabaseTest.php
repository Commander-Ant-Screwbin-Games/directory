<?php
declare(strict_types=1);
/**
 * Kooser Directory - A PDO wrapper for secure connections.
 * 
 * @package Kooser\Directory.
 */

namespace Directory\Test;

use Kooser\Directory\ConnectionManager;
use Kooser\Directory\Exception\ConnectionClosedException;
use Kooser\Directory\Exception\ConnectionFailedException;

use PHPUnit\Framework\TestCase;

/**
 * Test connecion manager functionality.
 */
class DatabaseTest extends TestCase
{

    /**
     * @return void Returns nothing.
     */
    public function testConstructor(): void
    {
        $dbh = new ConnectionManager([
            'database_dsn'  => 'mysql:host=localhost;dbname=test',
            'database_user' => 'travis'
        ]);
        $this->assertTrue(\true);
    }
}
