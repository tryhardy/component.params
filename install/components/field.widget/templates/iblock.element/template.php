<?php

use Tryhardy\Params\Helpers\ComponentParams;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

if ($arParams["DISABLED"] !== "Y") {
	$popupUrl = (new \Bitrix\Main\Web\Uri("/bitrix/admin/iblock_element_search.php"))
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

if (!function_exists('buildAdminElementLink')) {
	function buildAdminElementLink(int $id, $iblock = 0): string
	{
		if ($id <= 0) {
			return '';
		}
		if (!\Bitrix\Main\Loader::includeModule('iblock')) return '';

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

		return "/bitrix/admin/iblock_element_edit.php?IBLOCK_ID={$iblock}&type={$type}&ID={$id}";
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
                    style="
                        border: 1px solid lightgrey;
                        padding: 6px 12px;
                        border-radius: 3px;
                        box-shadow: 2px 2px 3px #434343;
                        color: darkgray;
                        font-weight: bold;
                        font-size: 18px;
                        line-height: 4px;
                        vertical-align: middle;
                        padding-bottom: 12px;"
					onclick="<?php if ($popupUrl): ?>jsUtils.OpenWindow('<?= $popupUrl ?>', 900, 700);<?php endif; ?>">...
			</button>
		</div>

	</div>
	<div style="margin-top: 10px;" id="sp_<?= $arResult["ID"] ?>">
		<?php
		$arResult["VALUE"] = (int)$arResult["VALUE"];

		if ($arResult["VALUE"] && \Bitrix\Main\Loader::includeModule('iblock')) {
			$elementDB = CIBlockElement::GetByID((int)$arResult["VALUE"]);

            if ($element = $elementDB->Fetch()) {
	            if ($adminElementLink = buildAdminElementLink((int)$element["ID"], (int)$element["IBLOCK_ID"])) {
		            echo "<a href='{$adminElementLink}' target='_blank'>{$element["NAME"]}</a>";
	            } else {
		            echo $element["~NAME"];
	            }
            }
		}
		?>
	</div>
</div>
