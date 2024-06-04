<?php /** @noinspection AutoloadingIssuesInspection */

namespace Uplab\customblock\Components;

use Bitrix\Main\Error;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use CBitrixComponent;
use Exception;
use Uplab\customblock\Field\FieldWidgetHelpers;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

class CIblockComponentParamsFieldWidget extends CBitrixComponent
{
	const DEFAULT_CACHE_TYPE = "N";
	const DEFAULT_CACHE_TIME = 0;

	/**
	 * Коллекция ошибок работы компонента
	 *
	 * @var ErrorCollection $errors
	 */
	protected ErrorCollection $errors;

	protected array $requiredModules = [];

	/**
	 * @param $arParams
	 *
	 * @return array
	 * @throws LoaderException
	 */
	public function onPrepareComponentParams($arParams): array
	{
		$this->includeModules();
		$this->errors = new ErrorCollection();

		$arParams["CACHE_TYPE"] = static::DEFAULT_CACHE_TYPE;
		$arParams["CACHE_TIME"] = static::DEFAULT_CACHE_TIME;

		$arParams["AREA_UNIQUE_ID"] = $arParams["AREA_UNIQUE_ID"] ?? "";
		if (empty($arParams["AREA_UNIQUE_ID"])) {
			$arParams["AREA_UNIQUE_ID"] = "content_value_" . randString(6);
		}

		$arParams["VALUE"] = $arParams["VALUE"] ?? "";
		$arParams["KEY"] = $arParams["KEY"] ?? "";
		return $arParams;
	}

	/**
	 * Подключает модули, необходимые для работы компонента
	 * @throws LoaderException
	 */
	protected function includeModules()
	{
		foreach ($this->requiredModules as $requiredModule) {
			if (empty($requiredModule)) {
				continue;
			}

			if (!Loader::includeModule($requiredModule)) {
				$this->errors->setError(new Error("Module `{$requiredModule}` is not installed."));
			}
		}
	}

	/**
	 * Отображает ошибки, возникшие при работе компонента, если они есть
	 */
	protected function showErrorsIfAny()
	{
		if ($this->errors->count()) {
			foreach ($this->errors as $error) {
				ShowError($error);
			}
		}
	}

	/**
	 * @return mixed
	 * @noinspection PhpMissingReturnTypeInspection
	 */
	public function executeComponent()
	{
		try {

			$this->prepareResult();

			$this->includeComponentTemplate();

			$this->executeEpilogue();

		}
		catch (Exception $exception) {
			$this->errors->setError(new Error($exception->getMessage()));
		}

		$this->showErrorsIfAny();

		return false;
	}

	/**
	 * Выполняет действия после выполнения компонента, например установка заголовков из кеша
	 */
	protected function executeEpilogue()
	{
	}

	private function prepareResult(): void
	{
		$this->arResult["ID"] = "widget_{$this->arParams["AREA_UNIQUE_ID"]}_{$this->arParams["KEY"]}";
		$this->arResult["ID_ATTR"] = " id=\"{$this->arResult["ID"]}\" ";
		$this->arResult["NAME_ATTR"] = " name=\"{$this->arParams["NAME"]}\" data-name=\"{$this->arParams["NAME"]}\" ";

		$this->arResult["DISABLED"] = $this->arParams["EDIT_MODE"] !== "Y" ? "Y" : "N";
		$this->arResult["DISABLED_ATTR"] = $this->arResult["DISABLED"] === "Y"
			? " disabled=\"disabled\" "
			: "";
		$this->arResult["VALUE"] = $this->arParams["~VALUE"];
		$this->arResult["VALUE_ATTR"] =
			' value="' .
			htmlspecialchars($this->arResult["VALUE"], ENT_COMPAT) .
			'"';
	}
}
