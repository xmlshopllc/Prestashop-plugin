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
*  @version  1.3.1
*}
<style>
    .d-flex {
        display: -webkit-box !important;
        display: -ms-flexbox !important;
        display: flex !important
    }

    .justify-content-around {
        -ms-flex-pack: distribute !important;
        justify-content: space-around !important
    }
</style>
<template id="menu_item_springxbs">
    <li id="springxbsBulkConcepts">
        <a href="#" onclick="$('#springxbsModalDialog').modal('show'); return false;" id="springxbsBulkConceptsLink"
           style="cursor: pointer"><img src="/modules/springxbs/logo.png" width="16" height="16"> {l s='Print Labels Bulk' mod='springxbs'}</a>
    </li>
</template>

<template id="textModeEl">
    <span class="text-danger"> - {l s='Test Mode'|escape:'htmlall' mod='springxbs'}</span>
</template>

<div class="modal fade" id="springxbsModalDialog" tabindex="-1" role="dialog"
     aria-labelledby="springxbsModalDialogLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header position-relative" style="padding: 10px;">
                <h3 class="modal-title" id="springxbsModalDialogLabel" style="display: inline-block;"><img src="/modules/springxbs/views/img/logo.png" width="30" height="30"> SpringXBS {l s='Print Labels Bulk' mod='springxbs'}</h3>
                <button type="button" class="close position-absolute" data-dismiss="modal" aria-label="Close"
                        style="top: 0px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-around">
                    {foreach $label_formats_list as $format}
                        <div class="">
                            <a data-href="{$format.link|escape:'htmlall':'UTF-8'}" href="" target="_blank"
                               class="springxbs-bulk-selector springxbs-{$format.name|escape:'htmlall':'UTF-8'}-selector btn btn-sm btn-primary">{$format.name|escape:'htmlall':'UTF-8'}</a>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .hidden {
        display: none !important;
    }
    .springxbs-modal-labels {
        list-style: none;
    }
</style>
<script>
    $(".btn-group.bulk-actions > ul.dropdown-menu").append($('#menu_item_springxbs').html());


    $('#springxbsModalDialog').on('show.bs.modal', function () {
        var tns_list = [],
            tns_list_test = [],
            html_list = [],
            testMode_text = $('#textModeEl').html();
        $(this).closest('form').find("[name='orderBox[]']:checked").each(function () {
                var $tnEl = $(this).closest('tr').find('.springxbs-tracking-number-here');
                if (!$tnEl.length) {
                    return;
                }
                var tn = $tnEl.html().replace(/^\s*/, '').replace(/\s*$/, ''),
                    isTest = !!$tnEl.data('istest');
            if (tn) {
                    if (!isTest) {
                        tns_list.push(tn);
                    } else {
                        tns_list_test.push(tn);
                    }
                    html_list.push('<li>' + tn + (isTest ? testMode_text : '') + '</li>')
                }
            }
        );

        $('.springxbs-bulk-selector').each(function () {
            $(this).attr('href', $(this).data('href') + tns_list.join(',') +
                (tns_list_test.length ? '&tns_test=' + tns_list_test.join(',') : '')
            );
        });

        $('#springxbsModalDialog .modal-body').html(
            html_list.length ? '<ul class="springxbs-modal-labels">' + html_list.join('') + '</ul>' : '{l s='No labels selected' mod='springxbs'}'
        );
        $('#springxbsModalDialog .modal-footer').toggleClass('hidden', !html_list.length)
    });

</script>