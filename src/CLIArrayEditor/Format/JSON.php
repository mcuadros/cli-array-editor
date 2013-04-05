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
class JSON implements Format
{
    /**
     * {@inheritdoc}
     */
    public function to(array $data)
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }

    /**
     * {@inheritdoc}
     */
    public function from($data)
    {
        return json_decode($data, true);
    }
}