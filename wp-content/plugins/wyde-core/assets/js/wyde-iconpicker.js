/*
 * Dropit v1.1.0
 * http://dev7studios.com/dropit
 *
 * Copyright 2012, Dev7studios
 * Free to use and abuse under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */

(function ($) {

    $.fn.dropit = function (method) {

        var methods = {

            init: function (options) {
                this.dropit.settings = $.extend({}, this.dropit.defaults, options);
                return this.each(function () {
                    var $el = $(this),
                         el = this,
                         settings = $.fn.dropit.settings;

                    // Hide initial submenus
                    $el.addClass('dropit')
                    .find('>' + settings.triggerParentEl + ':has(' + settings.submenuEl + ')').addClass('dropit-trigger')
                    .find(settings.submenuEl).addClass('dropit-submenu');

                    // Open on click
                    $el.find(settings.triggerParentEl + ':has(' + settings.submenuEl + ') > ' + settings.triggerEl).on(settings.action, function () {
                        // Close click menu's if clicked again
                        event.preventDefault();

                        if (settings.action == 'click' && $(this).parents(settings.triggerParentEl).hasClass('dropit-open')) {
                            settings.beforeHide.call(this);
                            $(this).parents(settings.triggerParentEl).removeClass('dropit-open');
                            settings.afterHide.call(this);
                            $(this).find(".dropit-arrow").removeClass("fa-angle-up").addClass("fa-angle-down");
                            return false;
                        }

                        // Hide open menus
                        settings.beforeHide.call(this);
                        $('.dropit-open').removeClass('dropit-open');
                        settings.afterHide.call(this);

                        // Open this menu
                        settings.beforeShow.call(this);
                        $(this).parents(settings.triggerParentEl).addClass('dropit-open');
                        settings.afterShow.call(this);
                        $(this).find(".dropit-arrow").removeClass("fa-angle-down").addClass("fa-angle-up");

                        return false;
                    });

                    // Close if outside click
                    $(document).on('click', function () {

                        settings.beforeHide.call(this);
                        $('.dropit-open').removeClass('dropit-open');
                        settings.afterHide.call(this);
                        $(this).find(".dropit-arrow").removeClass("fa-angle-up").addClass("fa-angle-down");


                    });

                    $el.find(".dropit-submenu").find(settings.triggerEl).on("click", function (event) {
                        event.preventDefault();
                        settings.beforeHide.call(this);
                        $el.find('.dropit-open').removeClass('dropit-open');
                        settings.afterHide.call(this);
                        settings.afterSelect.call(this, $el);
                        $(this).parents(settings.triggerParentEl).find(".dropit-arrow").removeClass("fa-angle-up").addClass("fa-angle-down");
                        return false;
                    });

                    // If hover
                    if (settings.action == 'mouseenter') {
                        $el.on('mouseleave', function () {
                            settings.beforeHide.call(this);
                            $(this).removeClass('dropit-open');
                            settings.afterHide.call(this);
                            $(this).find(".dropit-arrow").removeClass("fa-angle-up").addClass("fa-angle-down");
                        });
                    }

                    settings.afterLoad.call(this);
                });
            }

        };

        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method "' + method + '" does not exist in dropit plugin!');
        }

    };

    $.fn.dropit.defaults = {
        action: 'click', // The open action for the trigger
        submenuEl: 'ul', // The submenu element
        triggerEl: 'a', // The trigger element
        triggerParentEl: 'li', // The trigger parent element
        afterLoad: function () { }, // Triggers when plugin has loaded
        beforeShow: function () { }, // Triggers before submenu is shown
        afterShow: function () { }, // Triggers after submenu is shown
        beforeHide: function () { }, // Triggers before submenu is hidden
        afterHide: function () { }, // Triggers after submenu is hidden
        afterSelect: function () { } // Triggers after submenu is hidden
    };

    $.fn.dropit.settings = {};

    $(".wyde-icons").each(function () {
        
        var $el = $(this);

        $el.find(".list-icons").dropit({
            afterSelect: function (o) {
                $el.find(".selected-value").html($(this).html());
                $el.find("input.wyde-icon-field").val($(this).find("i").attr("class"));
            }
        });
    })


})(jQuery);