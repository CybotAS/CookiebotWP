<?php
/**
 * PHP-DI
 *
 * @link      http://php-di.org/
 * @copyright Matthieu Napoli (http://mnapoli.fr/)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace Cybot\Dependencies\DI;

use Cybot\Dependencies\DI\Definition\Definition;
use Cybot\Dependencies\DI\Definition\Dumper\DefinitionDumperDispatcher;

/**
 * Debug utilities.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Debug
{
    /**
     * Dump the definition to a string.
     *
     * @param Definition $definition
     *
     * @return string
     */
    public static function dumpDefinition(Definition $definition)
    {
        static $dumper;

        if (! $dumper) {
            $dumper = new DefinitionDumperDispatcher();
        }

        return $dumper->dump($definition);
    }
}
