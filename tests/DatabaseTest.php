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
use PHPUnit\Framework\Error\Error;

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
            'database_dsn'  => 'mysql:host=localhost;dbname=travis_ci_test',
            'database_user' => 'travis'
        ]);
        $this->assertTrue(\true);
    }

    /**
     * @return void Returns nothing.
     */
    public function testSuccessfulConnection(): void
    {
        $dbh = new ConnectionManager([
            'database_dsn'  => 'mysql:host=localhost;dbname=travis_ci_test',
            'database_user' => 'travis'
        ]);
        $this->assertTrue(\true);
        $dbh->establishConnection();
        $this->assertTrue(\true);
    }

    /**
     * @return void Returns nothing.
     */
    public function testCloseConnectionAndGetDriver(): void
    {
        $dbh = new ConnectionManager([
            'database_dsn'  => 'mysql:host=localhost;dbname=travis_ci_test',
            'database_user' => 'travis'
        ]);
        $this->assertTrue(\true);
        $dbh->establishConnection();
        $this->assertTrue(\true);
        $this->assertTrue(($dbh->getDriver() == 'mysql'));
        $dbh->closeConnectionString();
    }

    /**
     * @return void Returns nothing.
     */
    public function testBadConnectionDetails(): void
    {
        $this->expectException(ConnectionFailedException::class);
        $dbh = new ConnectionManager([
            'database_dsn'  => 'mysql:host=localhost;dbname=travis_ci_test',
            'database_user' => 'kooser'
        ]);
        $dbh->establishConnection();
    }

    /**
     * @return void Returns nothing.
     */
    public function testConnectionStringWithNoOpenConnection(): void
    {
        $this->expectException(ConnectionClosedException::class);
        $dbh = new ConnectionManager([
            'database_dsn'  => 'mysql:host=localhost;dbname=travis_ci_test',
            'database_user' => 'travis'
        ]);
        $conn = $dbh->getConnectionString();
    }

    /**
     * @return void Returns nothing.
     */
    public function testConnectionStringWithNoOpenConnectionWithTrigger(): void
    {
        $this->expectException(Error::class);
        $dbh = new ConnectionManager([
            'database_dsn'  => 'mysql:host=localhost;dbname=travis_ci_test',
            'database_user' => 'travis'
        ]);
        $conn = $dbh->getConnectionString();
    }

    /**
     * @return void Returns nothing.
     */
    public function testConnectionStringWithNoOpenConnectionWithTrigger(): void
    {
        $dbh = new ConnectionManager([
            'database_dsn'  => 'mysql:host=localhost;dbname=travis_ci_test',
            'database_user' => 'travis'
        ]);
        $this->assertTrue(\true);
        $dbh->establishConnection();
        $this->assertTrue(\true);
        $conn = $dbh->getConnectionString();
    }
}
