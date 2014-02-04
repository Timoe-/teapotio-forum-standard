(function (window, $) {
    var Teapot = window.Teapot || {};

    Teapot.wysiwyg = {

        selectors: {
            btn: {
                reply: '.message-reply',
                quote: '.message-quote'
            }
        },

        initialize: function () {
            this.load(null);
        },

        load: function ($element) {
            var editors,
                options;

            options = {
                buttons: ['formatting', '|', 'bold', 'italic', 'deleted', '|',
                          'unorderedlist', 'orderedlist', '|',
                          'image', 'video', 'table', 'link', '|', 'alignment', '|', 'horizontalrule'],
                convertVideoLinks: true,
                toolbarFixed: true,
                toolbarFixedBox: true,
                focusCallback: function (e) {
                    var className = 'redactor_wysiwyg-initial';

                    if ($(e.currentTarget).hasClass(className)) {
                        $(e.currentTarget)
                            .removeClass('redactor_wysiwyg-initial')
                            .empty();
                    }
                }
            };

            if ($element === null) {
                $editors = $('.wysiwyg');
            } else {
                $editors = $element.find('.wysiwyg');
            }

            $editors.each(function (index, element) {
                $(element).redactor(options);
            });
        },

        fnEventBtnReply: function (btn) {
            var self = this;

            $.get($(btn).attr('href'), function (response) {
                self.insert('<p>' + response.html + '</p>', $('#message-reply-to-topic .wysiwyg').first());
            });
        },

        fnEventBtnQuote: function (btn) {
            var self = this;

            $.get($(btn).attr('href'), function (response) {
                self.insert(response.html + '<p></p>', $('#message-reply-to-topic .wysiwyg').first());
            });
        },

        insert: function (html, $element) {
            $element.redactor('insertHtmlAdvanced', html);
        }
    };

    Teapot.wysiwyg.initialize();

    window.Teapot = Teapot;

})(window, jQuery);