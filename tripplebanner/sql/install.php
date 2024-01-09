<?php
/**
*  @author    sHKamil - Kamil Hałasa
*  @copyright sHKamil - Kamil Hałasa
*  @license   .l
*/
$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'tripplebanner` (
    `id_banner` int(11) NOT NULL AUTO_INCREMENT,
    `image_path` varchar(2000) NOT NULL,
    `link` varchar(2000) NOT NULL,
    `active` tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY  (`id_banner`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
