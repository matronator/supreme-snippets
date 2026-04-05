<?php

declare(strict_types=1);

class Debug
{
    /**
     * Shows all visible Vars of a Object, only in the first Level.
     * To get private or protected we need to call the class
     * in the Context of the Object ($this)
     * @param object $obj The Object
     * @param string|null $newLineCharacter The New Line Character (If null, it is based on \n for CLI or <br/> on web
     * @return void
     */
    public static function firstLevelVarDump($obj, $newLineCharacter = null) {
        //Decide which new Line Character we use (Based on Loïc suggestion)
        if ($newLineCharacter === null) {
            $newLineCharacter = php_sapi_name() == 'cli' ? PHP_EOL : '<br/>';
        }
        //Get all visible Items
        $data = get_object_vars($obj);

        //Loop through each Item
        foreach ($data as $key => $item) {
            //Display Key + Type
            echo $key . ' => ' . gettype($item);

            //Extract Details, beased on the Type
            if (is_string($item)) {
                echo '(' . strlen($item) . ') "' . $item . '"';
            } elseif (is_bool($item)) {
                echo '(' . ($item ? 'true' : 'false') . ')';
            } elseif (is_integer($item) || is_float($item)) {
                echo '(' . $item . ')';
            } elseif (is_object($item)) {
                echo '(' . get_class($item) . ')';
            }

            //Line Break
            echo $newLineCharacter;
        }
    }
}
