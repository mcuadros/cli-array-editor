<?php
/*
 * This file is part of the CLIArrayEditor package.
 *
 * (c) Máximo Cuadros <mcuadros@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CLIArrayEditor\Format;
use CLIArrayEditor\Format;

/**
* YAML format class
*/
class YAML implements Format
{
     /**
      * Class constructor
      *
      * @throws Exception if PECL yaml >= 0.5.0 not is pressent.
      */
    public function __construct()
    {
        if ( !function_exists('yaml_parse') ) {
            throw new \Exception('PECL yaml >= 0.5.0 extension not found');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function to(array $data)
    {
        return yaml_emit($data);
    }

    /**
     * {@inheritdoc}
     */
    public function from($data)
    {
        return yaml_parse($data);
    }
}