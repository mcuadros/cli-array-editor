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

use CLIArrayEditor\Editor;
use CLIArrayEditor\Format\JSON;
use CLIArrayEditor\Format\YAML;


class EditorTest extends \PHPUnit_Framework_TestCase
{

    public function testSetFormatAndGetFormat()
    {
        $json = new JSON();
        $editor = new Editor();
        
        $this->assertSame($editor, $editor->setFormat($json));
        $this->assertSame($json, $editor->getFormat());
    }

    public function testSetEditorAndGetEditor()
    {
        $cmd = '/usr/bin/vim';
        $editor = new Editor();
        
        $this->assertSame($editor, $editor->setEditor($cmd));
        $this->assertSame($cmd, $editor->getEditor());
    }

    public function testSetForceModifyAndGetForceModify()
    {
        $force = true;
        $editor = new Editor();
        
        $this->assertSame($editor, $editor->setForceModify($force));
        $this->assertSame($force, $editor->getForceModify());
    }

    public function testSetTemporalPathAndGetTemporalPath()
    {
        $path = '/tmp/';
        $editor = new Editor();

        $this->assertSame(sys_get_temp_dir(), $editor->getTemporalPath()); 
        $this->assertSame($editor, $editor->setTemporalPath($path));
        $this->assertSame($path, $editor->getTemporalPath()); 
    }

    public function testSetRetriesAndGetRetries()
    {
        $retries = 10;
        $editor = new Editor();

        $this->assertSame(3, $editor->getRetries()); 
        $this->assertSame($editor, $editor->setRetries($retries));
        $this->assertSame($retries, $editor->getRetries()); 
    }

    public function testSetFormat()
    {
        $tmp = array(
            'baz' => true,
            'foo' => 'bar'
        );

        $editor = new MockEditor;
        $result = $editor->setFormat(new JSON)->edit($tmp);

        $data = file_get_contents($editor->filename);

        $this->assertTrue((boolean)json_decode($data));
    }

    public function testSetEditor()
    {
        $tmp = array(
            'baz' => true,
            'foo' => 'bar'
        );

        $editor = new MockEditor;
        $result = $editor->setFormat(new JSON)->setEditor('foo')->edit($tmp);

        $this->assertSame('foo ' . $editor->filename, $editor->cmd);
    }

    public function testSetForceModify()
    {
        $tmp = array(
            'baz' => true,
            'foo' => 'bar'
        );

        $editor = new MockEditor;
        $result = $editor->setFormat(new JSON)->setForceModify(true)->edit($tmp);
        $this->assertFalse((boolean)$result);

        $result = $editor->setFormat(new JSON)->setForceModify(false)->edit($tmp);
        $this->assertTrue((boolean)$result);
    }

    public function testSetTemporalPath()
    {
        $tmp = array(
            'baz' => true,
            'foo' => 'bar'
        );

        $path = sys_get_temp_dir() . uniqid();
        mkdir($path);

        $editor = new MockEditor;
        $result = $editor->setFormat(new JSON)->setTemporalPath($path)->edit($tmp);

        $this->assertSame(dirname($editor->filename), $path);
    }

    public function testSetRetries()
    {
        $tmp = array(
            'baz' => true,
            'foo' => 'bar'
        );

        $editor = new MockEditor;
        $result = $editor->setFormat(new JSON)->setForceModify(true)->setRetries(10)->edit($tmp);

        $this->assertSame(10, $editor->loops);
    }

    public function testEdit()
    {
        $tmp = [
            'baz' => true,
            'foo' => 'bar'
        ];

        $editor = new Editor();
        $result = $editor
            ->setEditor('true ')
            ->setFormat(new YAML)
            ->edit($tmp);
    }
}

class MockEditor extends Editor
{
    public $filename;
    public $cmd;
    public $loops = 0;

    protected function writeToFile(array $data)
    {
        $this->filename = parent::writeToFile($data); 

        return $this->filename;
    }

    protected function runInTTY($cmd, $path = null)
    {
        $this->loops++;
        $this->cmd = $cmd;
        return true;
    }
}