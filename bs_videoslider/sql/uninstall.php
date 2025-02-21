<?php

/**
 * @author    BizoSizco <info@bizosiz.com>
 * @copyright 2025 BizoSizco
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

// Just log that module was uninstalled but data was preserved
PrestaShopLogger::addLog(
    'BsVideoSlider module uninstalled - all data preserved',
    1,
    null,
    'BsVideoSlider',
    1,
    true
);

return true;
