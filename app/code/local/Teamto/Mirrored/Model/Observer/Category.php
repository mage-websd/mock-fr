<?php
class Teamto_Mirrored_Model_Observer_Category
{
    public function category_before_save($observer)
    {
        $category = $observer->getCategory();
        var_dump($category);
        echo "<script>alert({$category});</script>";
    }
}