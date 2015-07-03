(function (OverDocs) {
    'use strict';

    var SearchResults = OverDocs.model.SearchResults = Backbone.Collection.extend();

    var Search = OverDocs.model.Search = Backbone.Model.extend({
        defaults: {
            query: '',
            category: undefined,
            topic: '',
            section: undefined
        },

        initialize: function () {
            _.bindAll(this, 'parseQuery', 'evaluate');
            this.on('change:query', this.parseQuery);
        },

        parseQuery: function () {
            // TODO: Actual parsing of the query into topic, category and section
            this.topic = this.query;
        },

        evaluate: function () {
            var sheets = Object.keys(OverDocs.index.sheets).map(function (key) {
                return OverDocs.index.sheets[key];
            });

            var fuse = new Fuse(sheets, {
                keys: ['title', 'summary', 'keywords'],
                threshold: 0.3
            });

            return new SearchResults(fuse.search(this.get('query')));
        }
    });
}(OverDocs));
