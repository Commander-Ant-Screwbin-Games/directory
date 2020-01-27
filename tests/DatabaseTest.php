<?php
declare(strict_types=1);

namespace Directory\Test;

use Directory\CacheStatement;
use Directory\ConnectionManager;
use Directory\Exception\ConnectionFailedException;
use Directory\SQLDatabaseHandler;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

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
            'database_dsn'  => 'mysql:host=localhost;dbname=travis_ci_test',
            'database_user' => 'travis'
        ]);
        $this->assertTrue(\true);
    }

    public function testCacheStatement()
    {
        $dbh = new ConnectionManager([
            'database_dsn'  => 'mysql:host=localhost;dbname=travis_ci_test',
            'database_user' => 'travis'
        ]);
        $this->assertTrue(\true);
        $dbh->establishConnection();
        $conn = $dbh->getConnectionString();
        $conn->query('CREATE TABLE Persons (
            PersonID int,
            LastName varchar(255),
            FirstName varchar(255),
            Address varchar(255),
            City varchar(255) 
        );');
        $conn = \null;
        $dbh->closeConnectionString();
        $this->assertTrue(\true);
        $database = new SQLDatabaseHandler($dbh);
        $this->assertTrue(\true);
        $database->insert('Persons', [
            'PersonID'  => 1,
            'LastName'  => 'English',
            'FirstName' => 'Nicholas',
            'Address'   => '%somedata%',
            'City'      => '%somedata%'
        ]);
        $this->assertTrue(\true);
        $adapter = new FilesystemAdapter('directory', 0, __DIR__ . '/../cache');
        $this->cacheStatement = new CacheStatement($adapter, $database);
        $resultA = $this->cacheStatement->select('SELECT * FROM `Persons` WHERE `LastName` = :ln', ['ln' => 'English']);
        $resultB = $this->cacheStatement->select('SELECT * FROM `Persons` WHERE `LastName` = :ln', ['ln' => 'English']);
        $this->assertTrue($resultA === $resultB);
        $database->delete(
            'Persons',
            "`LastName` = :ln",
            array("ln" => 'English')
        );
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
    public function testSqlHandlerConstructorAndDestructor(): void
    {
        $dbh = new ConnectionManager([
            'database_dsn'  => 'mysql:host=localhost;dbname=travis_ci_test',
            'database_user' => 'travis'
        ]);
        $this->assertTrue(\true);
        $database = new SQLDatabaseHandler($dbh);
        $this->assertTrue(\true);
    }

    /**
     * @return void Returns nothing.
     */
    public function testSqlHandlerMethods(): void
    {
        $dbh = new ConnectionManager([
            'database_dsn'  => 'mysql:host=localhost;dbname=travis_ci_test',
            'database_user' => 'travis'
        ]);
        $dbh->establishConnection();
        $conn = $dbh->getConnectionString();
        $conn->query('CREATE TABLE Persons (
            PersonID int,
            LastName varchar(255),
            FirstName varchar(255),
            Address varchar(255),
            City varchar(255) 
        );');
        $conn = \null;
        $dbh->closeConnectionString();
        $this->assertTrue(\true);
        $database = new SQLDatabaseHandler($dbh);
        $this->assertTrue(\true);
        $database->insert('Persons', [
            'PersonID'  => 1,
            'LastName'  => 'English',
            'FirstName' => 'Nicholas',
            'Address'   => '%somedata%',
            'City'      => '%somedata%'
        ]);
        $result = $database->select(
            "SELECT * FROM `Persons` WHERE `LastName` = :ln",
            array('ln' => 'English')
        );
        $this->assertTrue((\count($result) > 0));
        $database->update(
            'Persons',
            array('LastName' => 'Kooser'),
            "`LastName` = :ln",
            array("ln" => 'English')
        );
        $result = $database->select(
            "SELECT * FROM `Persons` WHERE `LastName` = :ln",
            array('ln' => 'English')
        );
        $this->assertTrue(!(\count($result) > 0));
        $database->delete(
            'Persons',
            "`LastName` = :ln",
            array("ln" => 'Kooser')
        );
        $result = $database->select(
            "SELECT * FROM `Persons` WHERE `LastName` = :ln",
            array('ln' => 'Kooser')
        );
        $this->assertTrue(!(\count($result) > 0));
    }
}
