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
    <div class="alert alert-info" role="alert">
        <p>
            {if $language_iso_code == 'es'}
        <ol style="{if $pisv15}list-style: decimal;{/if}">
            <li>Descargue una lista de sus productos con PRODUCTOS DE EXPORTACION CSV</li>
            <li>
                Abra el archivo .csv y cambie estas tres columnas
                <ul style="{if $pisv15}list-style: circle;margin-left: 2em;{/if}">
                    <li>HS Code / Código HS</li>
                    <li>Country of Origin Code(NL, GB, FR...) / Código del país de origen</li>
                    <li>International Description / Descripción internacional</li>
                </ul>
            </li>
            <li>Guarde y suba el archivo</li>
        </ol>
        {elseif $language_iso_code == 'fr'}
        <ol style="{if $pisv15}list-style: decimal;{/if}">
            <li>Téléchargez une liste de vos produits avec EXPORTER DES PRODUITS CSV</li>
            <li>
                Ouvrez le fichier .csv et modifiez ces trois colonnes
                <ul style="{if $pisv15}list-style: circle;margin-left: 2em;{/if}">
                    <li>HS Code / HS Code</li>
                    <li>Country of Origin Code(NL, GB, FR...) / Code du pays d'origine</li>
                    <li>International Description / Description internationale</li>
                </ul>
            </li>
            <li>Enregistrer et télécharger le fichier</li>
        </ol>
        {else}
        <ol style="{if $pisv15}list-style: decimal;{/if}">
            <li>Download a list of your products with EXPORT PRODUCTS CSV</li>
            <li>
                Open the .csv file and change these three columns
                <ul style="{if $pisv15}list-style: circle;margin-left: 2em;{/if}">
                    <li>HS Code</li>
                    <li>Country of Origin Code(NL, GB, FR...)</li>
                    <li>International Description</li>
                </ul>
            </li>
            <li>Save and upload the file</li>
        </ol>
        {/if}
        </p>
    </div>
    <div class="clear: both;" style="padding-bottom: 5em;">
        <div class="margin-form" style="margin-bottom: 2em;">
            <form action="{$form_url|escape:'htmlall':'UTF-8'}" target="_blank" method="post">
                <button type="submit" class="btn btn-primary btn-lg">{$btn_msg|escape:'htmlall':'UTF-8'}</button>
            </form>
        </div>
        <div class="margin-form" style="">
            <form action="{$form_url_upload|escape:'htmlall':'UTF-8'}" method="post" enctype="multipart/form-data">
                <div class="">
                    {if $isv15}
                        <input type="file" name="csv_file" accept=".csv">
                        <button type="submit" class="btn btn-primary btn-lg">{$btn_import_msg|escape:'htmlall':'UTF-8'}</button>
                    {else}
                    <div class="" style="float: left; margin-right: 2em;">
                        <label for="Springxbs_csv_file_input" class="p-0" style="padding: 0">
                        <div class=" btn btn-info {if $isv16}btn-lg{/if} pointer" id="page-header-desc-configuration-add_module">
                            <input type="file" style="display: none" name="csv_file" accept=".csv" id="Springxbs_csv_file_input"
                                   onchange="this.files.length ? document.getElementById('Springxbs_file_name_csv').innerHTML = this.files[0].name : '{$btn_file_placeholder|escape:'htmlall':'UTF-8'}'">
                            <div class="" style="display: inline-block;"><i class="material-icons">cloud_upload</i></div><div class="" id="Springxbs_file_name_csv" style="display: inline-block;position: relative;{if !$isv16}bottom: 5px;{/if}padding-left: 5px;">{$btn_file_placeholder|escape:'htmlall':'UTF-8'}</div>
                        </div>
                        </label>
                    </div>
                    <div class="" style="float: left;">
                        <button type="submit" class="btn btn-primary btn-lg">{$btn_import_msg|escape:'htmlall':'UTF-8'}</button>
                    </div>
                    {/if}
                </div>
            </form>
        </div>
    </div>

</div>

