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

class AdminSpringxbsTrackingController extends ModuleAdminController
{

    /**
     * AdminSpringxbsApiController constructor.
     * @throws PrestaShopException
     */
    public function __construct()
    {
        parent::__construct();

        if (Tools::getValue('token') != SpringXbsHelper::get(Springxbs::EXECUTION_TOKEN) &&
            Tools::getValue('token') != SpringXbsHelper::get(Springxbs::MODULE_API_KEY_NAME)) {
            die('Invalid token');
        }


        if (Tools::getValue('do') == 'print_label') {
            if (Tools::getValue('id_order') && Tools::getValue('label_format') && Tools::getValue('hash') &&
                Tools::getValue('id_order') == SpringXbsHelper::encryptDecrypt('decrypt', Tools::getValue('hash'))) {
                $this->printLabel(Tools::getValue('id_order'), Tools::getValue('label_format'));
            } else {
                die(Springxbs::$self_name . ': permission denied');
            }
        }

        if (!SpringXbsHelper::get(Springxbs::MODULE_ENABLE_EXTERNAL_TRACKING)) {
            die(Springxbs::$self_name . ': tracking disabled by configuration');
        }

        set_time_limit(600);
        $this->labelsProcess();

        die(Springxbs::$self_name . '_success');
    }

    /**
     * @return bool|ObjectModel|void
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function labelsProcess()
    {
        $api_controller = new AdminSpringxbsApiController();
        try {
            $api_controller->trackLabels();
        } catch (Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }

    /**
     * Get data to print by thermal printer: epl zpl
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function printLabel($id_order, $label_format)
    {
        $label = Db::getInstance()->getValue("
            SELECT label
            FROM " . _DB_PREFIX_ . "springxbs_shipment 
            WHERE id_order = '" . pSQL($id_order) . "' 
            AND label_format = '" . pSQL($label_format) . "'");

        if ($label) {
            echo SpringXbsHelper::decodeData($label);
        } else {
            echo 'The order does not have a label or the label has a different format';
        }

        exit();
    }
}
