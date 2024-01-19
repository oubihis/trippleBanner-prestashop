<?php
/**
 *  @author    sHKamil - Kamil Hałasa
 *  @copyright sHKamil - Kamil Hałasa
 *   @license   GPL
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

use Prestashop\Module\Tripplebanner\Classes\TrippleBannerModel;

class tripplebannerTrippleBannerEndpointModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $method = Tools::getValue('method');

        // Call the appropriate method based on the 'method' parameter
        if (!empty($method) && method_exists($this, $method)) {
            $this->{$method}();
        } else {
            parent::initContent();
        }
    }

    public function getActiveJSON() : void
    {
        $selected = TrippleBannerModel::getSelected();
        header('Content-Type: application/json');
        echo json_encode($selected);
        exit;
    }
}
