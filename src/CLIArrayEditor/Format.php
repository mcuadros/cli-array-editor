<?php
namespace CLIArrayEditor;

interface Format
{
    public function from($string);
    public function to(array $data);
}