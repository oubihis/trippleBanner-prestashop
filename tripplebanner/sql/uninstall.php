<?php
/**
 *  @author    sHKamil - Kamil Hałasa
 *  @copyright sHKamil - Kamil Hałasa
 *   @license   GPL
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

$sql = [];
$sql[] = "DROP TABLE IF EXISTS `" . _DB_PREFIX_ . "tripplebanner`";

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
