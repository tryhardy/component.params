<?php
use Bitrix\Main\Application;
use Uplab\Core\Helper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

/**
 * @global CMain $APPLICATION
 * @var array    $arParams
 * @var array    $arResult
 */
?>
<?php if ($arParams["EDIT_MODE"] === "Y"): ?>
	<?php
	$pathInfo = !empty($arParams["VALUE"])
		? pathinfo($arParams["VALUE"])
		: [];

	$defaultPath = $arParams["START_DIR"] ?? "/upload";

	$openFunctionName = $arResult["ID"] . "_open";
	$submitFunctionName = $arResult["ID"] . "_submit";

	$config = [
		"allowAllFiles"  => true,
		"showUploadTab"  => true,
		"path"           => $pathInfo["dirname"] ?? $defaultPath,
		"submitFuncName" => $submitFunctionName,
		"fileFilter"     => ($arParams["FILE_TYPE"] ?? "") ?: "jpg,jpeg,gif,png,svg",
		"view"           => "preview",
		"saveConfig"     => true,
	];
	?>

	<!--suppress ES6ConvertVarToLetConst, ThisExpressionReferencesGlobalObjectJS -->
	<script>

        if (typeof(window.loadJs) == "undefined") {
            window.loadJs = (url, onSuccess = false, onError = false) => {
                //if (document.querySelector(`script[src='${url}']`)) return;

                const script = document.createElement('script');

                script.src = url;
                document.body.append(script);

                script.onload = () => {
                    if (onSuccess && typeof onSuccess === 'function') onSuccess();
                };
            };
        }

        loadJs('/bitrix/js/main/file_dialog.js', function () {
            window.openFileDialog = function(event, elementId, config)
            {
                event.preventDefault();
                window.BXFileDialog = window.BXFileDialog || undefined;
                if (!window.BXFileDialog) return;

                window.oBXFileDialog = new BXFileDialog();
                // noinspection JSUnresolvedFunction
                oBXFileDialog.Open(
                    {
                        ...{
                            select: 'F',
                            operation: 'O',
                            saveConfig: true,
                            checkChildren: true,
                            genThumb: true,
                            showAddToMenuTab: false,
                            zIndex: 2500,

                            allowAllFiles: true,
                            showUploadTab: true,
                            path: '/upload',
                            submitFuncName: 'submitFileDialog',
                            fileFilter: 'jpg,jpeg,gif,png,svg',

                            site: BX.message['SITE_ID'] || 's1',
                            lang: BX.message['LANGUAGE_ID'] || 'ru',
                            sessid: BX.message["bitrix_sessid"] || '',
                        },
                        ...config
                    },
                    config
                );
            }
        });

        window.<?= $submitFunctionName ?> = function (filename, path, site, title, menu) {
            window.submitFileDialog(
                '<?= $arResult["ID"] ?>',
                filename,
                path,
                site,
                title,
                menu
            );
        }

        window.submitFileDialog = function (elementId, filename, path, site, title, menu) {
            const inputEl = document.getElementById(elementId);

            path = jsUtils.trim(path);
            path = path.replace(/\\/ig, '/');
            path = path.replace(/\/\//ig, '/');
            if (path.substr(path.length - 1) == '/')
                path = path.substr(0, path.length - 1);
            var full = (path + '/' + filename).replace(/\/\//ig, '/');
            if (path == '')
                path = '/';

            var arBuckets = [];
            if (arBuckets[site]) {
                full = arBuckets[site] + filename;
                path = arBuckets[site] + path;
            }

            if ('F' == 'D') name = full;

            inputEl.value = full;
        };
	</script>
    <input type="text"
		<?= $arResult["ID_ATTR"] ?>
		<?= $arResult["DISABLED_ATTR"] ?>
		<?= $arResult["NAME_ATTR"] ?>
		<?= $arResult["VALUE_ATTR"] ?>
    >
    <button onclick="window.openFileDialog(event, '<?=$arResult["ID"]?>', <?=(CUtil::PhpToJSObject($config) ?: CUtil::PhpToJSObject([]))?>);">...</button>
<?php endif; ?>
