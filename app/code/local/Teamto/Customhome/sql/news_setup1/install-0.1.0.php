<?php
//die('die tại sql');
    $installer = $this;
    $installer->startSetup();

    $installer->run('ALTER TABLE `cms_page` ADD COLUMN `is_news` integer NULL;');
    $installer->endSetup(); 