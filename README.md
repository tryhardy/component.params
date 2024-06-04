Простой модуль для настройки параметров компонентов без необходимости хранить значения в отдельной таблице в БД.

## Пример использования (множественный блок ссылок):
![Пример использования](/images/image_1.png)

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
        
    //Простое тектовое поле: <input type="text" name="{name}">
    $simpleInputField = (new Fields\Field(name: 'name')->setLabel('Some random label'));
    //Добавляем это поле в коллекцию
    $сollection->add($simpleInputField);
    
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
        code: "LINKS_BLOCK",
        name: "Ссылки:",
        parent: "ADDITIONAL_PARAMETERS",
        multiple: "Y",
        refresh: "N"
    );
}

?>
```
