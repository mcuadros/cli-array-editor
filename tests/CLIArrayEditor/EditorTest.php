<?php
/*
 * This file is part of the CLIArrayEditor package.
 *
 * (c) Máximo Cuadros <mcuadros@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
require __DIR__ . '/../../vendor/autoload.php';
use CLIArrayEditor\Editor;
use CLIArrayEditor\Format\JSON;
use CLIArrayEditor\Format\YAML;


class EditorTest extends PHPUnit_Framework_TestCase
{
    public function testNewArrayIsEmpty()
    {
        $tmp = [
            'baz' => true,
            'foo' => 'bar'
        ];

        $editor = new Editor();
        $result = $editor
            ->setEditor('vim')
            ->setForceModify(true)
            ->setFormat(new YAML)
            ->edit($tmp);
            
        print_r($result);
    }
}
