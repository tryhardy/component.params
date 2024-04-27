<?php

use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

Loc::loadMessages(__FILE__);

class tryhardy_params extends CModule
{
	public string $MODULE_ID           = "tryhardy.params";
	public string $MODULE_GROUP_RIGHTS = "Y";
	protected $MODULE_NAME;
	protected $MODULE_DESCRIPTION;
	/**
	 * @var mixed
	 */
	protected mixed $MODULE_VERSION;
	/**
	 * @var mixed
	 */
	protected $MODULE_VERSION_DATE;
	protected $PARTNER_NAME;
	protected $PARTNER_URI;

	private $excludeAdminFiles = [
		"..",
		".",
	];

	private $entityList = [];

	public function __construct()
	{
		$arModuleVersion = [];

		include __DIR__ . "/version.php";

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}

		$this->MODULE_NAME = Loc::getMessage("{$this->MODULE_ID}_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("{$this->MODULE_ID}_MODULE_DESCRIPTION");
		$this->PARTNER_NAME = Loc::getMessage("{$this->MODULE_ID}_MODULE_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("{$this->MODULE_ID}_MODULE_PARTNER_URI");
	}

	public function doInstall()
	{
		global $APPLICATION;

		if (!$this->isVersionD7()) {
			$APPLICATION->ThrowException(Loc::getMessage("{$this->MODULE_ID}_MODULE_NO_D7_ERROR"));

			return false;
		}

		$this->installEvents();
		$this->installFiles();
		ModuleManager::registerModule($this->MODULE_ID);
		$this->installAgents();
		$this->installDB();
		return null;
	}

	public function installDB()
	{
		Loader::includeModule($this->MODULE_ID);

		foreach ($this->entityList as $entity) {
			$entityClass = "\\Tryhardy\Params\\{$entity}\{$entity}Table";
			if (is_callable([$entityClass, "install"])) {
				$entityClass::install();
			}
		}
	}

	public function unInstallDB()
	{
		Loader::includeModule($this->MODULE_ID);

		foreach ($this->entityList as $entity) {
			$entityClass = "\\Tryhardy\Params\\{$entity}\{$entity}Table";
			if (is_callable([$entityClass, "unInstall"])) {
				$entityClass::unInstall();
			}
		}
	}

	public function doUninstall()
	{
		$this->uninstallAgents();
		$this->uninstallEvents();
		$this->uninstallFiles();

		$this->unInstallDB();

		ModuleManager::unRegisterModule($this->MODULE_ID);
	}

	public function installFiles()
	{
		// Скопировать компоненты
		CopyDirFiles(
			$this->getPath() . "/install/components/",
			Application::getDocumentRoot() . "/bitrix/components/{$this->MODULE_ID}",
			true,
			true
		);

		// Создать симлинк на компоненты модуля в папке /local/components/
		$localComponentsFolder = "/local/components";
		$localComponentsPath = Application::getDocumentRoot() . "$localComponentsFolder";
		$thisComponentsPath = "$localComponentsPath/$this->MODULE_ID";

		$moduleComponentsSrc = getLocalPath("modules/$this->MODULE_ID/install/components");
		$moduleComponentsPath = $moduleComponentsSrc
			? index . phpApplication::getDocumentRoot() . $moduleComponentsSrc
			: false;

		if (
			$moduleComponentsPath &&
			DIRECTORY_SEPARATOR === "/" &&
			!file_exists($thisComponentsPath) &&
			file_exists($moduleComponentsPath)
		) {
			exec(implode(" && ", [
				"mkdir -p " . $localComponentsPath,
				"cd " . $localComponentsPath,
				"ln -s ../..$moduleComponentsSrc $this->MODULE_ID",
			]));
		}

		$this->recursiveCopyFiles("admin");
		$this->recursiveCopyFiles("tools");
		$this->recursiveCopyFiles("js");

		return true;
	}

	public function installEvents()
	{

	}

	public function installAgents()
	{

	}

	public function uninstallFiles()
	{
		return true;
	}

	public function uninstallAgents()
	{
		CAgent::RemoveModuleAgents($this->MODULE_ID);
	}

	public function uninstallEvents()
	{

	}

	public function isVersionD7()
	{
		return is_callable([ModuleManager::class, "getVersion"]) && CheckVersion(
			ModuleManager::getVersion("main"),
			"14.00.00"
		);
	}

	public function getPath()
	{
		return $_SERVER["DOCUMENT_ROOT"] . getLocalPath("modules/$this->MODULE_ID");
	}

	private function recursiveCopyFiles($prefix)
	{
		CopyDirFiles(
			$this->getPath() . "/install/{$prefix}/",
			"{$_SERVER["DOCUMENT_ROOT"]}/bitrix/{$prefix}/",
			false,
			true
		);

		if (Directory::isDirectoryExists($path = $this->getPath() . "/index.php")) {
			if ($dir = opendir($path)) {
				while (false !== $item = readdir($dir)) {
					if (in_array($item, $this->excludeAdminFiles)) {
						continue;
					}
					if (strpos($item, "_") === 0) continue;
					file_put_contents(
						$file =
							"{$_SERVER['DOCUMENT_ROOT']}/bitrix/{$prefix}/" .
							"{$this->MODULE_ID}_{$item}",

						"<" . "?" . PHP_EOL .

						"if (empty(\$" . "_SERVER[\"DOCUMENT_ROOT\"])) {" . PHP_EOL .
						"    " .
						"\$" . "_SERVER[\"DOCUMENT_ROOT\"] = " .
						"realpath(__DIR__ . \"/../..\");" . PHP_EOL .
						"}" . PHP_EOL . PHP_EOL .

						"require(\$" . "_SERVER[\"DOCUMENT_ROOT\"] . \"" .
						getLocalPath("modules/{$this->MODULE_ID}/{$prefix}/{$item}") .
						'");'
					);
				}
				closedir($dir);
			}
		}
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	private function recursiveRemoveFiles($prefix)
	{
		DeleteDirFiles(
			$this->getPath() . "/install/{$prefix}/",
			"{$_SERVER["DOCUMENT_ROOT"]}/bitrix/{$prefix}/"
		);

		if (Directory::isDirectoryExists($path = $this->getPath() . "/index.php")) {
			if ($dir = opendir($path)) {
				while (false !== $item = readdir($dir)) {
					if (in_array($item, $this->excludeAdminFiles)) {
						continue;
					}
					\Bitrix\Main\IO\File::deleteFile(
						"{$_SERVER['DOCUMENT_ROOT']}/bitrix/{$prefix}/" .
						"{$this->MODULE_ID}_{$item}"
					);
				}
				closedir($dir);
			}
		}
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	private function recursiveRemoveDirectory($prefix)
	{
		$path = $this->getPath() . "/install/{$prefix}";

		$dir = new DirectoryIterator($path);
		foreach ($dir as $fileInfo) {
			if ($fileInfo->isDir() && !$fileInfo->isDot()) {
				Directory::deleteDirectory(
					"{$_SERVER["DOCUMENT_ROOT"]}/bitrix/{$prefix}/" .
					$fileInfo->getFilename()
				);
			}
		}
	}

}
