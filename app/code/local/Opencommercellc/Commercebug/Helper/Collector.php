<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/

class Opencommercellc_Commercebug_Helper_Collector extends Opencommercellc_Commercebug_Helper_Abstract
{
    static protected $items;
    static public function saveItem($key, $value)
    {
        self::$items[$key] = $value;
    }
}