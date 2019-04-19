?php
declare(strict_types=1);
/**
 * Kooser Directory - Manage your database with complete ease.
 * 
 * @package Kooser\Directory.
 */

namespace Kooser\Directory\Exception;

use Exception;

/**
 * If the connection could not be opened.
 */
class ConnectionFailedException extends Exception implements ExceptionInterface
{
}
