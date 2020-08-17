{*
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
*  @author Xmlshop LLC <tsuren@xmlshop.com>
*  @copyright  PostNL
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  @version  1.3.3
*}
<script>
    {foreach from=$carriers key="id_carrier" item="service_code"}
    var img = document.querySelector('img[src*="/{$id_carrier|escape:'htmlall':'UTF-8'}."]');
    if (img) {
        img.src = '{$src|escape:'htmlall':'UTF-8'}?service={$service_code|escape:'htmlall':'UTF-8'}&country={$country_iso|escape:'htmlall':'UTF-8'}&get_carrier_logo=1';
        img.style['max-width'] = '55px';
        img.style['max-height'] = '55px';
    }
    {/foreach}
</script>