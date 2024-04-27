<?php

namespace Tryhardy\Params\Module;

use CMain;
use CUser;
use Tryhardy\Params\Common\Constants;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @global CUser $USER
 */


class Options extends OptionsBase
{
	public $moduleId = Constants::MODULE_ID;

	public function onPostEvents()
	{
		parent::onPostEvents();
		$this->updateModuleFiles();
	}

}
