<?php
declare(strict_types=1);
/**
 * Kooser Directory - A PDO wrapper for secure connections.
 * 
 * @package Kooser\Framework\Directory.
 */

namespace Kooser\Framework\Directory\Exception;

use Exception;

/**
 * If the connection could not be opened.
 */
class ConnectionFailedException extends Exception implements ExceptionInterface
{
}
