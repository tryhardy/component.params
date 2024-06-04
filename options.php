<?php
use Bitrix\Main\Loader;
use Tryhardy\Params\Common\Constants;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 */

$module_id = "tryhardy.params";
Loader::includeModule($module_id);

$options = new Tryhardy\Params\Module\Options(__FILE__, [
	[
		"DIV"     => "common",
		"TAB"     => "Настройки",
		"OPTIONS" => [
			"Общее",
			[],
			"Сохраните настройки модуля для обновления стилей, скриптов, компонентов модуля",
		],
	],
]);


$options->drawOptionsForm();
