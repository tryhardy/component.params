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
<span class="checkbox-wrapper">
	<!--suppress HtmlFormInputWithoutLabel_CheckBox -->
	<input type="checkbox"
		<?= $arResult["ID_ATTR"] ?>
		<?= $arResult["DISABLED_ATTR"] ?>
		<?= $arResult["NAME_ATTR"] ?>
		value="Y"
		<?= $arResult["VALUE"] === "Y" ? "checked" : "" ?>
		<?= $arResult["PLACEHOLDER_ATTR"] ?>
	>
</span>
