<?php
/**
* 2025 BizoSizco
*
* @author    BizoSizco <info@bizosiz.com>
* @copyright 2025 BizoSizco
* @license   https://opensource.org/licenses/AFL-3.0  Academic Free License version 3.0
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class BsVideoSliderVideo extends ObjectModel
{
    public $id_video;
    public $title;
    public $description;
    public $url;
    public $type;
    public $thumbnail;
    public $position;
    public $active;
    public $date_add;
    public $date_upd;

    public static $definition = [
        'table' => 'bs_videoslider_video',
        'primary' => 'id_video',
        'multilang' => false,
        'fields' => [
            'title' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size' => 255
            ],
            'description' => [
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHtml',
                'size' => 4000
            ],
            'url' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isUrl',
                'required' => true,
                'size' => 255
            ],
            'type' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size' => 50
            ],
            'thumbnail' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'size' => 255
            ],
            'position' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ],
            'active' => [
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ],
            'date_add' => [
                'type' => self::TYPE_DATE,
                'validate' => 'isDate'
            ],
            'date_upd' => [
                'type' => self::TYPE_DATE,
                'validate' => 'isDate'
            ]
        ]
    ];

    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function add($autodate = true, $null_values = false)
    {
        if ($autodate && property_exists($this, 'date_add')) {
            $this->date_add = date('Y-m-d H:i:s');
        }
        if ($autodate && property_exists($this, 'date_upd')) {
            $this->date_upd = date('Y-m-d H:i:s');
        }

        if (!isset($this->position) || !$this->position) {
            $this->position = self::getHighestPosition() + 1;
        }

        return parent::add($autodate, $null_values);
    }

    public function update($null_values = false)
    {
        if (property_exists($this, 'date_upd')) {
            $this->date_upd = date('Y-m-d H:i:s');
        }

        return parent::update($null_values);
    }

    public static function getHighestPosition()
    {
        $sql = new DbQuery();
        $sql->select('MAX(position)');
        $sql->from('bs_videoslider_video');
        
        return (int)Db::getInstance()->getValue($sql);
    }

    public function updatePosition($way, $position)
    {
        $query = '
            UPDATE `'._DB_PREFIX_.'bs_videoslider_video`
            SET `position` = `position` '.($way ? '- 1' : '+ 1').'
            WHERE `position` '.($way ? '> '.($position - 1) : '< '.($position + 1));

        return Db::getInstance()->execute($query);
    }

    public static function getVideosByType($type, $active = true, $limit = null)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('bs_videoslider_video');
        $sql->where('type = "'.pSQL($type).'"');
        
        if ($active) {
            $sql->where('active = 1');
        }
        
        $sql->orderBy('position ASC');
        
        if ($limit) {
            $sql->limit((int)$limit);
        }

        return Db::getInstance()->executeS($sql);
    }

    public function getVideoEmbedCode()
    {
        switch ($this->type) {
            case 'aparat':
                return $this->getAparatEmbedCode();
            case 'direct':
                return $this->getDirectVideoCode();
            case 'iframe':
                return $this->getIframeCode();
            default:
                return '';
        }
    }

    protected function getAparatEmbedCode()
    {
        $video_id = $this->getAparatVideoId();
        if (!$video_id) {
            return '';
        }

        return '<iframe 
                src="https://www.aparat.com/video/video/embed/videohash/'.$video_id.'/vt/frame" 
                allowFullscreen="true" 
                webkitallowfullscreen="true" 
                mozallowfullscreen="true">
                </iframe>';
    }

    protected function getDirectVideoCode()
    {
        return '<video controls>
                <source src="'.htmlspecialchars($this->url).'" type="video/mp4">
                Your browser does not support the video tag.
                </video>';
    }

    protected function getIframeCode()
    {
        return '<iframe 
                src="'.htmlspecialchars($this->url).'" 
                allowfullscreen="true" 
                webkitallowfullscreen="true" 
                mozallowfullscreen="true">
                </iframe>';
    }

    protected function getAparatVideoId()
    {
        $url = $this->url;
        
        // Extract video ID from URL
        if (preg_match('/(?:\/v\/|\/video\/video\/embed\/videohash\/|\/video\/)([^\/\s?&]+)/', $url, $matches)) {
            return $matches[1];
        }
        
        return false;
    }

    public function getThumbnailUrl()
    {
        if ($this->thumbnail) {
            return _PS_MODULE_DIR_.'bs_videoslider/views/img/thumbnails/'.$this->thumbnail;
        }
        return false;
    }

    public static function createTables()
    {
        $sql = [];
        
        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'bs_videoslider_video` (
            `id_video` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `description` text,
            `url` varchar(255) NOT NULL,
            `type` varchar(50) NOT NULL,
            `thumbnail` varchar(255),
            `position` int(10) unsigned NOT NULL DEFAULT 0,
            `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NOT NULL,
            PRIMARY KEY (`id_video`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

        foreach ($sql as $query) {
            if (!Db::getInstance()->execute($query)) {
                return false;
            }
        }

        return true;
    }
    public static function dropTables()
    {
        $sql = [];

        $sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'bs_videoslider_video`;';

        foreach ($sql as $query) {
            if (!Db::getInstance()->execute($query)) {
                return false;
            }
        }

        return true;
    }
}