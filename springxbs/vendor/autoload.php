<?php
/**
 * 2019 Xmlshop LLC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author Xmlshop LLC <tsuren@xmlshop.com>
 * @copyright  PostNL
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @version  1.3.3
 */


if (!defined('_PS_VERSION_')) {
    return;
}

spl_autoload_register(function ($class_name) {
    $filename = dirname(__FILE__) . "/../classes/$class_name.php";
    if (file_exists($filename)) {
        require_once $filename;
        return true;
    }
    $filename = dirname(__FILE__) . "/../controllers/admin/$class_name.php";
    if (file_exists($filename)) {
        require_once $filename;
        return true;
    }
    $class_name_lower = Tools::strtolower($class_name);
    $filename = _PS_MODULE_DIR_ . "$class_name_lower/$class_name_lower.php";
    if (file_exists($filename)) {
        require_once $filename;
        return true;
    }
    return false;
});