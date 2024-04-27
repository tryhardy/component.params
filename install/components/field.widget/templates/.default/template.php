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
<!--suppress HtmlFormInputWithoutLabel_Default -->
<input type="text"
	<?= $arResult["ID_ATTR"] ?>
	<?= $arResult["DISABLED_ATTR"] ?>
	<?= $arResult["NAME_ATTR"] ?>
	<?= $arResult["VALUE_ATTR"] ?>
>