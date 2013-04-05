<?php
namespace CLIArrayEditor;

class Editor
{
    protected $format;
    protected $prefix;
    protected $path;
    protected $editor;
    protected $forceModify;

    public function __construct($prefix = 'cae')
    {
        $this->path = sys_get_temp_dir();
    }

    public function setFormat(Format $format)
    {
        $this->format = $format;
        return $this;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setEditor($cmd)
    {
        $this->editor = $cmd;
        return $this;
    }

    public function getEditor()
    {
        return $this->editor;
    }

    public function setForceModify($boolean)
    {
        $this->forceModify = (boolean)$boolean;
        return $this;
    }

    public function getForceModify($boolean)
    {
        return $this->forceModify;
    }

    public function edit(array $data, $retries = 3)
    {
        $result = false;
        while ( !$result && $retries > 0 ) {
            $retries--;

            $filename = $this->writeToFile($data);
            $this->launchEditor($filename);
            
            $result = $this->readFromFile($filename);
        }

        return $result;
    }

    protected function readFromFile($filename)
    {
        if ( !file_exists($filename) ) {
            throw new \Exception(sprintf('Unable to find the file "%s"', $filename));
        }

        $data = file_get_contents($filename);

        return $this->format->from($data);
    }

    protected function writeToFile(array $data)
    {
        $filename = sprintf('%s/%s_%s.%s', $this->path, $this->prefix, uniqid(), 'json');
        $string = $this->format->to($data);

        if ( file_put_contents($filename, $string) ) {
            return $filename;
        }

        throw new \Exception(sprintf('Unable to create the file "%s"', $filename));
    }

    protected function launchEditor($filename)
    {
        if ( $this->editor ) $bin = $this->editor;
        elseif ( isset($_SERVER['EDITOR']) ) $bin = $_SERVER['EDITOR'];
        else $bin = 'vim';

        $cmd = sprintf('%s %s', $bin, $filename);
        $this->runInTTY($cmd, sys_get_temp_dir());
    }

    protected function runInTTY($cmd, $path = null)
    {
        $descriptors = array(
            array('file', '/dev/tty', 'r'),
            array('file', '/dev/tty', 'w'),
            array('file', '/dev/tty', 'w')
        );

        $process = proc_open($cmd, $descriptors, $pipes, $path);
        if (is_resource($process)) {
         
            $return_value = proc_close($process);

            echo "command returned $return_value\n";
        }
    }
}