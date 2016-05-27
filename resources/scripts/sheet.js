(function () {
    'use strict';

    function createSelectAllButton() {
        return $('<button>Select all</button>')
            .addClass('code-button')
            .click(function () {
                selectText(this.parentNode.querySelector('code'), this);
            });
    }

    function selectText(el, button) {
        var sel = window.getSelection();
        var range = document.createRange();
        range.selectNodeContents(el);
        sel.removeAllRanges();
        sel.addRange(range);
        button.blur();
    }

    var $codeBlocks = $('pre');

    $codeBlocks.each(function (index, block) {
        $(block).prepend(createSelectAllButton());
    });
}());
