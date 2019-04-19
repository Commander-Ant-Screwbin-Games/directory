<?php
declare(strict_types=1);
/**
 * Kooser Directory - Manage your database with complete ease.
 * 
 * @package Kooser\Directory.
 */

namespace Kooser\Directory\Exception;

use Exception;

/**
 * If the connection is closed.
 */
class ConnectionClosedException extends Exception implements ExceptionInterface
{
}
