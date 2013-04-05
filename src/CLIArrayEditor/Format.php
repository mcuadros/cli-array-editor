<?php
/*
 * This file is part of the CLIArrayEditor package.
 *
 * (c) Máximo Cuadros <mcuadros@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CLIArrayEditor;

/**
* Format interface
*/
interface Format
{
    /**
     * Converts a string to array
     *
     * @param string $data 
     *
     * @return array
     */
    public function from($data);

    /**
     * Converts an array to a string
     *
     * @param array $data 
     *
     * @return string
     */
    public function to(array $data);
}