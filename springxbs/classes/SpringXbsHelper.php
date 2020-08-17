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

class SpringXbsHelper
{
    const NAMEL = "springxbs";
    const MSG_TYPE_ERROR = 'error';
    const MSG_TYPE_OK = 'ok';

    protected static $session = null;

    private static $v16plus = null;

    /**
     * Url to string
     *
     * @param $parsed_url
     * @return string
     */
    public static function stringifyUrl($parsed_url)
    {
        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    /**
     * Decode data from API requests
     *
     * @param $str
     * @return bool|string
     */
    public static function decodeData($str)
    {
        $subst_table =
            "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";

        $str = rtrim($str, '=');
        $length = Tools::strlen($str);

        $res = "";
        $i = 0;
        try {
            while ($i < $length) {
                $symb = array();

                for ($j = 0; $j < 4; $j++, $i++) {
                    if ($i >= $length) {
                        break;
                    }

                    $pos = strpos($subst_table, $str[$i]);
                    if ($pos === false) {
                        return false;
                    }
                    $symb[$i % 4] = $pos;
                }

                $res .= chr(($symb[0] << 2) | ($symb[1] >> 4));
                if (isset($symb[2])) {
                    $res .= chr(($symb[1] << 4) | ($symb[2] >> 2));
                }
                if (isset($symb[3])) {
                    $res .= chr(($symb[2] << 6) | $symb[3]);
                }
            }
        } catch (Exception $e) {
            $res = '';
        }
        return $res;
    }

    public static function encodeData($string = '')
    {
        $binval = self::convertBinaryStr($string);
        $final = "";
        $start = 0;
        while ($start < Tools::strlen($binval)) {
            if (Tools::strlen(Tools::substr($binval, $start)) < 6) {
                $binval .= str_repeat("0", 6 - Tools::strlen(Tools::substr($binval, $start)));
            }
            $tmp = bindec(Tools::substr($binval, $start, 6));
            if ($tmp < 26) {
                $final .= chr($tmp + 65);
            } elseif ($tmp > 25 && $tmp < 52) {
                $final .= chr($tmp + 71);
            } elseif ($tmp == 62) {
                $final .= "+";
            } elseif ($tmp == 63) {
                $final .= "/";
            } elseif (!$tmp) {
                $final .= "A";
            } else {
                $final .= chr($tmp - 4);
            }
            $start += 6;
        }
        if (Tools::strlen($final) % 4 > 0) {
            $final .= str_repeat("=", 4 - Tools::strlen($final) % 4);
        }
        return $final;
    }

    public static function convertBinaryStr($string)
    {
        if (Tools::strlen($string) <= 0) {
            return;
        }
        $tmp = decbin(ord($string[0]));
        $tmp = str_repeat("0", 8 - Tools::strlen($tmp)) . $tmp;
        return $tmp . self::convertBinaryStr(Tools::substr($string, 1));
    }

    public static function encryptDecrypt($action, $string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'ght-yju785bnmreuwehfowaephfowfeh';
        $secret_iv = '6544136482364187643';
        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = Tools::substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = self::encodeData($output);
        } elseif ($action == 'decrypt') {
            $output = openssl_decrypt(self::decodeData($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    /**
     * Translator alias
     *
     * @param $string string to translate
     * @return mixed|string
     */
    public static function l($string)
    {
        $module = Module::getInstanceByName(Springxbs::$self_name);

        return $module->l($string);
    }

    /**
     * get session storage
     *
     * @return SessionHelper|\Symfony\Component\HttpFoundation\Session\Session|null
     */
    public static function getSession()
    {
        if (!self::$session) {
            self::$session = class_exists('\Symfony\Component\HttpFoundation\Session\Session')
                ? new \Symfony\Component\HttpFoundation\Session\Session()
                : new SessionHelper();
        }
        return self::$session;
    }

    /**
     * Add to the storage of deferred messages
     *
     * @param $msg
     * @param $type
     */
    public static function setNotification($msg, $type = self::MSG_TYPE_OK)
    {
        $session = self::getSession();

        $data = $session->get(self::NAMEL, array());
        $data['notifications'][$type][] = self::l($msg);
        $session->set(self::NAMEL, $data);
    }

    /**
     * Get from the storage of deferred messages
     *
     * @return string
     */
    public static function callNotifications()
    {
        $session = self::getSession();

        $output = '';
        $data = $session->get(self::NAMEL, array());

        if (!empty($data['notifications'])) {
            foreach ($data['notifications'] as $type => $msgs) {
                if ($type == self::MSG_TYPE_OK) {
                    foreach ($msgs as $msg) {
                        $output .= self::displayConfirmation(self::l($msg));
                    }
                } elseif ($type == self::MSG_TYPE_ERROR) {
                    foreach ($msgs as $msg) {
                        $output .= self::displayError(self::l($msg));
                    }
                }
            }

            $data['notifications'] = array();
        }

        $session->set(self::NAMEL, $data);

        return $output;
    }

    /**
     * Helper displaying confirmation message.
     *
     * @param string|array $messages
     *
     * @return string
     */
    public static function displayConfirmation($messages)
    {
        return self::showNotifications('success', $messages);
    }

    /**
     * Helper displaying error message(s).
     *
     * @param string|array $errors
     *
     * @return string
     */
    public static function displayError($errors)
    {
        return self::showNotifications('error', $errors);
    }

    /**
     * Helper displaying warning message(s).
     *
     * @param string|array $warnings
     *
     * @return string
     */
    public static function displayWarning($warnings)
    {
        return self::showNotifications('warning', $warnings);
    }

    /**
     * Helper displaying information message(s).
     *
     * @param string|array $notes
     *
     * @return string
     */
    public static function displayInformation($notes)
    {
        return self::showNotifications('info', $notes);
    }

    /**
     * Helper displaying message(s).
     *
     * @param $type
     * @param $messages
     * @return string
     */
    private static function showNotifications($type, $messages)
    {
        switch ($type) {
            case 'success':
                $class_str = 'module_confirmation conf confirm alert alert-success';
                break;
            case 'info':
                $class_str = 'module_info info alert alert-info';
                break;
            case 'warning':
                $class_str = 'module_warning alert alert-warning';
                break;
            case 'error':
                $class_str = 'module_error alert alert-danger';
                break;
            default:
                $class_str = false;
                break;
        }

        if (!$class_str) {
            return '';
        }

        if (!is_array($messages)) {
            $messages = array($messages);
        }

        foreach ($messages as &$message) {
            $message = Springxbs::$self_display_name . ": " . self::l($message);
        }

        unset($message);
        Context::getContext()->smarty->assign(array(
            'class_str' => $class_str,
            'messages' => $messages,
        ));
        $module = Module::getInstanceByName(Springxbs::$self_name);

        return $module->display(Springxbs::$self_name, 'views/templates/admin/helper/notifications.tpl');
    }

    /**
     * For PrestaShop v1.5.x.x & v1.6.x.x
     *
     * @param $module_instance
     * @param $hook_name
     * @param $id_shop
     * @return bool
     * @throws PrestaShopDatabaseException
     */
    public static function hookExists($module_instance, $hook_name, $id_shop)
    {
        $prefix = _DB_PREFIX_;
        $id_hook = (int) Hook::getIdByName($hook_name);
        $id_shop = (int) $id_shop;
        $id_module = (int) $module_instance->id;

        $sql = "SELECT * FROM {$prefix}hook_module
                  WHERE `id_hook` = {$id_hook}
                  AND `id_module` = {$id_module}
                  AND `id_shop` = {$id_shop}";

        $rows = Db::getInstance()->executeS($sql);

        return !empty($rows);
    }

    /**
     * For PrestaShop v1.5.x.x & v1.6.x.x & 1.7.x.x
     *
     * @param object|int $order
     * @param $shipping_number
     * @return bool
     * @throws PrestaShopException
     */
    public static function setWsShippingNumber($order, $shipping_number)
    {
        if (!Validate::isLoadedObject($order)) {
            if (!is_numeric($order)) {
                return false;
            }
            $order = new Order($order);
        }
        if (method_exists($order, 'setWsShippingNumber')) {
            $order->setWsShippingNumber($shipping_number);
        } else {
            $id_order_carrier = Db::getInstance()->getValue('
            SELECT `id_order_carrier`
            FROM `' . _DB_PREFIX_ . 'order_carrier`
            WHERE `id_order` = ' . (int) $order->id);
            if ($id_order_carrier) {
                $order_carrier = new OrderCarrier($id_order_carrier);
                $order_carrier->tracking_number = $shipping_number;
                $order_carrier->update();
            } else {
                $order->shipping_number = $shipping_number;
            }
        }

        return true;
    }

    /**
     * For PrestaShop v1.5.x.x & v1.6.x.x
     *
     * @return string
     */
    public static function getAdmBaseLink()
    {
        $ssl = SpringXbsHelper::get('PS_SSL_ENABLED') && SpringXbsHelper::get('PS_SSL_ENABLED_EVERYWHERE');
        $shop = Context::getContext()->shop;

        $admDir = explode(DIRECTORY_SEPARATOR, _PS_ADMIN_DIR_);

        return ($ssl ? 'https://' . $shop->domain_ssl : 'http://' . $shop->domain) . "/"
            . end($admDir) . "/";
    }

    public static function getIdOrderCarrier($id)
    {
        return (int) Db::getInstance()->getValue('
                SELECT `id_order_carrier`
                FROM `' . _DB_PREFIX_ . 'order_carrier`
                WHERE `id_order` = ' . (int) $id);
    }

    /**
     * @param $key
     * @param null $id_lang
     * @param null $id_shop_group
     * @param null $id_shop
     * @return string
     */
    public static function get($key, $id_lang = null, $id_shop_group = null, $id_shop = null)
    {
        return Configuration::get(self::keyToVersion($key), $id_lang, $id_shop_group, $id_shop);
    }

    /**
     * @param $key
     * @param $values
     * @param bool $html
     * @param null $id_shop_group
     * @param null $id_shop
     * @return bool
     */
    public static function updateValue($key, $values, $html = false, $id_shop_group = null, $id_shop = null)
    {
        return Configuration::updateValue(self::keyToVersion($key), $values, $html, $id_shop_group, $id_shop);
    }

    /**
     * @param $key
     * @param $values
     * @param null $id_shop_group
     * @param null $id_shop
     */
    public static function set($key, $values, $id_shop_group = null, $id_shop = null)
    {
        return Configuration::set(self::keyToVersion($key), $values, $id_shop_group, $id_shop);
    }

    /**
     * @param $key
     * @param $values
     * @param bool $html
     * @return bool
     */
    public static function updateGlobalValue($key, $values, $html = false)
    {
        return Configuration::updateGlobalValue(self::keyToVersion($key), $values, $html);
    }

    /**
     * @param $key
     * @return bool
     */
    public static function deleteByName($key)
    {
        return Configuration::deleteByName(self::keyToVersion($key));
    }

    /**
     * @param $key
     * @return bool|string
     */
    public static function keyToVersion($key)
    {
        if (self::$v16plus === null) {
            $v = explode('.', _PS_VERSION_);
            self::$v16plus = $v[0] > 1 || $v[0] == 1 && $v[1] > 5;
        }
        if (!self::$v16plus) {
            return Tools::substr($key, 0, 32);
        }
        return $key;
    }
}
