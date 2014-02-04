(function (window, $) {
    var Teapot = Teapot || {};

    Teapot.page = {
        selectors: {
            content: '.content-wrapper'
        },

        pushStateNumber: 0,

        initialize: function () {

        },

        /**
         * Inject HTML content into the page
         *
         * @param  {object}  data  requires key 'title' and 'html'
         */
        inject: function (data) {
            var $wrapper = $(this.selectors.content);

            // Change title from the ajax response
            document.title = data.title;

            // Insert HTML into the content wrapper
            $(this.selectors.content).html(data.html);

            Teapot.events.load($wrapper);

            // Load any potential wysiwyg
            Teapot.wysiwyg.load($wrapper);

            // Go back up
            window.scrollTo(0,0);
        },

        updateToolbar: function (xdebugToken) {
            var currentElement;

            if (typeof Sfjs !== "undefined") {
                currentElement = $('.sf-toolbar')[0];
                Sfjs.load(currentElement.id, '/_wdt/'+ xdebugToken);
            }
        },

        fnEventToggle: function (btn) {
            var self = this;

            $.get($(btn).attr('href'), function(data) {
                var i;

                if (data.success !== 0) {
                    i = $(btn).find('i');

                    Teapot.ui.toggleIcon($(i));

                    Teapot.ui.toggleElementLabel($(btn));

                    Teapot.ui.toggleElementClass($(btn));
                }
            });
        },

        fnEventLoadPage: function (btn) {
            var self = this,
                data;

            $.get($(btn).attr('href'), function (data, status, xhr) {
                self.inject(data);

                self.updateToolbar(xhr.getResponseHeader('X-Debug-Token'));
            });

            this.pushStateNumber++;

            data = {n: this.pushStateNumber, t: 'main-view', p: $(btn).attr('href')};

            window.history.pushState(data, null, $(btn).attr('href'));
        },

        fnEventPopstate: function (event) {
            var self = this,
                state = event.state;

            if (state !== null && state.t === 'main-view') {
                $.get(state.p, function (data) {
                    self.inject(data);
                });
            }
        }
    };

    Teapot.page.initialize();

    window.Teapot = Teapot;

})(window, jQuery);