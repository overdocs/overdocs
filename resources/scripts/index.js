(function (OverDocs) {
    'use strict';

    if (OverDocs.index) {
        return;
    }

    var xhr = new XMLHttpRequest();

    xhr.open('GET', 'index.json', false);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                OverDocs.index = JSON.parse(xhr.responseText);
                OverDocs.events.trigger('index');
            } else {
                console.error('Could not GET index.json: HTTP ' + xhr.status);
            }
        }
    };
    xhr.send(null);
}(OverDocs));
