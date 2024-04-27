<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}
/**
 * @global CMain $APPLICATION
 * @var array    $arParams
 * @var array    $arResult
 */
?>
<div class="customblock-textarea-outer <?= $arResult["DISABLED"] === "Y" ? "disabled" : "" ?>">
	<!--suppress HtmlFormInputWithoutLabel_Textarea -->
	<textarea class="customblock-textarea"
		<?= $arResult["ID_ATTR"] ?>
		<?= $arResult["DISABLED_ATTR"] ?>
		<?= $arResult["NAME_ATTR"] ?>
	><?= $arResult["VALUE"] ?></textarea>
</div>
