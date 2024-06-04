Простой модуль для настройки параметров компонентов без необходимости хранить значения в отдельной таблице в БД.

## Пример использования (множественный блок ссылок):
![Пример использования](/images/image_1.png)

```php
<?php
use \Tryhardy\Params\Fields;
//в файле .parameters.php шаблона компонента
if (!\Bitrix\Main\Loader::includeModule('tryhardy.params')) {
    //Заводим коллекцию полей, из которых будет состоять кастомный блок параметров компонента
    $сollection = new Fields\FieldsCollection();
        
    //<input type="text" name="{name}">
    $simpleInputField = (new Fields\Field(name: 'name')->setLabel('Some random label'));
    $сollection->add($simpleInputField);
		
	//<select name="{name}"></select>
	$selectField = (new Fields\SelectField(name: 'theme'))->setLabel('Тема');
	// коллекция объектов <option> (пока что пустая)
	$selectOptionsCollection = new Fields\SelectOptionCollection();
	//<option value=""></option>
	$selectOptionsCollection->add((new Fields\SelectOption(text: "Синяя тема", value: "blue", selected: false)));
	$selectOptionsCollection->add((new Fields\SelectOption(text: "Зеленая тема", value: "green", selected: false)));
	//Добавляем <option> внутрь <select>
	$selectField->setCollection($selectOptionsCollection);
	//Добавляем <select> в коллекцию полей
	$сollection->add($selectField);
}

?>
```
