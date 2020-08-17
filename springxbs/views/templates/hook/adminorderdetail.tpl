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
<style>
    .hidden {
        display: none !important;
        visibility: hidden !important;
    }

    #springxbs-export-panel * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    #springxbs-export-panel *:before, #springxbs-export-panel *:after {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    #springxbs-export-panel img {
        vertical-align: middle;
    }

    #springxbs-export-panel a {
        color: #00aff0;
        text-decoration: none;
    }

    .springxbs-row {
        background: none;
        margin-left: -5px;
        margin-right: -5px;
        overflow: hidden;
    }

    .springxbs-row:after {
        clear: both;
    }

    .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12 {
        float: left;
        position: relative;
        min-height: 1px;
        padding-left: 5px;
        padding-right: 5px;
    }

    .springxbs-panel-heading {
        border: none;
        border-top-right-radius: 2px;
        border-top-left-radius: 2px;
        padding: 0 0 0 5px;
        margin: -20px -16px 15px -16px;
        font-size: 1.2em;
        line-height: 2.2em;
        height: 2.2em;
        text-transform: uppercase;
        border-bottom: solid 1px #eeeeee;
    }

    .springxbs-panel {
        position: relative;
        padding: 20px;
        margin-bottom: 20px;
        border: solid 1px #e6e6e6;
        background-color: white;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        -ms-border-radius: 5px;
        -o-border-radius: 5px;
        border-radius: 5px;
        -webkit-box-shadow: rgba(0, 0, 0, 0.1) 0 2px 0, white 0 0 0 3px inset;
        box-shadow: rgba(0, 0, 0, 0.1) 0 2px 0, white 0 0 0 3px inset;
    }

    .springxbs-panel .springxbs-panel {
        border: solid 1px #cccccc;
        -webkit-box-shadow: rgba(0, 0, 0, 0.1) 0 2px 0, white 0 0 0 3px inset;
        box-shadow: rgba(0, 0, 0, 0.1) 0 2px 0, white 0 0 0 3px inset;
    }

    .well {
        min-height: 20px;
        padding: 19px;
        margin-bottom: 20px;
        background-color: #fcfdfe;
        border: 1px solid #e1ebf5;
        border-radius: 3px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .table {
        background-color: transparent;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 5px;
        max-width: 100%;
        width: 100%;
    }

    .table thead > tr > th {
        background: transparent;
        border: none;
        font-weight: normal;
        vertical-align: top;
        border-bottom: solid 1px #a0d0eb;
        padding: 8px;
        line-height: 1.42857;
    }

    .table thead > tr > th span.title_box {
        color: #656565;
        display: block;
        word-wrap: nowrap;
        white-space: nowrap;
    }

    .table tbody > tr > td {
        border-top: none;
        color: #666666;
        background-color: white;
        padding: 3px 7px;
        vertical-align: middle;
        word-wrap: nowrap;
        font-size: 12px;
        border-bottom: solid 1px #eaedef;
        line-height: 1.42857;
    }

    svg {
        display: inline-block;
        height: inherit !important;
        user-select: none;
        width: inherit !important;
    }

    @media (min-width: 1200px) {
        .col-lg-4 {
            width: 33.33333%;
        }

        .col-lg-5 {
            width: 41.66667%;
        }

        .col-lg-7 {
            width: 58.33333%;
        }

        .col-lg-8 {
            width: 66.66667%;
        }
    }
    table.dimensions {
        margin-left: 0.5em;
    }
    table.dimensions{
        margin: 0.1em 0 0 0.2em;
    }
    table.dimensions td{
        padding-left: 0.7em;
    }
    .b {
        font-weight: 600;
    }
    .dimensions-container {
        margin-bottom: 0.2em;
        padding: 0.2em;
        background-color: #fcfdfe;
        border: 1px solid #e1ebf5;
    }
    .dim-inp {
        max-width: 5em !important;
    }
</style>
<script>
    function {$prefix|escape:'htmlall':'UTF-8'}_reloadAfter(link, selector) {
        $(link).hide();
        $('.void-label-progress').removeClass('hidden');
        $.ajax({
            type: 'get',
            url: link.href,
            success: function (res) {
                res = JSON.parse(res);

                if (res && res.error) {
                    alert(res.error);
                }
            }
        })
            .always(
                function () {
                    window.location.reload()
                }
            );

        return false;
    }

    function {$prefix|escape:'htmlall':'UTF-8'}_reloadDeferred(link) {
        {if !$is_shipment}
            var $form = $(link).closest('form');
            $form.attr('action', $form.attr('action') + '&' + $form.serialize());
            $form.submit();
        {/if}

        setTimeout(function () {
            $(link).parent().find('.label-progress').removeClass('hidden');
            $.get($('#label-check-link').attr('href'), function() {
                window.location.reload();
            });
        }, 300);

        {if !$is_shipment}
            return false;
        {else}
            return true;
        {/if}
    }
</script>
<div id="springxbs-export-panel">
    <div>
        <div class="springxbs-panel css-18xfwhk-Panel-Panel e11uoxxa2">
            <div class="springxbs-panel-heading css-1i3ls3p-PanelHeading e11uoxxa0">
                <img
                        src="/modules/springxbs/views/img/springxbsnl-grayscale.png" alt="" width="128" height="128"
                        style="height: 16px; width: 16px;"> Spring GDS
            </div>
            {if $messages}
                {*<p class="alert alert-danger">
                    <strong>Warning</strong>
                    {$error_msg|escape:'htmlall':'UTF-8'}
                </p>*}
                {$messages|escape:'htmlall':'UTF-8'}
            {/if}
            <div class="css-12ow375-Panel springxbs-row">
                <div class="css-1r7koq9-Panel col-lg-5  ">
                    <div class="springxbs-panel css-mpcyc0-Panel e11uoxxa2 springxbs-row">
                        <form id="popover_form_label" action="{$label_link|escape:'htmlall':'UTF-8'}" method="post" target="_blank">
                            <div class="col-lg-8">
                                {if !$is_shipment}
                                    <div class="dimensions-container">
                                        <div class="">Get label with <strong>Parcel Dimensions</strong> set below</div>
                                        <table class="dim-table">
                                            <tr>
                                                <td class="">
                                                    <label for="Parcel_dimensions_width" style="max-width: 6em !important;">width, cm</label>
                                                    <input class="dim-inp" id="Parcel_dimensions_width" type="number"
                                                           name="width" value="{$parcel_prefilled_dimensions['width']|floatval}">
                                                </td>
                                                <td class="">
                                                    <label for="Parcel_dimensions_height" style="max-width: 6em !important;">height, cm</label>
                                                    <input class="dim-inp" id="Parcel_dimensions_height" type="number"
                                                           name="height" value="{$parcel_prefilled_dimensions['height']|floatval}">
                                                </td>
                                                {if $psv_1_5}
                                            </tr><tr>
                                                {/if}
                                                <td class="">
                                                    <label for="Parcel_dimensions_length" style="max-width: 6em !important;">depth, cm</label>
                                                    <input class="dim-inp" id="Parcel_dimensions_length" type="number"
                                                           name="depth" value="{$parcel_prefilled_dimensions['depth']|floatval}">
                                                </td>
                                                <td class="">
                                                    <label for="Parcel_dimensions_weight" style="max-width: 6em !important;">weight, kg</label>
                                                    <input class="dim-inp" id="Parcel_dimensions_weight" type="number"
                                                           name="weight" value="{$parcel_prefilled_dimensions['weight']|floatval}">
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                {/if}
                                {if $parcel_dimensions}
                                <div class="dimensions-container">
                                    Ordered <strong>Parcel Dimensions</strong><br>
                                    <table class="dimensions">
                                        <tr>
                                            <td><span class="b">width</span> <br>{if $parcel_dimensions['width']}{$parcel_dimensions['width']|escape:'htmlall':'UTF-8'} cm{else}-{/if}</td>
                                            <td><span class="b">height</span> <br>{if $parcel_dimensions['height']}{$parcel_dimensions['height']|escape:'htmlall':'UTF-8'} cm{else}-{/if}</td>
                                            {if $psv_1_5}
                                        </tr><tr>
                                            {/if}
                                            <td><span class="b">depth</span> <br>{if $parcel_dimensions['depth']}{$parcel_dimensions['depth']|escape:'htmlall':'UTF-8'} cm{else}-{/if}</td>
                                            <td><span class="b">weight</span> <br>{if $parcel_dimensions['weight']}{$parcel_dimensions['weight']|escape:'htmlall':'UTF-8'} kg{else}-{/if}</td>
                                        </tr>
                                    </table>
                                </div>
                                {/if}
                                <div class="well css-1jfxmrc-ConceptViewAddress">
                                    <span>{$firstname|escape:'htmlall':'UTF-8'} {$lastname|escape:'htmlall':'UTF-8'}<br></span>
                                    <span>{$address1|escape:'htmlall':'UTF-8'}<br></span>
                                    <span>{$postcode|escape:'htmlall':'UTF-8'}<br></span>
                                    <span>{$city|escape:'htmlall':'UTF-8'} {$state|escape:'htmlall':'UTF-8'}<br></span>
                                    <span>{$country|escape:'htmlall':'UTF-8'}<br></span>
                                    <span>{$email|escape:'htmlall':'UTF-8'}<br></span>
                                    <span>{$order_reference|escape:'htmlall':'UTF-8'}</span>
                                </div>

                            </div>
                            <div class="css-1q4uqd2-Footer epcjjds5 col-lg-4">
                            <div class="form-group">
                                {if $label_exists_text}
                                    <h4 style="text-align: center; color: #00c700;">{$label_exists_text|escape:'htmlall':'UTF-8'}</h4>
                                {elseif $label_reprint_text}
                                    <h4 style="text-align: center; color: #c70000; border: 1px dashed #f00;">{$label_reprint_text|escape:'htmlall':'UTF-8'}</h4>
                                {/if}
                                <a href="{$label_check_link|escape:'htmlall':'UTF-8'}" id="label-check-link" style="display: none"></a>
                                <a href="{$label_link|escape:'htmlall':'UTF-8'}" target="_blank"
                                   onclick="{if !$is_shipment} return {$prefix|escape:'htmlall':'UTF-8'}_reloadDeferred(this) {/if};">
                                    <div class="" style="text-align: center">
                                        <svg version="1.0"
                                             width="51.2000000pt" height="40.0000000pt"
                                             viewBox="0 0 512.000000 400.000000"
                                             preserveAspectRatio="xMidYMid meet">
                                            <g transform="translate(0.000000,400.000000) scale(0.100000,-0.100000)"
                                               fill="#000000" stroke="none">
                                                <path d="M100 3981 c-19 -10 -48 -35 -65 -55 l-30 -38 0 -1888 0 -1888 30 -38
    c17 -20 46 -45 65 -55 34 -18 117 -19 2460 -19 2343 0 2426 1 2460 19 19 10
    48 35 65 55 l30 38 0 1888 0 1888 -30 38 c-17 20 -46 45 -65 55 -34 18 -117
    19 -2460 19 -2343 0 -2426 -1 -2460 -19z m4860 -1981 l0 -1840 -2400 0 -2400
    0 0 1840 0 1840 2400 0 2400 0 0 -1840z"/>
                                                <path d="M1134 3511 c-49 -12 -133 -62 -165 -98 -16 -19 -41 -57 -57 -86 l-27
    -52 0 -395 0 -395 34 -63 c38 -70 92 -120 167 -154 46 -21 65 -23 282 -26
    l232 -3 0 80 0 80 -234 3 c-348 4 -326 -27 -326 478 0 402 1 409 63 454 27 20
    44 21 263 24 l234 3 0 80 0 79 -217 -1 c-120 -1 -232 -4 -249 -8z"/>
                                                <path d="M1930 3507 c-50 -16 -143 -112 -158 -163 -17 -57 -17 -870 0 -926 15
    -50 78 -122 132 -151 38 -20 55 -22 216 -22 151 0 180 3 210 19 49 26 98 74
    123 121 22 39 22 48 22 495 0 447 0 456 -22 495 -28 53 -78 99 -133 124 -39
    18 -64 20 -200 20 -85 -1 -171 -6 -190 -12z m370 -167 c19 -19 20 -33 20 -460
    0 -427 -1 -441 -20 -460 -18 -18 -33 -20 -180 -20 -147 0 -162 2 -180 20 -19
    19 -20 33 -20 460 0 427 1 441 20 460 18 18 33 20 180 20 147 0 162 -2 180
    -20z"/>
                                                <path d="M2640 2880 l0 -640 258 0 c249 0 258 1 307 24 60 29 107 76 134 136
    20 43 21 64 21 474 0 307 -3 440 -12 470 -8 27 -31 60 -68 96 -36 37 -69 60
    -96 68 -28 8 -125 12 -293 12 l-251 0 0 -640z m540 460 c19 -19 20 -33 20
    -460 0 -427 -1 -441 -20 -460 -19 -19 -33 -20 -200 -20 l-180 0 0 480 0 480
    180 0 c167 0 181 -1 200 -20z"/>
                                                <path d="M3620 3501 c-19 -10 -48 -35 -65 -55 l-30 -38 -3 -516 c-3 -581 -6
    -553 72 -617 l38 -30 304 -3 304 -3 0 81 0 80 -280 0 -280 0 0 200 0 200 200
    0 200 0 0 80 0 80 -200 0 -200 0 0 200 0 200 280 0 280 0 0 80 0 80 -293 0
    c-266 0 -295 -2 -327 -19z"/>
                                                <path d="M480 3360 l0 -80 80 0 80 0 0 80 0 80 -80 0 -80 0 0 -80z"/>
                                                <path d="M4480 3360 l0 -80 80 0 80 0 0 80 0 80 -80 0 -80 0 0 -80z"/>
                                                <path d="M480 3040 l0 -80 80 0 80 0 0 80 0 80 -80 0 -80 0 0 -80z"/>
                                                <path d="M4480 3040 l0 -80 80 0 80 0 0 80 0 80 -80 0 -80 0 0 -80z"/>
                                                <path d="M480 2720 l0 -80 80 0 80 0 0 80 0 80 -80 0 -80 0 0 -80z"/>
                                                <path d="M4480 2720 l0 -80 80 0 80 0 0 80 0 80 -80 0 -80 0 0 -80z"/>
                                                <path d="M480 2400 l0 -80 80 0 80 0 0 80 0 80 -80 0 -80 0 0 -80z"/>
                                                <path d="M4480 2400 l0 -80 80 0 80 0 0 80 0 80 -80 0 -80 0 0 -80z"/>
                                                <path d="M320 2000 l0 -80 2240 0 2240 0 0 80 0 80 -2240 0 -2240 0 0 -80z"/>
                                                <path d="M480 1120 l0 -640 160 0 160 0 0 640 0 640 -160 0 -160 0 0 -640z"/>
                                                <path d="M960 1280 l0 -480 80 0 80 0 0 480 0 480 -80 0 -80 0 0 -480z"/>
                                                <path d="M1280 1280 l0 -480 160 0 160 0 0 480 0 480 -160 0 -160 0 0 -480z"/>
                                                <path d="M1760 1280 l0 -480 80 0 80 0 0 480 0 480 -80 0 -80 0 0 -480z"/>
                                                <path d="M2080 1120 l0 -640 80 0 80 0 0 640 0 640 -80 0 -80 0 0 -640z"/>
                                                <path d="M2400 1280 l0 -480 160 0 160 0 0 480 0 480 -160 0 -160 0 0 -480z"/>
                                                <path d="M2880 1280 l0 -480 80 0 80 0 0 480 0 480 -80 0 -80 0 0 -480z"/>
                                                <path d="M3200 1280 l0 -480 160 0 160 0 0 480 0 480 -160 0 -160 0 0 -480z"/>
                                                <path d="M3680 1280 l0 -480 80 0 80 0 0 480 0 480 -80 0 -80 0 0 -480z"/>
                                                <path d="M4000 1280 l0 -480 80 0 80 0 0 480 0 480 -80 0 -80 0 0 -480z"/>
                                                <path d="M4320 1120 l0 -640 160 0 160 0 0 640 0 640 -160 0 -160 0 0 -640z"/>
                                                <path d="M960 560 l0 -80 80 0 80 0 0 80 0 80 -80 0 -80 0 0 -80z"/>
                                                <path d="M1280 560 l0 -80 160 0 160 0 0 80 0 80 -160 0 -160 0 0 -80z"/>
                                                <path d="M1760 560 l0 -80 80 0 80 0 0 80 0 80 -80 0 -80 0 0 -80z"/>
                                                <path d="M2400 560 l0 -80 160 0 160 0 0 80 0 80 -160 0 -160 0 0 -80z"/>
                                                <path d="M2880 560 l0 -80 80 0 80 0 0 80 0 80 -80 0 -80 0 0 -80z"/>
                                                <path d="M3200 560 l0 -80 160 0 160 0 0 80 0 80 -160 0 -160 0 0 -80z"/>
                                                <path d="M3680 560 l0 -80 80 0 80 0 0 80 0 80 -80 0 -80 0 0 -80z"/>
                                                <path d="M4000 560 l0 -80 80 0 80 0 0 80 0 80 -80 0 -80 0 0 -80z"/>
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="" style="text-align: center">
                                        {$label_text|escape:'htmlall':'UTF-8'}<br>
                                        <span class="hidden label-progress" style="text-align: right;">
                                        <img width="25" height="25"
                                             src="/modules/springxbs/views/img/lg.rotating-balls-spinner.gif"
                                             alt="">
                                        in progress
                                        </span>
                                    </div>

                                </a>
                                <div class="" style="text-align: center; color: red">
                                    {$test_mode_msg|escape:'htmlall':'UTF-8'}
                                </div>

                                {if $error_level}
                                    <div class="" style="position: relative; bottom:-30px;right:0;">
                                        <div class="hidden reorder-label-progress" style="text-align: right;">
                                            <img width="25" height="25"
                                                 src="/modules/springxbs/views/img/lg.rotating-balls-spinner.gif"
                                                 alt="">
                                            {l s='in progress' mod='springxbs'}
                                        </div>
                                        <div class="" style="text-align: right;">
                                            <a href="{$reprint_by_api_link|escape:'htmlall':'UTF-8'}"
                                               onclick="return {$prefix|escape:'htmlall':'UTF-8'}_reloadAfter(this, '.reorder-label-progress');">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     xmlns:xlink="http://www.w3.org/1999/xlink" width="10pt"
                                                     height="10pt" viewBox="0 0 12 12" version="1.1">
                                                    <g id="surface1">
                                                        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(0%,0%,0%);fill-opacity:1;"
                                                              d="M 5.277344 6.007812 C 5.210938 6.007812 5.15625 6.0625 5.15625 6.128906 C 5.160156 6.488281 5.019531 6.828125 4.769531 7.082031 C 4.519531 7.339844 4.183594 7.480469 3.824219 7.484375 C 3.464844 7.484375 3.125 7.347656 2.871094 7.097656 C 2.613281 6.847656 2.472656 6.507812 2.46875 6.148438 C 2.46875 5.789062 2.605469 5.453125 2.855469 5.195312 C 3.105469 4.9375 3.441406 4.796875 3.804688 4.796875 C 3.871094 4.796875 3.925781 4.742188 3.925781 4.675781 C 3.925781 4.609375 3.871094 4.554688 3.804688 4.554688 C 3.378906 4.558594 2.980469 4.726562 2.683594 5.027344 C 2.386719 5.328125 2.222656 5.730469 2.226562 6.152344 C 2.230469 6.578125 2.398438 6.972656 2.699219 7.269531 C 3 7.566406 3.402344 7.730469 3.824219 7.726562 C 4.25 7.722656 4.644531 7.554688 4.941406 7.253906 C 5.238281 6.953125 5.402344 6.554688 5.398438 6.128906 C 5.398438 6.0625 5.34375 6.007812 5.277344 6.007812 Z M 5.277344 6.007812 "/>
                                                        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(0%,0%,0%);fill-opacity:1;"
                                                              d="M 7.34375 6.824219 L 6.875 6.628906 C 6.8125 6.605469 6.742188 6.632812 6.71875 6.695312 C 6.691406 6.757812 6.722656 6.828125 6.78125 6.851562 L 7.25 7.046875 C 7.355469 7.089844 7.40625 7.210938 7.359375 7.316406 L 7.152344 7.824219 C 7.109375 7.925781 6.988281 7.976562 6.882812 7.933594 L 6.414062 7.738281 C 6.363281 7.71875 6.300781 7.734375 6.269531 7.785156 C 6.050781 8.109375 5.777344 8.382812 5.457031 8.597656 C 5.40625 8.628906 5.390625 8.6875 5.410156 8.742188 L 5.605469 9.210938 C 5.625 9.261719 5.625 9.316406 5.605469 9.367188 C 5.585938 9.417969 5.542969 9.460938 5.492188 9.480469 L 4.984375 9.691406 C 4.933594 9.710938 4.878906 9.710938 4.828125 9.691406 C 4.777344 9.667969 4.734375 9.628906 4.714844 9.578125 L 4.523438 9.113281 C 4.5 9.058594 4.441406 9.027344 4.386719 9.039062 C 4.003906 9.117188 3.617188 9.117188 3.238281 9.039062 C 3.179688 9.027344 3.125 9.058594 3.101562 9.113281 L 2.90625 9.578125 C 2.863281 9.683594 2.742188 9.734375 2.640625 9.691406 L 2.132812 9.480469 C 2.027344 9.433594 1.976562 9.316406 2.019531 9.210938 L 2.214844 8.742188 C 2.238281 8.691406 2.21875 8.628906 2.171875 8.597656 C 1.847656 8.378906 1.570312 8.105469 1.359375 7.785156 C 1.324219 7.734375 1.265625 7.71875 1.210938 7.738281 L 0.742188 7.933594 C 0.691406 7.953125 0.636719 7.953125 0.585938 7.933594 C 0.535156 7.914062 0.496094 7.875 0.476562 7.824219 L 0.265625 7.3125 C 0.246094 7.261719 0.246094 7.207031 0.265625 7.15625 C 0.285156 7.105469 0.324219 7.066406 0.375 7.042969 L 0.84375 6.851562 C 0.894531 6.828125 0.925781 6.773438 0.914062 6.714844 C 0.839844 6.332031 0.839844 5.949219 0.914062 5.566406 C 0.925781 5.507812 0.898438 5.453125 0.84375 5.429688 L 0.375 5.238281 C 0.269531 5.195312 0.21875 5.074219 0.261719 4.96875 L 0.472656 4.460938 C 0.515625 4.355469 0.636719 4.308594 0.742188 4.351562 L 1.207031 4.546875 C 1.261719 4.566406 1.324219 4.550781 1.355469 4.5 C 1.570312 4.175781 1.847656 3.902344 2.167969 3.6875 C 2.214844 3.65625 2.234375 3.59375 2.210938 3.539062 L 2.019531 3.074219 C 2 3.023438 2 2.96875 2.019531 2.917969 C 2.039062 2.867188 2.078125 2.824219 2.128906 2.804688 L 2.640625 2.59375 C 2.6875 2.574219 2.746094 2.574219 2.796875 2.59375 C 2.847656 2.617188 2.886719 2.65625 2.90625 2.707031 L 3.101562 3.171875 C 3.128906 3.234375 3.199219 3.265625 3.257812 3.238281 C 3.320312 3.210938 3.351562 3.140625 3.324219 3.082031 L 3.128906 2.613281 C 3.082031 2.503906 3 2.417969 2.886719 2.371094 C 2.832031 2.347656 2.773438 2.335938 2.714844 2.335938 C 2.65625 2.335938 2.597656 2.347656 2.542969 2.371094 L 2.035156 2.582031 C 1.921875 2.628906 1.839844 2.710938 1.792969 2.824219 C 1.746094 2.933594 1.746094 3.054688 1.792969 3.167969 L 1.949219 3.542969 C 1.660156 3.746094 1.414062 3.996094 1.207031 4.285156 L 0.832031 4.128906 C 0.71875 4.082031 0.597656 4.082031 0.488281 4.128906 C 0.375 4.175781 0.289062 4.261719 0.246094 4.371094 L 0.0351562 4.878906 C -0.0625 5.105469 0.046875 5.371094 0.277344 5.464844 L 0.652344 5.621094 C 0.59375 5.96875 0.59375 6.320312 0.652344 6.667969 L 0.277344 6.824219 C 0.167969 6.867188 0.0820312 6.953125 0.0390625 7.066406 C -0.0078125 7.175781 -0.0078125 7.296875 0.0390625 7.410156 L 0.25 7.917969 C 0.296875 8.027344 0.378906 8.113281 0.492188 8.160156 C 0.601562 8.207031 0.722656 8.207031 0.835938 8.160156 L 1.210938 8.003906 C 1.414062 8.292969 1.664062 8.539062 1.953125 8.746094 L 1.792969 9.121094 C 1.699219 9.351562 1.808594 9.613281 2.035156 9.707031 L 2.542969 9.917969 C 2.773438 10.011719 3.035156 9.902344 3.128906 9.675781 L 3.285156 9.300781 C 3.636719 9.359375 3.988281 9.359375 4.335938 9.300781 L 4.488281 9.675781 C 4.535156 9.785156 4.621094 9.871094 4.730469 9.917969 C 4.84375 9.964844 4.964844 9.964844 5.074219 9.917969 L 5.585938 9.707031 C 5.695312 9.660156 5.78125 9.578125 5.828125 9.464844 C 5.875 9.355469 5.875 9.234375 5.828125 9.121094 L 5.671875 8.746094 C 5.957031 8.542969 6.207031 8.292969 6.414062 8.003906 L 6.789062 8.160156 C 6.898438 8.207031 7.023438 8.207031 7.132812 8.160156 C 7.242188 8.113281 7.328125 8.027344 7.375 7.917969 L 7.585938 7.410156 C 7.679688 7.183594 7.570312 6.917969 7.34375 6.824219 Z M 7.34375 6.824219 "/>
                                                        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(0%,0%,0%);fill-opacity:1;"
                                                              d="M 7.078125 1.832031 C 6.453125 1.832031 5.941406 2.34375 5.941406 2.96875 C 5.941406 3.59375 6.453125 4.105469 7.078125 4.105469 C 7.703125 4.105469 8.214844 3.59375 8.214844 2.96875 C 8.214844 2.34375 7.703125 1.832031 7.078125 1.832031 Z M 7.078125 3.859375 C 6.585938 3.859375 6.183594 3.460938 6.183594 2.96875 C 6.183594 2.472656 6.585938 2.074219 7.078125 2.074219 C 7.570312 2.074219 7.972656 2.472656 7.972656 2.96875 C 7.972656 3.460938 7.570312 3.859375 7.078125 3.859375 Z M 7.078125 3.859375 "/>
                                                        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(0%,0%,0%);fill-opacity:1;"
                                                              d="M 9.640625 2.410156 L 9.371094 2.410156 C 9.316406 2.171875 9.222656 1.949219 9.09375 1.742188 L 9.285156 1.550781 C 9.351562 1.484375 9.390625 1.394531 9.390625 1.296875 C 9.390625 1.203125 9.351562 1.113281 9.285156 1.042969 L 9 0.761719 C 8.933594 0.691406 8.84375 0.65625 8.746094 0.65625 C 8.652344 0.65625 8.5625 0.691406 8.492188 0.761719 L 8.300781 0.949219 C 8.09375 0.824219 7.871094 0.730469 7.636719 0.671875 L 7.636719 0.40625 C 7.636719 0.207031 7.472656 0.046875 7.277344 0.046875 L 6.875 0.046875 C 6.679688 0.046875 6.515625 0.207031 6.515625 0.40625 L 6.515625 0.675781 C 6.28125 0.730469 6.058594 0.824219 5.851562 0.953125 L 5.660156 0.761719 C 5.589844 0.695312 5.503906 0.65625 5.40625 0.65625 C 5.308594 0.65625 5.21875 0.695312 5.152344 0.761719 L 4.867188 1.046875 C 4.800781 1.113281 4.761719 1.203125 4.761719 1.300781 C 4.761719 1.394531 4.800781 1.484375 4.867188 1.554688 L 5.058594 1.746094 C 4.929688 1.953125 4.839844 2.175781 4.78125 2.410156 L 4.511719 2.410156 C 4.3125 2.410156 4.152344 2.574219 4.152344 2.769531 L 4.152344 3.171875 C 4.152344 3.367188 4.3125 3.53125 4.511719 3.53125 L 4.78125 3.53125 C 4.835938 3.765625 4.929688 3.988281 5.058594 4.199219 L 4.867188 4.386719 C 4.800781 4.457031 4.761719 4.546875 4.761719 4.640625 C 4.761719 4.738281 4.800781 4.828125 4.867188 4.898438 L 5.152344 5.179688 C 5.21875 5.25 5.308594 5.285156 5.40625 5.285156 C 5.503906 5.285156 5.589844 5.25 5.660156 5.179688 L 5.851562 4.988281 C 6.058594 5.117188 6.28125 5.207031 6.515625 5.265625 L 6.515625 5.535156 C 6.515625 5.734375 6.679688 5.894531 6.875 5.894531 L 7.277344 5.894531 C 7.472656 5.894531 7.636719 5.734375 7.636719 5.535156 L 7.636719 5.265625 C 7.871094 5.210938 8.09375 5.117188 8.300781 4.988281 L 8.492188 5.179688 C 8.5625 5.25 8.648438 5.285156 8.746094 5.285156 C 8.84375 5.285156 8.933594 5.25 9 5.179688 L 9.285156 4.898438 C 9.351562 4.828125 9.390625 4.738281 9.390625 4.640625 C 9.390625 4.546875 9.351562 4.457031 9.285156 4.386719 L 9.09375 4.199219 C 9.222656 3.988281 9.3125 3.765625 9.371094 3.53125 L 9.640625 3.53125 C 9.839844 3.53125 10 3.367188 10 3.171875 L 10 2.769531 C 9.996094 2.570312 9.839844 2.410156 9.640625 2.410156 Z M 9.636719 3.285156 L 9.269531 3.285156 C 9.210938 3.285156 9.164062 3.324219 9.152344 3.382812 C 9.097656 3.652344 8.992188 3.910156 8.835938 4.140625 C 8.804688 4.191406 8.808594 4.253906 8.851562 4.292969 L 9.109375 4.554688 C 9.132812 4.578125 9.144531 4.605469 9.144531 4.636719 C 9.144531 4.667969 9.132812 4.699219 9.109375 4.722656 L 8.828125 5.003906 C 8.804688 5.027344 8.777344 5.039062 8.746094 5.039062 C 8.714844 5.039062 8.683594 5.027344 8.664062 5.003906 L 8.402344 4.742188 C 8.359375 4.703125 8.296875 4.695312 8.25 4.730469 C 8.015625 4.882812 7.761719 4.988281 7.488281 5.042969 C 7.433594 5.054688 7.390625 5.105469 7.390625 5.164062 L 7.390625 5.53125 C 7.390625 5.597656 7.339844 5.648438 7.273438 5.648438 L 6.875 5.648438 C 6.808594 5.648438 6.757812 5.597656 6.757812 5.53125 L 6.757812 5.160156 C 6.757812 5.101562 6.714844 5.054688 6.660156 5.042969 C 6.386719 4.988281 6.128906 4.882812 5.898438 4.726562 C 5.851562 4.695312 5.785156 4.699219 5.746094 4.742188 L 5.484375 5 C 5.464844 5.023438 5.433594 5.035156 5.402344 5.035156 C 5.371094 5.035156 5.34375 5.023438 5.320312 5 L 5.039062 4.71875 C 5.015625 4.695312 5.003906 4.667969 5.003906 4.636719 C 5.003906 4.605469 5.015625 4.574219 5.039062 4.554688 L 5.296875 4.292969 C 5.339844 4.253906 5.34375 4.1875 5.3125 4.140625 C 5.15625 3.90625 5.050781 3.652344 4.996094 3.378906 C 4.984375 3.324219 4.9375 3.28125 4.878906 3.28125 L 4.511719 3.28125 C 4.445312 3.28125 4.394531 3.230469 4.394531 3.164062 L 4.394531 2.765625 C 4.394531 2.699219 4.445312 2.648438 4.511719 2.648438 L 4.878906 2.648438 C 4.9375 2.648438 4.984375 2.605469 4.996094 2.550781 C 5.050781 2.277344 5.15625 2.019531 5.3125 1.789062 C 5.34375 1.742188 5.339844 1.679688 5.296875 1.636719 L 5.039062 1.375 C 5.015625 1.355469 5.003906 1.328125 5.003906 1.292969 C 5.003906 1.261719 5.015625 1.234375 5.039062 1.210938 L 5.320312 0.929688 C 5.34375 0.90625 5.371094 0.894531 5.402344 0.894531 C 5.433594 0.894531 5.464844 0.90625 5.484375 0.929688 L 5.746094 1.1875 C 5.785156 1.230469 5.851562 1.234375 5.898438 1.203125 C 6.132812 1.046875 6.386719 0.941406 6.660156 0.886719 C 6.714844 0.875 6.757812 0.828125 6.757812 0.769531 L 6.757812 0.40625 C 6.757812 0.34375 6.808594 0.289062 6.875 0.289062 L 7.273438 0.289062 C 7.339844 0.289062 7.390625 0.34375 7.390625 0.40625 L 7.390625 0.773438 C 7.390625 0.832031 7.433594 0.882812 7.488281 0.894531 C 7.761719 0.949219 8.019531 1.054688 8.25 1.210938 C 8.269531 1.222656 8.292969 1.230469 8.316406 1.230469 C 8.34375 1.230469 8.378906 1.21875 8.398438 1.191406 L 8.660156 0.933594 C 8.683594 0.910156 8.710938 0.898438 8.742188 0.898438 C 8.773438 0.898438 8.804688 0.910156 8.824219 0.933594 L 9.109375 1.214844 C 9.132812 1.238281 9.144531 1.265625 9.144531 1.296875 C 9.144531 1.328125 9.132812 1.359375 9.109375 1.378906 L 8.847656 1.640625 C 8.808594 1.683594 8.800781 1.746094 8.835938 1.796875 C 8.988281 2.027344 9.09375 2.28125 9.148438 2.554688 C 9.160156 2.613281 9.210938 2.652344 9.269531 2.652344 L 9.636719 2.652344 C 9.699219 2.652344 9.753906 2.703125 9.753906 2.769531 L 9.753906 3.167969 C 9.753906 3.230469 9.703125 3.285156 9.636719 3.285156 Z M 9.636719 3.285156 "/>
                                                    </g>
                                                </svg>

                                                {$reprint_by_api_link_text|escape:'htmlall':'UTF-8'}
                                            </a>
                                        </div>
                                    </div>
                                {/if}

                                {if $void_show}
                                    <div class="" style="position: relative; bottom:-30px;right:0;">
                                        <div class="hidden void-label-progress" style="text-align: right;">
                                            <img width="25" height="25"
                                                 src="/modules/springxbs/views/img/lg.rotating-balls-spinner.gif"
                                                 alt="">
                                            {l s='in progress' mod='springxbs'}
                                        </div>
                                        <div class="" style="text-align: right;">
                                            <a href="{$void_label_link|escape:'htmlall':'UTF-8'}"
                                               onclick="return {$prefix|escape:'htmlall':'UTF-8'}_reloadAfter(this, '.void-label-progress');">
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     width="10px" height="10px" viewBox="0 0 64 64"
                                                     enable-background="new 0 0 64 64" xml:space="preserve">
                                            <path fill="none" stroke="#000000" stroke-width="2" stroke-miterlimit="10"
                                                  d="M53.919,10.08c12.108,12.106,12.108,31.733,0,43.84
                                                c-12.105,12.107-31.732,12.107-43.838,0c-12.108-12.106-12.108-31.733,0-43.84C22.187-2.027,41.813-2.027,53.919,10.08z"/>
                                                    <line fill="none" stroke="#000000" stroke-width="2"
                                                          stroke-miterlimit="10"
                                                          x1="10.08" y1="10.08" x2="53.92" y2="53.92"/>
                                            </svg>
                                                {$void_label_text|escape:'htmlall':'UTF-8'}
                                            </a>
                                        </div>
                                    </div>
                                {/if}
                            </div>
                        </div>
                        </form>
                    </div>
                </div>


                <div class="css-1r7koq9-Panel  col-lg-7">
                    <div class="springxbs-panel css-mpcyc0-Panel e11uoxxa2">
                        <div class="springxbs-panel-heading css-1i3ls3p-PanelHeading e11uoxxa0">
                            <div>
                                <svg height="14" aria-hidden="true" focusable="false" data-prefix="fas"
                                     data-icon="truck"
                                     class="svg-inline--fa fa-truck fa-w-20 " role="img"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                    <path fill="currentColor"
                                          d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48v320c0 26.5 21.5 48 48 48h16c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm320 0c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z"></path>
                                </svg>
                                {l s='Tracking events' mod='springxbs'}
                            </div>
                        </div>
                        <div>
                            <div class="table-responsive css-14bl0dh-PreAlertedTable">
                                <table class="table css-1p96tch-PreAlertedTable">
                                    <thead>
                                    <tr>
                                        <th><span class="title_box">{l s='Date/time' mod='springxbs'}</span></th>
                                        <th><span class="title_box">{l s='Location' mod='springxbs'}</span></th>
                                        <th><span class="title_box">{l s='Activity' mod='springxbs'}</span></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        {if !$tracking_data_rows}
                                        <td class="list-empty hidden-print" colspan="100">
                                            <div class="list-empty-msg css-rfm137-PreAlertedTable">
                                                <svg height="14" aria-hidden="true" focusable="false" data-prefix="fas"
                                                     data-icon="exclamation-triangle"
                                                     class="svg-inline--fa fa-exclamation-triangle fa-w-18 fa-4x "
                                                     role="img" xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 576 512">
                                                    <path fill="currentColor"
                                                          d="M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path>
                                                </svg>
                                                <div>{l s='There are no shipments' mod='springxbs'}</div>
                                            </div>
                                        </td>
                                        {else}
                                        {foreach $tracking_data as $row}
                                    <tr>
                                        <td>{$row['DateTime']|escape:'htmlall':'UTF-8'}</td>
                                        <td>{$row['Country']|escape:'htmlall':'UTF-8'}</td>
                                        <td>{$row['Description']|escape:'htmlall':'UTF-8'}</td>
                                    </tr>
                                    {/foreach}
                                    {/if}

                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>