(function (window, $) {
    var Teapot = window.Teapot || {};

    Teapot.ui = {
        selectors: {
            content: '.content-wrapper'
        },

        initialize: function () {

        },

        toggleElementLabel: function ($el) {
            var i,
                label = $el.attr('data-toggle-label');

            if (label) {
                i = $el.find('i');
                $el.attr('data-toggle-label', $el.text());
                $el.empty();
                $el.append(i);
                $el.append(label);
            }

            return $el;
        },

        toggleElementIcon: function ($el) {
            var self = this,
                $els = $el.find('i[data-toggle-class]');

            $els.each(function (index, element) {
                self.toggleIcon($(element));
            });

            return $el;
        },

        toggleElementClass: function ($el) {
            var className = $el.attr('data-toggle-class');

            if (className) {
                $el.attr('data-toggle-class', $el.attr('class'));
                $el.attr('class', className);
            }

            return $el;
        },

        toggleIcon: function ($el) {
            return this.toggleElementClass($el);
        }
    };

    Teapot.ui.initialize();

    window.Teapot = Teapot;

})(window, jQuery);