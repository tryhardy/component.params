function onOpenEditor (arParams)
{
    new JsUniversalEditor(arParams);
}

class JsUniversalEditor
{
    constructor(arParams)
    {
        this.itemClass = '.outer' + arParams.oInput.id;
        this.itemsClass = '.items' + arParams.oInput.id;
        this.hiddenInputClass = '.input' + arParams.oInput.id;
        this.moreButtonClass = '.more-btn' + arParams.oInput.id;

        this.arParams = arParams;
        this.jsOptions = this.arParams.data.length > 0 ? JSON.parse(this.arParams.data) : '';

        var $this = this;
        var strUrl = '/bitrix/js/tryhardy.params/custom.block/settings.php' + '?lang=' + this.jsOptions[0];

        BX.ajax.post(
            strUrl,
            {
                DATA: BX.util.urlencode($this.arParams.oInput.value),
                MULTIPLE: $this.arParams.propertyParams.MULTIPLE === 'Y' ? 'Y' : 'N',
                PROPERTY_ID: $this.arParams.propertyID,
                ID: $this.arParams.oInput.id,
                sessid: BX.bitrix_sessid(),
                OPTIONS: arParams.data,
                REQUEST: arParams.request
            },
            function (data) {

                if (data) {
                    $this.arParams.oCont.innerHTML = data;

                    var parent = $this.arParams.oCont;

                    // Вешаем события, отслеживающие изменения инпутов
                    $this.__bindEventsOnInput(parent);

                    // Вешаем копирование на кнопку добавления элемента
                    $this.__bindCloning(parent);
                }

            }
        )
    }

    __bindEventsOnInput = function(parent)
    {
        var $this = this;

        var childBlocks = parent.querySelectorAll($this.itemClass);
        this.arParams.items = childBlocks;

        $this.__fillDataObject();

        if (childBlocks.length > 0) {
            for (var i = 0; i < childBlocks.length; i++) {
                let number = i;
                var childs = childBlocks[number].querySelectorAll('input, select, textarea');

                if (childs.length > 0) {
                    for (var j = 0; j < childs.length; j++) {
                        childs[j].oninput = function (e) {
                            $this.__saveData(e, number);
                        }

                        childs[j].onchange = function (e) {
                            $this.__saveData(e, number);
                        }
                    }
                }
            }
        }
    }

    __fillDataObject = function()
    {
        var $this = this;

        var childBlocks = $this.arParams.oCont.querySelectorAll($this.itemClass);
        $this.arParams.items = childBlocks;

        if (!$this.arParams.dataObject) {
            $this.arParams.dataObject = []
        }

        if (this.arParams.items.length > 0) {
            for (var i = 0; i < childBlocks.length; i++) {
                let number = i;
                var childs = childBlocks[number].querySelectorAll('input, textarea, select');
                if (childs.length > 0) {
                    for (var j = 0; j < childs.length; j++) {
                        if (!$this.arParams.dataObject[number]) {
                            $this.arParams.dataObject[number] = {};
                        }

                        var name = childs[j].dataset.name;
                        var value = childs[j].value;
                        if (name) {
                            $this.arParams.dataObject[number][name] = value;
                        }
                    }
                }
            }
        }
    }

    __saveData = function(item, number)
    {
        var name = item.target.dataset.name;
        var value = item.target.value;

        if (item.target.type == 'checkbox' && !item.target.checked) {
            value = '';
        }

        if (!name || !this.arParams.items[number]) return;

        var hiddenInput = this.arParams.oCont.querySelector(this.hiddenInputClass);
        if (!hiddenInput) return;

        this.arParams.dataObject[number] = this.arParams.dataObject[number] || {};
        this.arParams.dataObject[number][name] = value;

        hiddenInput.value = JSON.stringify(this.arParams.dataObject);
    };

    /**
     * Событие на кнопку "добавить элемент"
     * @private
     */
    __bindCloning = function()
    {
        var $this = this;
        var parent = $this.arParams.oCont;
        let moreBtn = parent.querySelectorAll(this.moreButtonClass);
        console.log('__bindCloning');
        if (moreBtn.length > 0) {
            for (let i = 0; i < moreBtn.length; i++) {

                if (!moreBtn[i].dataset.group) {
                    moreBtn[i].onclick = function (e) {
                        $this.__addItem();
                    }
                }
                else {
                    console.log(moreBtn[i]);
                    moreBtn[i].onclick = function (e) {
                        $this.__addGroupItem(moreBtn[i]);
                    }
                }
            }
        }
    }

    /**
     * @param item
     * @private
     */
    __addGroupItem = function(button, action = 'clone_group')
    {
        var parent = button.closest(".group-fields");

        if (!parent) {
            console.log('parent not found');
            return;
        }

        var wrapper = parent.querySelector(".group-fields__wrapper");

        if (!wrapper) {
            console.log('wrapper not found');
            return;
        }

        var hash = parent.dataset.parent;
        var name = parent.dataset.name;
        var items = parent.querySelectorAll("[data-name='" + hash + "']");
        var $this = this;
        var arParams = this.arParams;
        var strUrl = '/bitrix/js/tryhardy.params/custom.block/settings.php' + '?lang=' + this.jsOptions[0];
        var count = items.length;

        BX.ajax.post(
            strUrl,
            {
                DATA: BX.util.urlencode(arParams.oInput.value),
                MULTIPLE: arParams.propertyParams.MULTIPLE === 'Y' ? 'Y' : 'N',
                PROPERTY_ID: arParams.propertyID,
                ID: arParams.oInput.id,
                sessid: BX.bitrix_sessid(),
                OPTIONS: arParams.data,
                ACTION:action,
                NUMBER: count,
                HASH: hash,
                NAME: name
            },
            function (data) {

                var clone = document.createElement('div');
                clone.innerHTML = data;
                var inputs = clone.querySelectorAll('input, textarea, select');
                if (inputs.length > 0) {
                    for (var i = 0; i < inputs.length; i++) {
                        inputs[i].value = '';
                    }
                }

                var currentData = clone.querySelector('.group-fields__item');

                if (currentData) {
                    wrapper.appendChild(currentData);
                }

                $this.__bindEventsOnInput($this.arParams.oCont);
                $this.__bindCloning();
            }
        )
    }

    /**
     * Функция клонирования элементов
     * @param item
     * @private
     */
    __addItem = function(action = 'clone')
    {
        var parent = this.arParams.oCont.querySelector(this.itemsClass);
        var $this = this;
        var arParams = this.arParams;
        var strUrl = '/bitrix/js/tryhardy.params/custom.block/settings.php' + '?lang=' + this.jsOptions[0];
        var count = this.arParams.items.length;

        BX.ajax.post(
            strUrl,
            {
                DATA: BX.util.urlencode(arParams.oInput.value),
                MULTIPLE: arParams.propertyParams.MULTIPLE === 'Y' ? 'Y' : 'N',
                PROPERTY_ID: arParams.propertyID,
                ID: arParams.oInput.id,
                sessid: BX.bitrix_sessid(),
                OPTIONS: arParams.data,
                ACTION:action,
                NUMBER: count
            },
            function (data) {
                var clone = document.createElement('div');
                clone.innerHTML = data;

                var inputs = clone.querySelectorAll('input, textarea, select');
                if (inputs.length > 0) {
                   for (var i = 0; i < inputs.length; i++) {
                       if (inputs[i].type == 'button') continue;
                       inputs[i].value = '';
                   }
                }

                var currentData = clone.querySelector($this.itemClass);

                parent.appendChild(currentData);
                $this.__bindEventsOnInput($this.arParams.oCont);
                $this.__bindCloning();
            }
        )
    }
}
