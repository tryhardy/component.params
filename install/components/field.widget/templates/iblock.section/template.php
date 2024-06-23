<?php

use Tryhardy\Params\Helpers\ComponentParams;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

if ($arParams["DISABLED"] !== "Y") {
	$popupUrl = (new \Bitrix\Main\Web\Uri("/bitrix/admin/iblock_section_search.php"))
		->addParams(
			[
				"lang"      => $arResult["LANGUAGE_ID"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"] ?? "",
				"n"         => $arResult["ID"],
				"iblockfix" => !empty($arParams["IBLOCK_ID"]) ? "y" : "n",
			]
		)
		->getUri();
}

if (!function_exists('buildAdminSectionLink')) {
	function buildAdminSectionLink(int $id, $iblock = 0): string
	{
		if ($id <= 0) {
			return '';
		}
		if (!\Bitrix\Main\Loader::includeModule('iblock')) {
			return '';
		}
		$iblock = (int)$iblock;
		if (empty($iblock)) {
			$res = \CIBlockElement::GetList([], ['ID' => $id], false, ['IBLOCK_ID', 'ID']);
			if ($item = $res->Fetch()) {
				$iblock = $item['IBLOCK_ID'];
			} else {
				return '';
			}
		}

		$res = \CIBlock::GetByID($iblock);
		if ($item = $res->Fetch()) {
			$type = $item['IBLOCK_TYPE_ID'];
		} else {
			return '';
		}

		return "/bitrix/admin/iblock_section_edit.php?IBLOCK_ID={$iblock}&type={$type}&ID={$id}";
	}
}
?>

<div>
	<div class="file-select-row">
		<!--suppress HtmlFormInputWithoutLabel -->
		<input type="text"
			   size="8"
			   style="max-width: 200px;"
			<?= $arResult["ID_ATTR"] ?>
			<?= $arResult["DISABLED_ATTR"] ?>
			<?= $arResult["NAME_ATTR"] ?>
			<?= $arResult["VALUE_ATTR"] ?>
			<?= $arResult["PLACEHOLDER_ATTR"] ?>
		>

		<div class="file-select-row__actions" <?= $arResult["DISABLED_ATTR"] ?>>
			<button type="button"
                    class="tryhardy_dialog_button"
					onclick="<?php if ($popupUrl): ?>jsUtils.OpenWindow('<?= $popupUrl ?>', 900, 700);<?php endif; ?>">...
			</button>
		</div>

	</div>
	<div style="margin-top: 10px;" id="sp_<?= $arResult["ID"] ?>">
		<?php
		if ($arResult["VALUE"] && \Bitrix\Main\Loader::includeModule('iblock')) {
			$element = CIBlockSection::GetList([], ['ID' => $arResult["VALUE"]], [], ['nTopCount' => 1], ['ID', 'NAME', 'IBLOCK_ID'])->GetNext();

			if ($element) {
				if ($adminElementLink = buildAdminSectionLink((int)$element["ID"], (int)$element["IBLOCK_ID"])) {
					echo "<a href='{$adminElementLink}' target='_blank'>{$element["NAME"]}</a>";
				} else {
					echo $element["~NAME"];
				}
			}
		}
		?>
	</div>
</div>
