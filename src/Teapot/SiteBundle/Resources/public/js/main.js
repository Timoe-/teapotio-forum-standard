(function (window, document) {

    $(document).ready(function (event) {

        $("#overlay")
            // .height($(document).height())
            .click(function(event){
                $(this).addClass('hide');
            });

        $("#nav-components a.to-main").live('click', function (event) {
            $("#nav-components").slideUp(100, function () {
                $(this).children().css('display', 'none');
            });

        });

        var pushStateNumber = 0;
        $("a.to-main").live('click', function (event){
            $('body').css('cursor', 'progress');

            $.get($(this).attr('href'), function (data, status, xhr) {
                reloadPage(data);

                $('body').css('cursor', 'default');
                $('a').css('cursor', 'pointer');

                var xdebugToken = xhr.getResponseHeader('X-Debug-Token');
                updateToolbar(xdebugToken);
            });

            pushStateNumber++;

            var data = {n: pushStateNumber, t: 'main-view', p: $(this).attr('href')};

            window.history.pushState(data, null, $(this).attr('href'));

            event.preventDefault();
        });

        window.onpopstate = function(event){
            var state = event.state;

            if (state !== null) {
                if (state.t === 'main-view') {
                    $.get(state.p, function(data) {
                        reloadPage(data);
                    });
                }
            }
        };

        var loadWysiwyg = function (element) {
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

            if (element === null) {
                editors = $('.wysiwyg');
            } else {
                editors = $(element).find('.wysiwyg');
            }

            editors.each(function (index, element) {
                $(element).redactor(options);
            });
        };

        loadWysiwyg(null);

        var reloadPage = function (data) {
            var targetClass = '.content-wrapper';
            document.title = data.title;
            $(targetClass).html(data.html);
            loadWysiwyg($(targetClass));
            window.scrollTo(0,0);
        };

        var scrollToTop = function () {
            var diff,
                distance = $(document).scrollTop(),
                delay = 2,
                decrementBy = 10, // How many pixels it decrements every "delay" ms
                interval = window.setInterval(function() {
                    distance -= decrementBy;

                    if (distance <= 0) {
                        distance = 0;
                        window.clearTimeout(interval);
                    }

                    window.scrollTo(0, distance);
                }, delay);
        };

        $(".form-save").live('submit', function(event){

            var form = this;

            $(form).find('.feedback').html('');

            $.post($(this).attr('action'), $(this).serialize(), function(data) {
                if (data.success === 0) {
                    $(form).find('.feedback').html('<div class="alert alert-danger">'+ data.message +'</div>');
                }
                else {
                    $(form).find('.feedback').html('<div class="alert alert-success">'+ data.message +'</div>');
                }
            });

            event.preventDefault();
        });

        $(".add-topic").live('click', function(event){
            var $wrap = $(".form-new-topic-wrap");

            if ($wrap.length > 0) {
                $wrap.slideToggle();

                event.preventDefault();
            }

        });

        $(".boards-concise-list li.selected").children('ul.hide').removeClass('hide');
        $(".boards-concise-list li.selected").parentsUntil('.boards-concise-list', 'ul.hide').removeClass('hide');
        toggleElementIcon($(".boards-concise-list li.selected").children('.expand-subcategory'));
        toggleElementIcon($(".boards-concise-list li.selected").parentsUntil('.boards-concise-list', 'li').children('a.expand-subcategory'));

        $("#nav-horizontal a").click(function (event) {
            event.preventDefault();

            var targetSelector = $(this).attr('href');
            var $targetElement = $(targetSelector);
            var targetDisplay = $targetElement.css('display');
            var display;

            $('#nav-components .nav-component').css('display', 'none');

            if (targetDisplay === 'block') {
                display = 'none';
            } else {
                display = 'block';
            }

            $('#nav-components, ' + targetSelector).css('display', display);

        });

        $("#nav-components .collapse").click(function (event) {
            event.preventDefault();
            $('#nav-components .nav-component, #nav-components').css('display', 'none');
        });

        $(".btn-toggle").live('click', function(event){
            event.preventDefault();

            var btn = this;

            $.get($(this).attr('href'), function(data) {

                if (data.success === 0) {
                    // $(form).find('.feedback').html('<div class="alert alert-danger">'+ data.message +'</div>');
                }
                else {

                    var i = $(btn).find('i');

                    toggleIcon(i);

                    toggleElementLabel(btn);

                    toggleElementClass(btn);
                }
            });
        });

        $('.toggle-visibility').live('click', function(event){
            var target = $(this).attr('data-target');
            var targets = target.split(',');

            for (var i = 0; i < targets.length; i++) {
                if ($(targets[i]).css('display') === 'block') {
                    $(targets[i]).css('display', 'none');
                } else {
                    $(targets[i]).css('display', 'block');
                }
            }

            toggleElementLabel(this);

            toggleElementClass(this);

            event.preventDefault();
        });

        $("#nav-list-boards .expand").live('click', function(event){
            var $list = $(this).parent().next('ul');

            $list.slideToggle();
            toggleElementIcon($(this));

            event.preventDefault();
        });

        $(".boards-expand-list").live('click', function(event){
            var $list = $(".boards-hidden-list");

            $list.slideToggle();
            toggleElementIcon($(this));
            event.preventDefault();

        });

        $(".expand-subcategory").live('click', function(event){
            var uls = $(this).siblings('ul');

            $(uls[0]).slideToggle();
            toggleElementIcon($(this));

            event.preventDefault();
        });
    });

    var toggleElementLabel = function (el) {
        var label = $(el).attr('data-toggle-label');

        if (label) {
            var i = $(el).find('i');
            $(el).attr('data-toggle-label', $(el).text());
            $(el).empty();
            $(el).append(i);
            $(el).append(label);
        }
    };

    var toggleElementIcon = function (el) {
        var $els = el.find('i[data-toggle-class]');

        $els.each(function(index){
            toggleIcon(this);
        });

        return true;
    };

    var toggleElementClass = function (el) {
        var className = $(el).attr('data-toggle-class');

        if (className) {
            $(el).attr('data-toggle-class', $(el).attr('class'));
            $(el).attr('class', className);
        }
    }

    var toggleIcon = function(el) {
        var className = $(el).attr('data-toggle-class');
        $(el).attr('data-toggle-class', $(el).attr('class'));
        $(el).attr('class', className);

        return el;
    };

    var updateToolbar = function (xdebugToken) {
        if (typeof Sfjs !== "undefined") {
            var currentElement = $('.sf-toolbar')[0];
            Sfjs.load(currentElement.id, '/_wdt/'+ xdebugToken);
        }
    };

})(window, document);