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
    <h3><i class="icon icon-wrench"></i> {$title|escape:'htmlall':'UTF-8'}</h3>
    <br>
    <div class="">
        <a href="{$back_url|escape:'htmlall':'UTF-8'}" style="color: #00f;text-decoration: underline;"><i class="icon-backward"></i> {$text_url|escape:'htmlall':'UTF-8'}</a>
    </div>
    <br>
    <table class="table tableDnD carrier">
        <thead>
        <tr class="nodrag nodrop">
            <th class="fixed-width-xs center">
                <span class="title_box">
                    ID
                </span>
            </th>
            <th class="">
                <span class="title_box">
                    {l s='Name' mod='springxbs'}
                </span>
            </th>
            <th class="center">
                <span class="title_box">
                    {l s='Logo' mod='springxbs'}
                </span>
            </th>
            <th class="">
                <span class="title_box">
                    {l s='Delay' mod='springxbs'}
                </span>
            </th>
            <th class="center">
                <span class="title_box">
                    {l s='Status' mod='springxbs'} ({l s='read only' mod='springxbs'})
                </span>
            </th>
            <th class=""></th>
        </tr>
        </thead>

        <tbody>
        {foreach from=$carriers key=key item=carrier}
            <tr class="{if $key%2!=1}odd{/if}" style="height: 4em;">
                <td class=" center">
                    {$carrier.id|escape:'htmlall':'UTF-8'}
                </td>
                <td class="">
                    {$carrier.name|escape:'htmlall':'UTF-8'}
                </td>
                <td class=" center">
                    <img src="{$carrier.logo_src|escape:'htmlall':'UTF-8'}" alt="" class="imgm img-thumbnail" />
                </td>
                <td class="">
                    {$carrier.delay|escape:'htmlall':'UTF-8'}
                </td>
                <td class="center">
                    {if $carrier.active == 1}
                        <div class="">
                            <i class="icon-check"></i>
                        </div>
                    {elseif $carrier.active == -1}
                        <div class="">
                            <i class="icon-briefcase"></i>
                        </div>
                    {else}
                        <div class="">
                            <i class="icon-remove"></i>
                        </div>
                    {/if}
                </td>
                <td class="">
                    {if $carrier.active == -1}
                        <form action="{$form_url|escape:'htmlall':'UTF-8'}&code={$carrier.service_code|escape:'htmlall':'UTF-8'}" method="post">
                            <div class="btn-group pull-right">
                                <a href="{$form_url|escape:'htmlall':'UTF-8'}&code={$carrier.service_code|escape:'htmlall':'UTF-8'}"
                                   class="edit btn btn-default">
                                    <i class="icon-floppy-o"></i> {l s='Install carrier' mod='springxbs'}
                                </a>
                            </div>
                        </form>
                    {/if}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>