<?php
/**
 * @author    BizoSizco <info@bizosiz.com>
 * @copyright 2025 BizoSizco
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminBsVideoSliderController extends ModuleAdminController
{
    protected $position_identifier = 'id_slider';
    public $secure_key;
    protected $_defaultOrderBy = 'position';
    protected $_defaultOrderWay = 'ASC';
    protected $currentDateTime = '2025-02-21 01:05:36';
    protected $currentUser = 'BizoSizco';

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'bs_videoslider';
        $this->identifier = 'id_slider';
        $this->className = 'BsVideoSlider';
        $this->lang = false;
        
        if (Module::isInstalled('bs_videoslider')) {
            $this->module = Module::getInstanceByName('bs_videoslider');
            $this->secure_key = $this->module->secure_key;
        }
        
        parent::__construct();

        $this->fields_list = [
            'id_slider' => [
                'title' => $this->trans('ID', [], 'Admin.Global'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ],
            'title' => [
                'title' => $this->trans('Title', [], 'Admin.Global'),
                'align' => 'left',
            ],
            'position' => [
                'title' => $this->trans('Hook Position', [], 'Admin.Global'),
                'align' => 'center',
                'type' => 'select',
                'list' => [
                    'displayHome' => $this->trans('Home', [], 'Admin.Global'),
                    'displayLeftColumn' => $this->trans('Left Column', [], 'Admin.Global'),
                    'displayRightColumn' => $this->trans('Right Column', [], 'Admin.Global'),
                    'displayFooter' => $this->trans('Footer', [], 'Admin.Global')
                ],
                'filter_key' => 'a!position',
                'callback' => 'getPositionName'
            ],
            'active' => [
                'title' => $this->trans('Status', [], 'Admin.Global'),
                'active' => 'status',
                'type' => 'bool',
                'align' => 'center',
                'class' => 'fixed-width-sm',
                'orderby' => false
            ],
            'date_add' => [
                'title' => $this->trans('Created', [], 'Admin.Global'),
                'align' => 'center',
                'type' => 'datetime'
            ],
            'date_upd' => [
                'title' => $this->trans('Modified', [], 'Admin.Global'),
                'align' => 'center',
                'type' => 'datetime'
            ]
        ];

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->trans('Delete selected', [], 'Admin.Actions'),
                'icon' => 'icon-trash',
                'confirm' => $this->trans('Delete selected items?', [], 'Admin.Notifications.Warning')
            ]
        ];

        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        
        $this->addJqueryUI('ui.sortable');
        $this->addJS([
            _PS_JS_DIR_.'jquery/ui/jquery.ui.sortable.min.js',
            _PS_MODULE_DIR_.$this->module->name.'/views/js/admin.js',
            _PS_JS_DIR_.'tiny_mce/tiny_mce.js',
            _PS_JS_DIR_.'admin/tinymce.inc.js',
        ]);
        $this->addCSS(_PS_MODULE_DIR_.$this->module->name.'/views/css/admin.css');
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_slider'] = [
                'href' => self::$currentIndex.'&addbs_videoslider&token='.$this->token,
                'desc' => $this->trans('Add New Slider', [], 'Admin.Actions'),
                'icon' => 'process-icon-new'
            ];
        }

        parent::initPageHeaderToolbar();
    }

    public function renderForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        if (!isset($this->secure_key) || empty($this->secure_key)) {
            $this->secure_key = $this->module->secure_key;
        }

        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Video Slider', [], 'Admin.Global'),
                'icon' => 'icon-film'
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->trans('Title', [], 'Admin.Global'),
                    'name' => 'title',
                    'required' => true,
                    'class' => 'fixed-width-xl',
                    'hint' => $this->trans('Enter the slider title.', [], 'Admin.Global')
                ],
                [
                    'type' => 'select',
                    'label' => $this->trans('Position', [], 'Admin.Global'),
                    'name' => 'position',
                    'required' => true,
                    'options' => [
                        'query' => [
                            ['id' => 'displayHome', 'name' => $this->trans('Home', [], 'Admin.Global')],
                            ['id' => 'displayLeftColumn', 'name' => $this->trans('Left Column', [], 'Admin.Global')],
                            ['id' => 'displayRightColumn', 'name' => $this->trans('Right Column', [], 'Admin.Global')],
                            ['id' => 'displayFooter', 'name' => $this->trans('Footer', [], 'Admin.Global')]
                        ],
                        'id' => 'id',
                        'name' => 'name'
                    ]
                ],
                [
                    'type' => 'switch',
                    'label' => $this->trans('Status', [], 'Admin.Global'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', [], 'Admin.Global')
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', [], 'Admin.Global')
                        ]
                    ]
                ],
                // تنظیمات نمایش
                [
                    'type' => 'text',
                    'label' => $this->trans('Items on Desktop', [], 'Admin.Global'),
                    'name' => 'slides_desktop',
                    'class' => 'fixed-width-xs',
                    'required' => true,
                    'desc' => $this->trans('Number of items to show on desktop (>1200px)', [], 'Admin.Global'),
                    'suffix' => $this->trans('items', [], 'Admin.Global')
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Items on Tablet', [], 'Admin.Global'),
                    'name' => 'slides_tablet',
                    'class' => 'fixed-width-xs',
                    'required' => true,
                    'desc' => $this->trans('Number of items to show on tablet (>768px)', [], 'Admin.Global'),
                    'suffix' => $this->trans('items', [], 'Admin.Global')
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Items on Mobile', [], 'Admin.Global'),
                    'name' => 'slides_mobile',
                    'class' => 'fixed-width-xs',
                    'required' => true,
                    'desc' => $this->trans('Number of items to show on mobile (<768px)', [], 'Admin.Global'),
                    'suffix' => $this->trans('items', [], 'Admin.Global')
                ],
                [
                    'type' => 'switch',
                    'label' => $this->trans('Autoplay', [], 'Admin.Global'),
                    'name' => 'autoplay',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'autoplay_on',
                            'value' => 1,
                            'label' => $this->trans('Yes', [], 'Admin.Global')
                        ],
                        [
                            'id' => 'autoplay_off',
                            'value' => 0,
                            'label' => $this->trans('No', [], 'Admin.Global')
                        ]
                    ]
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Autoplay Speed', [], 'Admin.Global'),
                    'name' => 'autoplay_speed',
                    'class' => 'fixed-width-sm',
                    'desc' => $this->trans('Time between slides in milliseconds', [], 'Admin.Global'),
                    'suffix' => 'ms'
                ],
                [
                    'type' => 'switch',
                    'label' => $this->trans('Infinite Loop', [], 'Admin.Global'),
                    'name' => 'infinite',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'infinite_on',
                            'value' => 1,
                            'label' => $this->trans('Yes', [], 'Admin.Global')
                        ],
                        [
                            'id' => 'infinite_off',
                            'value' => 0,
                            'label' => $this->trans('No', [], 'Admin.Global')
                        ]
                    ]
                ],
                [
                    'type' => 'switch',
                    'label' => $this->trans('Show Dots', [], 'Admin.Global'),
                    'name' => 'dots',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'dots_on',
                            'value' => 1,
                            'label' => $this->trans('Yes', [], 'Admin.Global')
                        ],
                        [
                            'id' => 'dots_off',
                            'value' => 0,
                            'label' => $this->trans('No', [], 'Admin.Global')
                        ]
                    ]
                ],
                [
                    'type' => 'switch',
                    'label' => $this->trans('Show Arrows', [], 'Admin.Global'),
                    'name' => 'arrows',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'arrows_on',
                            'value' => 1,
                            'label' => $this->trans('Yes', [], 'Admin.Global')
                        ],
                        [
                            'id' => 'arrows_off',
                            'value' => 0,
                            'label' => $this->trans('No', [], 'Admin.Global')
                        ]
                    ]
                ],
                [
                    'type' => 'free',
                    'label' => $this->trans('Videos', [], 'Admin.Global'),
                    'name' => 'video_list'
                ]
            ],
            'submit' => [
                'title' => $this->trans('Save', [], 'Admin.Actions')
            ],
            'buttons' => [
                'save-and-stay' => [
                    'type' => 'submit',
                    'title' => $this->trans('Save and Stay', [], 'Admin.Actions'),
                    'icon' => 'process-icon-save',
                    'class' => 'btn btn-default pull-right',
                    'name' => 'submitAdd'.$this->table.'AndStay'
                ]
            ]
        ];

        // Get current videos if editing
        $videos = [];
        if ($obj->id) {
            $sql = new DbQuery();
            $sql->select('*');
            $sql->from('bs_videoslider_videos');
            $sql->where('id_slider = '.(int)$obj->id);
            $sql->orderBy('position ASC');
            
            $videos = Db::getInstance()->executeS($sql);
        }

        $this->context->smarty->assign([
            'videos' => $videos,
            'id_slider' => (int)$obj->id,
            'secure_key' => $this->secure_key,
            'ps_version' => _PS_VERSION_,
            'datetime_now' => $this->currentDateTime,
            'current_user' => $this->currentUser,
            'module_dir' => _PS_MODULE_DIR_.$this->module->name.'/',
            'current_url' => $this->context->link->getAdminLink('AdminBsVideoSlider'),
            'token' => $this->token
        ]);

        $tpl = $this->context->smarty->createTemplate(
            _PS_MODULE_DIR_.'bs_videoslider/views/templates/admin/video_form.tpl'
        );
        
        $tpl->assign($this->context->smarty->getTemplateVars());

        $this->fields_value = [
            'active' => $obj->id ? $obj->active : 1,
            'slides_desktop' => $obj->id ? $obj->slides_desktop : 4,
            'slides_tablet' => $obj->id ? $obj->slides_tablet : 3,
            'slides_mobile' => $obj->id ? $obj->slides_mobile : 2,
            'autoplay' => $obj->id ? $obj->autoplay : 1,
            'autoplay_speed' => $obj->id ? $obj->autoplay_speed : 3000,
            'infinite' => $obj->id ? $obj->infinite : 1,
            'dots' => $obj->id ? $obj->dots : 1,
            'arrows' => $obj->id ? $obj->arrows : 1,
            'video_list' => $tpl->fetch()
        ];

        return parent::renderForm();
    }
    public function processSave()
    {
        if (!$this->secure_key || Tools::getValue('secure_key') != $this->secure_key) {
            $this->errors[] = $this->trans('Invalid security key.', [], 'Admin.Notifications.Error');
            return false;
        }

        if (Tools::isSubmit('submitAdd' . $this->table) || Tools::isSubmit('submitAdd' . $this->table . 'AndStay')) {
            $videoContent = Tools::getValue('video');
            if (isset($videoContent['content'])) {
                foreach ($videoContent['content'] as &$content) {
                    $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
                }
                $_POST['video']['content'] = $videoContent['content'];
            }
        }

        $object = parent::processSave();

        if ($object) {
            // Handle videos
            $videoTitles = Tools::getValue('video');
            if (is_array($videoTitles) && isset($videoTitles['title'])) {
                // First delete existing videos if editing
                if ($object->id) {
                    // Delete old images first
                    $old_videos = Db::getInstance()->executeS(
                        '
                        SELECT image FROM `' . _DB_PREFIX_ . 'bs_videoslider_videos`
                        WHERE `id_slider` = ' . (int)$object->id
                    );

                    if ($old_videos) {
                        foreach ($old_videos as $old_video) {
                            if ($old_video['image']) {
                                $old_image_path = _PS_MODULE_DIR_ . 'bs_videoslider/' . $old_video['image'];
                                if (file_exists($old_image_path)) {
                                    @unlink($old_image_path);
                                }
                            }
                        }
                    }

                    // Delete old records
                    Db::getInstance()->execute(
                        '
                        DELETE FROM `' . _DB_PREFIX_ . 'bs_videoslider_videos`
                        WHERE `id_slider` = ' . (int)$object->id
                    );
                }

                // Add new videos
                foreach ($videoTitles['title'] as $key => $title) {
                    if (empty($title) || !isset($videoTitles['content'][$key])) {
                        continue;
                    }

                    $image = '';
                    if (
                        isset($_FILES['video']['name']['image'][$key])
                        && !empty($_FILES['video']['name']['image'][$key])
                    ) {

                        // Create img directory if it doesn't exist
                        $img_dir = _PS_MODULE_DIR_ . 'bs_videoslider/views/img/';
                        if (!file_exists($img_dir)) {
                            mkdir($img_dir, 0777, true);
                        }

                        $ext = pathinfo($_FILES['video']['name']['image'][$key], PATHINFO_EXTENSION);
                        $image_name = 'video_' . $object->id . '_' . md5(uniqid()) . '.' . $ext;
                        $image_path = $img_dir . $image_name;

                        if (move_uploaded_file(
                            $_FILES['video']['tmp_name']['image'][$key],
                            $image_path
                        )) {
                            chmod($image_path, 0644);
                            $image = 'views/img/' . $image_name;
                        }
                    }

                    Db::getInstance()->insert('bs_videoslider_videos', [
                        'id_slider' => (int)$object->id,
                        'title' => pSQL($title),
                        'image' => pSQL($image),
                        'video' => pSQL($videoTitles['content'][$key], true),
                        'position' => (int)$key,
                        'active' => 1,
                        'date_add' => $this->currentDateTime,
                        'date_upd' => $this->currentDateTime
                    ]);
                }
            }
        }

        return $object;
    }

    public function processStatus()
    {
        if (Validate::isLoadedObject($object = $this->loadObject())) {
            if ($object->toggleStatus()) {
                $this->confirmations[] = $this->trans('The status has been updated successfully.', [], 'Admin.Notifications.Success');
            } else {
                $this->errors[] = $this->trans('An error occurred while updating the status.', [], 'Admin.Notifications.Error');
            }
        } else {
            $this->errors[] = $this->trans('An error occurred while loading the object.', [], 'Admin.Notifications.Error');
        }

        return $object;
    }

    public function ajaxProcessDeleteVideo()
    {
        header('Content-Type: application/json');

        $id_video = (int)Tools::getValue('id_video');
        if (!$id_video) {
            die(json_encode([
                'success' => false,
                'message' => $this->trans('Invalid video ID', [], 'Admin.Notifications.Error')
            ]));
        }

        // Get video info first
        $video = Db::getInstance()->getRow(
            '
            SELECT * FROM `' . _DB_PREFIX_ . 'bs_videoslider_videos`
            WHERE `id_video` = ' . (int)$id_video
        );

        if ($video) {
            // Delete image if exists
            if ($video['image']) {
                $image_path = _PS_MODULE_DIR_ . 'bs_videoslider/' . $video['image'];
                if (file_exists($image_path)) {
                    @unlink($image_path);
                }
            }

            // Delete from database
            $result = Db::getInstance()->execute(
                '
                DELETE FROM `' . _DB_PREFIX_ . 'bs_videoslider_videos`
                WHERE `id_video` = ' . (int)$id_video
            );

            die(json_encode([
                'success' => $result,
                'message' => $result
                    ? $this->trans('Video deleted successfully', [], 'Admin.Notifications.Success')
                    : $this->trans('Error deleting video', [], 'Admin.Notifications.Error')
            ]));
        }

        die(json_encode([
            'success' => false,
            'message' => $this->trans('Video not found', [], 'Admin.Notifications.Error')
        ]));
    }

    public function ajaxProcessUpdateVideoPositions()
    {
        header('Content-Type: application/json');

        $positions = Tools::getValue('positions');
        if (!is_array($positions)) {
            die(json_encode([
                'success' => false,
                'message' => $this->trans('Invalid positions data', [], 'Admin.Notifications.Error')
            ]));
        }

        $success = true;
        foreach ($positions as $index => $id_video) {
            $success &= Db::getInstance()->update(
                'bs_videoslider_videos',
                [
                    'position' => (int)$index,
                    'date_upd' => $this->currentDateTime
                ],
                'id_video = ' . (int)$id_video
            );
        }

        die(json_encode([
            'success' => $success,
            'message' => $success
                ? $this->trans('Positions updated successfully', [], 'Admin.Notifications.Success')
                : $this->trans('Error updating positions', [], 'Admin.Notifications.Error')
        ]));
    }

    protected function displayError($message, $description = false)
    {
        $this->errors[] = $this->trans($message, [], 'Admin.Notifications.Error');
        if ($description) {
            $this->errors[] = $this->trans($description, [], 'Admin.Notifications.Error');
        }
    }

    protected function clearCache()
    {
        if (isset($this->module)) {
            $this->module->_clearCache('bs_videoslider.tpl');
        }
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAdd' . $this->table) || Tools::isSubmit('submitAdd' . $this->table . 'AndStay')) {
            $this->clearCache();
        }

        return parent::postProcess();
    }

    public function getPositionName($position)
    {
        $positions = [
            'displayHome' => $this->trans('Home', [], 'Admin.Global'),
            'displayLeftColumn' => $this->trans('Left Column', [], 'Admin.Global'),
            'displayRightColumn' => $this->trans('Right Column', [], 'Admin.Global'),
            'displayFooter' => $this->trans('Footer', [], 'Admin.Global')
        ];

        return isset($positions[$position]) ? $positions[$position] : $position;
    }
}