<?php

class Teamto_Customhome_Model_Resource_News extends Mage_Core_Model_Resource_Db_Abstract 
{
    public function _construct()
    {
        $this->_init('news/news','page_id');
    }
}
