<?php
use Tryhardy\Params\Common\Constants;
use Tryhardy\Params\Fields\CheckboxField;
use Tryhardy\Params\Fields\FieldsCollection;
use Tryhardy\Params\Fields\FileField;
use Tryhardy\Params\Fields\GroupFields;
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
if (!\Bitrix\Main\Loader::includeModule('tryhardy.params')) {
    die('Module "tryhardy.params" is not installed');
}

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
$arRequest = [];
$arData = [];
if ($options['data']) {
	if (json_decode($options['request'], true)) {
		$arRequest = json_decode($options['request'], true);
	}

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

function showCustomParamsBlock($ID, $object, $data, $PROPERTY_ID, int $NUMBER = 0, string $NAME = '')
{
    global $APPLICATION;
    ?>

    <div class="customblock-block-outer outer<?=$ID?>">

    <?php foreach($object as $element) {
        $isRecursive = $element instanceof GroupFields;
        $isCheckbox = false;
        $template = '';

        if (!$NAME) {
            $name = $PROPERTY_ID . '[' . $NUMBER . ']' . '[' . $element->getName() . ']';
        }
        else {
            $name = $NAME . '[' . $NUMBER . ']' . '[' . $element->getName() . ']';
        }

        $params = [
            'EDIT_MODE' => 'Y',
            'VALUE' => $data[$element->getName()] ?: '',
            'NAME' => $name,
            'PLACEHOLDER' => $element->getPlaceholder(),
            'ATTR' => ' data-bx-comp-prop="true" data-bx-property-id=' . $name . ' ',
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
            case $element instanceof GroupFields:?>
                <?php
                $groupFieldsData = $data[$element->getName()];
                $hash = $element->getHash();
                ?>
                <div class="group-fields" data-parent="<?=$hash?>" data-name="<?=$name?>">
                    <label class="group-fields__label customblock-block-label">
	                    <?php if($element->getLabel()):?><p><?=$element->getLabel()?></p><?php endif;?>
                        <div class="group-fields__wrapper">
                            <?php
                            $n = 0;
                            if(is_array($groupFieldsData) && count($groupFieldsData) > 0):?>
                                <?php foreach($groupFieldsData as $groupData):?>
                                    <?php
                                    $emptyArray = array_filter($groupData, function ($value) {
	                                    return ($value !== null && $value !== '');
                                    });
                                    if (!empty($emptyArray)) :
                                        if ($_REQUEST['ACTION'] == 'clone_group' && $hash == $_REQUEST['HASH']) {
                                            $APPLICATION->RestartBuffer();
                                            $n = $NUMBER;
                                        }
                                        if ((string) $_REQUEST['NAME']) {
                                            $name = (string) $_REQUEST['NAME'];
                                        }
                                        ?>
                                        <div class="group-fields__item group-fields__item<?=$hash?>" data-name="<?=$hash?>">
                                            <?php
                                            showCustomParamsBlock($ID, $element->getFields(), $groupData, $PROPERTY_ID, $n, $name);
                                            if ($_REQUEST['ACTION'] == 'clone_group' && $hash == $_REQUEST['HASH']) die();
                                            $n++;
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach;?>
                            <?php endif;?>
                        </div>
                    </label>
	                <?php if ($element->isMultiple()):?>
                        <input type="button" data-group="true" class="more-btn more-btn<?=$ID?>" value="+" style="max-width: 40px;width: 40px;display: block;min-width: fit-content;">
	                <?endif;?>
                </div>
                <?php

	            break;
		    case $element instanceof FileField:
			    $template = 'file';
			    break;
		    default:
			    break;
	    }
        ?>

        <?php if (!$isRecursive):?>
            <label class="customblock-block-label <?=($isCheckbox ? "customblock-block-checkbox" : "")?>">

		    <?php if($element->getLabel() && !$isCheckbox):?>
                <p><?=$element->getLabel()?></p>
		    <?php endif;?>

            <?php $APPLICATION->IncludeComponent(
                'tryhardy.params:field.widget',
                $template,
                $params
            ); ?>

            <?php
            if($element->getLabel() && $isCheckbox):?>
                <p><?=$element->getLabel()?></p>
            <?php endif;?>

            </label>
        <?php endif;?>

        <?php
    }
    ?>
    </div>
    <?php
}
?>

<link rel="stylesheet" href="<?="/bitrix/js/".Constants::MODULE_ID."/custom.block/style.css"?>" type="text/css">
<div class="customblock-block-wrapper wrapper<?=$ID?>">
    <div class="customblock-block-items items<?=$ID?>">

        <?php if ($_REQUEST['ACTION'] == 'clone') $APPLICATION->RestartBuffer();?>
            <?php showCustomParamsBlock($ID, $object, $firstElement, $PROPERTY_ID, $NUMBER);?>
	    <?php if ($_REQUEST['ACTION'] == 'clone') die();?>

        <?php if(is_array($arData) && count($arData) > 0):?>
            <?php foreach($arData as $i => $data):?>
                <?php
                $emptyArray = array_filter($data, function ($value) {
	                return ($value !== null && $value !== '');
                });
                if (!empty($emptyArray)) {
	                $NUMBER = $i;
	                showCustomParamsBlock($ID, $object, $data, $PROPERTY_ID, $NUMBER);
                }
                ?>
            <?php endforeach;?>
        <?php endif;?>

    </div>
	<?php if ($multiple):?>
        <input type="button" class="more-btn more-btn<?=$ID?>" value="+">
	<?php endif;?>
</div>

<script>
    function initObserver(observer)
    {
        var allFields = element.querySelectorAll('input, textarea, select');

        for (let i = 0; i < allFields.length; i++) {
            var field = allFields[i];
            if (field.type != 'button') {
                var fieldId = field.name;
                observer.inputs[fieldId] = field.value;
            }
        }

        for (let i = 0; i < allFields.length; i++) {
            allFields[i].addEventListener('input', function (e) {
                var input = e.target;
                observer.inputs[input.name] = input.value;
            })

            allFields[i].addEventListener('change', function (e) {
                var input = e.target;
                observer.inputs[input.name] = input.value;
            })
        }
    }

    var element = document.querySelector('.wrapper<?=$ID?>');

    if (element) {
        if (!window.observer<?=$ID?>) {
            window.observer<?=$ID?> = {
                element: element,
                inputs: {}
            }

            initObserver(window.observer<?=$ID?>);
        }
        else {
            if (window.observer<?=$ID?>.inputs) {
                for (key in window.observer<?=$ID?>.inputs) {
                    var input = document.querySelector('[name="' + key + '"]');

                    if (input.type != 'button') {
                        var value = window.observer<?=$ID?>.inputs[key];
                        if (input) input.value = value;
                    }
                }
            }

            initObserver(window.observer<?=$ID?>);
        }
    }
</script>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
