<?php

use Tryhardy\Params\Common\Constants;
use Tryhardy\Params\Fields\CheckboxField;
use Tryhardy\Params\Fields\FieldsCollection;
use Tryhardy\Params\Fields\FileField;
use Tryhardy\Params\Fields\IblockField;
use Tryhardy\Params\Fields\SelectField;
use Tryhardy\Params\Fields\TextareaField;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");

global $APPLICATION;

$sDirName = dirname(pathinfo(__FILE__, PATHINFO_DIRNAME));
$sPath = substr($sDirName, strlen($_SERVER['DOCUMENT_ROOT']));
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if (!check_bitrix_sessid())  die('Wrong sessid');
if (!$request->isAjaxRequest()) die('Only for ajax requests');
if (!$_REQUEST['ID']) die('Wrong ID');

$PROPERTY_ID = (string) $_REQUEST['PROPERTY_ID'];
$ID = (string) $_REQUEST['ID'];
$requestData = urldecode($_REQUEST['DATA']);
$multiple = $_REQUEST['MULTIPLE'] === 'Y' ?: false;
$NUMBER = $_REQUEST['NUMBER'] ?: 0;

$options = [];
if (json_decode($_REQUEST['OPTIONS'], true)) {
	$options = json_decode($_REQUEST['OPTIONS'], true);
}

$object = new FieldsCollection();
if (unserialize($options['object']) instanceof FieldsCollection) {
	$object = unserialize($options['object']);
}

$firstElement = [];
$arData = [];
if ($options['data']) {
	//Если приходит массив значений, декодируем
	if (json_decode($options['data'], true)) {
		$arData = json_decode($options['data'], true);
	}

    if (unserialize($options['data'])) {
        $arData = unserialize($options['data']);
    }

	//Забираем первый элемент для отрисовки
    if (is_array($arData)) {
	    $firstElement = reset($arData);
	    unset($arData[key($arData)]);
    }
}
function showCustomParamsBlock($ID, $object, $data, $PROPERTY_ID, $NUMBER)
{
    global $APPLICATION;

    $html = "<div class=\"customblock-block-outer outer$ID\">";
    foreach($object as $element) {
        $isCheckbox = false;
        $template = '';
        $params = [
            'EDIT_MODE' => 'Y',
            'VALUE' => $data[$element->getName()] ?: '',
            'NAME' => $PROPERTY_ID . '[' . $NUMBER . ']' . '[' . $element->getName() . ']',
            'PLACEHOLDER' => $element->getPlaceholder(),
        ];

	    switch ($element) {
		    case $element instanceof SelectField:
			    $template = 'select';
			    $params['OPTIONS'] = $element->getOptions();
			    break;
		    case $element instanceof TextareaField:
			    $template = 'textarea';
			    break;
		    case $element instanceof CheckboxField:
			    $isCheckbox = true;
			    $template = 'checkbox';
			    break;
		    case $element instanceof IblockField:
			    $template = $element->getIsSection() ? 'iblock.section' : 'iblock.element';
			    $params['IBLOCK_ID'] = $element->getIblockId();
			    break;
		    case $element instanceof FileField:
			    $template = 'file';
			    break;
		    default:
			    break;
	    }

        $html .= "<label class=\"customblock-block-label " . ($isCheckbox ? "customblock-block-checkbox" : "") . "\">";

	    if($element->getLabel() && !$isCheckbox) {
            $html = "<p>".$element->getLabel()."</p>";
        }

	    ob_start();
        ?>

        <?php $APPLICATION->IncludeComponent(
		    'tryhardy.params:field.widget',
		    $template,
		    $params
	    ); ?>

        <?php
	    $htmlContent = ob_get_clean();
	    $html .= $htmlContent;

	    if($element->getLabel() && $isCheckbox) {
		    $html = "<p>".$element->getLabel()."</p>";
	    }

        $html .= "</label>";
    }

    $html .= "</div>";

    return $html;
}

\Bitrix\Main\Page\Asset::getInstance()->addCss("/bitrix/js/".Constants::MODULE_ID."/custom.block/style.css");
?>
<div class="customblock-block-wrapper wrapper<?=$ID?>">
    <div class="customblock-block-items items<?=$ID?>">
        <?php if ($_REQUEST['ACTION'] === 'clone') $APPLICATION->RestartBuffer();?>
            <?= showCustomParamsBlock($ID, $object, $firstElement, $PROPERTY_ID, $NUMBER); ?>
	    <?php if ($_REQUEST['ACTION'] === 'clone') die();?>

        <?php if(is_array($arData) && count($arData) > 0):?>
            <?php foreach($arData as $i => $data):?>
		        <?= showCustomParamsBlock($ID, $object, $data, $PROPERTY_ID, $NUMBER); ?>
            <?php endforeach;?>
        <?php endif;?>
    </div>
	<?php if ($multiple):?>
        <input type="button" class="more-btn more-btn<?=$ID?>" value="+">
	<?php endif;?>
</div>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
