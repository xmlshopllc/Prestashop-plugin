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
{if $popover_flag}
    <style>
        .spring-orders-grid-icon {
            padding: 2px;
            border: solid 1px rgba(0, 0, 0, .1);
        }

        .spring-orders-grid-icon i {
            font-size: 16pt !important;
            color: #6f6f6f;
        }

        .spring-orders-grid-icon:hover {
            background-color: #00aff0;
            border-color: #008abd;
        }

        .spring-orders-grid-icon:hover i {
            color: #fff;
        }

        .spring-orders-grid-icon:hover svg {
            fill: #fff;
        }

        .bootstrap .popover {
            -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
            background-clip: padding-box;
            background-color: #fff;
            border: 1px solid #ccc;
            border: 1px solid rgba(0, 0, 0, .2);
            border-radius: 6px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
            display: none;
            left: 0;
            max-width: 276px;
            padding: 1px;
            position: absolute;
            text-align: left;
            top: 0;
            white-space: normal;
            z-index: 1060;
        }

        .bootstrap .popover-content {
            padding: 9px 14px;
        }

        .bootstrap .popover > .arrow, .bootstrap .popover > .arrow:after {
            border-color: transparent;
            border-style: solid;
            display: block;
            height: 0;
            position: absolute;
            width: 0;
        }

        .bootstrap .arrow:after, .bootstrap .arrow:before {
            border-color: transparent;
            border-style: solid;
            content: "";
            display: inline-block;
            position: absolute;
        }

        .bootstrap .popover > .arrow {
            border-width: 11px;
        }

        .bootstrap .popover.left > .arrow {
            border-left-color: #999;
            border-left-color: rgba(0, 0, 0, .05);
            border-right-width: 0;
            margin-top: -11px;
            right: -11px;
            top: 50%;
        }

        .dim-inp {
            max-width: 5em !important;
        }

        div.image-save {
            width: 28px;
            height: 30px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
            background-image: url(data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgNjQgNjQiIHdpZHRoPSI1MTJweCI+PGcgaWQ9IkZyb3BweV9kaXNrIiBkYXRhLW5hbWU9IkZyb3BweSBkaXNrIj48cGF0aCBkPSJtNDkgM3YxN2EyIDIgMCAwIDEgLTIgMmgtMzBhMiAyIDAgMCAxIC0yLTJ2LTE3aC04YTQgNCAwIDAgMCAtNCA0djUwYTQgNCAwIDAgMCA0IDRoMnYtMjZhNCA0IDAgMCAxIDQtNGgzOGE0IDQgMCAwIDEgNCA0djI2aDJhNCA0IDAgMCAwIDQtNHYtNDZsLTgtOHoiIGZpbGw9IiM5YmM5ZmYiLz48cGF0aCBkPSJtMzkgN2g2djExaC02eiIgZmlsbD0iIzliYzlmZiIvPjxnIGZpbGw9IiMxZTgxY2UiPjxwYXRoIGQ9Im02MS43MDcgMTAuMjkzLTgtOGExIDEgMCAwIDAgLS43MDctLjI5M2gtNDZhNS4wMDYgNS4wMDYgMCAwIDAgLTUgNXY1MGE1LjAwNiA1LjAwNiAwIDAgMCA1IDVoNTBhNS4wMDYgNS4wMDYgMCAwIDAgNS01di00NmExIDEgMCAwIDAgLS4yOTMtLjcwN3ptLTEzLjcwNy02LjI5M3YxNmExIDEgMCAwIDEgLTEgMWgtMzBhMSAxIDAgMCAxIC0xLTF2LTE2em0tMzggNTZ2LTI1YTMgMyAwIDAgMSAzLTNoMzhhMyAzIDAgMCAxIDMgM3YyNXptNTAtM2EzIDMgMCAwIDEgLTMgM2gtMXYtMjVhNS4wMDYgNS4wMDYgMCAwIDAgLTUtNWgtMzhhNS4wMDYgNS4wMDYgMCAwIDAgLTUgNXYyNWgtMWEzIDMgMCAwIDEgLTMtM3YtNTBhMyAzIDAgMCAxIDMtM2g3djE2YTMgMyAwIDAgMCAzIDNoMzBhMyAzIDAgMCAwIDMtM3YtMTZoMi41ODZsNy40MTQgNy40MTR6Ii8+PHBhdGggZD0ibTM5IDE5aDZhMSAxIDAgMCAwIDEtMXYtMTFhMSAxIDAgMCAwIC0xLTFoLTZhMSAxIDAgMCAwIC0xIDF2MTFhMSAxIDAgMCAwIDEgMXptMS0xMWg0djloLTR6Ii8+PHBhdGggZD0ibTQ3IDQ1aC0zMGExIDEgMCAwIDAgMCAyaDMwYTEgMSAwIDAgMCAwLTJ6Ii8+PHBhdGggZD0ibTQ3IDM5aC0zMGExIDEgMCAwIDAgMCAyaDMwYTEgMSAwIDAgMCAwLTJ6Ii8+PHBhdGggZD0ibTQ3IDUxaC0zMGExIDEgMCAwIDAgMCAyaDMwYTEgMSAwIDAgMCAwLTJ6Ii8+PC9nPjwvZz48L3N2Zz4K);
        }

        div.image-save.not-edited {
            background-image: url(data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgNjQgNjQiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+PGcgaWQ9IkZyb3BweV9kaXNrIiBkYXRhLW5hbWU9IkZyb3BweSBkaXNrIj48cGF0aCBkPSJNNjEuNzA3LDEwLjI5M2wtOC04QTEsMSwwLDAsMCw1MywySDdBNS4wMDYsNS4wMDYsMCwwLDAsMiw3VjU3YTUuMDA2LDUuMDA2LDAsMCwwLDUsNUg1N2E1LjAwNiw1LjAwNiwwLDAsMCw1LTVWMTFBMSwxLDAsMCwwLDYxLjcwNywxMC4yOTNaTTQ4LDRWMjBhMSwxLDAsMCwxLTEsMUgxN2ExLDEsMCwwLDEtMS0xVjRaTTEwLDYwVjM1YTMsMywwLDAsMSwzLTNINTFhMywzLDAsMCwxLDMsM1Y2MFptNTAtM2EzLDMsMCwwLDEtMywzSDU2VjM1YTUuMDA2LDUuMDA2LDAsMCwwLTUtNUgxM2E1LjAwNiw1LjAwNiwwLDAsMC01LDVWNjBIN2EzLDMsMCwwLDEtMy0zVjdBMywzLDAsMCwxLDcsNGg3VjIwYTMsMywwLDAsMCwzLDNINDdhMywzLDAsMCwwLDMtM1Y0aDIuNTg2TDYwLDExLjQxNFoiIGZpbGw9IiMwMDAwMDAiLz48cGF0aCBkPSJNMzksMTloNmExLDEsMCwwLDAsMS0xVjdhMSwxLDAsMCwwLTEtMUgzOWExLDEsMCwwLDAtMSwxVjE4QTEsMSwwLDAsMCwzOSwxOVpNNDAsOGg0djlINDBaIiBmaWxsPSIjMDAwMDAwIi8+PHBhdGggZD0iTTQ3LDQ1SDE3YTEsMSwwLDAsMCwwLDJINDdhMSwxLDAsMCwwLDAtMloiIGZpbGw9IiMwMDAwMDAiLz48cGF0aCBkPSJNNDcsMzlIMTdhMSwxLDAsMCwwLDAsMkg0N2ExLDEsMCwwLDAsMC0yWiIgZmlsbD0iIzAwMDAwMCIvPjxwYXRoIGQ9Ik00Nyw1MUgxN2ExLDEsMCwwLDAsMCwySDQ3YTEsMSwwLDAsMCwwLTJaIiBmaWxsPSIjMDAwMDAwIi8+PC9nPjwvc3ZnPgo=);
        }

        div.image-save:hover {
            background-image: url(data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgNjQgNjQiIHdpZHRoPSI1MTJweCI+PGcgaWQ9IkZyb3BweV9kaXNrIiBkYXRhLW5hbWU9IkZyb3BweSBkaXNrIj48cGF0aCBkPSJtNDkgM2gtNDJhNCA0IDAgMCAwIC00IDR2NTBhNCA0IDAgMCAwIDQgNGg1MGE0IDQgMCAwIDAgNC00di00NmwtOC04eiIgZmlsbD0iI2QxZTdmOCIvPjxwYXRoIGQ9Im00OSAzdjE3YTIuMDA2IDIuMDA2IDAgMCAxIC0yIDJoLTMwYTIuMDA2IDIuMDA2IDAgMCAxIC0yLTJ2LTE3eiIgZmlsbD0iI2QxZDNkNCIvPjxwYXRoIGQ9Im0zOSA3aDZ2MTFoLTZ6IiBmaWxsPSIjNmQ2ZTcxIi8+PHBhdGggZD0ibTUxIDMxaC0zOGE0IDQgMCAwIDAgLTQgNHYyNmg0NnYtMjZhNCA0IDAgMCAwIC00LTR6IiBmaWxsPSIjMjQ4OGZmIi8+PHBhdGggZD0ibTMgNTZ2MWE0IDQgMCAwIDAgNCA0aDJ2LTV6IiBmaWxsPSIjYmRkYmZmIi8+PHBhdGggZD0ibTU1IDV2NTZoMmE0IDQgMCAwIDAgNC00di00NnoiIGZpbGw9IiNiZGRiZmYiLz48cGF0aCBkPSJtNDcgNTZoLTM4djVoNDZ2LTEzYTggOCAwIDAgMSAtOCA4eiIgZmlsbD0iIzAwNmRmMCIvPjxnIGZpbGw9IiMyMzFmMjAiPjxwYXRoIGQ9Im02MS43MDcgMTAuMjkzLTgtOGExIDEgMCAwIDAgLS43MDctLjI5M2gtNDZhNS4wMDYgNS4wMDYgMCAwIDAgLTUgNXY1MGE1LjAwNiA1LjAwNiAwIDAgMCA1IDVoNTBhNS4wMDYgNS4wMDYgMCAwIDAgNS01di00NmExIDEgMCAwIDAgLS4yOTMtLjcwN3ptLTEzLjcwNy02LjI5M3YxNmExIDEgMCAwIDEgLTEgMWgtMzBhMSAxIDAgMCAxIC0xLTF2LTE2em0tMzggNTZ2LTI1YTMgMyAwIDAgMSAzLTNoMzhhMyAzIDAgMCAxIDMgM3YyNXptNTAtM2EzIDMgMCAwIDEgLTMgM2gtMXYtMjVhNS4wMDYgNS4wMDYgMCAwIDAgLTUtNWgtMzhhNS4wMDYgNS4wMDYgMCAwIDAgLTUgNXYyNWgtMWEzIDMgMCAwIDEgLTMtM3YtNTBhMyAzIDAgMCAxIDMtM2g3djE2YTMgMyAwIDAgMCAzIDNoMzBhMyAzIDAgMCAwIDMtM3YtMTZoMi41ODZsNy40MTQgNy40MTR6Ii8+PHBhdGggZD0ibTM5IDE5aDZhMSAxIDAgMCAwIDEtMXYtMTFhMSAxIDAgMCAwIC0xLTFoLTZhMSAxIDAgMCAwIC0xIDF2MTFhMSAxIDAgMCAwIDEgMXptMS0xMWg0djloLTR6Ii8+PHBhdGggZD0ibTQ3IDQ1aC0zMGExIDEgMCAwIDAgMCAyaDMwYTEgMSAwIDAgMCAwLTJ6Ii8+PHBhdGggZD0ibTQ3IDM5aC0zMGExIDEgMCAwIDAgMCAyaDMwYTEgMSAwIDAgMCAwLTJ6Ii8+PHBhdGggZD0ibTQ3IDUxaC0zMGExIDEgMCAwIDAgMCAyaDMwYTEgMSAwIDAgMCAwLTJ6Ii8+PC9nPjwvZz48L3N2Zz4K);
        }

        div.image-save.loading {
            transform: rotate(999999deg);
            transition: transform 1500s;
            background-image: url(data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgMjYuMzQ5IDI2LjM1IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAyNi4zNDkgMjYuMzU7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8Zz4KCQk8Y2lyY2xlIGN4PSIxMy43OTIiIGN5PSIzLjA4MiIgcj0iMy4wODIiIGZpbGw9IiMwMDAwMDAiLz4KCQk8Y2lyY2xlIGN4PSIxMy43OTIiIGN5PSIyNC41MDEiIHI9IjEuODQ5IiBmaWxsPSIjMDAwMDAwIi8+CgkJPGNpcmNsZSBjeD0iNi4yMTkiIGN5PSI2LjIxOCIgcj0iMi43NzQiIGZpbGw9IiMwMDAwMDAiLz4KCQk8Y2lyY2xlIGN4PSIyMS4zNjUiIGN5PSIyMS4zNjMiIHI9IjEuNTQxIiBmaWxsPSIjMDAwMDAwIi8+CgkJPGNpcmNsZSBjeD0iMy4wODIiIGN5PSIxMy43OTIiIHI9IjIuNDY1IiBmaWxsPSIjMDAwMDAwIi8+CgkJPGNpcmNsZSBjeD0iMjQuNTAxIiBjeT0iMTMuNzkxIiByPSIxLjIzMiIgZmlsbD0iIzAwMDAwMCIvPgoJCTxwYXRoIGQ9Ik00LjY5NCwxOS44NGMtMC44NDMsMC44NDMtMC44NDMsMi4yMDcsMCwzLjA1YzAuODQyLDAuODQzLDIuMjA4LDAuODQzLDMuMDUsMGMwLjg0My0wLjg0MywwLjg0My0yLjIwNywwLTMuMDUgICAgQzYuOTAyLDE4Ljk5Niw1LjUzNywxOC45ODgsNC42OTQsMTkuODR6IiBmaWxsPSIjMDAwMDAwIi8+CgkJPGNpcmNsZSBjeD0iMjEuMzY0IiBjeT0iNi4yMTgiIHI9IjAuOTI0IiBmaWxsPSIjMDAwMDAwIi8+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==);
        }

        div.image-save.loaded {
            background-image: url(data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgaWQ9IkNhcGFfMSIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgNTEwIDUxMCIgaGVpZ2h0PSI1MTJweCIgdmlld0JveD0iMCAwIDUxMCA1MTAiIHdpZHRoPSI1MTJweCI+PHJhZGlhbEdyYWRpZW50IGlkPSJTVkdJRF8xXyIgY3g9IjI1NS4zNjIiIGN5PSIxNzcuMjQiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIiByPSIyMDguNDIyIj48c3RvcCBvZmZzZXQ9IjAiIHN0b3AtY29sb3I9IiNmY2NlY2UiLz48c3RvcCBvZmZzZXQ9Ii41Mzc5IiBzdG9wLWNvbG9yPSIjZmNjY2NkIi8+PHN0b3Agb2Zmc2V0PSIuNzMxNyIgc3RvcC1jb2xvcj0iI2ZjYzVjYiIvPjxzdG9wIG9mZnNldD0iLjg2OTQiIHN0b3AtY29sb3I9IiNmYmJhYzgiLz48c3RvcCBvZmZzZXQ9Ii45ODAzIiBzdG9wLWNvbG9yPSIjZmJhOWMzIi8+PHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjZmJhNWMyIi8+PC9yYWRpYWxHcmFkaWVudD48bGluZWFyR3JhZGllbnQgaWQ9ImxnMSI+PHN0b3Agb2Zmc2V0PSIwIiBzdG9wLWNvbG9yPSIjZmNjZWNlIi8+PHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjZmJhNWMyIi8+PC9saW5lYXJHcmFkaWVudD48bGluZWFyR3JhZGllbnQgaWQ9IlNWR0lEXzJfIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjI5NS4yMTgiIHgyPSIyNjUuODc4IiB4bGluazpocmVmPSIjbGcxIiB5MT0iOTMuMzE0IiB5Mj0iMTM3LjMyNSIvPjxsaW5lYXJHcmFkaWVudCBpZD0iU1ZHSURfM18iIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIiB4MT0iMzU0LjM2NSIgeDI9IjI4OC4zNjUiIHhsaW5rOmhyZWY9IiNsZzEiIHkxPSIxMTUuODk3IiB5Mj0iMTQ4Ljg5NyIvPjxsaW5lYXJHcmFkaWVudCBpZD0iU1ZHSURfNF8iIGdyYWRpZW50VHJhbnNmb3JtPSJtYXRyaXgoMCAxIDEgMCAtNTA2LjExOCA1MDYuMTE4KSIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiIHgxPSItMTA5LjU0NCIgeDI9IjEwLjQ1MSIgeTE9IjY3Mi41IiB5Mj0iODg3Ljk0NiI+PHN0b3Agb2Zmc2V0PSIwIiBzdG9wLWNvbG9yPSIjM2ZhOWY1Ii8+PHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjNjY2YWQ2Ii8+PC9saW5lYXJHcmFkaWVudD48bGluZWFyR3JhZGllbnQgaWQ9IlNWR0lEXzVfIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjQxNy43NDEiIHgyPSI2Ny43NDEiIHkxPSI0OTkuMzU0IiB5Mj0iMjk3LjM1NCI+PHN0b3Agb2Zmc2V0PSIwIiBzdG9wLWNvbG9yPSIjM2I4ZWFjIiBzdG9wLW9wYWNpdHk9IjAiLz48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiMzNDMxNjgiLz48L2xpbmVhckdyYWRpZW50PjxwYXRoIGQ9Im00MDQuMzA3IDI0MC45OC0xOS4wMi03NS40Yy0xMS4zMDUtNDQuODE2LTMxLjEwNS04Ny4wNDctNTguMzI1LTEyNC40MDJsLTI1LjYyOS0zNS4xNzNjLTYuMDk1LTguMzY1LTE4LjcxNi03LjkxMy0yNC4xOTcuODY2LTExLjE1MSAxNy44Ni0xMC41MjUgNDAuNjYgMS41ODcgNTcuODgxbDEzLjk4NSAxOS44ODRjLS4wMTItLjAxLS4wMjMtLjAyLS4wMzUtLjAyOS0yLjAzMi0xLjY3Ny00LjExNC0zLjM1Mi02LjE5Ny00Ljk3OGwtMzQuMzM3LTI2LjcxOGMtOC4xNzgtNi40LTIwLjE2NS0yLjQ4OS0yMy4wMSA3LjQ2Ny0xLjM3MiA0Ljc3NC0yLjAzMiA5LjY1MS0yLjAzMiAxNC40NzcgMCAxNS41OTQgNi45NTkgMzAuNjI5IDE5LjUwNSA0MC43MzdsMzEuMzkxIDI1LjM0N2M1Ljg4NyA0Ljc0NSAxMS40NTYgOS44NDkgMTYuNjgyIDE1LjI3M2wtMzAuMzU5LTExLjg1MmMtMjEuOTk5LTguNTg4LTQ2LjIwOS05LjcyMS02OC45MTQtMy4yMjRsLTY0Ljk4NiAyMi40NzljLTguMzY3IDIuMzk0LTEyLjI0NyAxMi4wMjMtNy44NzcgMTkuNTUgMTIuNzA2IDIxLjg4MyAzOS4xMTQgMzEuNzI3IDYzLjA0NCAyMy41MDFsMzAuMDgzLTEzLjU3NmMxMy4zODgtNC42MDIgMjguMTY2LTIuNTg2IDM5LjgzMiA1LjQzNGwzMi43NDEgMjIuNTFjMTAuMDY5IDYuOTIyIDE1LjM0NSAxOC45NTMgMTMuNjE3IDMxLjA0OC0xLjQyMyA5Ljk1OS03LjQyNCAxOC42NzgtMTYuMjE4IDIzLjU2NGwtNDYuODMyIDI2LjAxOGMtMTguMTg2IDEwLjEwNC00MC44NDYgNy4wODUtNTUuNzUzLTcuNDI3LTguODYyLTguNjI2LTEzLjk3Ny0yMC4zOS0xNC4yNDItMzIuNzU0bC4zNzQtMi44MzJjNC40NTctMzMuNzU0LTIyLjQwNS02My40NTctNTYuNDM1LTYyLjQwNS05LjM4MS4yOS0xNi42NDUgOC4zMTMtMTYuMDA2IDE3LjY3N2w0LjYxNiA2Ny41OTJjLjkgMTMuMTgzIDUuNzcgMjUuNzg0IDEzLjk2OSAzNi4xNDdsNTQuMjc5IDY4LjZjOS42NzMgMTIuMjI1IDIyLjg0MSAyMS4yMTQgMzcuNzUgMjUuNzcgMzkuNDAxIDEyLjA0IDgxLjMzOCAxMy4xMTQgMTIxLjMwMyAzLjEwOGw2LjExNy0xLjUzMWMyMS43MzEtNS40NDEgNDAtMjAuMTA3IDUwLjAwOS00MC4xNDhsMTUuNDA0LTMwLjg0MWMxNi41ODUtMzMuMjA0IDE2LjgyNS03MC4wMDQgNC4xMTYtMTAxLjY0eiIgZmlsbD0idXJsKCNTVkdJRF8xXykiLz48cGF0aCBkPSJtMjQ2LjYwMyAxMTUuNTkzIDMxLjM5MSAyNS4zNDdjNS44ODcgNC43NDUgMTEuNDU2IDkuODQ5IDE2LjY4MiAxNS4yNzNsMzcuNjk4IDE0LjcxNy0xLjgxNC04LjIxOGMtNS4xNTEtMjMuMzM1LTE0Ljg4Mi00NS40MTYtMjguNjMtNjQuOTYzbC05LjIyMS0xMy4xMTFjLS4wMTItLjAxLS4wMjMtLjAyLS4wMzUtLjAyOS0yLjAzMi0xLjY3Ny00LjExNC0zLjM1Mi02LjE5Ny00Ljk3OGwtMzQuMzM3LTI2LjcxOGMtOC4xNzgtNi40LTIwLjE2NS0yLjQ4OS0yMy4wMSA3LjQ2Ny0xLjM3MiA0Ljc3NC0yLjAzMiA5LjY1MS0yLjAzMiAxNC40NzctLjAwMSAxNS41OTMgNi45NTggMzAuNjI4IDE5LjUwNSA0MC43MzZ6IiBmaWxsPSJ1cmwoI1NWR0lEXzJfKSIvPjxwYXRoIGQ9Im0zMDEuOTI5IDk3Ljc0OWMxMy43NDggMTkuNTQ3IDIzLjQ3OSA0MS42MjggMjguNjMgNjQuOTYzbDEuODE0IDguMjE4IDEuOTc4Ljc3MmMzMy4zNjcgMTMuMDI2IDU3LjY5MSAzOC43NDcgNjkuOTU2IDY5LjI3OGwtMTkuMDItNzUuNGMtMTEuMzA1LTQ0LjgxNi0zMS4xMDUtODcuMDQ3LTU4LjMyNS0xMjQuNDAybC0yNS42MjktMzUuMTczYy02LjA5NS04LjM2NS0xOC43MTYtNy45MTMtMjQuMTk3Ljg2Ni0xMS4xNTEgMTcuODYtMTAuNTI1IDQwLjY2IDEuNTg3IDU3Ljg4MXoiIGZpbGw9InVybCgjU1ZHSURfM18pIi8+PHBhdGggZD0ibTQwOC44OTUgNDgwLjMwMnYtNTEuMDEzYzAtMTYuNDAyLTEzLjI5Ni0yOS42OTgtMjkuNjk4LTI5LjY5OGgtMjE2LjU2MmMtMTYuNDAyIDAtMjkuNjk4IDEzLjI5Ni0yOS42OTggMjkuNjk4djUxLjAxM2MwIDE2LjQwMiAxMy4yOTYgMjkuNjk4IDI5LjY5OCAyOS42OThoMjE2LjU2M2MxNi40MDEgMCAyOS42OTctMTMuMjk2IDI5LjY5Ny0yOS42OTh6IiBmaWxsPSJ1cmwoI1NWR0lEXzRfKSIvPjxwYXRoIGQ9Im0zNzkuMTk4IDM5OS41OTJoLTE5Ni42NmwxLjk1Ny45OTQgMTA5LjQxNCAxMDkuNDE0aDg1LjI4OWMxNi40MDIgMCAyOS42OTgtMTMuMjk2IDI5LjY5OC0yOS42OTh2LTUxLjAxMmMtLjAwMS0xNi40MDItMTMuMjk3LTI5LjY5OC0yOS42OTgtMjkuNjk4eiIgZmlsbD0idXJsKCNTVkdJRF81XykiLz48L3N2Zz4K);
        }

    </style>
{/if}
<div style="position: relative">
    <script>
        window.springxbs_dimensionsedited_flag = 0;

        function {$prefix|escape:'htmlall':'UTF-8'}_reloadPage_{$id|intval}(obj, e) {
            if (window.springxbs_dimensionsedited_flag && !confirm('{$dimensions_not_saved_confirm|escape:'htmlall':'UTF-8'}')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            {if !$label_exists || $error_level_1}
            setTimeout(function () {
                $(obj).parent().find('.label-progress').removeClass('hidden');
                $.get($(obj).data('link'), function () {
                    window.location.reload();
                });
            }, 350);
            {/if}
            return true;
        }

        function {$prefix|escape:'htmlall':'UTF-8'}_reloadPage_form{$id|intval}(obj) {
            var $form = $(obj).closest('form');
            $form.attr('action', $form.attr('action') + '&' + $form.serialize());
            $form.submit();
            {if !$label_exists || $error_level_1}
            setTimeout(function () {
                $(obj).parent().find('.label-progress').removeClass('hidden');
                $.get($(obj).data('link'), function () {
                    window.location.reload();
                });
            }, 350);
            {/if}
            return true;
        }

        function {$prefix|escape:'htmlall':'UTF-8'}_ajax_reload_{$id|intval}() {
            {if !$label_exists || $error_level_1}
            setTimeout(function () {
                window.location.reload();
            }, 350);
            {/if}
            return true;
        }
        {if $label_exists && $void_label_link}
        function {$prefix|escape:'htmlall':'UTF-8'}_cancel_label_{$id|intval}(link, el) {
            $(el).addClass('hidden');
            $(el).parent().find('.void-label-progress').removeClass('hidden');
            $.get(link).done(function () {
                window.location.reload();
            });
            return false;
        }
        {/if}

        {if $popover_flag}
        function springXBSsaveDimensionsTemporary(formNode, url) {
            var $icon_image = $(formNode).find('.image-save');
            $icon_image.addClass('loading');
            $.get(
                url +
                '&weight=' + formNode.elements.weight.value +
                '&depth=' + formNode.elements.depth.value +
                '&height=' + formNode.elements.height.value +
                '&width=' + formNode.elements.width.value,
                function () {
                    $icon_image.removeClass('loading').addClass('loaded');
                    setTimeout(function () {
                        $icon_image.removeClass('loaded').addClass('not-edited');
                    }, 1500);
                    window.springxbs_dimensionsedited_flag = false;

                    var formEls = formNode.elements;
                    window['{$prefix|escape:'htmlall':'UTF-8'}dimensions_entered_' + $(formNode).data('id')] = {
                        weight: formEls.weight.value,
                        depth: formEls.depth.value,
                        height: formEls.height.value,
                        width: formEls.width.value
                    };
                },
            );
        }

        function springXBSdimensionsChanged(formNode) {
            $(formNode).find('.image-save').removeClass('not-edited');
            window.springxbs_dimensionsedited_flag = 1;
        }

        function springXBSdimensionsChangedCheck() {
            if (window.springxbs_dimensionsedited_flag) {
                return !!confirm('{$dimensions_not_saved_confirm|escape:'htmlall':'UTF-8'}');
            }
            return true;
        }
        {/if}
    </script>
    <button type="button" id="popovers_{$id|intval}"
            data-popover-id="{$id|intval}"
            style="border-color: transparent; background-color: {$label_link_bg|escape:'htmlall':'UTF-8'}; padding: 0; text-align: center; {$border_style|escape:'htmlall':'UTF-8'}">
        <i class="icon icon-info-circle" style="font-size: 17px; height: 17px; width: 17px;"></i>
    </button>
    {if $label_exists || !$cannot_order_shipment_text}
        <div style="display: inline-block">
        <span class="btn-group-action">
          <span class="btn-group">
            <a href="{$label_link|escape:'htmlall':'UTF-8'}"
               onclick="return {$prefix|escape:'htmlall':'UTF-8'}_reloadPage_{$id|intval}(this, event);" target="_blank"
               class="spring-orders-grid-icon"
               data-toggle="tooltip" title="{$label_link_title|escape:'htmlall':'UTF-8'}">
                {if !$label_exists}
                    <svg height="15pt" viewBox="0 0 479.89999 479" width="15pt"><path
                                d="m194.71875 2.773438c-2.152344-2.136719-5.320312-2.8710942-8.191406-1.90625l-133.304688 44.410156-39.664062-39.664063c-3.140625-3.03125-8.128906-2.988281-11.214844.097657-3.085938 3.085937-3.128906 8.074218-.097656 11.214843l41.152344 41.152344-42.945313 128.800781c-.957031 2.871094-.207031 6.039063 1.9375 8.183594l282.839844 282.878906c3.125 3.121094 8.1875 3.121094 11.3125 0l181.015625-181.015625c3.121094-3.125 3.121094-8.1875 0-11.3125zm96.167969 458.199218-273.6875-273.703125 38.847656-116.558593 30.488281 30.484374c-11.324218 17.132813-7.835937 40.054688 8.070313 53.042969s39.0625 11.820313 53.582031-2.699219c14.519531-14.519531 15.6875-37.675781 2.699219-53.582031s-35.910157-19.394531-53.042969-8.070312l-32-32 121.050781-40.304688 273.695313 273.6875zm-176.640625-332.046875c3.136718 3.03125 8.128906 2.988281 11.214844-.097656 3.085937-3.085937 3.128906-8.074219.097656-11.214844l-15.945313-15.945312c10.535157-5.046875 23.171875-1.761719 29.910157 7.777343 6.742187 9.539063 5.621093 22.546876-2.652344 30.792969-9.496094 9.0625-24.441406 9.0625-33.9375 0-7.226563-7.15625-9.089844-18.117187-4.632813-27.257812zm0 0"/><path
                                d="m166.433594 302.582031 22.625-22.625 124.453125 124.449219-22.628907 22.628906zm0 0"/><path
                                d="m223.003906 246.011719 22.625-22.625 124.449219 124.453125-22.628906 22.628906zm0 0"/><path
                                d="m256.933594 212.074219 22.628906-22.625 124.453125 124.445312-22.625 22.628907zm0 0"/><path
                                d="m290.886719 178.132812 11.3125-11.3125 124.449219 124.453126-11.316407 11.316406zm0 0"/><path
                                d="m200.367188 268.648438 11.316406-11.316407 124.453125 124.445313-11.3125 11.316406zm0 0"/></svg>





{else}





                    <i class="icon-print"></i>
                {/if}
            </a>
          </span>
        </span>
        </div>
    {/if}
</div>

<template id="popover_content_wrapper_{$id|intval}">
    <button type="button" class="close" onclick="$('#popovers_{$id|intval}').popover('hide');">×</button>
    <div class="">
        <div class="btn-group">
            {if $carriers_to_set_list}
                <div class="">
                    <small>
                        {l s='Change carrier' mod='springxbs'}
                    </small>
                </div>
                <div class="panel panel-default" style="padding: 12px;">
                    <form id="popover_form_{$id|intval}" action="{$form_link|escape:'htmlall':'UTF-8'}" method="post">
                        {foreach $carriers_to_set_list as $item}
                            <div class="" style="display: inline-block">
                                <small>
                                    {if $item['current']}{l s='Current' mod='springxbs'}{/if}
                                </small>
                                <br>
                                <label for="popover_form_{$id|intval}{$item['service_code']|escape:'htmlall':'UTF-8'}">
                                    <div class="myparcel-btn btn btn-{$item['active']|escape:'htmlall':'UTF-8'}"
                                         data-carrier="{$item['id']|escape:'htmlall':'UTF-8'}"
                                         data-link="{if $item['current']}  {/if}"
                                         style="border: 3px solid {if $item['current']}#a69d9d;{else}#fff{/if}; border-radius: 3px;" >
                                        {$item['service_name']|escape:'htmlall':'UTF-8'}
                                    </div>
                                    <input id="popover_form_{$id|intval}{$item['service_code']|escape:'htmlall':'UTF-8'}"
                                           class="hidden" type="radio"
                                           name="shipping_carrier"
                                           value="{$item['form_shipping_carrier']|escape:'htmlall':'UTF-8'}"
                                           onclick="if (springXBSdimensionsChangedCheck()) $(this).closest('form').submit();">
                                </label>
                            </div>
                        {/foreach}
                        <input class="hidden" type="hidden" name="submitShippingNumber"
                               value="{$form_submitShippingNumber|escape:'htmlall':'UTF-8'}">
                        <input class="hidden" type="hidden" name="shipping_tracking_number"
                               value="{$form_shipping_tracking_number|escape:'htmlall':'UTF-8'}">
                        <input class="hidden" type="hidden" name="id_order_carrier"
                               value="{$form_id_order_carrier|escape:'htmlall':'UTF-8'}">
                    </form>
                </div>
            {/if}
        </div>
    </div>
    <form id="popover_form_label_{$id|intval}" data-id="{$id|intval}" action="{$label_link|escape:'htmlall':'UTF-8'}" method="post" target="_blank">
        <div class="">
            <div class="well">
                <span>{$firstname|escape:'htmlall':'UTF-8'} {$lastname|escape:'htmlall':'UTF-8'}<br></span>
                <span>{$address1|escape:'htmlall':'UTF-8'}<br></span>
                <span>{$postcode|escape:'htmlall':'UTF-8'}<br></span>
                <span>{$city|escape:'htmlall':'UTF-8'} {$state|escape:'htmlall':'UTF-8'}<br></span>
                <span>{$country|escape:'htmlall':'UTF-8'}<br></span>
                <span>{$email|escape:'htmlall':'UTF-8'}<br></span>
                <span>{$order_reference|escape:'htmlall':'UTF-8'}</span>
                {if $parcel_dimensions}
                    <br>
                    Ordered
                    <strong>Parcel Dimensions</strong>
                    <br>
                    <table>
                        <tr>
                            <td>
                                <span>width <br>{if $parcel_dimensions['width']}{$parcel_dimensions['width']|escape:'htmlall':'UTF-8'} cm{else}-{/if}</span>
                            </td>
                            <td>
                                <span>height <br>{if $parcel_dimensions['height']}{$parcel_dimensions['height']|escape:'htmlall':'UTF-8'} cm{else}-{/if}</span>
                            </td>
                            <td>
                                <span>depth <br>{if $parcel_dimensions['depth']}{$parcel_dimensions['depth']|escape:'htmlall':'UTF-8'} cm{else}-{/if}</span>
                            </td>
                            <td>
                                <span>weight <br>{if $parcel_dimensions['weight']}{$parcel_dimensions['weight']|escape:'htmlall':'UTF-8'} kg{else}-{/if}</span>
                            </td>
                        </tr>
                    </table>
                {/if}
            </div>
            {if !$label_exists && $can_get_label}
                <div class="">
                    <div class="">Get label with <strong>Parcel Dimensions</strong> set below</div>
                    <table class="dim-table">
                        <tr>
                            <td class="">
                                <label for="Parcel_dimensions_width_{$id|intval}">width, cm</label>
                                <input class="dim-inp" id="Parcel_dimensions_width_{$id|intval}" type="number"
                                       name="width" value="{$parcel_prefilled_dimensions['width']|floatval}"
                                       onchange="springXBSdimensionsChanged(document.getElementById('popover_form_label_{$id|intval}'))">
                            </td>
                            <td class="">
                                <label for="Parcel_dimensions_height_{$id|intval}">height, cm</label>
                                <input class="dim-inp" id="Parcel_dimensions_height_{$id|intval}" type="number"
                                       name="height" value="{$parcel_prefilled_dimensions['height']|floatval}"
                                       onchange="springXBSdimensionsChanged(document.getElementById('popover_form_label_{$id|intval}'))">
                            </td>
                            <td class="">
                                <label for="Parcel_dimensions_length_{$id|intval}">depth, cm</label>
                                <input class="dim-inp" id="Parcel_dimensions_length_{$id|intval}" type="number"
                                       name="depth" value="{$parcel_prefilled_dimensions['depth']|floatval}"
                                       onchange="springXBSdimensionsChanged(document.getElementById('popover_form_label_{$id|intval}'))">
                            </td>
                            <td class="">
                                <label for="Parcel_dimensions_weight_{$id|intval}">weight, kg</label>
                                <input class="dim-inp" id="Parcel_dimensions_weight_{$id|intval}" type="number"
                                       name="weight" value="{$parcel_prefilled_dimensions['weight']|floatval}"
                                       onchange="springXBSdimensionsChanged(document.getElementById('popover_form_label_{$id|intval}'))">
                            </td>
                            <td class="" style="vertical-align: bottom !important;">
                                <div onclick="springXBSsaveDimensionsTemporary(document.getElementById('popover_form_label_{$id|intval}'), '{$save_dimensions_link|escape:'htmlall':'UTF-8'}')"
                                     class="image-save not-edited"></div>
                            </td>
                        </tr>
                    </table>
                </div>
            {/if}
        </div>
        {if $cannot_order_shipment_text}
            <div class="alert alert-warning">
                {$cannot_order_shipment_text|escape:'htmlall':'UTF-8'}
            </div>
        {/if}
        {if $error_level_1}
            <div class="alert alert-warning">
                {$label_reprint_text|escape:'htmlall':'UTF-8'}
            </div>
        {/if}
        <div class="" style="margin: 0 0 1rem 1rem;">
            {if $label_exists || !$cannot_order_shipment_text}
                <div style="margin: 0 0 1rem 1rem; float: right;">
                <span class="btn-group-action">
                  <span class="btn-group">
                    <span class="hidden label-progress" style="text-align: right;">
                        <img width="25" height="25"
                             src="/modules/springxbs/views/img/lg.rotating-balls-spinner.gif"
                             alt="">
                        in progress
                    </span>
                      <button class="btn btn-default" data-link="{$label_check_link|escape:'htmlall':'UTF-8'}"
                              onclick="return {$prefix|escape:'htmlall':'UTF-8'}_reloadPage_form{$id|intval}(this);"
                              type="button" data-toggle="tooltip">{$label_link_title|escape:'htmlall':'UTF-8'}</button>
                  </span>
                </span>
                </div>
            {/if}
            {if $error_level_1}
                <div style="float: right; margin: 0 0 1rem 1rem;">
                <span class="btn-group-action">
                  <span class="btn-group">
                    <a href="{$reprint_by_api_link|escape:'htmlall':'UTF-8'}"
                       onclick="return {$prefix|escape:'htmlall':'UTF-8'}_reloadPage_{$id|intval}();" target="_blank"
                       data-toggle="tooltip">
                        <span class="btn btn-default">{$reprint_by_api_link_text|escape:'htmlall':'UTF-8'}</span>
                    </a>
                  </span>
                </span>
                </div>
            {/if}
        </div>
        {if $label_exists && $void_label_link}
            <div style="margin: 0 1rem 1rem 0; float: left;">
        <span class="btn-group-action">
          <span class="btn-group">
            <span class="hidden void-label-progress" style="text-align: right;">
                <img width="25" height="25"
                     src="/modules/springxbs/views/img/lg.rotating-balls-spinner.gif"
                     alt="">
                in progress
            </span>
            <span onclick="return {$prefix|escape:'htmlall':'UTF-8'}_cancel_label_{$id|intval}('{$void_label_link|escape:'htmlall':'UTF-8'}', this);"
                  data-toggle="tooltip">
                <span class="btn btn-sm btn-danger">{$void_label_text|escape:'htmlall':'UTF-8'}</span>
            </span>
          </span>
        </span>
            </div>
        {/if}
    </form>
</template>

<script class="text/javascript">
    $(document).ready(function () {
        window.{$prefix|escape:'htmlall':'UTF-8'}dimensions_entered_{$id|intval} = false;
        $("#popovers_{$id|intval}").popover({
            placement: 'left',

            html: true,
            content: function () {
                return $('#popover_content_wrapper_{$id|intval}').html();
            },
            template: '<div id="popover_top_el_{$id|intval}" class="popover spring-orders-popover" style="min-width:400px;" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
        });

        $("#popovers_{$id|intval}").on('show.bs.popover', function () {
            $('table#table-order td').css('pointer-events', 'none');
            $(this).closest('td').css('pointer-events', 'auto');
        });

        $("#popovers_{$id|intval}").on('shown.bs.popover', function () {
            if ({$prefix|escape:'htmlall':'UTF-8'}dimensions_entered_{$id|intval}) {
                var interv = setInterval(function () {
                    if (!document.getElementById('popover_form_label_{$id|intval}')) {
                        return;
                    }
                    clearInterval(interv);
                    var formEls = document.getElementById('popover_form_label_{$id|intval}').elements;
                    formEls.weight.value = {$prefix|escape:'htmlall':'UTF-8'}dimensions_entered_{$id|intval}.weight;
                    formEls.depth.value = {$prefix|escape:'htmlall':'UTF-8'}dimensions_entered_{$id|intval}.depth;
                    formEls.height.value = {$prefix|escape:'htmlall':'UTF-8'}dimensions_entered_{$id|intval}.height;
                    formEls.width.value = {$prefix|escape:'htmlall':'UTF-8'}dimensions_entered_{$id|intval}.width;
                }, 50);
            }

        });

        $("#popovers_{$id|intval}").on('hide.bs.popover', function () {
            $('table#table-order td').css('pointer-events', 'auto');
        });

    });
    $('[data-toggle="tooltip"]').tooltip();

    {if $popover_flag}

    $('body').on('click', function (e) {
        if (!$(e.target).parents('.spring-orders-popover').length && window.springxbs_dimensionsedited_flag && !confirm('{$dimensions_not_saved_confirm|escape:'htmlall':'UTF-8'}')) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }

        $('button[data-popover-id]').each(function () {
            var id = $(this).data('popover-id'),
                $popover_selector = $('#popovers_' + id);

            if ($popover_selector.parents('td').find('.popover-content').length && $popover_selector.has(e.target).length === 0 && $('#popover_top_el_' + id).has(e.target).length === 0) {
                window.springxbs_dimensionsedited_flag = false;
                $popover_selector.popover('hide');
            }
        });
    });
    {/if}
</script>
