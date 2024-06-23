# Оглавление
1. [Описание](#t1)
2. [Пример использования](#t2)
3. [Вывод \<input type="text">](#t3)
4. [Вывод \<select>](#t4)
5. [Вывод \<textarea>](#t5)
6. [Вывод диалогового окна с выбором элемента инфоблока (по ID)](#t6)
7. [Вывод диалогового окна с выбором выбора раздела инфоблока](#t7)
8. [Вывод \<input type="checkbox">](#t8)
9. [Вывод диалогового окна с выбором файла](#t9)
10. [Вывод вложенных групп полей](#t10)

# <a id='t1'>Описание</a>
Простой модуль для настройки параметров компонентов без необходимости хранить значения в отдельной таблице в БД.

В некоторых случаях помогает сэкономить время на написании миграций.

Состав полей можно конфигурировать практически в любых комбинациях.

Полезно, если на проекте много плашек с фактоидами и табами, которые нет смысла хранить в БД.

## <a id='t2'>Пример использования (множественный блок ссылок)</a>
![Пример использования](/images/image_1.png)

## <a id='t3'>Вывод \<input type="text"></a>
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

if (\Bitrix\Main\Loader::includeModule('tryhardy.params')) {
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

## <a id='t4'>Вывод \<select></a>
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

if (\Bitrix\Main\Loader::includeModule('tryhardy.params')) {
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

### <a id='t5'>Вывод \<textarea></a>
```php
//в файле .parameters.php шаблона компонента
use \Tryhardy\Params\Fields;

/**
 * @global  CMain    $APPLICATION
 * @var     array    $arParams
 * @var     array    $arCurrentValues
 * @var     array    $arResult
 */

if (\Bitrix\Main\Loader::includeModule('tryhardy.params')) {
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

### <a id='t6'>Вывод диалогового окна с выбором элемента инфоблока (по ID)</a>
```php
//в файле .parameters.php шаблона компонента
use \Tryhardy\Params\Fields;

/**
 * @global  CMain    $APPLICATION
 * @var     array    $arParams
 * @var     array    $arCurrentValues
 * @var     array    $arResult
 */

if (\Bitrix\Main\Loader::includeModule('tryhardy.params')) {
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

### <a id='t7'>Вывод диалогового окна с выбором выбора раздела инфоблока (по ID)</a>
```php
//в файле .parameters.php шаблона компонента
use \Tryhardy\Params\Fields;

/**
 * @global  CMain    $APPLICATION
 * @var     array    $arParams
 * @var     array    $arCurrentValues
 * @var     array    $arResult
 */

if (\Bitrix\Main\Loader::includeModule('tryhardy.params')) {
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

### <a id='t8'>Вывод \<input type="checkbox"></a>
```php
//в файле .parameters.php шаблона компонента
use \Tryhardy\Params\Fields;

/**
 * @global  CMain    $APPLICATION
 * @var     array    $arParams
 * @var     array    $arCurrentValues
 * @var     array    $arResult
 */

if (\Bitrix\Main\Loader::includeModule('tryhardy.params')) {
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
https://github.com/tryhardy/tryhardy.params/assets/61752684/02e3a90d-0db2-49bf-9367-c0683a358f83



### <a id='t9'>Вывод диалогового окна с выбором файла</a>
```php
//в файле .parameters.php шаблона компонента
use \Tryhardy\Params\Fields;

/**
 * @global  CMain    $APPLICATION
 * @var     array    $arParams
 * @var     array    $arCurrentValues
 * @var     array    $arResult
 */

if (\Bitrix\Main\Loader::includeModule('tryhardy.params')) {
    $сollection = new Fields\FieldsCollection();
    
    $field1 = (new Fields\FileField(name: "image_desc"))->setLabel("Изображение для таба (Desc)");
    $field2 = (new Fields\FileField(name: "image_tab"))->setLabel("Изображение для таба (Tab)");
    $field3 = (new Fields\FileField(name: "image_mob"))->setLabel("Изображение для таба (Mob)");
    $сollection->add($field1);
    $сollection->add($field2);
    $сollection->add($field3);
    
    //Добавляем получившуюся коллекцию полей в параметры компонента
    \Tryhardy\Params\Helpers\ComponentParams::setCustomParams(
        $arTemplateParameters,
        $arCurrentValues,
        fieldsCollection: $сollection,
        code: "IMAGES_BLOCK",
        name: "Множественный блок с изображениями",
        parent: "ADDITIONAL_PARAMETERS",
        multiple: "Y",
        refresh: "N"
    );
    
}
```
https://github.com/tryhardy/tryhardy.params/assets/61752684/fb9bde56-3e39-41d6-8395-0485b4bd6dda


### <a id='t10'>Вывод вложенных групп полей</a>
```php
//в файле .parameters.php шаблона компонента
use \Tryhardy\Params\Fields;

/**
 * @global  CMain    $APPLICATION
 * @var     array    $arParams
 * @var     array    $arCurrentValues
 * @var     array    $arResult
 */

if (\Bitrix\Main\Loader::includeModule('tryhardy.params')) {

    //Создаем пустую коллекцию полей
    $сollection = new FieldsCollection();
    //Добавляем в нее <input type="text" name="name" placeholder="Заголовок">
	$сollection->add((new Field('name', 'Заголовок'))->setLabel('Заголовок'));
    //Добавляем в нее <textarea name="descr" placeholder="Описание">
	$сollection->add((new TextareaField('descr', 'Описание'))->setLabel('Описание'));

    //Создаем множественную пустую группу полей, которая будет вложена внутрь коллекции $сollection
    $groupField = (new GroupFields('groupFields', 'Группа полей'))->setMultiple();
	//Настраиваем коллекцию полей, которые будут вложены в экземпляр $groupField
	$GroupFieldsCollection = new FieldsCollection();
	$GroupFieldsCollection->add((new Field('href', 'Href'))->setLabel('Href'));
	$GroupFieldsCollection->add((new Field('text', 'Название ссылки'))->setLabel('Описание'));
	$GroupFieldsCollection->add((new IblockField((int)$arCurrentValues['IBLOCK_ID'], 'iblock'))->setLabel('Инфоблок'));
	$groupField->setFields($GroupFieldsCollection);
	
	//Добавляем группу полей в коллекцию 
	$сollection->add($groupField);
    
    //Добавляем получившуюся коллекцию полей в параметры компонента
    \Tryhardy\Params\Helpers\ComponentParams::setCustomParams(
        $arTemplateParameters,
        $arCurrentValues,
        fieldsCollection: $сollection,
        code: "GROUP_FIELDS",
        name: "Кастомный блок",
        parent: "ADDITIONAL_SETTINGS",
        multiple: "Y",
        refresh: "N"
    );
    
}
```
https://github.com/tryhardy/tryhardy.params/assets/61752684/0cd6fd72-d745-47bf-a5c7-109a3e1da801 
