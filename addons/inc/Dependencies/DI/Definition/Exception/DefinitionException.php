<?php
/**
 * PHP-DI
 *
 * @link      http://php-di.org/
 * @copyright Matthieu Napoli (http://mnapoli.fr/)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace Cybot\Dependencies\DI\Definition\Exception;

use Cybot\Dependencies\DI\Debug;
use Cybot\Dependencies\DI\Definition\Definition;

/**
 * Invalid DI definitions
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class DefinitionException extends \Exception
{
    public static function create(Definition $definition, $message)
    {
        return new self(sprintf(
            "%s\nFull definition:\n%s",
            $message,
            Debug::dumpDefinition($definition)
        ));
    }
}
