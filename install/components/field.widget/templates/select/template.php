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
	<!--suppress HtmlFormInputWithoutLabel_Select -->
    <select
        <?= $arResult["ID_ATTR"] ?>
        <?= $arResult["DISABLED_ATTR"] ?>
        <?= $arResult["NAME_ATTR"] ?>
	    <?= $arResult["PLACEHOLDER_ATTR"] ?>
    >
	    <?php foreach ($arParams['OPTIONS'] as $key => $val): ?>
            <option <?=($arResult["VALUE"] == $key) ? 'selected' : ''?> value="<?=$key?>"><?=$val?></option>
	    <?php endforeach; ?>
    </select>
</div>
