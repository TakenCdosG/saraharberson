(function ($) {
    "use strict";

    window.$ = $;
    /*****************************************
    Wyde Core 2.0.1
    /******************************************/
    $.extend(window, {
        wyde: {
            init: function () {
                this.version = "1.2";
                this.browser = {};
                this.detectBrowser();

                Modernizr.addTest("boxsizing", function () {
                    return Modernizr.testAllProps("boxSizing") && (document.documentMode === undefined || document.documentMode > 7);
                });

            },
            detectBrowser: function () {

                this.browser.touch = (Modernizr.touch) ? true : false;
                this.browser.css3 = (Modernizr.csstransforms3d) ? true : false;
                
                var self = this;
                var getBrowserScreenSize = function(){
                    var w = $(window).width();
                    self.browser.xs = w < 768;
                    self.browser.sm = (w > 767 && w < 992);
                    self.browser.md = (w > 991 && w < 1200);
                    self.browser.lg = w > 1199;                  
                  
                };

                getBrowserScreenSize();

                var ua = window.navigator.userAgent;
                var msie = ua.indexOf("MSIE ");

                // IE 10 or older
                if (msie > 0) {
                    this.browser.msie = parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
                }

                // IE 11
                var trident = ua.indexOf('Trident/');
                if (trident > 0) {
                    var rv = ua.indexOf('rv:');
                    this.browser.msie = parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
                }

                // IE 12
                var edge = ua.indexOf('Edge/');
                if (edge > 0) {
                    this.browser.msie = parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
                }

                this.browser.prefix = "";

                if (this.browser.css3 === true) {
                    var styles = window.getComputedStyle(document.documentElement, "");
                    this.browser.prefix = "-" + (Array.prototype.slice.call(styles).join("").match(/-(moz|webkit|ms)-/) || (styles.OLink === "" && ["", "o"]))[1] + "-";
                }
                
                $(window).resize(function (event) {                    
                    getBrowserScreenSize();                    
                });

            }
        }
    });

    wyde.init();

    /*****************************************
    String Extension
    /*****************************************/
    $.extend(String, {

        format: function () {
            if (arguments.length == 0) return null;
            var args;
            if (arguments.length == 1) args = arguments[0];
            else args = arguments;

            var result = args[0];
            for (var i = 1; i < args.length; i++) {
                var re = new RegExp("\\{" + (i - 1) + "\\}", "gm");
                result = result.replace(re, args[i]);
            }
            return result;
        }
    });

    $.extend(String.prototype, {

        trim: function (ch) {
            if (!ch) ch = ' ';
            return this.replace(new RegExp("^" + ch + "+|" + ch + "+$", "gm"), "").replace(/(\n)/gm, "");
        },
        ltrim: function () {
            return this.replace(/^\s+/, "");
        },
        rtrim: function () {
            return this.replace(/\s+$/, "");
        },
        reverse: function () {
            var res = "";
            for (var i = this.length; i > 0; --i) {
                res += this.charAt(i - 1);
            }
            return res;
        },
        startWith: function (pattern) {
            return this.match('^' + pattern);
        },
        endsWith: function (pattern) {
            return this.match(pattern + '$');
        }
    });

    /*****************************************
    Utilities
    /*****************************************/
    $.extend(window, {

        /*  Convert Hex color to RGB color */
        hex2rgb: function (hex, opacity) {

            var hex = hex.replace("#", "");
            var r = parseInt(hex.substring(0, 2), 16);
            var g = parseInt(hex.substring(2, 4), 16);
            var b = parseInt(hex.substring(4, 6), 16);

            return String.format("rgba({0},{1},{2},{3})", r, g, b, opacity);
        },
        /*  Convert RGB color to Hex color */
        rgb2hex: function (rgb) {
            rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
            return (rgb && rgb.length === 4) ? "#" +
              ("0" + parseInt(rgb[1], 10).toString(16)).slice(-2) +
              ("0" + parseInt(rgb[2], 10).toString(16)).slice(-2) +
              ("0" + parseInt(rgb[3], 10).toString(16)).slice(-2) : '';
        },
        getViewPort: function () {
            var win = $(window);
            var viewport = {
                top: win.scrollTop(),
                left: win.scrollLeft()
            };
            viewport.right = viewport.left + win.width();
            viewport.bottom = viewport.top + win.height();

            return viewport;
        },
        getHash: function (url) {
            return (url && url.indexOf("#") > -1) ? url.substr(url.indexOf("#")) : null;
        }
    });

    /*****************************************
    Is on screen 
    /*****************************************/
    $.fn.isOnScreen = function () {

        var viewport = getViewPort();

        var bounds = this.offset();
        if(bounds){
            bounds.right = bounds.left + this.outerWidth();
            bounds.bottom = bounds.top + this.outerHeight();
        }
        return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
    };

    /*****************************************
    Request Animation frame
    /*****************************************/
    (function () {

        var lastTime = 0;
        var vendors = ['ms', 'moz', 'webkit', 'o'];
        for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
            window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
            window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame']
                                 || window[vendors[x] + 'CancelRequestAnimationFrame'];
        }

        if (!window.requestAnimationFrame) {
            window.requestAnimationFrame = function (callback) {
                var currTime = new Date().getTime();
                var timeToCall = Math.max(0, 16 - (currTime - lastTime));
                var id = window.setTimeout(function () { callback(currTime + timeToCall); },
          timeToCall);
                lastTime = currTime + timeToCall;
                return id;
            };
        }

        if (!window.cancelAnimationFrame) {
            window.cancelAnimationFrame = function (id) {
                clearTimeout(id);
            };
        }
    })();

})(jQuery);