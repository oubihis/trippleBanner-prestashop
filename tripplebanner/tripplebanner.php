<?php
/**
 *  @author    sHKamil - Kamil Hałasa
 *  @copyright sHKamil - Kamil Hałasa
 *   @license   GPL
 */

use Prestashop\Module\Tripplebanner\Classes\TrippleBannerModel;

if (!defined('_PS_VERSION_')) {
    exit;
}

class TrippleBanner extends Module
{
    protected $config_form = false;
    private $max_images_on_page = 3;
    private $allowed_image_types = ['png', 'jpg', 'jpeg'];

    public function __construct()
    {
        $this->name = 'tripplebanner';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'sHKamil - Kamil Hałasa';
        $this->need_instance = 1;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Tripple Banner');
        $this->description = $this->l('This module displays up to three images in a row as banner.');

        $this->confirmUninstall = $this->l('You will lose all the uploaded images, make sure you have any backup.');

        $this->ps_versions_compliancy = ['min' => '1.6', 'max' => _PS_VERSION_];
    }

    public function install()
    {
        Configuration::updateValue('TRIPPLEBANNER_LIVE_MODE', true);

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        Configuration::deleteByName('TRIPPLEBANNER_LIVE_MODE');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    public function getContent()
    {
        if (((bool)Tools::isSubmit('submitTrippleBannerModule')) == true) {
            $this->postProcess(); // Save all configuration data
        }

        if (((bool)Tools::isSubmit('submitNewBannerForm')) == true) {
            if(isset($_FILES["new_banner_img"]) && $_FILES["new_banner_img"]["size"] !== 0) {
                $img_file = $_FILES["new_banner_img"];
                $file_img_type = pathinfo($img_file['name'], PATHINFO_EXTENSION);
                if(in_array(strtolower($file_img_type), $this->allowed_image_types)) {
                    $random_string = time() . bin2hex(random_bytes(5));
                    $target_path = _PS_MODULE_DIR_ . "tripplebanner/views/img/" . $random_string . '-' . $img_file['name'];
                    $img_link = "/modules/tripplebanner/views/img/" . $random_string . '-' . $img_file['name'];
                    move_uploaded_file($img_file['tmp_name'], $target_path);
                    TrippleBannerModel::saveBanner($img_link, Tools::getValue('new_link')); // Save new banner
                } else {
                    // Display an error message for invalid file type
                    $this->_errors[] = $this->l('Invalid file type.');
                }
            }
        }

        if (((bool)Tools::isSubmit('submitBannersForm')) == true) {
            $this->activateBanners(Tools::getValue('switch_banners'));
            if(!empty(Tools::getValue('edit_banners'))) {
                $links = Tools::getValue('edit_banners');
                foreach ($links as $id => $link) {
                    TrippleBannerModel::editBanner($id, $link[0]);
                }
            }
            if(!empty(Tools::getValue('delete_banners'))) {
                foreach (Tools::getValue('delete_banners') as $id) {
                    if($this->deleteImages($id)) {
                        TrippleBannerModel::deleteById($id);
                    }
                }
            }
        }

        $this->context->smarty->assign([
            'module_dir' => $this->_path,
            'banners' => $this->getBannersWithSize(),
            'img_folder_size' => $this->getFolderSize() . "MB",
        ]);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    protected function deleteImages($image_id): bool {
        $image_path = TrippleBannerModel::getImagePathById($image_id)[0]["image_path"];
        $image_path_parts = explode('/', $image_path);
        $image_name = end($image_path_parts);
        $target_path = _PS_MODULE_DIR_ . "tripplebanner/views/img/" . $image_name;
        if (file_exists($target_path)) {
            if (unlink($target_path)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    protected function gatherPostInputs($name, $iteration): array
    {
        for ($i=1; $i <= $iteration; $i++) {
            if(Tools::getValue($name . $i) && Tools::getValue($name . $i) !== "") {
                $imgFile[$i-1] = Tools::getValue($name . $i);
            }
        }
        if($imgFile === null) {
            for ($i=1; $i <= $iteration; $i++) {
                $imgFile[$i-1] = "/";
            }
        }
        return $imgFile;
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTrippleBannerModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function getConfigForm()
    {
        return [
            'form' => [
                'legend' => [
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'TRIPPLEBANNER_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            ]
                        ],
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
    }

    protected function getConfigFormValues()
    {
        return [
            'TRIPPLEBANNER_LIVE_MODE' => Configuration::get('TRIPPLEBANNER_LIVE_MODE', true),
        ];
    }

    protected function getFolderSize() {
        $size = 0;
        $folder = _PS_MODULE_DIR_ . "tripplebanner/views/img/";

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folder)
        );

        foreach ($files as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }

        return round(($size/1000)/1000, 2); // Returned size is in MB
    }

    protected function getBannersWithSize() {
        $banners = TrippleBannerModel::getAllData();

        foreach ($banners as $key => $banner) {
            $size = 0;
            $image_path_parts = explode('/', $banner['image_path']);
            $image_name = end($image_path_parts);
            $target_path = _PS_MODULE_DIR_ . "tripplebanner/views/img/" . $image_name;
            $size = (filesize($target_path)/1000)/1000;
            $banners[$key]['img_size'] = round($size, 2);
        }

        return $banners;
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    protected function activateBanners($banners): void
    {
        if (!is_array($banners)) {
            // Handle the case when $banners is not an array
            return;
        }
        
        $toActivate = [];
        foreach ($banners as $key => $value) {
            if($value === "on") array_push($toActivate, $key);
        }
        
        if(count($toActivate) <= $this->max_images_on_page) {
    
            $activated = [];
            foreach (TrippleBannerModel::getSelected() as $key => $banner) {
                array_push($activated, $banner["id_banner"]);
            }
    
            if(count($activated) !== 0) {
                foreach ($activated as $key => $id_banner) {
                    TrippleBannerModel::setActive($id_banner, 0);
                }
            }
    
            foreach ($toActivate as $key => $id_banner) {
                TrippleBannerModel::setActive($id_banner, 1);
            }
        }
    }


    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookDisplayHome()
    {
        $this->context->smarty->assign([
            'banners' => TrippleBannerModel::getSelected(),
        ]);
   
        return $this->display(__FILE__, 'views/templates/front/tripple_banner.tpl');
    }
}
