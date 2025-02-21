<?php
/**
 * @author    BizoSizco <info@bizosiz.com>
 * @copyright 2025 BizoSizco
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 * Last updated: 2025-02-21 01:23:22 by BizoSizco
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(_PS_MODULE_DIR_.'bs_videoslider/classes/BsVideoSliderVideo.php');

class Bs_VideoSlider extends Module
{
    public function __construct()
    {
        $this->name = 'bs_videoslider';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'BizoSizco';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Video Slider');
        $this->description = $this->l('Responsive video slider with support for direct videos and Aparat');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayHome') &&
            $this->registerHook('displayLeftColumn') &&
            $this->registerHook('displayRightColumn') &&
            $this->registerHook('displayFooter');
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    public function getContent()
    {
        $output = '';
        
        if (Tools::isSubmit('submit'.$this->name)) {
            $this->postProcess();
            $output .= $this->displayConfirmation($this->l('Settings updated successfully'));
        }
        
        if (Tools::isSubmit('submitVideo')) {
            $this->processVideoForm();
        }
        
        if (Tools::isSubmit('deleteVideo')) {
            $this->deleteVideo((int)Tools::getValue('id_video'));
        }
        
        if (Tools::isSubmit('toggleStatus')) {
            $this->toggleVideoStatus((int)Tools::getValue('id_video'));
        }

        return $output.$this->renderForm();
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
        $helper->submit_action = 'submit'.$this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Title'),
                        'name' => 'BS_VIDEOSLIDER_TITLE',
                        'required' => true,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Display Position'),
                        'name' => 'BS_VIDEOSLIDER_POSITION',
                        'required' => true,
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 'home',
                                    'name' => $this->l('Homepage')
                                ),
                                array(
                                    'id_option' => 'left',
                                    'name' => $this->l('Left Column')
                                ),
                                array(
                                    'id_option' => 'right',
                                    'name' => $this->l('Right Column')
                                ),
                                array(
                                    'id_option' => 'footer',
                                    'name' => $this->l('Footer')
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Autoplay'),
                        'name' => 'BS_VIDEOSLIDER_AUTOPLAY',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        )
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Autoplay Speed'),
                        'name' => 'BS_VIDEOSLIDER_SPEED',
                        'desc' => $this->l('Time in milliseconds'),
                        'class' => 'fixed-width-sm',
                        'suffix' => 'ms'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Desktop Slides'),
                        'name' => 'BS_VIDEOSLIDER_DESKTOP',
                        'class' => 'fixed-width-xs',
                        'required' => true
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Tablet Slides'),
                        'name' => 'BS_VIDEOSLIDER_TABLET',
                        'class' => 'fixed-width-xs',
                        'required' => true
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Mobile Slides'),
                        'name' => 'BS_VIDEOSLIDER_MOBILE',
                        'class' => 'fixed-width-xs',
                        'required' => true
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show Dots'),
                        'name' => 'BS_VIDEOSLIDER_DOTS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show Arrows'),
                        'name' => 'BS_VIDEOSLIDER_ARROWS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Infinite Loop'),
                        'name' => 'BS_VIDEOSLIDER_INFINITE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    protected function getConfigFormValues()
    {
        return array(
            'BS_VIDEOSLIDER_TITLE' => Configuration::get('BS_VIDEOSLIDER_TITLE'),
            'BS_VIDEOSLIDER_POSITION' => Configuration::get('BS_VIDEOSLIDER_POSITION', 'home'),
            'BS_VIDEOSLIDER_AUTOPLAY' => Configuration::get('BS_VIDEOSLIDER_AUTOPLAY', true),
            'BS_VIDEOSLIDER_SPEED' => Configuration::get('BS_VIDEOSLIDER_SPEED', 3000),
            'BS_VIDEOSLIDER_DESKTOP' => Configuration::get('BS_VIDEOSLIDER_DESKTOP', 4),
            'BS_VIDEOSLIDER_TABLET' => Configuration::get('BS_VIDEOSLIDER_TABLET', 3),
            'BS_VIDEOSLIDER_MOBILE' => Configuration::get('BS_VIDEOSLIDER_MOBILE', 2),
            'BS_VIDEOSLIDER_DOTS' => Configuration::get('BS_VIDEOSLIDER_DOTS', true),
            'BS_VIDEOSLIDER_ARROWS' => Configuration::get('BS_VIDEOSLIDER_ARROWS', true),
            'BS_VIDEOSLIDER_INFINITE' => Configuration::get('BS_VIDEOSLIDER_INFINITE', true),
        );
    }

    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    protected function processVideoForm()
    {
        $id_video = (int)Tools::getValue('id_video');
        $video = $id_video ? new BsVideoSliderVideo($id_video) : new BsVideoSliderVideo();
        
        $video->title = Tools::getValue('title');
        $video->video = Tools::getValue('video');
        $video->active = Tools::getValue('active');

        // Handle image upload
        if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file_name = md5(uniqid()).'.'.$ext;
            
            if (!move_uploaded_file(
                $_FILES['image']['tmp_name'],
                _PS_MODULE_DIR_.$this->name.'/views/img/'.$file_name
            )) {
                return false;
            }
            
            if ($video->image) {
                @unlink(_PS_MODULE_DIR_.$this->name.'/views/img/'.$video->image);
            }
            
            $video->image = $file_name;
        }

        if ($id_video) {
            return $video->update();
        }
        
        return $video->add();
    }

    protected function deleteVideo($id_video)
    {
        $video = new BsVideoSliderVideo($id_video);
        return $video->delete();
    }

    protected function toggleVideoStatus($id_video)
    {
        $video = new BsVideoSliderVideo($id_video);
        return $video->toggleStatus();
    }

    public function hookHeader()
    {
        Media::addJsDef(array(
            'bs_videoslider' => array(
                'autoplay' => (bool)Configuration::get('BS_VIDEOSLIDER_AUTOPLAY'),
                'speed' => (int)Configuration::get('BS_VIDEOSLIDER_SPEED'),
                'desktop' => (int)Configuration::get('BS_VIDEOSLIDER_DESKTOP'),
                'tablet' => (int)Configuration::get('BS_VIDEOSLIDER_TABLET'),
                'mobile' => (int)Configuration::get('BS_VIDEOSLIDER_MOBILE'),
                'dots' => (bool)Configuration::get('BS_VIDEOSLIDER_DOTS'),
                'arrows' => (bool)Configuration::get('BS_VIDEOSLIDER_ARROWS'),
                'infinite' => (bool)Configuration::get('BS_VIDEOSLIDER_INFINITE')
            )
        ));

        $this->context->controller->addJS($this->_path.'views/js/slick.min.js');
        $this->context->controller->addJS($this->_path.'views/js/bs_videoslider.js');
        $this->context->controller->addCSS($this->_path.'views/css/slick.css');
        $this->context->controller->addCSS($this->_path.'views/css/slick-theme.css');
        $this->context->controller->addCSS($this->_path.'views/css/bs_videoslider.css');
    }

    protected function getVideosByPosition($position)
    {
        if (!in_array($position, array('home', 'left', 'right', 'footer'))) {
            return array();
        }

        return BsVideoSliderVideo::getVideosBySlider(
            Configuration::get('BS_VIDEOSLIDER_ID'),
            true
        );
    }

    public function hookDisplayHome()
    {
        if (Configuration::get('BS_VIDEOSLIDER_POSITION') !== 'home') {
            return '';
        }

        $videos = $this->getVideosByPosition('home');
        
        $this->context->smarty->assign(array(
            'videos' => $videos,
            'slider_title' => Configuration::get('BS_VIDEOSLIDER_TITLE'),
        ));

        return $this->display(__FILE__, 'views/templates/hook/bs_videoslider.tpl');
    }

    public function hookDisplayLeftColumn()
    {
        if (Configuration::get('BS_VIDEOSLIDER_POSITION') !== 'left') {
            return '';
        }

        $videos = $this->getVideosByPosition('left');

        $this->context->smarty->assign(array(
            'videos' => $videos,
            'slider_title' => Configuration::get('BS_VIDEOSLIDER_TITLE'),
        ));

        return $this->display(__FILE__, 'views/templates/hook/bs_videoslider.tpl');
    }

    public function hookDisplayRightColumn()
    {
        if (Configuration::get('BS_VIDEOSLIDER_POSITION') !== 'right') {
            return '';
        }

        $videos = $this->getVideosByPosition('right');

        $this->context->smarty->assign(array(
            'videos' => $videos,
            'slider_title' => Configuration::get('BS_VIDEOSLIDER_TITLE'),
        ));

        return $this->display(__FILE__, 'views/templates/hook/bs_videoslider.tpl');
    }

    public function hookDisplayFooter()
    {
        if (Configuration::get('BS_VIDEOSLIDER_POSITION') !== 'footer') {
            return '';
        }

        $videos = $this->getVideosByPosition('footer');

        $this->context->smarty->assign(array(
            'videos' => $videos,
            'slider_title' => Configuration::get('BS_VIDEOSLIDER_TITLE'),
        ));

        return $this->display(__FILE__, 'views/templates/hook/bs_videoslider.tpl');
    }
}