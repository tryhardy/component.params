function openFileDialogSubmit(...args) {
    //console.log(args);
}

window.UPFIB_openFileDialog = function (config) {
    window.BXFileDialog = window.BXFileDialog || undefined;
    if (!window.BXFileDialog) {
        return;
    }

    window.oBXFileDialog = new BXFileDialog();

    // noinspection JSUnresolvedFunction
    oBXFileDialog.Open(
        {
            ...{
                select: 'F',
                operation: 'O',
                saveConfig: true,
                checkChildren: true,
                genThumb: true,
                showAddToMenuTab: false,
                zIndex: 2500,

                allowAllFiles: true,
                showUploadTab: true,
                path: '/upload',
                submitFuncName: 'openFileDialogSubmit',
                fileFilter: 'jpg,jpeg,gif,png,svg',

                site: BX.message['SITE_ID'] || 's1',
                lang: BX.message['LANGUAGE_ID'] || 'ru',
                sessid: BX.message["bitrix_sessid"] || '',
            },
            ...config
        },
        config
    );
}

window.UPFIB_submitFileDialog = function (elementId, filename, path, site, title, menu) {
    const inputEl = document.getElementById(elementId);

    path = jsUtils.trim(path);
    path = path.replace(/\\/ig, '/');
    path = path.replace(/\/\//ig, '/');
    if (path.substr(path.length - 1) == '/')
        path = path.substr(0, path.length - 1);
    var full = (path + '/' + filename).replace(/\/\//ig, '/');
    if (path == '')
        path = '/';

    var arBuckets = [];
    if (arBuckets[site]) {
        full = arBuckets[site] + filename;
        path = arBuckets[site] + path;
    }

    if ('F' == 'D') name = full;

    inputEl.value = full;
};
