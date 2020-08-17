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

require_once dirname(__FILE__) . '/vendor/autoload.php';

class Springxbs extends Module
{
    const API_ACCESS_POINT_LIVE = "https://mtapi.net/";
    const API_ACCESS_POINT_TEST = "https://mtapi.net/?testMode=1";

    const API_ACCESS_POINT_COMMUNICATE = "https://mailingtechnology.com/prestashop/presta.php";
    const IMG_URL = "https://mailingtechnology.com/prestashop/presta.php";

    const SPRING_TRACKING_URL = 'https://mailingtechnology.com/tracking/?tn=';
    const SPRING_TRACKING_URL_CARRIER = 'https://mailingtechnology.com/tracking/?tn=@';

    const API_SPRING_SIDE_USER_ID = "SPRINGXBS_API_SIDE_USER_ID";
    const API_SPRING_TRACK_LABELS_STAMP = "SPRINGXBS_API_SPRING_TRACK_LABELS_STAMP";
    const API_SPRING_TRACK_DELAY = 890;

    const API_ERROR_LEVEL_FAILURE = 100;

    const CARRIER_ID_SERVICE_CODE = 'SPRINGXBS_CARRIER_ID_SERVICE_CODE';

    const IS_API_SETTINGS_TAB = 'IS_SPRINGXBS_API_SET';

    const IS_LABELS_SETTINGS_TAB = 'IS_SPRINGXBS_LABELS_SET';
    const IS_NOTIFICATIONS_SETTINGS_TAB = 'IS_SPRINGXBS_NOTIFICATIONS_SET';
    const IS_ADVANCED_SETTINGS_TAB = 'IS_SPRINGXBS_ADVANCED_SET';
    const IS_CONSIGNOR_SETTINGS_TAB = 'IS_SPRINGXBS_CONSIGNOR_SET';

    const MODULE_API_KEY_NAME = 'SPRINGXBS_API_KEY';
    const DEFAULT_HS_CODE = 'SPRINGXBS_DEFAULT_HS_CODE';

    const DEFAULT_DECLARATION_TYPE = 'SPRINGXBS_DEFAULT_DECLARATION_TYPE';
    const DEFAULT_DECLARATION_VALUE = 'SaleOfGoods';
    public static $declaration_types = array(
        array('id' => 'SaleOfGoods', 'name' => 'Sale Of Goods'),
        array('id' => 'Documents', 'name' => 'Documents'),
        array('id' => 'Gift', 'name' => 'Gift'),
        array('id' => 'ReturnedGoods', 'name' => 'Returned Goods'),
        array('id' => 'CommercialSample', 'name' => 'Commercial Sample')
    );

    const MODULE_TEST_MODE_NAME = 'SPRINGXBS_TEST_MODE';
    const MODULE_ENABLE_EXTERNAL_TRACKING = 'SPRINGXBS_ENABLE_EXTERNAL_TRACK';
    const LABEL_FORMAT = 'SPRINGXBS_LABEL_FORMAT';
    const DEFAULT_LABEL_FORMAT = 'PDF';

    protected $label_formats = array(
        array(
            'id' => 'PDF',
            'name' => 'PDF',
        ), array(
            'id' => 'ZPL200',
            'name' => 'ZPL200',
        ), array(
            'id' => 'ZPL300',
            'name' => 'ZPL300',
        ), array(
            'id' => 'PNG',
            'name' => 'PNG',
        ), array(
            'id' => 'EPL',
            'name' => 'EPL',
        ),
    );

    public static $label_formats_list = array('PDF', 'ZPL200', 'PNG', 'ZPL300', 'EPL');

    const SPRINGXBS_TO_SHOP_STATUS_MAP = 'SPRINGXBS_TO_SHOP_STATUS_MAP';
    const SPRINGXBS_TO_SHOP_STATUS_MAP_SAVED = 'SPRINGXBS_TO_SHOP_STATUS_MAP_USER_SAVED';

    const EMAIL_NOTIFICATIONS = 'SPRINGXBS_EMAIL_NOTIFICATIONS';

    const MODULE_REMOVE_DATA_WHILE_UNINSTALL = 'SPRINGXBS_REMOVE_DATA_WHILE_UNINSTALL';

    const API_LOGGER_ENABLED = 'SPRINGXBS_API_LOGGER_ENABLED';

    const SHIPPER_COMPANY_NAME = 'SPRINGXBS_SHIPPER_COMPANY_NAME';

    const EXECUTION_TOKEN = 'SPRINGXBS_EXECUTION_TOKEN';

    public $hooks = array(
        'displayAdminOrder',
        'actionAdminOrdersListingFieldsModifier',
        'actionEmailSendBefore',
        'displayAfterCarrier',
        'displayCarrierList',
        'displayAdminListAfter',
    );

    protected $order_statuses;

    protected $base_url;

    protected $base_url_without_token;

    const SPRINGXBS_CARRIERS_SERVICE_2_NAME = "SPRINGXBS_CARRIER_SERVICE_2_NAME";
    const SPRINGXBS_CARRIERS_SERVICES_NAMES = "SPRINGXBS_CARRIERS_SERVICE_NAMES";

    protected $carrier_id_service_code;

    const TRACKING_EVENTS = "SPRINGXBS_TRACKING_EVENTS";
    protected $tracking_events = array();
    protected $default_tracking_events = array(
        //-100 => 'LABEL CANCELLED',
        0 => 'PARCEL CREATED',
        20 => 'ACCEPTED',
        //21 => 'IN TRANSIT',
        100 => 'DELIVERED',
    );

    public static $self_name = 'springxbs';

    public static $self_display_name = 'Spring GDS';

    private $install_verified = true;

    const CUSTOM_COUNTER = "SPRINGXBS_CUSTOM_COUNTER";

    public $v;

    public $pisv15;
    public $pisv16;
    public $pisv17;

    public $v16plus;
    public $v17plus;

    /**
     * Springxbs constructor.
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws ReflectionException
     */
    public function __construct()
    {
        $this->name = 'springxbs';
        $this->tab = 'shipping_logistics';
        $this->version = '1.3.3';
        $this->author = 'Spring GDS';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->module_key = '97579987e6bb59c24dd2c180acee1c47';
        $this->v = explode('.', _PS_VERSION_);
        $this->v16plus = $this->v[0] > 1 || $this->v[0] == 1 && $this->v[1] > 5;
        $this->v17plus = $this->v[0] > 1 || $this->v[0] == 1 && $this->v[1] > 6;

        $this->pisv15 = $this->v[0] == 1 && $this->v[1] == 5;
        $this->pisv16 = $this->v[0] == 1 && $this->v[1] == 6;
        $this->pisv17 = $this->v[0] == 1 && $this->v[1] == 7;

        parent::__construct();

        $this->readyMakeSure();

        if (!empty(Context::getContext()->employee->id)) {
            $this->base_url_without_token = $this->getAdminLink(
                'AdminModules',
                false,
                array(
                    'configure' => $this->name,
                    'tab_module' => $this->tab,
                    'module_name' => $this->name,
                )
            );
            $this->base_url = $this->getAdminLink(
                'AdminModules',
                true,
                array(
                    'configure' => $this->name,
                    'tab_module' => $this->tab,
                    'module_name' => $this->name,
                )
            );
        }

        $this->displayName = $this->l('Spring GDS');
        $this->description = $this->l('Shipping with Spring GDS');

        $this->ps_versions_compliancy = array('min' => '1.5.1.0', 'max' => _PS_VERSION_);
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (defined("_PS_MODE_DEV_") &&
            _PS_MODE_DEV_ === true &&
            method_exists(new Configuration, 'clearConfigurationCacheForTesting')) {
            Configuration::clearConfigurationCacheForTesting();
        }

        $this->order_statuses = OrderState::getOrderStates($this->context->language->id);
        $this->carrier_id_service_code = self::getCarrierServiceCodes();
        $this->tracking_events = json_decode(SpringXbsHelper::get(self::TRACKING_EVENTS), true);

        if (!$this->tracking_events) {
            $this->tracking_events = $this->default_tracking_events;
        }

        foreach ($this->tracking_events as &$event) {
            $event = $this->l($event);
        }

        unset($event);
    }

    /**
     * Get an array of service codes to their names from the configuration,
     * or default if not set
     *
     * @return array
     */
    public static function getServicesNames()
    {
        $services_names = json_decode(SpringXbsHelper::get(self::SPRINGXBS_CARRIERS_SERVICE_2_NAME), true);
        if (!$services_names) {
            $services_names = array(
                'TRCK' => 'Spring GDS - TRACKED',
                'SIGN' => 'Spring GDS - SIGNATURED',
                'UNTR' => 'Spring GDS - UNTRACKED',
            );
        }
        return $services_names;
    }

    /**
     * Get mapping from configuration
     *
     * @return mixed
     */
    public static function getCarrierServiceCodes()
    {
        $carrier_codes = json_decode(SpringXbsHelper::get(Springxbs::CARRIER_ID_SERVICE_CODE), true);

        if (!$carrier_codes) {
            $carrier_codes = array();

            foreach (array_keys(self::getServicesNames()) as $service_code) {
                $carrier = Carrier::getCarrierByReference(SpringXbsHelper::get(self::$self_name . "_" . $service_code));

                if (isset($carrier->id_reference)) {
                    $carrier_codes[$carrier->id_reference] = $service_code;
                }
            }

            SpringXbsHelper::updateValue(self::CARRIER_ID_SERVICE_CODE, json_encode($carrier_codes));
        }

        return $carrier_codes;
    }

    /**
     * @return array
     * @throws PrestaShopException
     */
    public static function getTrackingEvents()
    {
        $self = Module::getInstanceByName(self::$self_name);

        return $self->tracking_events;
    }

    /**
     * Get array of statuses mapping from the configuration
     *
     * @return array
     */
    public static function getStatusesMap()
    {
        return (array)json_decode(SpringXbsHelper::get(self::SPRINGXBS_TO_SHOP_STATUS_MAP), true);
    }

    /**
     * Get the ajax link with the current token to one of the AdminSpringxbsApi controller methods
     *
     * @param bool $id_order
     * @param bool $action
     * @return array|string
     * @throws PrestaShopException
     */
    public static function getApiAjaxLink($id_order = false, $action = false)
    {
        $params = array(
            'ajax' => '1',
        );

        if ($id_order !== false) {
            $params['id_order'] = (int)$id_order;
        }

        if ($action !== false) {
            $params['action'] = $action;
        }

        $self = Module::getInstanceByName(self::$self_name);

        return static::appendQueryToUrl($self->context->link->getAdminLink('AdminSpringxbsApi'), $params);
    }

    /**
     * Get a link to print zpl label
     *
     * @param $id_order
     * @return string
     * @throws PrestaShopException
     */
    public static function getZprnLabelFileLink($filename)
    {
        $self = Module::getInstanceByName(self::$self_name);
        $url = static::appendQueryToUrl(
            $self->context->link->getBaseLink(''),
            array(''),
            true
        );
        $_url = array();
        $_url['scheme'] = 'zprn';
        $_url['host'] = "uvw|$url[host]|";
        $_url['path'] = "/modules/$filename";

        return SpringXbsHelper::stringifyUrl($_url);
    }

    /**
     * Get a link to the form to set/change a carrier
     *
     * @param $id_order
     * @return string
     * @throws PrestaShopException
     */
    public static function getSetCarrierLink($id_order)
    {
        $id_order = (int)$id_order;
        $self = Module::getInstanceByName(self::$self_name);

        return static::appendQueryToUrl($self->context->link->getAdminLink('AdminOrders'), array(
            'id_order' => $id_order,
        ));
    }

    /**
     * E-mail send in depending on the module configuration
     *
     * @param $email
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function hookActionEmailSendBefore($email)
    {
        if (SpringXbsHelper::get(self::EMAIL_NOTIFICATIONS)) {
            return true;
        }

        $id_cart = Order::getIdByCartId($email['cart']->id);

        if (!$id_cart) {
            return true;
        }

        $order = new Order($id_cart);
        $carrier = new Carrier($order->id_carrier);
        $carriers_codes = self::getCarrierServiceCodes();

        if ($carriers_codes[$carrier->id_reference]) {
            return false;
        }

        return true;
    }

    /**
     * get Pre filled Parcel Dimensions
     *
     * @param Order $order
     * @return array
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public static function getPreFilledParcelDimensions(Order $order)
    {
        $ordered_products = array_filter((array) $order->getProducts());
        $parcel_prefilled_dimensions = array('width' => 0, 'height' => 0, 'depth' => 0, 'weight' => 0);
        foreach ($ordered_products as $product) {
            $parcel_prefilled_dimensions['width'] = max($parcel_prefilled_dimensions['width'], $product['width']);
            $parcel_prefilled_dimensions['height'] = max($parcel_prefilled_dimensions['height'], $product['height']);
            $parcel_prefilled_dimensions['depth'] = max($parcel_prefilled_dimensions['depth'], $product['depth']);
            $parcel_prefilled_dimensions['weight'] += $product['weight'] * $product['product_quantity'];
        }
        $parcel_prefilled_dimensions['weight'] = max(0.1, $parcel_prefilled_dimensions['weight']);
        return SpringTools::normalizeDimensions($parcel_prefilled_dimensions);
    }

    /**
     * Hook on admin order page
     *
     * @param array $params Hook parameters
     * @return string Hook HTML
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function hookDisplayAdminOrder($params)
    {
        $id_order = (int)$params['id_order'];
        $output = '';

        if (!SpringXbsHelper::get(self::API_SPRING_SIDE_USER_ID)) {
            return '';
        }

        $this->ajax = true;
        $order = new Order($id_order);

        if (!Validate::isLoadedObject($order)) {
            return '';
        }

        $carrier = new Carrier($order->id_carrier);
        $service_name = false;

        if (isset($this->carrier_id_service_code[$carrier->id_reference])) {
            $service_name = $this->carrier_id_service_code[$carrier->id_reference];
        }

        $shipment = AdminSpringxbsApiController::getShipmentStatic($id_order);
        $messages = SpringXbsHelper::callNotifications();//also is a flag redirect

        if (!$messages && $shipment && $service_name != $shipment['service']) {
            $springxbs_api = new AdminSpringxbsApiController();
            $springxbs_api->voidShipment($id_order);
            $result = $springxbs_api->orderShipment($id_order, false);

            if (!empty($result['error'])) {
                SpringXbsHelper::setNotification($result['error'], SpringXbsHelper::MSG_TYPE_ERROR);
            }

            Tools::redirectAdmin(
                $this->context->link->getAdminLink('AdminOrders', true) .
                "&id_order=$params[id_order]&vieworder"
            );
        }

        if (!$shipment) {
            $can_create = AdminSpringxbsApiController::canCreateLabel($id_order);

            if (!empty($can_create['error'])) {
                $this->context->smarty->assign(
                    array(
                        'msg' => $can_create['error'],
                        'module_name' => $this->l($this->displayName),
                    )
                );

                return $this->display(__FILE__, 'views/templates/hook/adminorderdetail_cannot_create.tpl');
            }
        }


        $address = new Address($order->id_address_delivery);
        $customer = new Customer($order->id_customer);
        $api = new AdminSpringxbsApiController();
        $tracking_data = $api->trackShipment($id_order);

        if (!empty($tracking_data['error'])) {
            $output .= SpringXbsHelper::displayError($this->l($tracking_data['error']));
            $tracking_data = array();
        }

        $label_exists_text = false;
        $label_reprint_text = false;

        if ($shipment && $shipment['error_level']) {
            $label_reprint_text = $this->l('The label is temporary, please reorder it');
        } elseif ($shipment) {
            $label_exists_text = $this->l('The shipment is ordered');
            $dimensions = AdminSpringxbsApiController::getShipmentDimensions($id_order);
        } elseif (!$shipment) {
            $pp_dimensions = SpringTools::getParcelDimensions($id_order);
        }

        $this->context->smarty->assign(
            array(
                'messages' => $messages,
                'prefix' => $this->name,
                'firstname' => $address->firstname,
                'lastname' => $address->lastname,
                'address1' => $address->address1,
                'postcode' => $address->postcode,
                'city' => $address->city,
                'state' => State::getNameById($address->id_state),
                'country' => Country::getNameById(
                    isset($customer->id_lang)
                        ? $customer->id_lang
                        : Context::getContext()->language->id,
                    $address->id_country
                ),
                'email' => $customer->email,
                'order_reference' => $order->reference,

                'label_exists_text' => $label_exists_text,
                'label_text' => $shipment ? $this->l('Reprint label') : $this->l('Get label'),
                'label_link' => static::appendQueryToUrl(
                    $this->context->link->getAdminLink('AdminSpringxbsApi'),
                    array(
                        'ajax' => '1',
                        'action' => $shipment ? 'printLabel' : 'createLabel',
                        'id_order' => $id_order,
                    )
                ),
                'label_check_link' => $this->getShipmentCheckUrl($id_order),
                'is_shipment' => $shipment ? 1 : 0,

                'tracking_data' => $tracking_data,
                'tracking_data_rows' => count($tracking_data),
                'test_mode_msg' => $shipment['test_mode'] ? $this->l('Test mode') : '',

                'void_show' => $shipment,
                'void_label_text' => $this->l('Cancel label'),
                'void_label_link' => static::appendQueryToUrl(
                    $this->context->link->getAdminLink('AdminSpringxbsApi'),
                    array(
                        'ajax' => '1',
                        'action' => 'voidLabel',
                        'id_order' => $id_order,
                    )
                ),
                'error_level' => $shipment['error_level'],
                'reprint_by_api_link_text' => $this->l('Reorder label'),
                'label_reprint_text' => $label_reprint_text,
                'reprint_by_api_link' => static::appendQueryToUrl(
                    $this->context->link->getAdminLink('AdminSpringxbsApi'),
                    array(
                        'ajax' => '1',
                        'action' => 'reorderLabel',
                        'id_order' => $id_order,
                    )
                ),
                'parcel_prefilled_dimensions' => isset($pp_dimensions) ? $pp_dimensions : array(),
                'parcel_dimensions' => isset($dimensions) ? $dimensions : false,
                'psv_1_5' => $this->pisv15,
            )
        );

        return $output . $this->display(__FILE__, 'views/templates/hook/adminorderdetail.tpl');
    }

    /**
     * @param $id_order
     * @return array|string
     * @throws PrestaShopException
     */
    public function getShipmentCheckUrl($id_order)
    {
        $label_check_link = $this->context->link->getAdminLink('AdminSpringxbsApi');

        $lcl_parts = parse_url($label_check_link);
        if (!isset($lcl_parts['scheme']) && !isset($lcl_parts['host'])) {
            $admin_dir = basename(_PS_ADMIN_DIR_);
            if (stripos($label_check_link, $admin_dir) === false) {
                $label_check_link = '/' . basename(_PS_ADMIN_DIR_) . '/' . $label_check_link;
            }
        }

        $label_check_link = static::appendQueryToUrl(
            $label_check_link,
            array(
                'ajax' => '1',
                'action' => 'isLabelReady',
                'id_order' => $id_order,
            )
        );
        return $label_check_link;
    }

    /**
     * Edit order grid display
     *
     * @param array $params
     * @throws PrestaShopException
     */
    public function hookActionAdminOrdersListingFieldsModifier($params)
    {

        if (isset($params['select']) && SpringXbsHelper::get(self::API_SPRING_SIDE_USER_ID)) {
            $table = _DB_PREFIX_ . "springxbs_shipment";
            $params['select'] .=
                ", IFNULL($table.test_mode, 0) as label_test_mode, 
                $table.tracking_number as spring_tracking_number, 
                $table.service as spring_service, 
                IFNULL($table.status_code, -10) as spring_status,
                $table.error_level as spring_error_level ";

            $params['join'] .= " LEFT JOIN $table ON $table.id_order=a.id_order";

            $params['fields']['spring_service'] = array(
                'title' => $this->displayName,
                'class' => 'fixed-width-lg',
                'callback' => 'printTrackTrace',
                'callback_object' => 'SpringTools',
                'search' => false,
                'orderby' => false,
                'remove_onclick' => true,
            );

            $params['fields']['spring_status'] = array(
                'title' => '',
                'class' => 'fixed-width-sm',
                'callback' => 'printLabelIcon',
                'callback_object' => 'SpringTools',
                'search' => false,
                'orderby' => false,
                'remove_onclick' => true,
            );
        }
    }

    /**
     * @param $params
     * @return string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function hookDisplayCarrierList($params)
    {
        $country_state = Address::getCountryAndState($params['cart']->id_address_delivery);
        $country_iso = Country::getIsoById($country_state['id_country']);
        $carriers_original = self::getCarrierServiceCodes();
        $carriers = array();

        foreach ($carriers_original as $id => $code) {
            $tmp_carrier = Carrier::getCarrierByReference($id);
            $carriers[$tmp_carrier->id] = $code;
        }

        $this->context->smarty->assign(array(
            'country_iso' => $country_iso,
            'carriers' => $carriers,
            'src' => self::IMG_URL
        ));

        return $this->display($this->name, 'views/templates/hook/display-after-carrier.tpl');
    }

    /**
     * @param $params
     * @return string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function hookDisplayAfterCarrier($params)
    {
        $country_state = Address::getCountryAndState($params['cart']->id_address_delivery);
        $country_iso = Country::getIsoById($country_state['id_country']);
        $carriers_original = self::getCarrierServiceCodes();
        $carriers = array();

        foreach ($carriers_original as $id => $code) {
            $tmp_carrier = Carrier::getCarrierByReference($id);
            $carriers[$tmp_carrier->id] = $code;
        }

        $this->context->smarty->assign(array(
            'country_iso' => $country_iso,
            'carriers' => $carriers,
            'src' => self::IMG_URL
        ));

        return $this->display($this->name, 'views/templates/hook/display-after-carrier.tpl');
    }

    /**
     * Append get params to a query string of the url
     *
     * @param string $url_string
     * @param array $query
     * @param bool $not_stringify
     * @return string|array
     */
    public static function appendQueryToUrl($url_string, $query = array(), $not_stringify = false)
    {
        $url = parse_url($url_string);
        $url['query'] = isset($url['query']) ? $url['query'] : '';
        parse_str($url['query'], $old_query);
        $url['query'] = http_build_query($old_query + $query, PHP_QUERY_RFC1738);

        if ($not_stringify) {
            return $url;
        }

        return SpringXbsHelper::stringifyUrl($url);
    }

    /**
     * Get admin link
     *
     * @param string $controller
     * @param bool $with_token
     * @param array $params
     * @return string
     * @throws PrestaShopException
     */
    public function getAdminLink($controller, $with_token = true, $params = array())
    {
        if (empty($this->context->link)) {
            $this->context->link = new Link();
        }

        $url = parse_url($this->context->link->getAdminLink($controller, $with_token));
        $url['query'] = isset($url['query']) ? $url['query'] : '';
        parse_str($url['query'], $query);
        $url['query'] = http_build_query($query + $params, PHP_QUERY_RFC1738);

        return SpringXbsHelper::stringifyUrl($url);
    }

    /**
     * Get a link to call tracking to from outside
     *
     * @return bool
     * @throws PrestaShopException
     */
    public static function getTrackingLink()
    {
        $link = new Link();
        $url = (method_exists($link, 'getAdminBaseLink')
                ? ""
                : SpringXbsHelper::getAdmBaseLink())
            . $link->getAdminLink('AdminSpringxbsTracking', false);

        return $url . '&token=' . SpringXbsHelper::get(self::EXECUTION_TOKEN);
    }

    /**
     * Get a link to call tracking to from outside
     *
     * @param $id_order
     * @param $format
     * @return bool
     * @throws PrestaShopException
     */
    public static function getZplLink($id_order, $format)
    {
        $link = self::getTrackingLink();
        $parts = parse_url($link);

        return 'zprn://uvw|' . $parts['host'] . '|/'  . $parts['path'] . '?' . $parts['query'] .
            "&do=print_label&id_order=$id_order&label_format=$format" .
            '&hash=' . SpringXbsHelper::encryptDecrypt('encrypt', $id_order);
    }

    /**
     * Verify the API key with the Spring GDS
     *
     * @param $api_key
     * @return array|bool
     * @throws PrestaShopException
     */
    private function verifyApiKey($api_key)
    {
        $req = array(
            'api_key' => $api_key,
            'active' => SpringXbsHelper::get(self::MODULE_ENABLE_EXTERNAL_TRACKING),
            'url' => urlencode(Springxbs::getTrackingLink()),
            'version' => $this->version,
            'ps_version' => _PS_VERSION_,
        );
        $external_id = SpringXbsHelper::get(self::API_SPRING_SIDE_USER_ID);

        if ($external_id) {
            $req['ID'] = $external_id;
        }

        $result = AdminSpringxbsApiController::getUrl(
            self::API_ACCESS_POINT_COMMUNICATE . "?" . http_build_query($req)
        );

        $api_controller = new AdminSpringxbsApiController();
        $api_controller->apiLog(0, 0, 0, 'PING', $req, $result);

        if ($result['error']) {
            return $result['error'];
        } elseif (!$result['data']) {
            return array('error' => $this->l('Bad attempt, try later'));
        }

        $data = json_decode($result['data'], true);

        if (!isset($data['ID'])) {
            return array('error' => $this->l('Bad attempt, try later'));
        }

        SpringXbsHelper::updateValue(self::API_SPRING_SIDE_USER_ID, (int)$data['ID']);

        if (!$data['ID']) {
            return false;
        }

        return true;
    }

    /**
     * Get settings by API and save
     *
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    private function getSettings()
    {
        $req = array(
            'api_key' => SpringXbsHelper::get(self::MODULE_API_KEY_NAME),
            'version' => $this->version,
            'ps_version' => _PS_VERSION_,
            'get_settings' => 1,
        );
        $external_id = SpringXbsHelper::get(self::API_SPRING_SIDE_USER_ID);

        if ($external_id) {
            $req['ID'] = $external_id;
        }

        $result = AdminSpringxbsApiController::getUrl(
            self::API_ACCESS_POINT_COMMUNICATE . "?" . http_build_query($req)
        );

        if (!empty($result['error'])) {
            return false;
        }

        $settings = json_decode($result['data'], true);

        if ($settings['service_list']) {
            $service_list = json_encode($settings['service_list']);
            SpringXbsHelper::updateValue(self::SPRINGXBS_CARRIERS_SERVICE_2_NAME, $service_list);

            foreach ($settings['service_list'] as $service_code => $name) {
                $carrier_obj = Carrier::getCarrierByReference(
                    SpringXbsHelper::get($this->name . "_" . $service_code, $service_code)
                );

                if (Validate::isLoadedObject($carrier_obj)) {
                    $carrier_obj->name = $name;
                    $carrier_obj->update();
                }
            }
        }

        if ($settings['service_names']) {
            $service_names = json_encode($settings['service_names']);
            SpringXbsHelper::updateValue(self::SPRINGXBS_CARRIERS_SERVICES_NAMES, $service_names);
        }

        if ($settings['service_2_name']) {
            $service_2_name = json_encode($settings['service_2_name']);
            SpringXbsHelper::updateValue(AdminSpringxbsApiController::DB_SERVICE_NUM_TO_CODE_NAME, $service_2_name);
        }

        if ($settings['statuses_for_mapping']) {
            $statuses_for_mapping = json_encode($settings['statuses_for_mapping']);
            SpringXbsHelper::updateValue(self::TRACKING_EVENTS, $statuses_for_mapping);
            $this->tracking_events = $settings['statuses_for_mapping'];
        }

        if ($settings['default_mapping']) {
            $saved_mapping = json_decode(SpringXbsHelper::get(self::SPRINGXBS_TO_SHOP_STATUS_MAP_SAVED), true);

            if ($saved_mapping) {
                foreach ($saved_mapping as $s_status => $p_status) {
                    if (isset($settings['default_mapping'][$s_status])) {
                        $settings['default_mapping'][$s_status] = $p_status;
                    }
                }
            }

            $default_mapping = json_encode($settings['default_mapping']);
            SpringXbsHelper::updateValue(self::SPRINGXBS_TO_SHOP_STATUS_MAP, $default_mapping);
        }

        return true;
    }

    /**
     * Configuration Page: get content of the forms
     *
     * @return string Configuration page HTML
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function getContent()
    {
        $output = '';

        if (Tools::getValue('manage_services')) {
            $output .= SpringXbsHelper::callNotifications();
            $output .= $this->displayManageServicesForm();
            return $output;
        }

        if (Tools::getValue('manage_services_apply')) {
            $output .= $this->handleManageServices();
            return $output;
        }

        if (Tools::getValue('export_products')) {
            $this->exportProductsCatalog();
            exit();
        }

        if (Tools::getValue('import_products')) {
            $this->importProductsCatalog();
        }

        if (Tools::isSubmit('submit' . $this->name)) {
            if (Tools::getValue(self::IS_API_SETTINGS_TAB)) {
                $api_key = trim((string)Tools::getValue(self::MODULE_API_KEY_NAME));
                $tracking_state = (int)Tools::getValue(self::MODULE_ENABLE_EXTERNAL_TRACKING);
                SpringXbsHelper::updateValue(self::MODULE_ENABLE_EXTERNAL_TRACKING, $tracking_state);
                $verification = $this->verifyApiKey($api_key);

                if (isset($verification['error'])) {
                    SpringXbsHelper::setNotification($verification['error'], SpringXbsHelper::MSG_TYPE_ERROR);
                } elseif ($verification == true) {
                    SpringXbsHelper::updateValue(self::MODULE_API_KEY_NAME, $api_key);
                } else {
                    SpringXbsHelper::setNotification(
                        'The entered API key is not accepted Spring GDS, contact your manager',
                        SpringXbsHelper::MSG_TYPE_ERROR
                    );
                }

                SpringXbsHelper::updateValue(
                    self::MODULE_TEST_MODE_NAME,
                    (int)Tools::getValue(self::MODULE_TEST_MODE_NAME)
                );
                SpringXbsHelper::setNotification('API settings updated ');
            } elseif (Tools::getValue(self::IS_LABELS_SETTINGS_TAB)) {
                SpringXbsHelper::updateValue(self::LABEL_FORMAT, (string)Tools::getValue(self::LABEL_FORMAT));
                SpringXbsHelper::setNotification('Labels settings updated');
            } elseif (Tools::getValue(self::IS_NOTIFICATIONS_SETTINGS_TAB)) {
                $_map = array();

                foreach (self::getTrackingEvents() as $code => $name) {
                    unset($name);
                    $_internal_state = Tools::getValue(self::SPRINGXBS_TO_SHOP_STATUS_MAP . '_' . $code);

                    if ($_internal_state != -1) {
                        $_map[$code] = $_internal_state;
                    }
                }

                SpringXbsHelper::updateValue(self::SPRINGXBS_TO_SHOP_STATUS_MAP, json_encode($_map));
                SpringXbsHelper::updateValue(self::SPRINGXBS_TO_SHOP_STATUS_MAP_SAVED, json_encode($_map));
                SpringXbsHelper::updateValue(self::EMAIL_NOTIFICATIONS, 1);
                SpringXbsHelper::setNotification('Notifications settings updated');
            } elseif (Tools::getValue(self::IS_ADVANCED_SETTINGS_TAB)) {
                SpringXbsHelper::updateValue(
                    self::MODULE_REMOVE_DATA_WHILE_UNINSTALL,
                    (string)Tools::getValue(self::MODULE_REMOVE_DATA_WHILE_UNINSTALL)
                );
                SpringXbsHelper::updateValue(
                    self::API_LOGGER_ENABLED,
                    (string)Tools::getValue(self::API_LOGGER_ENABLED)
                );
                SpringXbsHelper::setNotification('Advanced settings updated');
            } elseif (Tools::getValue(self::IS_CONSIGNOR_SETTINGS_TAB)) {
                SpringXbsHelper::updateValue(
                    self::SHIPPER_COMPANY_NAME,
                    (string)Tools::getValue(self::SHIPPER_COMPANY_NAME)
                );
                $hs_code = trim(Tools::getValue(self::DEFAULT_HS_CODE));

                if ($hs_code) {
                    if (SpringTools::validateHSCode($hs_code)) {
                        SpringXbsHelper::updateValue(self::DEFAULT_HS_CODE, $hs_code);
                    } else {
                        SpringXbsHelper::setNotification(
                            'Invalid Default HS code. Should contain at least 6 digits. i.e.: 999900 or 9999.00',
                            SpringXbsHelper::MSG_TYPE_ERROR
                        );
                    }
                }

                SpringXbsHelper::updateValue(
                    self::DEFAULT_DECLARATION_TYPE,
                    (string)Tools::getValue(self::DEFAULT_DECLARATION_TYPE)
                );

                SpringXbsHelper::setNotification('Customs information updated');
            }

            Tools::redirectAdmin(
                $this->context->link->getAdminLink('AdminModules', true) .
                '&configure=' . $this->name
            );
        }

        $api_id = SpringXbsHelper::get(self::API_SPRING_SIDE_USER_ID);

        if ($api_id) {
            $this->getSettings();
        }

        $output .= SpringXbsHelper::callNotifications();
        $output .= $this->displaySettingsApiForm();

        if ($api_id) {
            $output .= $this->displayStartManageServicesForm()
                . $this->displayShippingSettingsForm()
                . $this->displaySettingsLabelForm()
                . $this->displaySettingsNotificationForm()
                . $this->displaySettingsAdvancedForm()
                . $this->displayExportFilesForm();
        }

        return $output;
    }

    /**
     * Form API key
     *
     * @return string HTML for the bo page
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    protected function displaySettingsApiForm()
    {
        // Get default language
        $default_lang = (int)SpringXbsHelper::get('PS_LANG_DEFAULT');
        $api_key = SpringXbsHelper::get(self::MODULE_API_KEY_NAME);
        $api_key_msg = $this->l('You can obtain an API key by contacting us at communications@spring-gds.com');

        // Init Fields form array
        $see_full_settitngs = null;

        if (!SpringXbsHelper::get(self::API_SPRING_SIDE_USER_ID)) {
            $see_full_settitngs = $this->l('To unlock full settings - enter the correct API key');
        }

        $v16plus = $this->v[0] > 1 || $this->v[0] == 1 && $this->v[1] > 5;

        $fields_form = array();
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('API Settings'),
            ),
            'description' => $api_key_msg,
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => self::IS_API_SETTINGS_TAB,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('API key'),
                    'name' => self::MODULE_API_KEY_NAME,
                    'hint' => $see_full_settitngs,
                    'size' => $v16plus ? 255 : 32,
                    'maxlength' => 255,
                    'required' => true
                ),
                array( // radio
                    'type' => $v16plus ? 'switch' : 'radio',
                    'label' => $this->l('Test Mode'),
                    'name' => self::MODULE_TEST_MODE_NAME,
                    'hint' =>
                        $this->l('Select NO when you are finished testing and would like') . ' ' .
                        $this->l('to produce valid shipping labels'),
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_off',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'active_no',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
                array(
                    'type' => $v16plus ? 'switch' : 'radio',
                    'label' => $this->l('Import tracking updates'),
                    'name' => self::MODULE_ENABLE_EXTERNAL_TRACKING,
                    'hint' =>
                        $this->l('This will periodically check if there are tracking updates on your') . ' ' .
                        $this->l('shipments and import them to Prestashop'),
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_off',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'active_no',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' => array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                    '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value[self::IS_API_SETTINGS_TAB] = 1;
        $helper->fields_value[self::MODULE_API_KEY_NAME] = $api_key;

        if (SpringXbsHelper::get(self::API_SPRING_SIDE_USER_ID)) {
            $tracking_link = Springxbs::getTrackingLink();
            if ($tracking_link) {
                $fields_form[0]['form']['input'][] = array(
                    'type' => 'text',
                    'label' => $this->l('Your tracking link'),
                    'name' => '__informer_not_handled_',
                    'size' => $v16plus ? 255 : 32,
                    'readonly' => true,
                    'disabled' => true
                );
                $helper->fields_value['__informer_not_handled_'] = $tracking_link;
            }
        }

        $helper->fields_value[self::MODULE_TEST_MODE_NAME] = SpringXbsHelper::get(self::MODULE_TEST_MODE_NAME);
        $helper->fields_value[self::MODULE_ENABLE_EXTERNAL_TRACKING] =
            SpringXbsHelper::get(self::MODULE_ENABLE_EXTERNAL_TRACKING);

        return $helper->generateForm($fields_form);
    }

    /**
     * Show button to go to services
     *
     * @return string HTML for the bo page
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    protected function displayStartManageServicesForm()
    {
        $this->context->smarty->assign(array(
            'form_url' => static::appendQueryToUrl(
                $this->context->link->getAdminLink('AdminModules'),
                array(
                    'configure' => $this->name,
                    'manage_services' => 1,
                )
            ),
            'title' => $this->l('Manage services'),
            'help_msg' => $this->l('Allows to edit list of carriers'),
            'btn_msg' => $this->l('Start manage'),
        ));

        return $this->display($this->name, 'views/templates/admin/configure/start-manage-services.tpl');
    }

    /**
     * Show button to go to services
     *
     * @return string HTML for the bo page
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    protected function displayExportFilesForm()
    {
        $this->context->smarty->assign(array(
            'language_iso_code' => Tools::strtolower($this->context->language->iso_code),
            'pisv15' => $this->pisv15,

            'form_url' => static::appendQueryToUrl(
                $this->context->link->getAdminLink('AdminModules'),
                array(
                    'configure' => $this->name,
                    'export_products' => 1,
                )
            ),
            'form_url_upload' => static::appendQueryToUrl(
                $this->context->link->getAdminLink('AdminModules'),
                array(
                    'configure' => $this->name,
                    'import_products' => 1,
                )
            ),
            'title' => $this->l('ADDITIONAL PRODUCT DATA FOR CUSTOMS FORM'),
            'btn_msg' => $this->l('Export Products CSV'),
            'btn_file_placeholder' => $this->l('Choose CSV file with updated data'),
            'btn_import_msg' => $this->l('Upload file'),
            'isv16' => $this->pisv16,
            'isv15' => $this->pisv15,
        ));
        return $this->display($this->name, 'views/templates/admin/configure/export-import.tpl');
    }

    /**
     * Upload products in csv
     * @throws PrestaShopDatabaseException
     */
    public function exportProductsCatalog()
    {
        if ($this->pisv15) {
            SpringTools::exportProductsCsvV15($this->context->language->id, $this->context->shop->id);
        } else {
            SpringTools::exportProductsCsvV16plus($this->context->language->id, $this->context->shop->id);
        }
    }

    /**
     * Upload products in csv
     * @throws PrestaShopDatabaseException
     */
    public function importProductsCatalog()
    {
        if (empty($_FILES['csv_file']['tmp_name'])) {
            SpringXbsHelper::setNotification("No file uploaded", SpringXbsHelper::MSG_TYPE_OK);
            return;
        }
        $rows_added = SpringTools::importProductsCsv(Tools::file_get_contents($_FILES['csv_file']['tmp_name']));

        $msg = $this->l('CSV processed, ') .
            $rows_added . ' ' .
            $this->l('rows added');

        SpringXbsHelper::setNotification($msg, SpringXbsHelper::MSG_TYPE_OK);
    }

    /**
     * Show services form
     *
     * @return string HTML for the bo page
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    protected function displayManageServicesForm()
    {
        $list = self::getServicesNames();
        $carriers = array();

        foreach (array_keys($list) as $service_code) {
            $carrier = Carrier::getCarrierByReference(
                SpringXbsHelper::get($this->name . "_" . $service_code, $service_code)
            );

            if (!Validate::isLoadedObject($carrier)) {
                $carriers[] = array(
                    'id' => '-',
                    'active' => -1,
                    'name' => $list[$service_code],
                    'service_code' => $service_code,
                    'logo' => '',
                    'delay' => '',
                    'is_new' => 1,
                );
                continue;
            }

            $thumb = ImageManager::thumbnail(
                _PS_SHIP_IMG_DIR_ . (int)$carrier->id . '.jpg',
                $this->name . '_carrier_' . $carrier->id . '.jpg',
                40,
                'jpg',
                false
            );

            preg_match("/src=['\"]([^'\"]*)/is", $thumb, $out);
            $thumb_src = $out[1];

            $carriers[] = array(
                'id' => $carrier->id,
                'active' => $carrier->active,
                'name' => $carrier->name,
                'service_code' => $service_code,
                'logo_src' => $thumb_src,
                'delay' => is_array($carrier->delay) ? reset($carrier->delay) : $carrier->delay,
                'is_new' => 0,
            );
        }

        $this->context->smarty->assign(array(
            'form_url' => static::appendQueryToUrl(
                $this->context->link->getAdminLink('AdminModules'),
                array(
                    'configure' => $this->name,
                    'manage_services_apply' => 1,
                )
            ),
            'back_url' => static::appendQueryToUrl(
                $this->context->link->getAdminLink('AdminModules'),
                array(
                    'configure' => $this->name,
                )
            ),
            'text_url' => $this->l('back to configuration'),
            'title' => $this->l('Manage services'),

            'carriers' => $carriers,
        ));

        return $this->display($this->name, 'views/templates/admin/configure/manage-services.tpl');
    }

    /**
     * Handle services form
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function handleManageServices()
    {
        $service_to_install = Tools::getValue('code');
        $list = self::getServicesNames();

        if (!$service_to_install || !$list[$service_to_install]) {
            SpringXbsHelper::setNotification(
                $this->l('No such service code available: ') . $service_to_install,
                SpringXbsHelper::MSG_TYPE_ERROR
            );
            return false;
        }

        $carrier = Carrier::getCarrierByReference(
            SpringXbsHelper::get($this->name . "_" . $service_to_install, $service_to_install)
        );

        if (Validate::isLoadedObject($carrier)) {
            SpringXbsHelper::setNotification(
                'Already installed',
                SpringXbsHelper::MSG_TYPE_ERROR
            );
            return false;
        }

        SpringXbsHelper::updateValue(self::CARRIER_ID_SERVICE_CODE, '');

        if ($this->addCarrier($list[$service_to_install], $this->name . "_" . $service_to_install)) {
            SpringXbsHelper::setNotification(
                'Carrier with service code added, you can configure it on the Shipping->Carriers page'
            );
            Tools::redirectAdmin(
                static::appendQueryToUrl(
                    $this->context->link->getAdminLink('AdminModules'),
                    array(
                        'configure' => $this->name,
                        'manage_services' => 1,
                    )
                )
            );

            return true;
        }

        SpringXbsHelper::setNotification(
            'Service adding error',
            SpringXbsHelper::MSG_TYPE_ERROR
        );

        return false;
    }

    /**
     * Label settings form
     *
     * @return string HTML for the bo page
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    protected function displaySettingsLabelForm()
    {
        $default_lang = (int)SpringXbsHelper::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fields_form = array();
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Labels Settings'),
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => self::IS_LABELS_SETTINGS_TAB,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Label format'),
                    'name' => self::LABEL_FORMAT,
                    'options' => array(
                        'query' => $this->label_formats,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'class' => 'fixed-width-xxl',
                ),

            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' => array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                    '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value[self::IS_LABELS_SETTINGS_TAB] = 1;
        $value_label_format = SpringXbsHelper::get(self::LABEL_FORMAT) ?: self::DEFAULT_LABEL_FORMAT;
        $helper->fields_value[self::LABEL_FORMAT] = $value_label_format;

        return $helper->generateForm($fields_form)
            . $this->display($this->name, 'views/templates/admin/configure/epl-printer-instructions.tpl');
    }

    /**
     * Notifications settings form
     *
     * @return string HTML for the bo page
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    protected function displaySettingsNotificationForm()
    {
        // Get default language
        $default_lang = (int)SpringXbsHelper::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fields_form = array();
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('ORDER STATUS SETTINGS'),
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => self::IS_NOTIFICATIONS_SETTINGS_TAB,
                ),

                array(
                    'type' => 'hidden',
                    'label' => $this->l('Email notifications'),
                    'name' => self::EMAIL_NOTIFICATIONS,
                    'desc' => $this->l('Send based on XBS status events'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_off',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'active_no',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        array_unshift($this->order_statuses, array(
            'id_order_state' => '-1', 'name' => ''
        ));

        foreach (self::getTrackingEvents() as $code => $name) {
            $fields_form[0]['form']['input'][] = array(
                'type' => 'select',
                'label' => $this->l($name),
                'name' => self::SPRINGXBS_TO_SHOP_STATUS_MAP . '_' . $code,
                'hint' =>
                    $this->l('Set the Prestashop order status that should be') . ' ' .
                    $this->l('associated with the carrier tracking event'),
                'id' => self::SPRINGXBS_TO_SHOP_STATUS_MAP . $code,
                'options' => array(
                    'query' => $this->order_statuses,
                    'id' => 'id_order_state',
                    'name' => 'name',
                    'orderby' => 'id_order_state',
                ),
                'class' => 'fixed-width-xxl',
            );
        }

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' => array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                    '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value[self::IS_NOTIFICATIONS_SETTINGS_TAB] = 1;
        $helper->fields_value[self::EMAIL_NOTIFICATIONS] = SpringXbsHelper::get(self::EMAIL_NOTIFICATIONS);
        $_map = self::getStatusesMap();

        foreach (self::getTrackingEvents() as $code => $name) {
            $helper->fields_value[self::SPRINGXBS_TO_SHOP_STATUS_MAP . '_' . $code]
                = isset($_map[$code]) ? $_map[$code] : '';
        }

        return $helper->generateForm($fields_form);
    }

    /**
     * Advanced settings form
     *
     * @return string HTML for the bo page
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    protected function displaySettingsAdvancedForm()
    {
        // Get default language
        $default_lang = (int)SpringXbsHelper::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fields_form = array();
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Advanced Settings'),
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => self::IS_ADVANCED_SETTINGS_TAB,
                ),
                array(
                    'type' => $this->v16plus ? 'switch' : 'radio',
                    'label' => $this->l('API logger'),
                    'name' => self::API_LOGGER_ENABLED,
                    'hint' => $this->l('By enabling this option, API calls are being logged'),
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_no',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
                array(
                    'type' => $this->v16plus ? 'switch' : 'radio',
                    'label' => $this->l('Remove all data while uninstall'),
                    'name' => self::MODULE_REMOVE_DATA_WHILE_UNINSTALL,
                    'hint' => $this->l('By enabling this option all shipping data will be deleted during uninstall'),
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_no',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' => array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                    '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value[self::IS_ADVANCED_SETTINGS_TAB] = 1;

        $helper->fields_value[self::API_LOGGER_ENABLED] = SpringXbsHelper::get(self::API_LOGGER_ENABLED);
        $helper->fields_value[self::MODULE_REMOVE_DATA_WHILE_UNINSTALL]
            = SpringXbsHelper::get(self::MODULE_REMOVE_DATA_WHILE_UNINSTALL);

        return $helper->generateForm($fields_form);
    }

    /**
     * Shipping settings form
     *
     * @return string HTML for the bo page
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    protected function displayShippingSettingsForm()
    {
        // Get default language
        $default_lang = (int)SpringXbsHelper::get('PS_LANG_DEFAULT');

        $v16plus = $this->v[0] > 1 || $this->v[0] == 1 && $this->v[1] > 5;

        $multiline_hints =
            $this->l('An Harmonized System (HS) code is a standardized description of the content of a parcel.') . ' ' .
            $this->l('This is often required by customs for international shipments.') . ' ' .
            $this->l('For example, if you sell paintings the HS code is 9701100000');

        // Init Fields form array
        $fields_form = array();
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('CUSTOMS INFORMATION'),
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => self::IS_CONSIGNOR_SETTINGS_TAB,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Shipper company name'),
                    'name' => self::SHIPPER_COMPANY_NAME,
                    'size' => $v16plus ? 255 : 128,
                    'maxlength' => 255,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('HS code'),
                    'name' => self::DEFAULT_HS_CODE,
                    'hint' => $multiline_hints,
                    'size' => $v16plus ? 255 : 32,
                    'maxlength' => 255,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Declaration Type'),
                    'name' => self::DEFAULT_DECLARATION_TYPE,
                    'hint' =>
                        $this->l('Declaration Type is used on the customs form.') . ' ' .
                        $this->l('Sales of Goods is usually the correct setting for e-commerce'),
                    'id' => self::DEFAULT_DECLARATION_TYPE,
                    'options' => array(
                        'query' => self::$declaration_types,
                        'id' => 'id',
                        'name' => 'name',
                    )
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' => array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                    '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value[self::IS_CONSIGNOR_SETTINGS_TAB] = 1;
        $shipper_company_name =
            SpringXbsHelper::get(self::SHIPPER_COMPANY_NAME) ?: SpringXbsHelper::get('PS_SHOP_NAME');
        $helper->fields_value[self::SHIPPER_COMPANY_NAME] = $shipper_company_name;
        $helper->fields_value[self::DEFAULT_HS_CODE] = SpringXbsHelper::get(self::DEFAULT_HS_CODE);
        $helper->fields_value[self::DEFAULT_DECLARATION_TYPE] =
            SpringXbsHelper::get(self::DEFAULT_DECLARATION_TYPE) ?:
                SpringXbsHelper::get(self::DEFAULT_DECLARATION_VALUE);

        return $helper->generateForm($fields_form);
    }

    /**
     * Install the module
     *
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws ReflectionException
     */
    public function install()
    {
        if ($this->install_verified) {
            if ($this->_errors) {
                return false;
            }

            return true;
        }

        return $this->readyMakeSure();
    }

    /**
     * Fix the module installation if needs
     *
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws ReflectionException
     */
    public function readyMakeSure()
    {
        Cache::clean('hook_idsbyname');

        if (!$this->id) {
            $result = Db::getInstance()->insert($this->table, array(
                'name' => $this->name,
                'active' => 1,
                'version' => $this->version
            ), false, false, Db::INSERT_IGNORE);

            if (!$result) {
                $this->_errors[] = Context::getContext()->getTranslator()
                    ->trans(
                        'Technical error: PrestaShop could not install this module.',
                        array(),
                        'Admin.Modules.Notification'
                    );
                return false;
            }

            $this->id = Db::getInstance()->Insert_ID();
            Cache::clean('Module::isInstalled' . $this->name);//slow
            $id_added = true;
        }

        $this->enable();

        if ($this->pisv15 || $this->pisv16) {
            $access_exists = Db::getInstance()->getValue(
                'SELECT 1 FROM `' . _DB_PREFIX_ . 'module_access` WHERE `id_module` =' . (int)$this->id,
                false
            );
            if (!$access_exists) {
                if ($this->pisv15) {
                    // Permissions management v1.5
                    Db::getInstance()->execute('
                      INSERT INTO `' . _DB_PREFIX_ . 'module_access` (`id_profile`, `id_module`, `view`, `configure`) (
                        SELECT id_profile, ' . (int)$this->id . ', 1, 1
                        FROM ' . _DB_PREFIX_ . 'access a
                        WHERE id_tab = (
                            SELECT `id_tab` FROM ' . _DB_PREFIX_ . 'tab
                            WHERE class_name = \'AdminModules\' LIMIT 1)
                        AND a.`view` = 1)');

                    Db::getInstance()->execute('
                      INSERT INTO `' . _DB_PREFIX_ . 'module_access` (`id_profile`, `id_module`, `view`, `configure`) (
                        SELECT id_profile, ' . (int)$this->id . ', 1, 0
                        FROM ' . _DB_PREFIX_ . 'access a
                        WHERE id_tab = (
                            SELECT `id_tab` FROM ' . _DB_PREFIX_ . 'tab
                            WHERE class_name = \'AdminModules\' LIMIT 1)
                        AND a.`view` = 0)');
                } elseif ($this->pisv16) {
                    // Permissions management v1.6
                    Db::getInstance()->execute('
                      INSERT INTO `' . _DB_PREFIX_ . 'module_access` 
                      (`id_profile`, `id_module`, `view`, `configure`, `uninstall`) (
                        SELECT id_profile, ' . (int)$this->id . ', 1, 1, 1
                        FROM ' . _DB_PREFIX_ . 'access a
                        WHERE id_tab = (
                            SELECT `id_tab` FROM ' . _DB_PREFIX_ . 'tab
                            WHERE class_name = \'AdminModules\' LIMIT 1)
                        AND a.`view` = 1)');

                    Db::getInstance()->execute('
                      INSERT INTO `' . _DB_PREFIX_ . 'module_access` 
                      (`id_profile`, `id_module`, `view`, `configure`, `uninstall`) (
                        SELECT id_profile, ' . (int)$this->id . ', 1, 0, 0
                        FROM ' . _DB_PREFIX_ . 'access a
                        WHERE id_tab = (
                            SELECT `id_tab` FROM ' . _DB_PREFIX_ . 'tab
                            WHERE class_name = \'AdminModules\' LIMIT 1)
                        AND a.`view` = 0)');
                }
            }
        } elseif ($this->pisv17) {
            $access_exists = Db::getInstance()->getValue(
                'SELECT 1 FROM `' . _DB_PREFIX_ . 'authorization_role` 
                    WHERE `slug` LIKE "ROLE_MOD_MODULE_' . Tools::strtoupper($this->name) . '_%"',
                false
            );
            if (!$access_exists) {
                // Permissions management v1.7
                foreach (array('CREATE', 'READ', 'UPDATE', 'DELETE') as $action) {
                    $slug = 'ROLE_MOD_MODULE_' . Tools::strtoupper($this->name) . '_' . $action;

                    Db::getInstance()->execute(
                        'INSERT INTO `' . _DB_PREFIX_ . 'authorization_role` (`slug`) VALUES ("' . $slug . '")'
                    );

                    Db::getInstance()->execute('
                        INSERT INTO `' . _DB_PREFIX_ . 'module_access` (`id_profile`, `id_authorization_role`) (
                            SELECT id_profile, "' . Db::getInstance()->Insert_ID() . '"
                            FROM ' . _DB_PREFIX_ . 'access a
                            LEFT JOIN `' . _DB_PREFIX_ . 'authorization_role` r
                            ON r.id_authorization_role = a.id_authorization_role
                            WHERE r.slug = "ROLE_MOD_TAB_ADMINMODULESSF_' . $action . '"
                    )');
                }
            }
        }

        // Adding Restrictions for client groups
        if (!empty($id_added)) {
            Group::addRestrictionsForModule($this->id, Shop::getShops(true, null, true));
            if ($this->pisv16) {
                Hook::exec('actionModuleInstallAfter', array('object' => $this));
            }

            if (isset(Module::$update_translations_after_install) && Module::$update_translations_after_install &&
                method_exists($this, 'updateModuleTranslations')) { // slow
                $this->updateModuleTranslations();
            }
        }

        $this->installSql();
        $module_tabs = Tab::getModuleTabList();
        $tabs = array();

        if (!isset($module_tabs[Tools::strtolower('AdminSpringxbsApi')])) {
            $tabs[] = $this->installTab('AdminSpringxbsApi', 'Api');
        }

        if (!isset($module_tabs[Tools::strtolower('AdminSpringxbsTracking')])) {
            $tabs[] = $this->installTab('AdminSpringxbsTracking', 'Tracking');
        }

        $this->installCarriers();
        $this->setModuleDefaults();

        foreach ($this->hooks as $hook) {
            /* For PS v1.5.x.x */
            if (method_exists(new Hook, 'isModuleRegisteredOnHook')) {
                if (!Hook::isModuleRegisteredOnHook($this, $hook, $this->context->shop->id)) {
                    $this->registerHook($hook);
                }
            } elseif (!SpringXbsHelper::hookExists($this, $hook, $this->context->shop->id)) {
                $this->registerHook($hook);
            }
        }

        if (method_exists(new Tools, 'clearCache')) {
            Tools::clearCache(Context::getContext()->smarty);
        }

        $this->install_verified = true;

        /*if ($tabs) {
            foreach ($tabs as $id_tab) {
                Tab::initAccess($id_tab);
            }
        }*/

        return true;
    }

    /**
     * install carriers
     *
     * @return void
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws ReflectionException
     */
    private function installCarriers()
    {
        $carrier_id_service_code = array();

        foreach (self::getServicesNames() as $service_code => $name) {
            $added_carrier = $this->addCarrier($name, $this->name . "_" . $service_code);

            if ($added_carrier) {
                $id_reference = $added_carrier->id_reference;

                if (!$id_reference) {
                    $id_reference = $added_carrier->id;
                }

                $carrier_id_service_code[$id_reference] = $service_code;
            }
        }

        SpringXbsHelper::get(self::CARRIER_ID_SERVICE_CODE)
            ? SpringXbsHelper::updateValue(self::CARRIER_ID_SERVICE_CODE, json_encode($carrier_id_service_code))
            : SpringXbsHelper::set(self::CARRIER_ID_SERVICE_CODE, json_encode($carrier_id_service_code));
    }

    /**
     * install carriers
     *
     * @return void
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws ReflectionException
     */
    private function setModuleDefaults()
    {
        // default states mapping set
        if (SpringXbsHelper::get(self::EXECUTION_TOKEN)) {
            return;
        }

        $settings = array(
            self::SPRINGXBS_TO_SHOP_STATUS_MAP => '{"0":"3","20":"4","100":"5"}',
            self::LABEL_FORMAT => self::DEFAULT_LABEL_FORMAT,
            self::MODULE_ENABLE_EXTERNAL_TRACKING => 1,
            self::API_LOGGER_ENABLED => 1,
            self::EMAIL_NOTIFICATIONS => 1,
        );

        foreach ($settings as $name => $value) {
            SpringXbsHelper::updateValue($name, $value);
        }

        $token = Tools::encrypt(Tools::getShopDomainSsl() . time());
        SpringXbsHelper::updateGlobalValue(self::EXECUTION_TOKEN, $token);
    }

    /**
     * Install tab
     *
     * @param $my_module_name
     * @param $tab_name
     * @return int
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function installTab($my_module_name, $tab_name)
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $my_module_name;
        $tab->name = array();

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $tab_name;
        }

        $tab->id_parent = -1;
        $tab->module = $this->name;
        $tab->add();

        return $tab->id;
    }

    /**
     * Installs the module sql
     *
     * @return bool Indicates whether the module has been successfully installed
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws ReflectionException
     */
    public function installSql()
    {
        $shipment_table = \Db::getInstance()->execute(
            "CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "springxbs_shipment ( 
                id_order INT UNSIGNED NOT NULL, 
                test_mode TINYINT(1) NOT NULL DEFAULT 0, 
                service VARCHAR(128) NOT NULL DEFAULT '',
                error_level INT NOT NULL DEFAULT 0, 
                tracking_number VARCHAR(255) NOT NULL DEFAULT '', 
                label_format VARCHAR(10) NOT NULL DEFAULT '',
                label_type VARCHAR(10) NOT NULL DEFAULT '',
                label MEDIUMTEXT NOT NULL DEFAULT '', 
                status_code SMALLINT NOT NULL DEFAULT -1, 
                data MEDIUMTEXT NOT NULL DEFAULT '', 
                tracking_events MEDIUMTEXT NOT NULL DEFAULT '', 
                tracking_time int(10) NOT NULL DEFAULT 0,
                date_add datetime DEFAULT '0000-00-00 00:00:00',
                date_upd datetime DEFAULT '0000-00-00 00:00:00',
                PRIMARY KEY (id_order),
                KEY (status_code)
            ) ENGINE = " . pSQL(_MYSQL_ENGINE_) . ";"
        );
    
        $products_table = \Db::getInstance()->execute(
            "CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "springxbs_product ( 
                id_product int(10) NOT NULL,
                hs_code VARCHAR(20) NOT NULL DEFAULT '', 
                coo CHAR(2) NOT NULL DEFAULT '',
                intl_description text, 
                UNIQUE KEY (id_product)
            ) ENGINE = " . pSQL(_MYSQL_ENGINE_) . ";"
        );

        return $shipment_table && $products_table;
    }


    /**
     * Uninstalls the module
     *
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws ReflectionException
     */
    public function uninstall()
    {
        $this->uninstallSql();

        $sql = "SELECT id_tab id FROM " . _DB_PREFIX_ . "tab WHERE module='springxbs'";
        $tabs = \Db::getInstance()->executeS($sql);

        foreach ($tabs as $tab) {
            $tab_obj = new Tab($tab['id']);
            $tab_obj->delete();
        }

        if (SpringXbsHelper::get(self::MODULE_REMOVE_DATA_WHILE_UNINSTALL)) {
            foreach (array_keys(self::getServicesNames()) as $service_code) {
                $carrier_obj = Carrier::getCarrierByReference(
                    SpringXbsHelper::get($this->name . "_" . $service_code, $service_code)
                );

                if (Validate::isLoadedObject($carrier_obj)) {
                    $carrier_obj->delete();
                    $carrier_obj->deleted = 1;
                    $carrier_obj->update();
                }
            }

            $reflection = new ReflectionClass(__CLASS__);
            $constants = $reflection->getConstants();

            foreach ($constants as $constant) {
                if (preg_match("/^SPRINGXBS_/", $constant) && SpringXbsHelper::get($constant)) {
                    SpringXbsHelper::deleteByName($constant);
                }
            }
        }

        foreach ($this->hooks as $hook) {
            $this->unregisterHook($hook);
        }

        Cache::clean('hook_idsbyname');

        if (parent::uninstall() === false) {
            return false;
        }

        return true;
    }

    /**
     * Cancel tables
     *
     * @return bool
     */
    public function uninstallSql()
    {
        if (SpringXbsHelper::get(self::MODULE_REMOVE_DATA_WHILE_UNINSTALL)) {
            $shipment_table = \Db::getInstance()->execute(
                "DROP TABLE IF EXISTS " . _DB_PREFIX_ . "springxbs_shipment"
            );

            \Db::getInstance()->execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "springxbs_shipment_old");

            return $shipment_table;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getExtraTranslations()
    {
        return array(
            $this->l('Address line is required'),
            $this->l('Can not recognize tracking number'),
            $this->l('Cannot create label: Service type error'),
            $this->l('Cannot get label:'),
            $this->l('Carrier not allowed'),
            $this->l('City is required'),
            $this->l('Country is required'),
            $this->l('Error, API key is not set'),
            $this->l('Fatal error, try later'),
            $this->l('Fatal error:'),
            $this->l('Fatal error: no label, try later'),
            $this->l('JSON encoding error'),
            $this->l('Label format is not correct, check your system settings'),
            $this->l('Name or company name is required'),
            $this->l('No API key'),
            $this->l('Not tracked'),
            $this->l('Nothing done'),
            $this->l('Set Spring GDS carrier to order the shipment'),
            $this->l('Some error occurred'),
            $this->l('Virtual product cannot be shipped by physical carrier'),
            $this->l('The entered API key is not accepted Spring GDS, contact your manager'),
            $this->l('Labels settings updated'),
            $this->l('Notifications settings updated'),
            $this->l('Advanced settings updated'),
            $this->l('Invalid Default HS code. Should contain at least 6 digits. i.e.: 999900 or 9999.00'),
            $this->l('Customs information updated'),
            $this->l('Already installed'),
            $this->l('Carrier with service code added, you can configure it on the Shipping->Carriers page'),
            $this->l('Service adding error'),
            $this->l('PARCEL CREATED'),
            $this->l('ACCEPTED'),
            $this->l('IN TRANSIT'),
            $this->l('DELIVERY EXCEPTION'),
            $this->l('IN CUSTOMS'),
            $this->l('CUSTOMS EXCEPTION'),
            $this->l('DELIVERY ATTEMPTED'),
            $this->l('DELIVERY AWAITING COLLECTION'),
            $this->l('DELIVERY SCHEDULED'),
            $this->l('DELIVERED'),
            $this->l('LOST OR DESTROYED'),
            $this->l('RETURN IN TRANSIT'),
            $this->l('RETURN RECEIVED'),
            $this->l('Dimensions not saved, continue?'),
            $this->l('HS Code'),
            $this->l('Country of Origin Code(NL, GB, FR...)'),
            $this->l('International Description'),
            $this->l('Name'),
            $this->l('Reference'),
            $this->l('Category'),
            $this->l('Price (tax excl.)'),
            $this->l('Product ID'),
            $this->l('Order not exists'),
            $this->l('HS code should contain at least 6 digits. i.e.: 999900 or 9999.00'),
        );
    }

    /**
     * Add a carrier
     *
     * @param string $name Carrier name
     * @param string $key Carrier ID
     *
     * @return bool|Carrier
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    protected function addCarrier($name, $key)
    {
        $carrier = Carrier::getCarrierByReference(SpringXbsHelper::get($key));

        if (Validate::isLoadedObject($carrier)) {
            return $carrier; // Already added to DB
        }

        $carrier = new Carrier();
        $carrier->name = $name;
        $carrier->delay = array();
        $carrier->is_module = false;
        $carrier->active = 0;
        $carrier->url = self::SPRING_TRACKING_URL_CARRIER;

        foreach (Language::getLanguages() as $lang) {
            $id_lang = (int)$lang['id_lang'];
            $carrier->delay[$id_lang] = '-';
        }

        if ($carrier->add()) {
            @copy(
                dirname(__FILE__) . '/views/img/logo.png',
                _PS_SHIP_IMG_DIR_ . DIRECTORY_SEPARATOR . (int)$carrier->id . '.jpg'
            );

            SpringXbsHelper::updateGlobalValue($key, (int)$carrier->id);

            $this->addGroups($carrier);

            return $carrier;
        }

        return false;
    }

    /**
     * @param Carrier $carrier
     */
    protected function addGroups(Carrier $carrier)
    {
        $groups_ids = array();
        $groups = Group::getGroups(Context::getContext()->language->id);

        foreach ($groups as $group) {
            $groups_ids[] = $group['id_group'];
        }

        /* For v1.5.x.x where setGroups does not exists */
        if (method_exists($carrier, 'setGroups')) {
            $carrier->setGroups($groups_ids);
        } else {
            $this->setGroups($carrier, $groups_ids);
        }
    }

    /**
     * Set carrier-group relation (for PrestaShop v1.5.x.x)
     *
     * @param Carrier $carrier
     * @param $groups
     * @param bool $delete
     * @return bool
     */
    protected function setGroups(Carrier $carrier, $groups, $delete = true)
    {
        if ($delete) {
            Db::getInstance()
                ->execute('DELETE FROM ' . _DB_PREFIX_ . 'carrier_group WHERE id_carrier=' . (int)$carrier->id);
        }

        if (!is_array($groups) || !count($groups)) {
            return true;
        }

        $sql = 'INSERT INTO ' . _DB_PREFIX_ . 'carrier_group (id_carrier, id_group) VALUES ';

        foreach ($groups as $id_group) {
            $sql .= '(' . (int)$carrier->id . ', ' . (int)$id_group . '),';
        }

        return Db::getInstance()
            ->execute(rtrim($sql, ','));
    }

    /**
     * @param $params
     * @return string
     */
    public function hookDisplayAdminListAfter($params)
    {
        if (!SpringXbsHelper::get(self::API_SPRING_SIDE_USER_ID)) {
            return '';
        }

        $html = '';
        if (Tools::getValue('controller') === 'AdminOrders' && !Tools::getValue('id_order')) {
            $formats_data = array();
            foreach ($this->label_formats as $format_data) {
                if ($format_data['name'] == 'PNG') {
                    continue;
                }
                $protocol = 'https';
                if (in_array($format_data['name'], array('ZPL200', 'ZPL300', 'EPL'))) {
                    $protocol = 'zprn';
                }
                $format_data['link'] = $protocol . '://mailingtechnology.com/API/label_multiple_plugin.php?' .
                    'format=' . $format_data['name'] . '&userId=api&apikey=' .
                    Configuration::get(static::MODULE_API_KEY_NAME) . '&tns=';
                $formats_data[] = $format_data;
            }

            $this->context->smarty->assign(array(
                'logo_icon_src' => '/modules/springxbs/logo.png',
                'label_formats_list' => $formats_data,
            ));

            return $this->display(__FILE__, 'views/templates/hook/display-admin-list-after.tpl');
        }
        return $html;
    }
}
