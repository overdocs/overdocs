 (function (OverDocs) {
    'use strict';

    var Search        = OverDocs.model.Search;
    var SearchResults = OverDocs.model.SearchResults;

    var Keys = {
        ARROW_UP: 38,
        ARROW_DOWN: 40,
        ARROW_LEFT: 37,
        ARROW_RIGHT: 39,
        RETURN: 13,
        ESCAPE: 27
    };

    var SearchResultView = OverDocs.View.extend({
        template: OverDocs.getTemplate('search-result'),

        tagName: 'a',

        className: 'search-result',

        events: {
            'click a': 'open'
        },

        initialize: function () {
            _.bindAll(this, 'open');
            this.render();
        },

        render: function () {
            this.$el.attr('href', this.model.get('path'))
                .html(this.template({
                    path: this.model.get('path'),
                    title: this.model.get('title'),
                    summary: this.model.get('summary'),
                    category: this.model.get('category'),
                    categoryName: OverDocs.index.category_names[this.model.get('category')]
                }));
        },

        open: function () {
            window.location.href = this.model.get('path');
            return false;
        }
    });

    var SearchResultsView = OverDocs.view.CollectionView.extend({
        ChildView: SearchResultView,
        className: '.search-results',
        collection: SearchResults,
        cursor: 0,

        events: {
            'mouseenter .search-result': 'onResultMouseEnter'
        },

        initialize: function () {
            _.bindAll(this, 'render', 'selectNth', 'openSelected', 'selectedView',
                    'moveBy', 'hasNth', 'updateSelection');
            this.observe();
            this.render();
        },

        render: function () {
            this.renderCollection();
            this.updateSelection();
        },

        moveBy: function (delta) {
            if (this.hasNth(this.cursor + delta)) {
                this.cursor += delta;
                this.updateSelection();
            }
        },

        onResultMouseEnter: function (e) {
            var $target = $(e.target);
            var index = this.$('.search-result').index($target);

            if (index >= 0) {
                this.selectNth(index);
            }
        },

        selectNth: function (index) {
            if (this.hasNth(index)) {
                this.cursor = index;
                this.updateSelection();
            }
        },

        hasNth: function (index) {
            return (index >= 0 && index < this.collection.length);
        },

        updateSelection: function () {
            this.$('.search-result--selected').removeClass('search-result--selected');

            if (!this.isEmpty()) {
                this.selectedView().$el.addClass('search-result--selected');
            }
        },

        isEmpty: function () {
            return !this.collection.length;
        },

        openSelected: function () {
            this.selectedView().open();
        },

        selectedView: function () {
            return this.viewAt(this.cursor);
        }
    });

    var SearchView = OverDocs.view.SearchView = OverDocs.View.extend({
        template: OverDocs.getTemplate('search'),

        ui: {
            input: '.search-input',
            results: '.search-results',
            search: '.search'
        },

        events: {
            'keyup .search-input': 'handleKey'
        },

        initialize: function () {
            _.bindAll(this, 'handleKey');
            this.render();
        },

        render: function () {
            this.$el.html(this.template());
            this.refresh();
            this.randomize();
        },

        randomize: function () {
            var keys = Object.getOwnPropertyNames(OverDocs.index.sheets);
            var key = keys[Math.floor(Math.random() * keys.length)];
            var sheet = OverDocs.index.sheets[key];

            this.ui.input.attr('placeholder', 'Search for »' + sheet.title.toLowerCase() + '«');
        },

        updateSearchResults: function (query) {
            if (query.trim() === '') {
                this.clear();
                return;
            }

            var search = new Search({
                query: query
            });
            var results = search.evaluate();

            if (results.length) {
                this.ui.search.addClass('search--hasResults');
                this.ui.search.removeClass('search--hasNoResults');
            } else {
                this.ui.search.addClass('search--hasNoResults');
                this.ui.search.removeClass('search--hasResults');
            }

            if (!this.resultsView) {
                this.resultsView = new SearchResultsView({
                    collection: results,
                    el: this.ui.results
                });
            } else {
                this.resultsView.collection.reset(results.models);
            }
        },

        handleKey: function (e) {
            if (e.keyCode === Keys.ARROW_DOWN && this.resultsView) {
                this.resultsView.moveBy(1);
                return false;
            } else if (e.keyCode === Keys.ARROW_UP && this.resultsView) {
                this.resultsView.moveBy(-1);
                return false;
            } else if (e.keyCode === Keys.RETURN && this.resultsView) {
                this.resultsView.openSelected();
                return false;
            } else if (e.keyCode === Keys.ESCAPE) {
                this.ui.input.val('');
                this.clear();
            } else {
                this.updateSearchResults(this.ui.input.val());
            }
        },

        clear: function () {
            this.resultsView.collection.reset();
            this.ui.search.removeClass('search--hasResults');
            this.ui.search.removeClass('search--hasNoResults');
        }
    });
}(OverDocs));
