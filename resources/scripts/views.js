(function (OverDocs) {
    'use strict';

    var View = OverDocs.View = Backbone.View.extend({
        empty: function () {
            this.$el.empty();
            this.stopListening();
        },

        add: function (view) {
            this.$el.append(view instanceof Backbone.View ? view.$el : view);
        },

        initialize: function (options) {
            this.render();
            Backbone.View.prototype.initialize.call(options);
        },

        refresh: function () {
            // We save a copy of original ui object (the selector to name
            // mapping) in case the view needs to be refresh()ed once again.
            var ui;
            if (this.origUi) {
                ui = this.origUi;
            } else {
                ui = this.origUi = this.ui;
            }
            this.ui = {};
            if (ui) {
                for (var key in ui) {
                    if (ui.hasOwnProperty(key)) {
                        this.ui[key] = this.$(ui[key]);
                    }
                }
            }
        }
    });

    var CollectionView = OverDocs.view.CollectionView = View.extend({
        /**
         * The view representing the model.
         * @type {function}
         */
        ChildView: View,

        /**
         * Initializes the CollectionView. If you override
         * initialize(), call this.observe().
         */
        initialize: function () {
            this.childViews = [];
            this.observe();
            this.render();
        },

        /**
         * Adds listeners to re-render the view when the collection changes.
         */
        observe: function () {
            if (!(this.collection instanceof Backbone.Collection)) {
                throw new TypeError('Expected the collection to be a Backbone.Collection.');
            }
            this.collection.on('add change remove reset', this.render.bind(this));
        },

        unobserve: function () {
            this.collection.off('add change remove reset', this.render.bind(this));
        },

        /**
         * Re-renders the collection view.
         */
        render: function () {
            this.renderCollection();
        },

        /**
         * Renders collection items.
         */
        renderCollection: function () {
            this.empty();
            this.childViews = [];
            this.collection.each(function (model) {
                var childView = new this.ChildView({
                    model: model
                });
                this.childViews.push(childView);
                this.$el.append(childView.$el);
            }.bind(this));
        },

        /**
         * Returns the ChildView at the given position between [0, n - 1].
         */
        viewAt: function (index) {
            return this.childViews[index];
        }
    });

}(OverDocs));
