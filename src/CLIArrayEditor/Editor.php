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
* Main editor class
*/
class Editor
{
    protected $format;
    protected $prefix;
    protected $path;
    protected $editor;
    protected $comments;
    protected $forceModify;
    protected $retries = 3;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->path = sys_get_temp_dir();
    }

    /**
     * Sets the format instance
     *
     * @param Format $format a Format instance
     *
     * @return self The current Editor instance
     */
    public function setFormat(Format $format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * Gets the format object
     *
     * @return Format 
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Sets the command used to launch the editor if not is defined $EDITOR from env,
     * will be used, if not present "vim" will be used.
     *
     * @param string $cmd shell command eg.: "/urs/bin/vim"
     *
     * @return self The current Editor instance
     */
    public function setEditor($cmd)
    {
        $this->editor = $cmd;
        return $this;
    }

    /**
     * Gets the editor command
     *
     * @return string 
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * Sets if a not modified element is marked as a fail.
     *
     * @param boolean $boolean
     *
     * @return self The current Editor instance
     */
    public function setForceModify($boolean)
    {
        $this->forceModify = (boolean)$boolean;
        return $this;
    }

    /**
     * Gets force modify option
     *
     * @return boolean 
     */
    public function getForceModify()
    {
        return $this->forceModify;
    }

    /**
     * Sets the path where the temporal files ar stored
     *
     * @param string $path
     *
     * @return self The current Editor instance
     */
    public function setTemporalPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Gets the temporal directory
     *
     * @return string 
     */
    public function getTemporalPath()
    {
        return $this->path;
    }
    
    /**
     * Gets maximum retries
     *
     * @return integer 
     */
    public function getRetries()
    {
        return $this->retries;
    }

    /**
     * Sets maximun retries when editing, if malformed content or not unmodified
     *
     * @param integer $retries
     *
     * @return self The current Editor instance
     */
    public function setRetries($retries)
    {
        $this->retries = $retries;
        return $this;
    }

    /**
     * Gets comments to be attached to the file
     *
     * @return integer 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Sets comments to be attached to the file if the format selected supports it.
     *
     * @param string $comments
     *
     * @return self The current Editor instance
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * Launch the editor and return the modified result, false if failed.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function edit(array $data)
    {
        $result = false;
        $retries = $this->retries;

        while ( !$result && $retries > 0 ) {
            $retries--;

            $filename = $this->writeToFile($data);
            $this->launchEditor($filename);
            
            $result = $this->readFromFile($filename);

            if ( $this->forceModify && $result == $data ) $result = false; 
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
        $string = $this->format->to($data, $this->comments);

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
        if ( is_resource($process) ) {
            return proc_close($process);
        } else {
            return false;
        }
    }
}