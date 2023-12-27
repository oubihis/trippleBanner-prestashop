<?php
/**
*  @author    sHKamil - Kamil Hałasa
*  @copyright sHKamil - Kamil Hałasa
*  @license   .l
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class TrippleBanner extends Module
{
    protected $config_form = false;

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
        Configuration::updateValue('TRIPPLEBANNER_IMG_1', '');
        Configuration::updateValue('TRIPPLEBANNER_LINK_1', '');
        Configuration::updateValue('TRIPPLEBANNER_IMG_2', '');
        Configuration::updateValue('TRIPPLEBANNER_LINK_2', '');
        Configuration::updateValue('TRIPPLEBANNER_IMG_3', '');
        Configuration::updateValue('TRIPPLEBANNER_LINK_3', '');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        Configuration::deleteByName('TRIPPLEBANNER_LIVE_MODE');
        Configuration::deleteByName('TRIPPLEBANNER_IMG_1');
        Configuration::deleteByName('TRIPPLEBANNER_LINK_1');
        Configuration::deleteByName('TRIPPLEBANNER_IMG_2');
        Configuration::deleteByName('TRIPPLEBANNER_LINK_2');
        Configuration::deleteByName('TRIPPLEBANNER_IMG_3');
        Configuration::deleteByName('TRIPPLEBANNER_LINK_3');

        return parent::uninstall();
    }

    public function getContent()
    {


        if (Tools::isSubmit('submitTrippleBannerModule')) {

            $allowedImageTypes = ['png', 'jpg', 'jpeg'];
            for ($i=1; $i <= 3; $i++) {
                if(isset($_FILES["TRIPPLEBANNER_IMG_" . $i]) && $_FILES["TRIPPLEBANNER_IMG_" . $i]["size"] !== 0) {
                    $imgFile[$i-1] = $_FILES["TRIPPLEBANNER_IMG_" . $i];
                }
            }

            foreach ($imgFile as $key => $file) {
                $fileImgType = pathinfo($file['name'], PATHINFO_EXTENSION);
                if(in_array(strtolower($fileImgType), $allowedImageTypes)) {
                    // $targetPath = _PS_MODULE_DIR_ . "tripplebanner/views/img/banner-img-" . $key+1 . "." . $fileImgType;
                    $targetPath = "banner-img-" . $key+1 . "." . $fileImgType;
                    move_uploaded_file($file['tmp_name'], $targetPath);
                    Configuration::updateValue('TRIPPLEBANNER_IMG_' . $key+1, $targetPath);
                } else {
                    // Display an error message for invalid file type
                    $this->_errors[] = $this->l('Invalid file type. Allowed types: png, jpg, jpeg.');
                }
            }
            Configuration::updateValue('TRIPPLEBANNER_LIVE_MODE', true);
            if(!empty($_POST['delete_images'])) $this->removeFromDB($_POST['delete_images']);
            
            $links = $this->gatherPostInputs("TRIPPLEBANNER_LINK_", 3);
            foreach ($links as $key => $link) {
                Configuration::updateValue('TRIPPLEBANNER_LINK_' . $key+1, $link);
            }
        } 
        $this->context->smarty->assign('module_dir', $this->_path);
        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    protected function removeFromDB($files): void
    {
        foreach ($files as $key => $file) {
            Configuration::updateValue($file, "");
        }
    }

    protected function gatherPostInputs($name, $iteration): array
    {
        for ($i=1; $i <= $iteration; $i++) {
            if(isset($_POST[$name. $i]) && $_POST[$name . $i] !== "") {
                $imgFile[$i-1] = $_POST[$name . $i];
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
                        'col' => 3,
                        'type' => 'file',
                        'desc' => $this->l('Upload image 1 (1/1 aspect ratio preferred, for example 1024x1024)'),
                        'name' => 'TRIPPLEBANNER_IMG_1',
                        'label' => $this->l('Upload image 1'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Enter link for image 1'),
                        'name' => 'TRIPPLEBANNER_LINK_1',
                        'label' => $this->l('Enter link for image 1'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'file',
                        'desc' => $this->l('Upload image 2 (1/1 aspect ratio preferred, for example 1024x1024)'),
                        'name' => 'TRIPPLEBANNER_IMG_2',
                        'label' => $this->l('Upload image 2'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Enter link for image 2'),
                        'name' => 'TRIPPLEBANNER_LINK_2',
                        'label' => $this->l('Enter link for image 2'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'file',
                        'desc' => $this->l('Upload image 3 (1/1 aspect ratio preferred, for example 1024x1024)'),
                        'name' => 'TRIPPLEBANNER_IMG_3',
                        'label' => $this->l('Upload image 3'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Enter link for image 3'),
                        'name' => 'TRIPPLEBANNER_LINK_3',
                        'label' => $this->l('Enter link for image 3'),
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
            'TRIPPLEBANNER_IMG_1' => Configuration::get('TRIPPLEBANNER_IMG_1'),
            'TRIPPLEBANNER_LINK_1' => Configuration::get('TRIPPLEBANNER_LINK_1'),
            'TRIPPLEBANNER_IMG_2' => Configuration::get('TRIPPLEBANNER_IMG_2'),
            'TRIPPLEBANNER_LINK_2' => Configuration::get('TRIPPLEBANNER_LINK_2'),
            'TRIPPLEBANNER_IMG_3' => Configuration::get('TRIPPLEBANNER_IMG_3'),
            'TRIPPLEBANNER_LINK_3' => Configuration::get('TRIPPLEBANNER_LINK_3'),
        ];
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
        return $this->display(__FILE__, 'views/templates/front/tripple_banner.tpl');
    }
}
