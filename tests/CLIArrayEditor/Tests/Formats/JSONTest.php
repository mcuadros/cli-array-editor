<?php
/*
 * This file is part of the CLIArrayEditor package.
 *
 * (c) Máximo Cuadros <mcuadros@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CLIArrayEditor\Tests;

use CLIArrayEditor\Format\JSON;


class JSONTest extends \PHPUnit_Framework_TestCase
{
    public function testTo() 
    {
        $tmp = array(
            'baz' => true,
            'foo' => 'bar'
        );

        $expected = "{\n    \"baz\": true,\n    \"foo\": \"bar\"\n}";
        $json = new JSON;

        $this->assertSame($expected, $json->to($tmp));
    }

    public function testFrom() 
    {
        $expected = array(
            'baz' => true,
            'foo' => 'bar'
        );

        $tmp = "{\n    \"baz\": true,\n    \"foo\": \"bar\"\n}";
        $json = new JSON;

        $this->assertSame($expected, $json->from($tmp));
    }
}
