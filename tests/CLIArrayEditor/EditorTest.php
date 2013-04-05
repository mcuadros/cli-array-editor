<?php
require __DIR__ . '/../../vendor/autoload.php';
use CLIArrayEditor\Editor;
use CLIArrayEditor\Format\JSON;


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
            ->setFormat(new JSON)
            ->edit($tmp);
            
        print_r($result);
    }
}
