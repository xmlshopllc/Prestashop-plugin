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
{if $progress > -1}
    <div class="progress"
         style="background-color: rgb(221, 221, 221); margin-bottom: 0px; {$border_style|escape:'htmlall':'UTF-8'}"
         title="{$test_mode_msg|escape:'htmlall':'UTF-8'}">
        <div
                class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"
                aria-valuenow="{$progress|escape:'htmlall':'UTF-8'}"
                style="width: {$progress|escape:'htmlall':'UTF-8'}%; text-align: left;">
            <a class="springxbs-tracking-number-here" href="{$carrier_tracking_url|escape:'htmlall':'UTF-8'}" data-istest="{if $test_mode_msg}1{/if}"
               target="_blank" rel="noopener noreferrer"
               style="color: rgb(255, 255, 255); text-shadow: rgb(0, 0, 0) -1px -1px 0px, rgb(0, 0, 0) 1px -1px 0px, rgb(0, 0, 0) -1px 1px 0px, rgb(0, 0, 0) 1px 1px 0px; padding-left: 5px; padding-right: 5px;">{$tracking_number|escape:'htmlall':'UTF-8'}</a>
        </div>
    </div>
{else}
    <div class="springxbs-tracking-number-here" data-istest="{if $test_mode_msg}1{/if}" style="cursor: auto;" title="{$not_tracked|escape:'htmlall':'UTF-8'}" data-toggle="tooltip">
        {$tracking_number|escape:'htmlall':'UTF-8'}
    </div>
{/if}