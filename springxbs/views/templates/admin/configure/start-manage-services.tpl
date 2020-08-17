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
<div class="panel">
    <form action="{$form_url|escape:'htmlall':'UTF-8'}" method="post">
        <h3><i class="icon icon-wrench"></i> {$title|escape:'htmlall':'UTF-8'}</h3>
        <br>
        <div class="alert alert-info" role="alert">
            <p>{$help_msg|escape:'htmlall':'UTF-8'}</p>
        </div>
        <div class="margin-form" style="clear: both;">
            <button type="submit" class="btn btn-primary btn-lg">{$btn_msg|escape:'htmlall':'UTF-8'}</button>
        </div>
        <br>
    </form>
</div>

