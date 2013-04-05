<?php
namespace CLIArrayEditor\Format;
use CLIArrayEditor\Format;

class JSON implements Format
{
    public function to(array $data)
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }

    public function from($data)
    {
        return json_decode($data, true);
    }
}