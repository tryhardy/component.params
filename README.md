Простой модуль для настройки параметров компонентов без необходимости хранить значения в отдельной таблице в БД.

## Пример использования (множественный блок ссылок):
![Пример использования](/images/image_1.png)

## Вывод простого текстового инпута
```php
<?php
//в файле .parameters.php шаблона компонента
use \Tryhardy\Params\Fields;

/**
 * @global  CMain    $APPLICATION
 * @var     array    $arParams
 * @var     array    $arCurrentValues
 * @var     array    $arResult
 */

if (!\Bitrix\Main\Loader::includeModule('tryhardy.params')) {
    //Заводим пустую коллекцию полей (в нее будем добавлять селекты, инпуты, радиобаттоны и т.д.)
    $сollection = new Fields\FieldsCollection();
        
    //Простое тектовое поле: <input type="text" name="theme">
    $simpleInputField = (new Fields\Field(name: 'name')->setLabel('Some random label'));
    //Добавляем это поле в коллекцию
    $сollection->add($simpleInputField);
    
    //Добавляем получившуюся коллекцию полей в параметры компонента
    \Tryhardy\Params\Helpers\ComponentParams::setCustomParams(
        $arTemplateParameters,
        $arCurrentValues,
        fieldsCollection: $сollection,
        code: "INPUTS_BLOCK",
        name: "Ссылки:",
        parent: "ADDITIONAL_PARAMETERS",
        multiple: "Y",
        refresh: "N"
    );
}

?>
```

## Вывод селекта
```php
<?php
//в файле .parameters.php шаблона компонента
use \Tryhardy\Params\Fields;

/**
 * @global  CMain    $APPLICATION
 * @var     array    $arParams
 * @var     array    $arCurrentValues
 * @var     array    $arResult
 */

if (!\Bitrix\Main\Loader::includeModule('tryhardy.params')) {
    //Заводим пустую коллекцию полей (в нее будем добавлять селекты, инпуты, радиобаттоны и т.д.)
    $сollection = new Fields\FieldsCollection();
    
    //Создаем пустой <select name="{name}"></select>
    $selectField = (new Fields\SelectField(name: 'theme'))->setLabel('Тема');
    // Создаем коллекцию объектов <option> (пока что пустую)
    $selectOptionsCollection = new Fields\SelectOptionCollection();
    //Создаем тег <option value="blue">Синяя тема</option>
    $option1 = new Fields\SelectOption(text: "Синяя тема", value: "blue", selected: false);
    //Добавляем option1 в коллекцию объектов option
    $selectOptionsCollection->add($option1);
    
    //Создаем тег <option value="green">Зеленая тема</option>
    $option2 = new Fields\SelectOption(text: "Зеленая тема", value: "green", selected: false);
    //Добавляем option2 в коллекцию объектов option
    $selectOptionsCollection->add($option2);
    
    //Добавляем коллекцию объектов <option> внутрь объекта <select>
    $selectField->setCollection($selectOptionsCollection);
    
    //Добавляем получившийся <select> в коллекцию полей
    $сollection->add($selectField);
    
    //Добавляем получившуюся коллекцию полей в параметры компонента
    \Tryhardy\Params\Helpers\ComponentParams::setCustomParams(
        $arTemplateParameters,
        $arCurrentValues,
        fieldsCollection: $сollection,
        code: "SELECTS_BLOCK",
        name: "Ссылки:",
        parent: "ADDITIONAL_PARAMETERS",
        multiple: "Y",
        refresh: "N"
    );
}

?>
```

### Тег \<textarea\>\<\/textarea\>
```php
//в файле .parameters.php шаблона компонента
use \Tryhardy\Params\Fields;

/**
 * @global  CMain    $APPLICATION
 * @var     array    $arParams
 * @var     array    $arCurrentValues
 * @var     array    $arResult
 */

if (!\Bitrix\Main\Loader::includeModule('tryhardy.params')) {
    $сollection = new Fields\FieldsCollection();
    
    //<textarea name="description"></textarea>
    $textareaField = (new Fields\TextareaField(name: "description"))->setLabel("Описание");
    
    //Добавляем получившийся <textarea> в коллекцию полей
    $сollection->add($textareaField);
    
    //Добавляем получившуюся коллекцию полей в параметры компонента
    \Tryhardy\Params\Helpers\ComponentParams::setCustomParams(
        $arTemplateParameters,
        $arCurrentValues,
        fieldsCollection: $сollection,
        code: "DESCRIPTION_BLOCK",
        name: "Заголовок с описанием:",
        parent: "ADDITIONAL_PARAMETERS",
        multiple: "Y",
        refresh: "N"
    );
    
}
```

### Вывод поля выбора элемента инфоблока
```php
//в файле .parameters.php шаблона компонента
use \Tryhardy\Params\Fields;

/**
 * @global  CMain    $APPLICATION
 * @var     array    $arParams
 * @var     array    $arCurrentValues
 * @var     array    $arResult
 */

if (!\Bitrix\Main\Loader::includeModule('tryhardy.params')) {
    $сollection = new Fields\FieldsCollection();
    
    $field = (new Fields\IblockField(iblockId: 10, name: "element"))->setLabel("Элемент инфоблока");
    
    $сollection->add($field);
    
    //Добавляем получившуюся коллекцию полей в параметры компонента
    \Tryhardy\Params\Helpers\ComponentParams::setCustomParams(
        $arTemplateParameters,
        $arCurrentValues,
        fieldsCollection: $сollection,
        code: "ELEMENTS_IBLOCK",
        name: "Элементы инфоблока",
        parent: "ADDITIONAL_PARAMETERS",
        multiple: "Y",
        refresh: "N"
    );
    
}
```

### Вывод поля выбора раздела инфоблока
```php
//в файле .parameters.php шаблона компонента
use \Tryhardy\Params\Fields;

/**
 * @global  CMain    $APPLICATION
 * @var     array    $arParams
 * @var     array    $arCurrentValues
 * @var     array    $arResult
 */

if (!\Bitrix\Main\Loader::includeModule('tryhardy.params')) {
    $сollection = new Fields\FieldsCollection();
    
    $field = (new Fields\IblockField(iblockId: 10, name: "section"))->setLabel("Раздел инфоблока")->setIsSection();
    
    $сollection->add($field);
    
    //Добавляем получившуюся коллекцию полей в параметры компонента
    \Tryhardy\Params\Helpers\ComponentParams::setCustomParams(
        $arTemplateParameters,
        $arCurrentValues,
        fieldsCollection: $сollection,
        code: "SECTIONS_IBLOCK",
        name: "Разделы инфоблока",
        parent: "ADDITIONAL_PARAMETERS",
        multiple: "Y",
        refresh: "N"
    );
    
}
```

### Вывод чекбокса
```php
//в файле .parameters.php шаблона компонента
use \Tryhardy\Params\Fields;

/**
 * @global  CMain    $APPLICATION
 * @var     array    $arParams
 * @var     array    $arCurrentValues
 * @var     array    $arResult
 */

if (!\Bitrix\Main\Loader::includeModule('tryhardy.params')) {
    $сollection = new Fields\FieldsCollection();
    
    $field = (new Fields\CheckboxField(name: "agreement"))
        ->setLabel("Подпись для чекбокса")
        ->setChecked(); //Устанавливаем, если нужно, чтобы чекбокс был по умолчанию выбран
    
    $сollection->add($field);
    
    //Добавляем получившуюся коллекцию полей в параметры компонента
    \Tryhardy\Params\Helpers\ComponentParams::setCustomParams(
        $arTemplateParameters,
        $arCurrentValues,
        fieldsCollection: $сollection,
        code: "CHECKBOX_BLOCK",
        name: "Множественный блок с чекбоксами",
        parent: "ADDITIONAL_PARAMETERS",
        multiple: "Y",
        refresh: "N"
    );
    
}
```
