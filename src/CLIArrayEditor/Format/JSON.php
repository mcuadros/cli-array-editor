<?php
/*
 * This file is part of the CLIArrayEditor package.
 *
 * (c) Máximo Cuadros <mcuadros@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CLIArrayEditor\Format;
use CLIArrayEditor\Format;

/**
* YAML format class
*/
class JSON implements Format
{
    /**
     * {@inheritdoc}
     */
    public function to(array $data)
    {
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            return json_encode($data, JSON_PRETTY_PRINT);
        }

        return $this->pretty(json_encode($data));
    }

    /**
     * {@inheritdoc}
     */
    public function from($data)
    {
        return json_decode($data, true);
    }

    /**
     * Pretty print for JSON in PHP 5.3
     *
     * @link http://snipplr.com/view/60559/prettyjson/
     * @param string $json
     * 
     * @return string
     */
    private function pretty($json)
    {
     
        $result      = '';
        $pos         = 0;
        $strLen      = strlen($json);
        $indentStr   = '    ';
        $newLine     = "\n";
        $prevChar    = '';
        $outOfQuotes = true;
     
        for ($i=0; $i<=$strLen; $i++) {
     
            // Grab the next character in the string.
            $char = substr($json, $i, 1);
     
            // Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;
     
            // If this character is the end of an element, 
            // output a new line and indent the next line.
            } else if(($char == '}' || $char == ']') && $outOfQuotes) {
                $result .= $newLine;
                $pos --;
                for ($j=0; $j<$pos; $j++) {
                    $result .= $indentStr;
                }
            }
     
            // Add the character to the result string.
            $result .= $char;
     
            // If the last character was the beginning of an element, 
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos ++;
                }
     
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
     
            if (($char == ':') && $outOfQuotes) {
                $result .= ' ';
            }

            $prevChar = $char;
        }
     
        return $result;
    }
}