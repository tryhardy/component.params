<?php

namespace Tryhardy\Params\Helpers;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Service\GeoIp\Manager;
use Tryhardy\Params\Fields\FieldsCollection;

/**
 * Помогает cформировать массив параметров
 * для .parameters.php в шаблонах компонентов
 */
class ComponentParams
{
	const PATH_TO_JS = '/bitrix/js/component.params/custom.block/settings.js';

	/**
	 * Устанавливает кастомный блок параметров
	 * @param array $params
	 * @param array $arCurrentValues
	 * @param FieldsCollection $fieldsCollection
	 * @param string $code
	 * @param string $name
	 * @param string $parent
	 * @param string $multiple
	 * @param string $refresh
	 * @return void
	 */
	public static function setCustomParams(
		array &$params,
		array $arCurrentValues,
		FieldsCollection $fieldsCollection,
		string $code,
		string $name = 'Кастомный блок',
		string $parent = "ADDITIONAL_SETTINGS",
		string $multiple = 'N',
		string $refresh = 'N'
	) : void
	{
		if (!is_array($arCurrentValues[$code])) $arCurrentValues[$code] = [];

		if ($code == 'FEATURES') {
			foreach($arCurrentValues[$code] as $key => &$value) {
				if (!is_int($key)) {
					$value = [];
				}
			}
		}

		$params[$code] = [
			'NAME' => $name,
			'TYPE' => 'CUSTOM',
			'JS_FILE' => static::PATH_TO_JS,
			'JS_EVENT' => 'onOpenEditor',
			'JS_DATA' => json_encode([
				'object' => serialize($fieldsCollection),
				'data' => serialize($arCurrentValues[$code])
			]),
			'DEFAULT' => '',
			'PARENT' => $parent,
			"MULTIPLE" => $multiple,
			"REFRESH" => $refresh
		];
	}
}
