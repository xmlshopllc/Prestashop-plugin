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

class AdminSpringxbsApiController extends ModuleAdminController
{
    const DEFAULT_MIN_WEIGHT = 0.1;
    const PRESTASHOP_LOGGER_ERROR = 3;
    const DB_SERVICE_NUM_TO_CODE_NAME = 'SPRINGXBS_DB_SERVICE_NUM_TO_CODE';
    const PARCEL_CREATED_CODE = 0;
    const PARCEL_DELIVERED_CODE = 100;

    private $carrier_id_service_code;
    private $reorder_labels = array();
    private $debug = false;

    /**
     * AdminSpringxbsApiController constructor.
     * @throws PrestaShopException
     */
    public function __construct()
    {
        parent::__construct();
        $this->carrier_id_service_code = Springxbs::getCarrierServiceCodes();
    }

    /**
     * Figure out can label be created
     *
     * @param $id_order
     * @param bool $order
     * @param bool $carrier
     * @param bool $address
     * @param bool $customer
     * @return array
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public static function canCreateLabel($id_order, $order = false, $carrier = false, $address = false)
    {
        $id_order = (int)$id_order;

        if (!is_object($order)) {
            $order = new Order($id_order);
        }

        if (method_exists($order, 'getIdOrderCarrier') && !$order->getIdOrderCarrier()) {
            return array('error' => SpringXbsHelper::l('Virtual product cannot be shipped by physical carrier'));
        }

        if (!method_exists($order, 'getIdOrderCarrier') && !SpringXbsHelper::getIdOrderCarrier($order->id)) {
            return array('error' => SpringXbsHelper::l('Virtual product cannot be shipped by physical carrier'));
        }

        if (!is_object($carrier)) {
            $carrier_orig = new Carrier($order->id_carrier);
            $carrier = Carrier::getCarrierByReference($carrier_orig->id_reference);
        }

        if (!is_object($address)) {
            $address = new Address($order->id_address_delivery);
        }

        if (!SpringXbsHelper::get(Springxbs::MODULE_API_KEY_NAME)) {
            return array('error' => SpringXbsHelper::l('No API key'));
        }

        if (!in_array(SpringXbsHelper::get(Springxbs::LABEL_FORMAT), Springxbs::$label_formats_list)) {
            return array('error' => SpringXbsHelper::l('Label format is not correct, check your system settings'));
        }

        $carrier_id_service_code = Springxbs::getCarrierServiceCodes();
        $services_names = Springxbs::getServicesNames();

        if (empty($carrier->id_reference) || empty($carrier_id_service_code[$carrier->id_reference]) ||
            empty($services_names[$carrier_id_service_code[$carrier->id_reference]])) {
            return array('error' => SpringXbsHelper::l('Set Spring GDS carrier to order the shipment'));
        }

        if (!$address->firstname && !$address->company) {
            return array('error' => SpringXbsHelper::l('Name or company name is required'));
        }

        if (!$address->address1) {
            return array('error' => SpringXbsHelper::l('Address line is required'));
        }

        if (!$address->city) {
            return array('error' => SpringXbsHelper::l('City is required'));
        }

        if (!Country::getIsoById($address->id_country)) {
            return array('error' => SpringXbsHelper::l('Country is required'));
        }

        if (!SpringXbsHelper::get(Springxbs::MODULE_API_KEY_NAME)) {
            return array('error' => SpringXbsHelper::l("Error, API key is not set"));
        }

        return array();
    }

    /**
     * compare function
     *
     * @return int
     */
    public function trackingSorter($a, $b)
    {
        return $a['time'] - $b['time'];
    }

    /**
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws ReflectionException
     */
    public function trackLabels()
    {
        $stamp =
            (int)SpringXbsHelper::get(Springxbs::API_SPRING_TRACK_LABELS_STAMP)
            + (int)SpringXbsHelper::get(Springxbs::API_SPRING_TRACK_DELAY);

        if ($stamp > time()) {
            return false;
        }

        SpringXbsHelper::updateValue(Springxbs::API_SPRING_TRACK_LABELS_STAMP, time());

        $retrack_all = Tools::getValue('retrack_all') ? ' OR 1=1 ' : '';
        $order_by = Tools::getValue('order_by_rand') ? ' RAND() ' : 'tracking_time';

        $rows = Db::getInstance()->executeS(
            "SELECT id_order, tracking_number FROM " . _DB_PREFIX_ . "springxbs_shipment
            WHERE (status_code NOT IN (25, 100, 101, 111, 11101, 11102, 125) $retrack_all)
            AND date_add>='" . pSQL(date("Y-m-d 00:00:00", time() - 86400 * 93)) . "'
            ORDER BY $order_by ASC"
        );

        set_time_limit(600);


        echo "Runned on :" . _PS_VERSION_ . "|1.3.3\n";
        echo sizeof($rows) . " todo\n";
        $cnt = 0;
        $this->reorder_labels = array();
        $this->debug = 1;

        foreach ($rows as $row) {
            echo "processing " . (++$cnt) . ":$row[tracking_number]\n";
            flush();
            try {
                $this->trackShipment($row['id_order'], true);
            } catch (Exception $e) {
                echo $e->getMessage() . "\n";
                flush();
            }
            usleep(20000);
        }
        if (!empty($this->reorder_labels)) {
            $this->reorderLabels();
        }
        $this->debug = 0;
        return true;
    }

    /**
     * Print label by ajax
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function ajaxProcessPrintLabel()
    {
        $this->ajax = true;
        $id_order = (int)Tools::getValue('id_order');
        $order = new Order($id_order);

        if (!Validate::isLoadedObject($order)) {
            exit('Order not exists');
        }

        return $this->printLabel($id_order);
    }

    /**
     * Print label by ajax
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function ajaxProcessIsLabelReady()
    {
        $this->ajax = true;
        $id_order = (int)Tools::getValue('id_order');

        $attempts = 15;
        while ($attempts--) {
            if (!$this->getShipment($id_order)) {
                usleep(300000);
                continue;
            }
            return true;
        }
        return false;
    }

    /**
     * Print existing label
     *
     * @param $id_order
     * @throws PrestaShopException
     */
    private function printLabel($id_order)
    {
        $id_order = (int)$id_order;
        $shipment = $this->getShipment($id_order);

        if (!$shipment) {
            exit('The shipment is not ordered yet');
        }

        $service_name = $shipment['service'];
        $label_format = SpringXbsHelper::get(Springxbs::LABEL_FORMAT);

        if ($shipment['label_format'] == $label_format) {
            $this->outputLabel(
                $shipment['id_order'],
                SpringXbsHelper::decodeData($shipment['label']),
                $shipment['label_format']
            );
        } else {
            $this->getLabelByApi($id_order, $service_name, $label_format);
        }
    }

    /**
     * Print existing ZPL label by ajax
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function ajaxProcessPrintZprnLabel()
    {
        $id_order = (int)Tools::getValue('id_order');
        $order = new Order($id_order);

        if (!Validate::isLoadedObject($order)) {
            exit('Order not exists');
        }

        $label_format = SpringXbsHelper::get(Springxbs::LABEL_FORMAT);
        $shipment = \Db::getInstance()->getRow("SELECT * FROM " . _DB_PREFIX_ . "springxbs_shipment  
                WHERE id_order = '" . (int)$order->id . "' AND label_format='" . pSQL($label_format) . "'");

        if (!$shipment['label']) {
            exit('Shipment not ordered yet');
        }

        $label = SpringXbsHelper::decodeData($shipment['label']);
        echo $label;
        exit();
    }

    /**
     * Order shipment by ajax
     *
     * @return mixed|string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function ajaxProcessCreateLabel()
    {
        $this->ajax = true;
        $id_order = (int)Tools::getValue('id_order');
        $result = $this->orderShipment($id_order);

        if ($result['error']) {
            echo SpringXbsHelper::l($result['error']);
            exit();
        }

        return true;
    }

    /**
     * Order shipment
     *
     * @param $id_order
     * @param bool $output
     * @return array|bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function orderShipment($id_order, $output = true)
    {
        $id_order = (int)$id_order;
        $order = new Order($id_order);

        if (!Validate::isLoadedObject($order)) {
            return array('error' => SpringXbsHelper::l('Carrier not allowed'));
        }

        $address = new Address($order->id_address_delivery);
        $customer = new Customer($order->id_customer);
        $carrier = new Carrier($order->id_carrier);
        $result = self::canCreateLabel($id_order, $order, $carrier, $address);

        if (!empty($result['error'])) {
            return array('error' => $result['error']);
        }

        $service_name = $this->carrier_id_service_code[$carrier->id_reference];

        if (!$service_name) {
            return array('error' => SpringXbsHelper::l('Cannot create label: Service type error'));
        }

        $shipment = $this->getShipment($id_order, $service_name);

        if ($shipment && !$output) {
            return array('error' => SpringXbsHelper::l('Nothing done'));
        } elseif ($shipment) {
            $this->printLabel($id_order);
        }

        $hs_code_default = SpringXbsHelper::get(Springxbs::DEFAULT_HS_CODE);

        $products_data = SpringTools::getSpringProductsList($order);

        $products = array();

        foreach ($order->getProducts() as $product) {
            $intl_description = $products_data[$product['id_product']]['intl_description'];
            $hs_code = $products_data[$product['id_product']]['hs_code'];
            $coo = $products_data[$product['id_product']]['coo'];
            $coo_arr = $coo ? array("OriginCountry" => Tools::strtoupper($coo)) : array();

            $products[] = array(
                    "Description" => $intl_description ?: $product['product_name'],
//                "Sku" => "000001",
                    "HsCode" => $hs_code ?: $hs_code_default,
//                "OriginCountry" => "GB",
//                "PurchaseUrl" => "http://url.com/book1",
                    "Quantity" => $product['product_quantity'],
                    "Value" => static::round2f($product['total_price_tax_incl'])
                ) + $coo_arr;
        }

        $phone = $address->phone;

        if (!$phone && $address->phone_mobile) {
            $phone = $address->phone_mobile;
        }

        $order_currency = Currency::getCurrency($order->id_currency);
        $declaration_type = SpringXbsHelper::get(Springxbs::DEFAULT_DECLARATION_TYPE);

        if (!$declaration_type) {
            $declaration_type = Springxbs::DEFAULT_DECLARATION_VALUE;
        }

        $dimensions = SpringTools::getParcelDimensions($id_order);

        $shipment = array(
            "LabelFormat" => SpringXbsHelper::get(Springxbs::LABEL_FORMAT),
            "ShipperReference" =>
                $order->reference . "-" . SpringXbsHelper::get(Springxbs::API_SPRING_SIDE_USER_ID), // unique
            //"DisplayId" => "123450000",
            //"InvoiceNumber" => "678900000",
            "Service" => $service_name,
            "ConsignorAddress" => array(
                "Name" => "",
                "Company" => (string)SpringXbsHelper::get(Springxbs::SHIPPER_COMPANY_NAME),
                "AddressLine1" => "",
                "AddressLine2" => "",
                "City" => "",//mandatory field, can be empty
                "State" => "",//mandatory field, can be empty
                "Zip" => "",//mandatory field, can be empty
                "Country" => "",//mandatory field, can be empty
                "Phone" => "",//mandatory field, can be empty
                "Email" => "",//mandatory field, can be empty
            ),
            "ConsigneeAddress" => array(
                "Name" => $address->firstname . " " . $address->lastname,
                "Company" => $address->company,
                "AddressLine1" => $address->address1,
                "AddressLine2" => $address->address2,
                "City" => $address->city,
                "State" => $this->getStateIsoById($address->id_state),
                "Zip" => $address->postcode,
                "Country" => Tools::strtoupper(Country::getIsoById($address->id_country)),
                "Phone" => $phone,
                "Email" => $customer->email,
                "Vat" => $address->vat_number,
            ),
            "Weight" => static::round1f(
                max($dimensions['weight'], self::DEFAULT_MIN_WEIGHT)
            ),

            "WeightUnit" => "kg",
            "Length" => $dimensions['depth'],
            "Width" => $dimensions['width'],
            "Height" => $dimensions['height'],
            "DimUnit" => "cm",

            "Value" => static::round2f($order->total_products_wt),
            "Currency" => $order_currency['iso_code'],
            /*"CustomsDuty" => "",
            "Description" => "",*/
            "DeclarationType" => $declaration_type,
            "Products" => $products
        );

        $request_data_enc = array(
            "Apikey" => SpringXbsHelper::get(Springxbs::MODULE_API_KEY_NAME),
            "Command" => "OrderShipment",
            "Shipment" => $shipment
        );
        $api_result = $this->getApiData($request_data_enc, SpringXbsHelper::get(Springxbs::MODULE_TEST_MODE_NAME));

        if (isset($api_result['error'])) {
            return array('error' => $api_result['error']);
        }

        if (empty($api_result['Shipment']['TrackingNumber']) ||
            empty($api_result['Shipment']['LabelFormat']) ||
            empty($api_result['Shipment']['LabelImage']) ||
            empty($api_result['Shipment']['LabelType'])) {
            return array('error' => SpringXbsHelper::l('Fatal error, try later'));
        }

        $tracking_number = $api_result['Shipment']['TrackingNumber'];
        $this->saveNewShipment($id_order, $shipment, $api_result, $service_name, self::PARCEL_CREATED_CODE);

        if ($api_result['Shipment']['LabelType'] != 'TEMP') {//leave to reorder
            SpringTools::clearParcelDimensionsSession($id_order);
        }

        if (method_exists($order, 'setWsShippingNumber')) {
            $order->setWsShippingNumber($tracking_number);
        } else {
            empty($order->id) and $order->id = $id_order;
            SpringXbsHelper::setWsShippingNumber($order, $tracking_number);
        }

        $order->shipping_number = $tracking_number;
        $order->update();

        Hook::exec('actionAdminOrdersTrackingNumberUpdate', array(
            'order' => $order,
            'customer' => $customer,
            'carrier' => $carrier,
        ), null, false, true, false, $order->id_shop);

        $this->updateOrderState($order, self::PARCEL_CREATED_CODE);

        if ($output) {
            $this->outputLabel(
                $id_order,
                SpringXbsHelper::decodeData($api_result['Shipment']['LabelImage']),
                $api_result['Shipment']['LabelFormat']
            );
            exit();
        }

        return true;
    }

    /**
     * Void label by ajax
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function ajaxProcessVoidLabel()
    {
        $this->ajax = true;
        $id_order = (int)Tools::getValue('id_order');
        die(json_encode($this->voidShipment($id_order)));
    }

    /**
     * Reorder label by ajax
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function ajaxProcessReorderLabel()
    {
        $this->ajax = true;
        $id_order = (int)Tools::getValue('id_order');
        $void = $this->voidShipment($id_order);

        if ($void['error']) {
            return array('error' => $void['error']);
        }

        $result = $this->orderShipment($id_order, false);

        if ($result['error']) {
            return array('error' => $result['error']);
        }

        if (Tools::getValue('output')) {
            $shipment = $this->getShipment($id_order);
            $this->outputLabel(
                $id_order,
                SpringXbsHelper::decodeData($shipment['label']),
                $shipment['label_format']
            );
        }

        die("true");
    }

    /**
     * Update the status of the order
     *
     * @param Order $order
     * @param $status_code
     */
    protected function updateOrderState(Order &$order, $status_code)
    {
        \Db::getInstance()->execute("UPDATE " . _DB_PREFIX_ . "springxbs_shipment 
                SET status_code = " . (int)$status_code . " WHERE id_order=" . (int)$order->id);

        $_map = Springxbs::getStatusesMap();

        if (isset($_map[$status_code]) && $_map[$status_code] != $order->current_state) {
            if ($this->debug) {
                echo "Current status: " . $order->current_state . "\n";
                echo "New status: " . $_map[$status_code] . " ($status_code) \n";
                echo "Employee ID: " . (Context::getContext()->employee->id) . "\n";
            }
            if (!Context::getContext()->employee->id) {
                $em_id = (int)\Db::getInstance()->getValue("SELECT id_employee FROM
                " . _DB_PREFIX_ . "employee  WHERE id_profile IN (1,2) AND active=1 ORDER BY id_employee ASC");

                Context::getContext()->employee = new Employee($em_id);
                echo "Recovered Employee: " . (Context::getContext()->employee->id) . "\n";
            }
            $order->setCurrentState($_map[$status_code], Context::getContext()->employee->id);
        }
    }

    /**
     * Get label by API and output it
     *
     * @param $id_order
     * @param $service_name
     * @param $label_format
     * @throws PrestaShopException
     */
    protected function getLabelByApi($id_order, $service_name, $label_format)
    {
        $shipment = $this->getShipment($id_order, $service_name);
        $test_mode = SpringXbsHelper::get(Springxbs::MODULE_TEST_MODE_NAME);

        if (isset($shipment['test_mode']) && $shipment['test_mode'] != $test_mode) {
            $this->voidShipment($id_order);
            $this->orderShipment($id_order);//reorder shipment
            exit();
        }

        if (!$shipment) {
            exit('The shipment is not exists');
        }

        if ($shipment['label_format'] == $label_format) {
            $this->outputLabel(
                $id_order,
                SpringXbsHelper::decodeData($shipment['label']),
                $label_format
            );
        }

        $order = new Order($id_order);

        $tracking_items = array(
            array(
                "ShipperReference" =>
                    $order->reference . "-" . SpringXbsHelper::get(Springxbs::API_SPRING_SIDE_USER_ID)
            ),
        );

        $system_tn = SpringTools::getWsShippingNumber($id_order);
        $tracking_numbers = array_unique(array_filter(array_map('trim', array(
            $shipment['tracking_number'],
            $system_tn,
        ))));

        foreach ($tracking_numbers as $tracking_number) {
            $tracking_items[] = array(
                "TrackingNumber" => $tracking_number,
            );
        }

        foreach ($tracking_items as $shipment_data) {
            $request_data_enc = array(
                "Apikey" => SpringXbsHelper::get(Springxbs::MODULE_API_KEY_NAME),
                "Command" => "GetShipmentLabel",
                "Shipment" => $shipment_data,
            );
            $api_result = $this->getApiData($request_data_enc, $test_mode);

            if (!isset($api_result['Error']) && isset($api_result['Shipment'])) {
                //success
                \Db::getInstance()->execute(
                    "UPDATE " . _DB_PREFIX_ . "springxbs_shipment
                SET label = '" . pSQL($api_result['Shipment']['LabelImage']) . "',
                label_format = '" . pSQL($api_result['Shipment']['LabelFormat']) . "', 
                test_mode = " . (int)$test_mode . ", 
                error_level = " . (int)$api_result['ErrorLevel'] . " 
                WHERE id_order = " . (int)$id_order
                );

                $this->outputLabel(
                    $id_order,
                    SpringXbsHelper::decodeData($api_result['Shipment']['LabelImage']),
                    $api_result['Shipment']['LabelFormat']
                );
                return true;
            }
        }

        return $api_result['Error'] ?: $api_result['error'];//Response or network error
    }

    /**
     * Reorder labels
     *
     * @return bool
     * @throws PrestaShopException
     */
    public function reorderLabels()
    {
        if (!$this->reorder_labels) {
            return false;
        }

        foreach ($this->reorder_labels as $id_order => $tracking_number) {
            $shipment = $this->getShipment($id_order);
            $test_mode = $shipment['test_mode'];

            if (!$shipment) {
                continue;
            }

            $label_url = 'https://mailingtechnology.com/API/label_plenty.php?testMode=' . $test_mode
                . '&userId=api&tn=' . $tracking_number
                . '&format=' . $shipment['label_format']
                . '&base64=1&apikey=' . SpringXbsHelper::get(Springxbs::MODULE_API_KEY_NAME);

            $label_data = self::getUrl($label_url);

            if ($label_data['error'] || !$label_data['data']) {
                continue;
            }

            \Db::getInstance()->execute(
                "UPDATE " . _DB_PREFIX_ . "springxbs_shipment
                SET label = '" . pSQL($label_data['data']) . "', 
                tracking_number = '" . pSQL($tracking_number) . "'
                WHERE id_order = " . (int)$id_order
            );

            SpringXbsHelper::setWsShippingNumber($id_order, $tracking_number);
        }

        $this->reorder_labels = array();

        return true;
    }

    /**
     * Track shipment
     *
     * @param $id_order
     * @param bool $state_out
     * @return array|mixed
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function trackShipment($id_order, $state_out = false)
    {
        $order = new Order($id_order);

        if (!Validate::isLoadedObject($order)) {
            return array();
        }

        $shipment = $this->getShipment($id_order);

        if (!$shipment) {
            return array();
        }

        $time = time();

        if (!empty($shipment['tracking_events']) && $shipment['tracking_time'] > $time - 600) {
            return json_decode($shipment['tracking_events'], true);
        }

        $tracking_items = array(
            array(
                "ShipperReference" =>
                    $order->reference . "-" . SpringXbsHelper::get(Springxbs::API_SPRING_SIDE_USER_ID)
            ),
        );

        $system_tn = SpringTools::getWsShippingNumber($id_order);
        $tracking_numbers = array_unique(array_filter(array_map('trim', array(
            $shipment['tracking_number'],
            $system_tn,
        ))));

        foreach ($tracking_numbers as $tracking_number) {
            $tracking_items[] = array(
                "TrackingNumber" => $tracking_number,
            );
        }


        foreach ($tracking_items as $i => $shipment_data) {
            if ($state_out) {
                echo "Start $i ($id_order|-|$shipment[status_code])" . reset($shipment_data) . "\n";
            }
            $request_data_enc = array(
                "Apikey" => SpringXbsHelper::get(Springxbs::MODULE_API_KEY_NAME),
                "Command" => "TrackShipment",
                "Shipment" => $shipment_data
            );

            $api_result = $this->getApiData($request_data_enc, $shipment['test_mode']);

            if (!isset($api_result['Error']) && isset($api_result['Shipment']['Events'])) {
                //success
                if ($state_out && !$i && ($api_result['Shipment']['TrackingNumber'] != $shipment['tracking_number'] ||
                        $api_result['Shipment']['TrackingNumber'] != $system_tn)) {
                    echo "New tn:" . $api_result['Shipment']['TrackingNumber'] . "\n";
                    $this->reorder_labels[$id_order] = $api_result['Shipment']['TrackingNumber'];
                }

                if ($state_out) {
                    echo "Tracking number:" . $api_result['Shipment']['TrackingNumber'] . " - data received\n";
                }

                $events_encoded = json_encode($api_result['Shipment']['Events']);

                \Db::getInstance()->execute(
                    "UPDATE " . _DB_PREFIX_ . "springxbs_shipment 
                    SET tracking_events='" . pSQL($events_encoded) .
                    "', tracking_time=$time WHERE id_order=" . (int)$id_order
                );

                $last_event = end($api_result['Shipment']['Events']);

                if ($last_event) {
                    $status_code = $last_event['Code'];
                    if ($state_out) {
                        echo "Before order state\n";
                    }
                    try {
                        $this->updateOrderState($order, $status_code);
                    } catch (Exception $e) {
                        echo 'Exeption-updateOrderState:' . $e->getMessage() . "\n";
                    }
                    if ($state_out) {
                        echo "After order state\n";
                    }
                }

                return $api_result['Shipment']['Events'];
            }
        }

        if (isset($api_result['Error'])) {
            return $api_result;
        }

        return array();
    }

    /**
     * Void shipment
     *
     * @param $id_order
     * @return bool|mixed
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function voidShipment($id_order)
    {
        $order = new Order($id_order);

        if (!Validate::isLoadedObject($order)) {
            return array('error' => SpringXbsHelper::l('Order not found'));
        }

        $shipment = $this->getShipment($id_order);

        $tracking_items = array(
            array(
                "ShipperReference" =>
                    $order->reference . "-" . SpringXbsHelper::get(Springxbs::API_SPRING_SIDE_USER_ID)
            ),
        );

        $system_tn = SpringTools::getWsShippingNumber($id_order);
        $tracking_numbers = array_unique(array_filter(array_map('trim', array(
            $shipment['tracking_number'],
            $system_tn,
        ))));

        foreach ($tracking_numbers as $tracking_number) {
            $tracking_items[] = array(
                "TrackingNumber" => $tracking_number,
            );
        }


        foreach ($tracking_items as $shipment_data) {
            $request_data_enc = array(
                "Apikey" => SpringXbsHelper::get(Springxbs::MODULE_API_KEY_NAME),
                "Command" => "VoidShipment",
                "Shipment" => $shipment_data,
            );
            $api_result = $this->getApiData($request_data_enc, $shipment['test_mode']);

            if (!isset($api_result['Error']) && isset($api_result['Shipment'])) {
                //success
                \Db::getInstance()->execute(
                    "DELETE FROM " . _DB_PREFIX_ . "springxbs_shipment WHERE id_order=" . (int)$id_order
                );

                SpringXbsHelper::setWsShippingNumber($order, '');
                return true;
            }
        }

        return $api_result['Error'] ?: $api_result['error'];//Response or network error
    }

    /**
     * Get shipment by id, service name
     *
     * @param $id_order
     * @param bool $service_name
     * @param bool $label_format
     * @return array|bool|object|null
     * @throws PrestaShopException
     */
    public static function getShipmentStatic($id_order, $service_name = false, $label_format = false)
    {
        $id_order = (int)$id_order;
        $service_cond = '';

        if ($service_name) {
            $service_cond = "AND service = '" . pSQL($service_name) . "'";
        }

        $label_format_cond = '';

        if ($label_format) {
            $label_format_cond = "AND label_format = '" . pSQL($label_format) . "'";
        }

        $sql = "SELECT * FROM " . _DB_PREFIX_ . "springxbs_shipment 
                WHERE id_order = $id_order $service_cond $label_format_cond";

        return \Db::getInstance()->getRow($sql);
    }

    /**
     * get Shipment Dimensions
     *
     * @param $id_order
     * @return array|bool
     * @throws PrestaShopException
     */
    public static function getShipmentDimensions($id_order)
    {
        $shipment = self::getShipmentStatic($id_order);
        if (!$shipment) {
            return false;
        }
        $shipment = json_decode($shipment['data'], true);
        return SpringTools::normalizeDimensions(array(
            'weight' => $shipment['request_shipment']['Weight'],
            'depth' => $shipment['request_shipment']['Length'],
            'width' => $shipment['request_shipment']['Width'],
            'height' => $shipment['request_shipment']['Height'],
        ));
    }

    /**
     * Get shipment by id, service name, label format
     *
     * @param $id_order
     * @param bool $service_name
     * @param bool $label_format
     * @return array|bool|object|null
     * @throws PrestaShopException
     */
    protected function getShipment($id_order, $service_name = false, $label_format = false)
    {
        return self::getShipmentStatic($id_order, $service_name, $label_format);
    }

    /**
     * Save new shipment
     *
     * @param $id_order
     * @param $request_shipment
     * @param $api_response
     * @param $service_name
     * @param $status_code
     * @return bool|int|string
     * @throws PrestaShopException
     */
    private function saveNewShipment($id_order, $request_shipment, $api_response, $service_name, $status_code)
    {
        $id_order = (int)$id_order;

        if ($this->getShipment($id_order)) {
            return false;
        }

        if (!in_array($service_name, Springxbs::getCarrierServiceCodes())) {
            return false;
        }

        $date = date("Y-m-d H:i:s");
        $label = $api_response['Shipment']['LabelImage'];//base64 encoded
        $label_format = $api_response['Shipment']['LabelFormat'];
        $label_type = $api_response['Shipment']['LabelType'];

        unset($api_response['Shipment']['LabelImage'], $api_response['Shipment']['LabelType']);

        $db = \Db::getInstance();
        $sql = "INSERT INTO " . _DB_PREFIX_ . "springxbs_shipment 
                (id_order, test_mode, service, error_level, tracking_number, label_format, label_type, label, 
                    status_code, data, date_add, date_upd)
                VALUE (" . (int)$id_order . ",
                " . (int)SpringXbsHelper::get(Springxbs::MODULE_TEST_MODE_NAME) . ",
                '" . pSQL($service_name) . "',
                " . (int)$api_response['ErrorLevel'] . ",
                '" . pSQL($api_response['Shipment']['TrackingNumber']) . "',
                '" . pSQL($label_format) . "',
                '" . pSQL($label_type) . "',
                '" . pSQL($label) . "',
                " . (int)$status_code . ",
                '" . pSQL(json_encode(array(
                'request_shipment' => $request_shipment,
                'response_shipment' => $api_response['Shipment'],
            ))) . "',
                '" . pSQL($date) . "',
                '" . pSQL($date) . "')";

        $db->execute($sql);
        $insert_id = $db->Insert_ID();

        return $insert_id;
    }

    /**
     * Api requests log
     *
     * @param $test_mode
     * @param $error_level
     * @param $error
     * @param $command
     * @param $request
     * @param $response
     * @return bool
     */
    public function apiLog($test_mode, $error_level, $error, $command, $request, $response)
    {
        if (SpringXbsHelper::get(Springxbs::API_LOGGER_ENABLED) && $error_level) {
            if (class_exists('PrestaShopLogger')) {
                PrestaShopLogger::addLog(
                    Tools::ucfirst(Springxbs::$self_name) . " - $command: $error",
                    self::PRESTASHOP_LOGGER_ERROR,
                    $error_level,
                    null,
                    null,
                    true
                );
            } else {
                Logger::addLog(
                    Tools::ucfirst(Springxbs::$self_name) . " - $command: $error",
                    self::PRESTASHOP_LOGGER_ERROR,
                    $error_level,
                    null,
                    null,
                    true
                );
            }
        }

        $request = is_string($request) ? $request : var_export($request, true);
        $response = is_string($response) ? $response : var_export($response, true);
        $test_mode = (int)$test_mode;

        return true;
    }

    /**
     * Manage API request
     *
     * @param $data
     * @param $test_mode
     * @return array|mixed
     */
    protected function getApiData($data, $test_mode)
    {
        $api_url = $test_mode ? Springxbs::API_ACCESS_POINT_TEST : Springxbs::API_ACCESS_POINT_LIVE;

        if (is_array($data['Shipment'])) {
            $data['Shipment']['Source'] = 'prestashop';
        }

        $request_str = json_encode($data);

        if (!$request_str) {
            return array('error' => SpringXbsHelper::l('JSON encoding error'));
        }

        $response = self::getUrl($api_url, $request_str);
        $response_data = json_decode($response['data'], true);

        if (!empty($response['error']) ||
            !isset($response_data['ErrorLevel'], $response_data['Shipment'])) {
            $error_msg = "Fatal error, command is not complete";
            $error_msg .= !empty($response['error']) ? " - " . $response['error'] : '';
            $error_msg .= !empty($response_data['Error']) ? " - " . $response_data['Error'] : '';
            $this->apiLog(
                $test_mode,
                Springxbs::API_ERROR_LEVEL_FAILURE,
                $error_msg,
                $data['Command'],
                $request_str,
                $response['data']
            );

            return array('error' => $error_msg);
        }

        $this->apiLog(
            $test_mode,
            $response_data['ErrorLevel'],
            isset($response_data['Error']) ? $response_data['Error'] : '',
            $data['Command'],
            $request_str,
            $response['data']
        );

        return $response_data;
    }

    /**
     * Execute API request
     *
     * @param $url
     * @param $post_data
     * @return array
     */
    public static function getUrl($url, $post_data = false)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($post_data) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }

        $data = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        return array('data' => $data, 'error' => $error);
    }

    /**
     * Get a state id with its iso code.
     * @param $id_state
     * @param null $id_country
     * @return false|string|null
     */
    private function getStateIsoById($id_state, $id_country = null)
    {
        return Db::getInstance()->getValue('
		SELECT iso_code
		FROM ' . _DB_PREFIX_ . 'state
		WHERE id_state = \'' . pSQL($id_state) . '\'
		' . ($id_country ? 'AND id_country = ' . (int)$id_country : ''));
    }

    /**
     * Round float with precision
     *
     * @param $val
     * @return string
     */
    public static function round1f($val)
    {
        return sprintf('%.1f', (double)$val);
    }

    /**
     * Round float with precision
     *
     * @param $val
     * @return string
     */
    public static function round2f($val)
    {
        return sprintf('%.2f', (double)$val);
    }

    /**
     * Round float with precision
     *
     * @param $val
     * @return string
     */
    public static function round3f($val)
    {
        return sprintf('%.3f', (double)$val);
    }

    /**
     * Output label to screen or printer driver
     *
     * @param $id_order
     * @param $label
     * @param string $format
     * @param string $name
     * @throws PrestaShopException
     */
    protected function outputLabel($id_order, $label, $format = 'PNG', $name = 'label')
    {
        $id_order = (int)$id_order;
        $format = Tools::strtolower($format);

        if ($format == 'png') {
            header("Content-Type: image/png");
        } elseif ($format == 'jpg') {
            header("Content-Type: image/jpeg");
        } elseif ($format == 'bmp') {
            header("Content-Type: image/bmp");
        } elseif ($format == 'pdf') {
            header("Content-Type: application/pdf");
            header("Content-Disposition: inline; filename={$name}.pdf");
        } elseif ($format == "zpl300" || $format == "zpl200" || $format == "epl") {
            $format = str_replace(array('.', '/'), '', $format);
            $link = Springxbs::getZplLink($id_order, $format);
            Tools::redirectAdmin($link);
            exit();
        } else {
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename={$name}.{$format}");
        }

        echo $label;
        exit();
    }

    /**
     * @return string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function ajaxProcessChangeOrderCarrier()
    {
        $id_order = (int)Tools::getValue('id_order');

        $this->ajax = true;
        $order = new Order($id_order);

        if (!Validate::isLoadedObject($order)) {
            return '';
        }

        $id_carrier = (int)Tools::getValue('shipping_carrier');

        $order_carrier = new OrderCarrier($order->getIdOrderCarrier());

        $old_id_carrier = $order_carrier->id_carrier;
        if (!empty($id_carrier) && $old_id_carrier != $id_carrier) {
            $order->id_carrier = (int)$id_carrier;
            $order_carrier->id_carrier = (int)$id_carrier;
            $order_carrier->update();
            if (method_exists($order, 'refreshShippingCost')) {
                try {
                    $order->refreshShippingCost();
                } catch (Exception $e) {
                    //@todo
                }
            }
        }
        $order->update();
        Tools::redirectAdmin(
            Context::getContext()->link->getAdminLink('AdminOrders') .
            "&id_order=$id_order"
        );
    }

    /**
     * Save parcel dimensions
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public static function ajaxProcessSaveDimensionsTemporary()
    {
        $id_order = (int)Tools::getValue('id_order');
        $order = new Order($id_order);

        if (!Validate::isLoadedObject($order)) {
            exit(SpringXbsHelper::l('Order not exists'));
        }

        SpringTools::saveParcelDimensionsTemporary($id_order);
        exit(0);
    }
}
