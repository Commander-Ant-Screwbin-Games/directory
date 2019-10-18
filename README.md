[![Travis (.org) branch](https://img.shields.io/travis/kooser-framework/directory/master.svg)](https://travis-ci.org/kooser-framework/directory)
[![Coveralls github branch](https://img.shields.io/coveralls/github/kooser-framework/directory/master.svg)](https://coveralls.io/github/kooser-framework/directory?branch=master)

# Directory

A PDO wrapper for secure connections.

## Installation

via Composer:

The best way to install this php component is through composer. If you do not have composer installed you can install it directly from the [composer website](https://getcomposer.org/). After composer is successfully installed run the command line code below.

```sh
composer require kooser/directory
```

## Usage

### Basic usage

Using the directory is very simple. Here is an example below.

```php
<?php

use Kooser\Directory\ConnectionManager;
use Kooser\Directory\SQLDatabaseHandler;

// Require the composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Setup our connection.
$dbh = new ConnectionManager([
    'database_dsn'  => 'mysql:host=localhost;dbname=travis_ci_test',
    'database_user' => 'travis'
]);

// Establish the connection.
$dbh->establishConnection();

// You can get the connection string for complicated use.
$conn = $dbh->getConnectionString();

// Run a query.
$conn->query('CREATE TABLE Persons (
    PersonID int,
    LastName varchar(255),
    FirstName varchar(255),
    Address varchar(255),
    City varchar(255) 
);');

// After use close the connection string as well as the connection manager.
// If you do not close the connection manager it will close automatically after use.
$conn = \null;
$dbh->closeConnectionString();

// Setup a new SQL handler.
// You do not need to run establish connection because the SQL handler establishes it for you.
$database = new SQLDatabaseHandler($dbh);

// Insert data.
$database->insert('Persons', [
    'PersonID'  => 1,
    'LastName'  => 'English',
    'FirstName' => 'Nicholas',
    'Address'   => '%somedata%',
    'City'      => '%somedata%'
]);

// Get the record.
$result = $database->select(
    "SELECT * FROM Persons WHERE LastName = :ln",
    ['ln' => 'English']
);
var_dump($result);

// Update the record.
$database->update(
    'Persons',
    ['LastName' => 'Kooser'],
    "LastName = :ln",
    ["ln" => 'English']
);

// Get the record again.
$result = $database->select(
    "SELECT * FROM Persons WHERE LastName = :ln",
    ['ln' => 'English']
);
var_dump($result);

// Delete the record.
$database->delete(
    'Persons',
    "LastName = :ln",
    ["ln" => 'Kooser']
);

// Try to get the record.
$result = $database->select(
    "SELECT * FROM Persons WHERE LastName = :ln",
    ['ln' => 'Kooser']
);
var_dump($result);

```

We supprt other database connections like `Informix`, `Oracle`, `PgSQL`, and `SQLite`.

## Contributing

All contributions are welcome! If you wish to contribute.

## License

This project is licensed under the terms of the [MIT License](https://opensource.org/licenses/MIT).
