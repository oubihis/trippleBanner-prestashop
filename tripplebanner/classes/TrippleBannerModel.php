<?php
/**
*  @author    sHKamil - Kamil Hałasa
*  @copyright sHKamil - Kamil Hałasa
*  @license   .l
*/

namespace Prestashop\Module\Tripplebanner\Classes;

class TrippleBannerModel extends \ObjectModel
{
    public static $definition = [
        'table' => 'tripplebanner',
        'primary' => 'id_banner',
        'fields' => [
            'image_path' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true],
            'link' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true],
            'active' => ['type' => self::TYPE_BOOL, 'required' => true],
        ],
    ];
    
    public static function getAllData() {
        $table = '`' . _DB_PREFIX_ . 'tripplebanner`';
        $sql = 'SELECT * FROM ' . $table;
        return \Db::getInstance()->executeS($sql);
    }

    public static function getImagePathById($id) {
        $table = '`' . _DB_PREFIX_ . 'tripplebanner`';
        $sql = 'SELECT image_path FROM ' . $table . 'WHERE id_banner=' . $id;
        return \Db::getInstance()->executeS($sql);
    }

    public static function getSelected() {
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'tripplebanner` WHERE active = 1';
        return \Db::getInstance()->executeS($sql);
    }

    public static function saveBanner($image_path, $link) {
        $db = \Db::getInstance();
        $data = [
            'image_path' => $image_path,
            'link' => $link
        ];
        return $db->insert('tripplebanner', $data);
    }

    public static function setActive($id, $active) {
        $db = \Db::getInstance();
        return $db->update('tripplebanner', [
            'active' => $active
        ], 'id_banner = ' . $id, 1);
    }

    public static function editBanner($id, $link) {
        $db = \Db::getInstance();
        return $db->update('tripplebanner', [
            'link' => $link
        ], 'id_banner = ' . $id, 1);
    }

    public static function deleteById($id) {
        $db = \Db::getInstance();
        return $db->delete('tripplebanner', 'id_banner = ' . $id);
    }
}
