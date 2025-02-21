<?php

/**
 * @author    BizoSizco <info@bizosiz.com>
 * @copyright 2025 BizoSizco
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 * Last updated: 2025-02-21 01:13:55 by BizoSizco
 */

$sql = [];

// Create main slider table
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'bs_videoslider` (
    `id_slider` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `position` varchar(255) NOT NULL,
    `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
    `slides_desktop` int(10) unsigned NOT NULL DEFAULT 4,
    `slides_tablet` int(10) unsigned NOT NULL DEFAULT 3,
    `slides_mobile` int(10) unsigned NOT NULL DEFAULT 2,
    `autoplay` tinyint(1) unsigned NOT NULL DEFAULT 1,
    `autoplay_speed` int(10) unsigned NOT NULL DEFAULT 3000,
    `infinite` tinyint(1) unsigned NOT NULL DEFAULT 1,
    `dots` tinyint(1) unsigned NOT NULL DEFAULT 1,
    `arrows` tinyint(1) unsigned NOT NULL DEFAULT 1,
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    PRIMARY KEY (`id_slider`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

// Create videos table
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'bs_videoslider_videos` (
    `id_video` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `id_slider` int(10) unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `image` varchar(255) DEFAULT NULL,
    `video` text NOT NULL,
    `position` int(10) unsigned NOT NULL DEFAULT 0,
    `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    PRIMARY KEY (`id_video`),
    KEY `id_slider` (`id_slider`),
    KEY `position` (`position`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

// Add foreign key constraints
$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'bs_videoslider_videos`
    ADD CONSTRAINT `' . _DB_PREFIX_ . 'bs_videoslider_videos_ibfk_1`
    FOREIGN KEY (`id_slider`)
    REFERENCES `' . _DB_PREFIX_ . 'bs_videoslider` (`id_slider`)
    ON DELETE CASCADE;';

// Create indexes for better performance
$sql[] = 'CREATE INDEX `active_position` ON `' . _DB_PREFIX_ . 'bs_videoslider_videos` (`active`, `position`);';
$sql[] = 'CREATE INDEX `slider_active` ON `' . _DB_PREFIX_ . 'bs_videoslider` (`active`);';

// Create directory structure
$directories = [
    _PS_MODULE_DIR_ . 'bs_videoslider/',
    _PS_MODULE_DIR_ . 'bs_videoslider/views/',
    _PS_MODULE_DIR_ . 'bs_videoslider/views/css/',
    _PS_MODULE_DIR_ . 'bs_videoslider/views/js/',
    _PS_MODULE_DIR_ . 'bs_videoslider/views/img/',
    _PS_MODULE_DIR_ . 'bs_videoslider/views/templates/',
    _PS_MODULE_DIR_ . 'bs_videoslider/views/templates/admin/',
    _PS_MODULE_DIR_ . 'bs_videoslider/views/templates/hook/',
    _PS_MODULE_DIR_ . 'bs_videoslider/controllers/',
    _PS_MODULE_DIR_ . 'bs_videoslider/controllers/admin/',
    _PS_MODULE_DIR_ . 'bs_videoslider/classes/',
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        @mkdir($dir, 0777, true);
        @chmod($dir, 0777);
    }
}

// Create .htaccess files for security
$htaccess_content = "# Apache 2.2
<IfModule !mod_authz_core.c>
    Order deny,allow
    Deny from all
    <Files ~ \"(?i)^.*\.(jpg|jpeg|gif|png|bmp|tiff|svg|pdf|mov|mpeg|mp4|avi|mpg|wma|flv|webm|ico|css|js)$\">
        Allow from all
    </Files>
</IfModule>

# Apache 2.4
<IfModule mod_authz_core.c>
    Require all denied
    <Files ~ \"(?i)^.*\.(jpg|jpeg|gif|png|bmp|tiff|svg|pdf|mov|mpeg|mp4|avi|mpg|wma|flv|webm|ico|css|js)$\">
        Require all granted
    </Files>
</IfModule>

# Prevent directory listings
Options -Indexes

# Prevent execution of PHP files
<FilesMatch \".+\.ph(p[3457]?|t|tml|ps)\">
    Require all denied
</FilesMatch>

# Block sensitive files
<FilesMatch \"(?i)((^\.|/\.|~|\.(bak|config|dist|fla|inc|ini|log|psd|sh|sql|swp)|composer\.(json|lock))$)\">
    Require all denied
</FilesMatch>

# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Enable browser caching
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg \"access plus 1 year\"
    ExpiresByType image/jpeg \"access plus 1 year\"
    ExpiresByType image/gif \"access plus 1 year\"
    ExpiresByType image/png \"access plus 1 year\"
    ExpiresByType text/css \"access plus 1 month\"
    ExpiresByType application/javascript \"access plus 1 month\"
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options \"nosniff\"
    Header set X-Frame-Options \"SAMEORIGIN\"
    Header set X-XSS-Protection \"1; mode=block\"
    Header set Referrer-Policy \"strict-origin-when-cross-origin\"
</IfModule>";

$htaccess_file = _PS_MODULE_DIR_ . 'bs_videoslider/.htaccess';
if (!file_exists($htaccess_file)) {
    @file_put_contents($htaccess_file, $htaccess_content);
    @chmod($htaccess_file, 0644);
}

// Create index.php files
$index_content = "<?php
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Location: ../');
exit;";

foreach ($directories as $dir) {
    $index_file = $dir . 'index.php';
    if (!file_exists($index_file)) {
        @file_put_contents($index_file, $index_content);
        @chmod($index_file, 0644);
    }
}

// Log installation
PrestaShopLogger::addLog(
    sprintf('BsVideoSlider module installed by %s', 'BizoSizco'),
    1,
    null,
    'BsVideoSlider',
    1,
    true
);

// Return true if all queries executed successfully
foreach ($sql as $query) {
    if (!Db::getInstance()->execute($query)) {
        return false;
    }
}

return true;
