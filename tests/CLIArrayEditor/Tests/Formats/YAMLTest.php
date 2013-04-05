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

use CLIArrayEditor\Format\YAML;


class YAMLTest extends \PHPUnit_Framework_TestCase
{
    public function testTo() 
    {
        if ( !function_exists('yaml_parse') ) {
            $this->markTestIncomplete('PECL yaml >= 0.5.0 extension not found');
        }

        $tmp = array(
            'baz' => true,
            'foo' => 'bar'
        );

        $expected = "---\nbaz: true\nfoo: bar\n...\n";
        $yaml = new YAML;

        $this->assertSame($expected, $yaml->to($tmp));
    }

    public function testFrom() 
    {
        if ( !function_exists('yaml_emit') ) {
            $this->markTestIncomplete('PECL yaml >= 0.5.0 extension not found');
        }

        $expected = array(
            'baz' => true,
            'foo' => 'bar'
        );

        $tmp = "---\nbaz: true\nfoo: bar\n...\n";
        $yaml = new YAML;

        $this->assertSame($expected, $yaml->from($tmp));
    }
}
