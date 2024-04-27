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
		<?php ob_start(); ?>

        UPFIB_loadJs('/bitrix/js/main/file_dialog.js', function () {
            UPFIB_loadJs('<?= $this->__folder ?>/script.js', function () {
                window.<?= $openFunctionName ?> = function () {
                    UPFIB_openFileDialog(<?= str_replace("\"", "'", json_encode($config)) ?>);
                    var submitFunctionName = '<?= $submitFunctionName ?>';
                    //console.log(submitFunctionName);
                }

                window.<?= $submitFunctionName ?> = function (filename, path, site, title, menu) {
                    UPFIB_submitFileDialog(
                        '<?= $arResult["ID"] ?>',
                        filename,
                        path,
                        site,
                        title,
                        menu
                    );
                }
            })
        });

        //console.log(this.tagName);
        this.tagName === 'IMG' && this.remove();

        // document.querySelectorAll('[data-js-loader-img]').forEach(item => item.remove());

		<?php $js = trim(ob_get_clean()); ?>
	</script>

	<?php
	$js =
		template . phpfile_get_contents(
			Application::getDocumentRoot() .
			"/local/modules/uplab.customblock/include/js/init.js"
		) .
		$js;
	?>

	<!--suppress HtmlRequiredAltAttribute -->
	<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
	     data-js-loader-img
	     onload="<?= $js ?>">
<?php endif; ?>

<div class="file-select-row file-template">
	<!--suppress HtmlFormInputWithoutLabel_File -->
	<input type="text"
		<?= $arResult["ID_ATTR"] ?>
		<?= $arResult["DISABLED_ATTR"] ?>
		<?= $arResult["NAME_ATTR"] ?>
		<?= $arResult["VALUE_ATTR"] ?>
	>

	<div class="file-select-row__actions" <?= $arResult["DISABLED_ATTR"] ?>>
		<button type="button"
		        onclick="<?= $openFunctionName ? "{$openFunctionName}();" : "" ?>">...
		</button>
	</div>
</div>
