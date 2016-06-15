    (function ($) {
    "use strict";

    /*****************************************
    Wyde Page
    /*****************************************/
    if( typeof wyde == 'undefined' ) {
        console.log("Wyde core script is required!");
        return;
    }

    $.extend(wyde, {

        page: {

            init: function () {

                this.isHome = $("body").hasClass("home");
                this.onePage = $("body").hasClass("onepage");

                if (typeof wyde_page_settings != "undefined") $.extend(this, wyde_page_settings);

                this.createPreloader();

                this.showLoader();

                this.initMenu();

                this.improveScrolling();

                this.ready = false;

                var self = this;

                $(window).bind("wyde.page.beforechange", function () {
                    self.hideNav();
                    self.hideSlidingBar();
                    self.hideSearch();
                    self.hideFullScreenNav();
                });

                $(window).bind("wyde.page.pageloaded", function () {
                    self.load();
                });

                $(window).bind("wyde.page.ready", function () {
                    self.ready = true;
                });

                $(window).bind("wyde.page.statechange", function (event) {
                    self.stateChange();
                });

                $(window).bind("scroll", function (event) {
                    self.scrolled(event);
                });

                $(window).smartresize(function (event) {
                    self.resize(event);
                });

                this.preloadImages(function () {
                    if (self.ready) {
                        self.contentLoad();
                        $(window).trigger("wyde.page.ready");
                    } else {
                        self.load();
                    }
                });

                $(document).ready(function () {
                    if (self.ready) {
                        self.contentLoad();
                        $(window).trigger("wyde.page.ready");
                    } else {
                        self.load();
                    }
                });

                if (this.ajax_page && typeof this.ajaxPage == "function") {
                    this.ajaxPage();
                }

                if (this.ajax_search && typeof this.ajaxSearch == "function") {
                    this.ajaxSearch();
                }

            },
            createPreloader: function () {
                this.preloader = $("#preloader");
                if (!this.preloader.length) return;
                this.preloader.bind("mousewheel", function () {
                    return false;
                });
            },
            showLoader: function () {
                if (!this.preloader.length) return false;
                this.preloader.show();
                this.preloader.removeClass("loaded");
                return true;
            },
            hideLoader: function () {
                if (!this.preloader.length) return false;

                var self = this;
                setTimeout(function () {
                    self.preloader.addClass("loaded");
                }, 200);

                setTimeout(function () {
                    self.preloader.hide();
                }, 700);

                return true;
            },
            preloadImages: function (callback) {

                if (this.showLoader()){           

                    var self = this;

                    if(this.isPreload){

                        $("body").waitForImages({
                            finished: function () {
                                if (typeof callback == "function") {
                                    callback();
                                }
                                self.hideLoader();
                            },
                            waitForAll: true
                        });

                        return;
                        
                    }            
                }    

                if (typeof callback == "function") {
                    callback();
                }

                this.hideLoader();

            },
            improveScrolling: function () {
                if(!this.smooth_scroll) return;
                //Remove Hover Effect to Improve Scrolling Performance 
                if (!wyde.browser.touch && (!wyde.browser.msie || wyde.browser.msie > 9)) {
                    var body = document.body, timer;
                    window.addEventListener("scroll", function () {
                        if (timer) clearTimeout(timer);
                        if (!body.classList.contains("scrolling")) {
                            body.classList.add("scrolling");
                        }
                        timer = setTimeout(function () {
                            body.classList.remove("scrolling");
                        }, 200);
                    }, false);
                }

            },
            stateChange: function () {
                this.windowScroll = $(window).scrollTop();
                this.isHome = $("body").hasClass("home");
                this.header = $("#header");
                this.headerTop = this.header.length ? this.header.offset().top : 0;
                this.stickyHeight = $("body:not(.no-header) #header.w-sticky").length > 0 ? 65 : 0;
                this.pageTop = $("#content").offset().top;
                this.titleArea = $(".title-wrapper");
                if (this.titleArea.length) {
                    if (this.titleArea.hasClass("w-size-full")) {
                        this.titleArea.css("height", $(window).height());
                    }
                    this.titleBottom = (this.titleArea.offset().top + this.titleArea.outerHeight());
                } else {
                    this.titleBottom = (this.headerTop + this.header.height());
                }
            },
            load: function () {     
                this.initPrimaryNav();           
                this.stateChange();
                this.ready = true;
            },
            contentLoad: function () {   
                this.updateMenu();
                this.showNav();
                if (this.onePage) {
                    this.initOnePageMenu();
                }
                var self = this;
                setTimeout(function () {
                    self.initFooter();
                }, 500);

                var hash = window.location.hash;
                hash = hash.replace(/[^\w#_-]+/g, '');
                if (hash && $(hash).length) {
                    if (hash == $("#nav .menu > li").first().find("a").attr("href")) hash = 0;
                    this.scrollTimer = setTimeout(function () { self.scrollTo(hash); }, 500);
                }

            },
            scrolled: function (event) {

                this.windowScroll = $(window).scrollTop();
                this.headerSticky(this.windowScroll);

                if (this.onePage) {
                    this.scrollSpy();
                }

            },
            resize: function (event) {

                $(window).trigger("wyde.page.statechange");

                this.initPrimaryNav();

                this.updateMenu();

                this.initFooter();

            },
            headerSticky: function (scrolled) {

                if (this.header && this.header.is(":visible") && this.stickyHeight) {
                    if (!this.titleArea.length && scrolled > 0 && (scrolled + this.pageTop) > this.headerTop) {
                        this.header.addClass("w-fixed");
                        $("body").addClass("sticky-nav");
                    } else if ( this.titleArea.length && (scrolled + this.pageTop) > this.titleBottom) {
                        this.header.addClass("w-fixed");
                        $("body").addClass("sticky-nav");
                    //} else if (scrolled > 0 && (scrolled + this.pageTop) > this.headerTop) {
                        //this.header.addClass("w-scrolled");
                        //$("body").addClass("sticky-nav");
                    } else {
                        this.header.removeClass("w-fixed");
                        $("body").removeClass("sticky-nav");
                    }
                }
            },
            scrollSpy: function () {

                if (this.sections && this.sections.length) {

                    var fromTop = this.windowScroll + this.stickyHeight + 90;

                    var currentSections = this.sections.map(function () {
                        if ($(this).offset().top < fromTop)
                            return this;
                    });

                    currentSections = currentSections[currentSections.length - 1];
                    var id = currentSections && currentSections.length ? currentSections[0].id : "";                    

                    if (this.currentSectionId !== id) {
                        this.currentSectionId = id;
                        
                        //this.menuItems.parent().removeClass("current-menu-item").end().filter("a[href='#" + id + "']").parent().addClass("current-menu-item");
                        $(".top-menu .menu-item > a, .vertical-menu .menu-item > a").each(function(){
                            $(this).parent().removeClass("current-menu-item").end().filter("a[href='#" + id + "']").parent().addClass("current-menu-item");
                        });                        
                    }
                }
            },
            initMenu: function () {   

                this.initPrimaryNav();           

                $("#page-overlay").bind("mousewheel", function () {
                    return false;
                });

                var isTransparent = false;
                var textColor = false;
                var self = this;
                $(".mobile-nav-icon").on("click", function () {
                    
                    if($("body").hasClass("side-nav-active")){
                        $("body").removeClass("side-nav-active");
                        if(isTransparent){
                            self.header.addClass("w-transparent");
                            if(textColor) self.header.addClass(textColor);
                        } 
                    }else{
                        $("body").addClass("side-nav-active");
                        isTransparent = self.header.not(".w-fixed").hasClass("w-transparent");
                        textColor = false;
                        if(isTransparent){
                            self.header.removeClass("w-transparent");
                            if(self.header.hasClass("w-light") && self.header.hasClass("w-text-light")){
                                textColor = "w-text-light";
                                self.header.removeClass(textColor);
                            }else if(self.header.hasClass("w-dark") && self.header.hasClass("w-text-dark")){
                                textColor = "w-text-dark";
                                self.header.removeClass(textColor);
                            }
                        } 
                    }
                    
                });
               
                this.initDropdownNav();
                this.initSideNav();                         
                this.initSlidingBar();
                this.initFullScreenNav();
                this.initSearch();
                this.updateScrollTarget();
            },
            initPrimaryNav:function(){
                if ( $(window).width() < 1080 ) {
                    $("body").removeClass("side-nav-active").addClass("mobile-nav");
                } else {
                    $("body").removeClass("mobile-nav");
                }
            },
            initDropdownNav:function(){              

                if( !$("body").hasClass("mobile-nav") ){

                    setTimeout(function () {
                        
                        var winWidth = $(window).width();

                        $(".dropdown-nav li.menu-item-has-children:not(.megamenu)").each(function () {

                            var rPos = $(this).offset().left + $(this).outerWidth() + $(this).find("> .sub-menu").outerWidth();
                            
                            if (rPos > winWidth){
                                $(this).addClass("align-right");
                            } else {
                                $(this).removeClass("align-right");
                            } 

                        });                        

                    }, 500);

                    this.initMegaMenu();    
                }               
                
            },
            initMegaMenu: function () {

                setTimeout(function () {
                
                    $(".dropdown-nav .megamenu > ul").each(function () {

                        var $el = $(this);

                        $el.css("left", "");
                        $el.css("width", $(".dropdown-nav").width() );

                        if ( $el.position().left > 0 ) {
                            $el.css("left", - ( $el.position().left ) );
                        }
                        
                    }); 

                }, 1000);         

            },
            initSideNav: function () {                
                var $sidenav = $("#side-nav .side-nav-wrapper");
                $sidenav.wydeScroller({ scrollbar: false });
                $("#vertical-nav").wydeVerticalMenu({
                    saveState: !this.ajax_page,
                    changed: function () {                            
                        // Refresh scroller
                        $sidenav.wydeScroller("refresh");
                    }
                });                                 
                   
            },
            initFullScreenNav: function () {

                var $el = $("#fullscreen-nav");
                var $scroller = $el.find(".full-nav-wrapper")

                if (!$el.length) return;

                var self = this;

                $el.bind("mousewheel", function () {
                    return false;
                });

                $(".full-nav-icon").on("click", function () {
                    self.showFullScreenNav();
                });

                $scroller.css("max-height", $el.height() - $el.find(".social-icons").outerHeight(true));
                $scroller.wydeScroller({ scrollbar: false });
                $("#full-nav").wydeVerticalMenu({
                    changed: function (o) {
                        setTimeout(function () {
                            $scroller.height($("#full-nav").height());
                        }, 300);
                        setTimeout(function () {
                            $scroller.wydeScroller("refresh");
                        }, 600);
                    }
                });

                $(window).smartresize(function () {
                    $el.find(".full-nav-wrapper").height($("#full-nav").height());
                    $el.find(".full-nav-wrapper").css("max-height", $el.height() - $el.find(".social-icons").outerHeight(true));
                });

            },
            initSlidingBar: function () {
                if( $.fn.wydeScroller ) $("#slidingbar .slidingbar-wrapper").wydeScroller({ scrollbar: false });
                var self = this;
                $(".menu-item-slidingbar > a").on("click", function (event) {
                    event.preventDefault();
                    if ($("body").hasClass("sliding-active")) {
                        self.hideSlidingBar();
                    } else {
                        self.showSlidingBar();
                    }
                    return false;
                });

                $(".sliding-remove-button").on("click", function (event) {
                    event.preventDefault();
                    self.hideSlidingBar();
                    return false;
                });

            },
            updateMenu:function(){                      
                this.initDropdownNav();                                
            },
            updateMenuLinks:function(){
                this.updateScrollTarget();
                $("#vertical-nav").wydeVerticalMenu("refresh");
                $("#full-nav").wydeVerticalMenu("refresh");
            },
            showSlidingBar: function () {
                $("body").addClass("sliding-active");
                $("#slidingbar .slidingbar-wrapper").wydeScroller("refresh");

                var self = this;
                $(".sliding-active #page-overlay").off("click").on("click", function () {
                    self.hideSlidingBar();
                });
            },
            hideSlidingBar: function () {
                $("body").removeClass("sliding-active");
            },
            initOnePageMenu: function () {

                var self = this;
                if (self.isHome) {

                    $("#header-logo a, #side-nav-logo a").off("click").on("click", function (event) {
                        event.preventDefault();

                        self.scrollTo(0);

                        if (window.location.hash) {
                            if (typeof window.history.pushState == "function") {
                                history.pushState({ path: self.siteURL }, "", self.siteURL);
                            } else document.location.href = self.siteURL;
                        }

                        return false;

                    });

                    this.currentSectionId = false;
                    this.menuItems = $(".menu-item a[href^='#']");
                    this.sections = this.menuItems.map(function () {
                        var $item = $($(this).attr("href"));
                        if ($item.length) {
                            return $item;
                        }
                    });

                }
                if (this.windowScroll == 0) $("#nav li").first().addClass("current-menu-item");        

            },
            updateScrollTarget: function () {
                var self = this;

                $(".top-menu .menu-item > a[href*='#'], .vertical-menu .menu-item > a[href*='#'], .footer-menu a[href*='#']").each(function(){

                    if (this.pathname == window.location.pathname){
                        
                        var $el = $(this);                        

                        $el.on("click", function (event) {                            

                            var hash = getHash($el.attr("href"));
                            if (!hash) {
                                return true;
                            } else if (hash == '#') {

                                event.preventDefault();
                                return false;

                            } else {

                                event.preventDefault();

                                var duration = 0;

                                if($(".mobile-nav.side-nav-active").length){    
                                    duration = 600;
                                    $(".mobile-nav").removeClass("side-nav-active");                            
                                }else if($(".fullscreen-nav.full-nav-active").length){    
                                    duration = 600;
                                    self.hideFullScreenNav();                          
                                }

                                if ($el.parent().hasClass("menu-item") && $el.parents("ul").find("li").index($el.parent()) == 0) {
                                    setTimeout(function(){
                                        self.scrollTo(0);
                                    }, duration);                            
                                } else {
                                    if (self.scrollTimer) clearTimeout(self.scrollTimer);
                                    setTimeout(function(){
                                        self.scrollTo(hash);
                                    }, duration);     
                                }
                               
                                History.pushState(null, $el.attr("title") ? $el.attr("title") : "", hash);                                   

                                return false;
                            }
                        });
                    }

                });
            },
            updateVerticalNav: function () {
                if( !$.fn.wydeVerticalMenu ) return;
                $("#vertical-nav").wydeVerticalMenu("refresh");
                $("#full-nav").wydeVerticalMenu("refresh");
            },
            initFooter: function () {
                this.initToTopButton();
            },
            initToTopButton: function () {
                var self = this;
                $("#toplink-button, #toplink-wrapper a").off("click").on("click", function (event) {
                    event.preventDefault();
                    self.scrollTo(0);
                    return false;
                });

                $("#content").waypoint(function (direction) {
                    if (direction == "down") {
                        $("#toplink-button").show();
                        setTimeout(function () {
                            $("#toplink-button").addClass("active");
                        }, 100);
                    } else {
                        $("#toplink-button").removeClass("active");
                        setTimeout(function () {
                            $("#toplink-button").hide();
                        }, 500);
                    }
                }, {
                    offset: -400
                });

                $("#content").waypoint(function (direction) {
                    if (direction == "down") {
                        $("#toplink-button").removeClass("active");
                        setTimeout(function () {
                            $("#toplink-button").hide();
                        }, 500);
                    } else {
                        $("#toplink-button").show();
                        setTimeout(function () {
                            $("#toplink-button").addClass("active");
                        }, 100);
                    }
                }, {
                    offset: "bottom-in-view"
                });

            },
            initSearch: function () {

                var $el = $("#live-search");

                var self = this;

                $el.bind("mousewheel", function () {
                    return false;
                });

                $(".live-search-button").on("click", function (event) {
                    event.preventDefault();
                    self.showSearch();
                    return false;
                });

                $el.find(".fullscreen-remove-button").on("click", function (event) {
                    event.preventDefault();
                    self.hideSearch();
                    return false;
                });

                $el.find("input[name='s']").keypress(function (event) {
                    if (event.which == 13) {
                        event.preventDefault();
                        $("form", $el).submit();
                    }
                });

            },
            showFullScreenNav: function () {
                var $el = $("#fullscreen-nav");
                if (!$el.length) return;
                $("body").bind("mousedown.prev DOMMouseScroll.prev mousewheel.prev", function (event) {
                    event.preventDefault();
                });
                $el.css("visibility", "visible");
                var self = this;
                $(".full-nav-icon").off("click").on("click", function () {
                    self.hideFullScreenNav();
                });
                setTimeout(function () {
                    $("body").addClass("full-nav-active");
                }, 100);
            },
            hideFullScreenNav: function () {
                var $el = $("#fullscreen-nav");
                if (!$el.length) return;
                if (!$("body").hasClass("full-nav-active")) return;
                $("body").unbind("mousedown.prev DOMMouseScroll.prev mousewheel.prev");
                $("body").removeClass("full-nav-active");
                var self = this;
                $(".full-nav-icon").off("click").on("click", function () {
                    self.showFullScreenNav();
                });
                setTimeout(function () {
                    $el.css("visibility", "");
                }, 500);
            },
            showSearch: function () {
                var $el = $("#live-search");
                $("body").bind("mousedown.prev DOMMouseScroll.prev mousewheel.prev", function (event) {
                    event.preventDefault();
                });
                this.hideNav();
                $el.show();
                setTimeout(function () {
                    $el.addClass("active");
                    if ($(".search-list li", $el).length > 0) {
                        $el.find(".autocomplete").addClass("open").show();
                    }
                    $el.find("input[name='s']").focus();
                }, 100);
            },
            hideSearch: function () {
                var $el = $("#live-search");
                if (!$el.hasClass("active")) return;
                $("body").unbind("mousedown.prev DOMMouseScroll.prev mousewheel.prev");
                $el.find(".autocomplete").removeClass("open").hide();
                this.showNav();
                $el.removeClass("active");
                setTimeout(function () {
                    $el.hide();
                }, 500);
            },
            showNav: function () {
                $("#header").removeClass("inactive");
            },
            hideNav: function () {
                $("#header").addClass("inactive");
                $("body.mobile-nav").removeClass("side-nav-active");
                $("body").removeClass("sliding-active");
            },
            scrollTo: function (target, options) {
                if (typeof options == "function") options = { onAfter: options };
                var headerHeight = this.stickyHeight;
                if (!$("#header:visible").length) headerHeight = 0;
                var settings = $.extend({}, { duration: 1000, easing: "easeInOutExpo", offset: -(this.pageTop + headerHeight) }, options);
                $(window).scrollTo(target, settings);
            }

        }

    });

    /*****************************************
    Wyde AJAX Page
    /*****************************************/
    $.extend(wyde.page, {
        ajaxPage: function () {

            // Prepare our Variables
            var History = window.History;

            // Check to see if History.js is enabled for our Browser
            if (!History.enabled) {
                return false;
            }

            this.rootUrl = History.getRootUrl();
            this.targetPos = 0;
            this.settings = {
                search: ".ajax-search-form",
                scope: "#content",
                excludeURLs: [],
                excludeSelectors: [],
                transition: "fade"
            };

            if (typeof this.ajax_page_settings != "undefined") this.settings = $.extend(this.settings, this.ajax_page_settings);

            this.searchPath = "";
            this.ignoreURLs = [];

            this.ignoreURLs.push("wp-login");
            this.ignoreURLs.push("wp-admin");
            this.ignoreURLs.push("wp-content");

            var self = this;

            $(this.settings.excludeURLs).each(function (i, v) {
                if (v) self.ignoreURLs.push(v);
            });

            $(this.settings.excludeSelectors).each(function () {
                if (this.tagName.toLowerCase() == "a") {
                    self.ignoreURLs.push(this.href);
                } else {
                    $(this).find("a").each(function () {
                        self.ignoreURLs.push(this.href);
                    });
                }

            });

            // Internal Helper
            $.expr[':'].internal = function (obj, index, meta, stack) {
                var $el = $(obj),
                url = $el.attr("href") || "",
                isInternal;

                isInternal = url.substring(0, self.rootUrl.length) === self.rootUrl || url.indexOf(':') === -1;

                if ($el.attr("target") && $el.attr("target") != "_self") isInternal = false;

                return isInternal;
            };

            this.updateLink();

            if (self.settings.search) {

                $(self.settings.search).each(function (index) {

                    if ($(this).attr("action")) {
                        //Get the current action so we know where to submit to
                        self.searchPath = $(this).attr("action");

                        //bind our code to search submit, now we can load everything through ajax :)
                        //$("#searchform").name = "searchform";
                        $(this).submit(function () {
                            self.submitSearch($(this).serialize());
                            return false;
                        });
                    }
                });
            }

            $(window).bind("statechange", function () {
                var state = History.getState(),
                url = state.url;
                self.loadContent(url);
            });

        },
        getInternalLinks: function(container){
            var self = this;
            return $(container ? container : document.body).find("a:not(.no-ajax)").filter(function(){

                var $el = $(this),
                url = $el.attr("href") || "",
                isInternal;

                isInternal = url.substring(0, self.rootUrl.length) === self.rootUrl || url.indexOf(':') === -1;

                if ($el.attr("target") && $el.attr("target") != "_self") isInternal = false;

                return isInternal;

            });
            
        },
        updateLink: function (newElements) {

            var self = this;

            if(!self.ajax_page) return;

            if (self.isIgnore(document.URL) || $("body").hasClass("woocommerce") || $("body").hasClass("woocommerce-page")) return;

            this.getInternalLinks(newElements).each(function () {

                if (!self.isIgnore(this)) {
                    
                    $(this).on("click", function (event) {
                        event.preventDefault();

                        var $el = $(this);
                        var url = $el.attr("href");
                        var title = $el.attr("title") || null;

                        if (event.which == 2 || event.metaKey) {
                            return true;
                        }
                        // set scroll target position
                        self.targetPos = 0;
                        var hash = getHash(url);
                        if (hash) {
                            url = url.replace(hash, "");
                            self.targetPos = hash;                            
                        }

                        History.pushState(null, title, url);

                        return false;
                    });

                }
            });

        },
        isIgnore: function (link) {

            if(!link) return true;    
            if( typeof link == "object" && ( $(link).parent().hasClass("no-ajax") || $(link).parents(".woocommerce").length ) ){
                return true;
            }        

            var url = link.href ? link.href : link.toString();

            if(!url) return true;

            if(url.startWith("#")) return true;

            if(link.pathname == window.location.pathname && url.indexOf("#") > -1) return true;

            for(var i in this.ignoreURLs) {
                if (url.indexOf(this.ignoreURLs[i]) > -1) {
                    return true;
                }
            }

            return false;
        },
        removeSlider: function () {
            // Revolution Slider
            if($(".rev_slider").length && typeof $.fn.revolution == "function") {
                try {
                    $(".rev_slider").revkill();
                } catch (e) {
                    //console.log(e);
                }
            }
        },
        refreshSlider: function () {
            // Revolution Slider
            if ($(".rev_slider").length && typeof $.fn.revolution == "function") {
                /*$(".rev_slider").on("revolution.slide.onloaded", function () {
                    $(window).trigger("wyde.page.statechange");
                });*/
                $(window).trigger("resize");
            }
        },
        loadContent: function (url, getData) {

            this.absoluteURL = url;

            //this.relativeURL = this.absoluteURL.replace(/^.*\/\/[^\/]+/, '');

            this.relativeURL = this.absoluteURL.replace(this.rootUrl, '');

            var self = this;

            this.hideContent(function () {

                self.removeSlider();

                $.ajax({
                    type: "GET",
                    url: url,
                    data: getData,
                    cache: false,
                    dataType: "html",
                    success: function (response) {

                        self.updateContent(response);

                        $(window).trigger("wyde.page.pageloaded");

                        self.updateLink();                        

                        self.showContent(function () {

                            self.refreshSlider();

                            $(window).trigger("wyde.page.statechange");

                            if (typeof self.contentLoad == "function") {
                                self.contentLoad();
                            }

                            $(window).trigger("wyde.page.ready");

                            if (self.targetPos) {
                                setTimeout(function () {
                                    History.pushState(null, "", self.targetPos); 
                                    self.scrollTo(self.targetPos);
                                }, 800);
                            }

                        });

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        window.location.href = url;
                    },
                    statusCode: {
                        404: function () {
                            console.log("Page not found!");
                        }
                    }
                });

            });


        },
        hideContent: function (callback) {

            $(window).trigger("wyde.page.beforechange");

            var self = this;

            var $el = $(self.settings.scope);

            var windowWidth = $(window).width() + 100;
            var windowHeight = $(window).height() + 100;

            var duration = 1000;

            switch (self.settings.transition) {
                case "fade":
                    duration = 800;
                    if (wyde.browser.css3) {
                        $el.css(wyde.browser.prefix + "transition", wyde.browser.prefix + "opacity 0.8s").css({ opacity: 0 });
                    } else {
                        $el.animate({ opacity: 0 }, duration);
                    }
                    break;
                case "slideToggle":
                    $("body").css({ overflow: "hidden" });
                    if (wyde.browser.css3) {
                        $el.css(wyde.browser.prefix + "transition", wyde.browser.prefix + "transform 1s cubic-bezier(0.785, 0.135, 0.150, 0.860)").css(wyde.browser.prefix + "transform", "translateX(" + (-windowWidth) + "px)");
                    } else {
                        $el.animate({ left: -(windowWidth) }, duration, "easeInOutCirc");
                    }
                    break;
                case "slideLeft":
                    $("body").css({ overflow: "hidden" });
                    if (wyde.browser.css3) {
                        $el.css(wyde.browser.prefix + "transition", wyde.browser.prefix + "transform 1s cubic-bezier(0.785, 0.135, 0.150, 0.860)").css(wyde.browser.prefix + "transform", "translateX(" + (-windowWidth) + "px)");
                        setTimeout(function () {
                            $el.css(wyde.browser.prefix + "transition", "").css(wyde.browser.prefix + "transform", "translateX(" + windowWidth + "px)");
                        }, duration);
                    } else {
                        $el.animate({ left: -(windowWidth) }, duration, "easeInOutCirc");
                    }
                    break;
                case "slideRight":
                    $("body").css({ overflow: "hidden" });
                    if (wyde.browser.css3) {
                        $el.css(wyde.browser.prefix + "transition", wyde.browser.prefix + "transform 1s cubic-bezier(0.785, 0.135, 0.150, 0.860)").css(wyde.browser.prefix + "transform", "translateX(" + windowWidth + "px)");
                        setTimeout(function () {
                            $el.css(wyde.browser.prefix + "transition", "").css(wyde.browser.prefix + "transform", "translateX(" + (-windowWidth) + "px)");
                        }, duration);
                    } else {
                        $el.animate({ left: windowWidth }, duration, "easeInOutCirc");
                    }
                    break;
                case "slideUp":
                    $("body").css({ overflow: "hidden" });
                    if (wyde.browser.css3) {
                        $el.css(wyde.browser.prefix + "transition", wyde.browser.prefix + "transform 1s cubic-bezier(0.785, 0.135, 0.150, 0.860), opacity 1s").css(wyde.browser.prefix + "transform", "translateY(" + (-windowHeight) + "px)").css("opacity", 0);
                        setTimeout(function () {
                            $el.css(wyde.browser.prefix + "transition", "").css(wyde.browser.prefix + "transform", "translateY(" + windowHeight + "px)");
                        }, duration);
                    } else {
                        $el.animate({ top: -(windowHeight), opacity: 0 }, duration, "easeInOutCirc");
                    }
                    break;
                case "slideDown":
                    $("body").css({ overflow: "hidden" });
                    if (wyde.browser.css3) {
                        $el.css(wyde.browser.prefix + "transition", wyde.browser.prefix + "transform 1s cubic-bezier(0.785, 0.135, 0.150, 0.860), opacity 1s").css(wyde.browser.prefix + "transform", "translateY(" + windowHeight + "px)").css("opacity", 0);
                        setTimeout(function () {
                            $el.css(wyde.browser.prefix + "transition", "").css(wyde.browser.prefix + "transform", "translateY(" + (-windowHeight) + "px)");
                        }, duration);
                    } else {
                        $el.animate({ top: windowHeight, opacity: 0 }, duration, "easeInOutCirc");
                    }
                    break;
            }

            setTimeout(function () {

                self.showLoader();

                self.scrollTo(0, {
                    duration: 100
                });

                if (typeof callback == "function") {
                    callback();
                }

            }, duration);


        },
        showContent: function (callback) {

            var self = this;
            var duration = 1000;

            var $el = $(self.settings.scope);

            var windowWidth = $(window).width() + 100;
            var windowHeight = $(window).height() + 100;

            this.preloadImages(function () {

                switch (self.settings.transition) {
                    case "fade":
                        duration = 800;
                        if (wyde.browser.css3) {
                            $el.css({ opacity: 1 });
                            setTimeout(function () {
                                $el.css(wyde.browser.prefix + "transition", "").css("opacity", "");
                            }, duration + 500);
                        } else {
                            $el.animate({ opacity: 1 }, duration);
                        }
                        break;
                    case "slideToggle":
                        if (wyde.browser.css3) {
                            $el.css(wyde.browser.prefix + "transform", "translateX(0)");
                            setTimeout(function () {
                                $el.css(wyde.browser.prefix + "transition", "").css(wyde.browser.prefix + "transform", "");
                                $("body").css({ overflow: "" });
                            }, duration + 500);
                        } else {
                            $el.animate({ left: 0 }, duration, "easeInOutCirc", function () {
                                $("body").css({ overflow: "" });
                            });
                        }
                        break;
                    case "slideLeft":
                        if (wyde.browser.css3) {
                            $el.css(wyde.browser.prefix + "transition", wyde.browser.prefix + "transform 1s cubic-bezier(0.785, 0.135, 0.150, 0.860)").css(wyde.browser.prefix + "transform", "translateX(0)");
                            setTimeout(function () {
                                $el.css(wyde.browser.prefix + "transition", "").css(wyde.browser.prefix + "transform", "");
                                $("body").css({ overflow: "" });
                            }, duration + 500);
                        } else {
                            $el.css({ left: windowWidth }).animate({ left: 0 }, duration, "easeInOutCirc", function () {
                                $("body").css({ overflow: "" });
                            });
                        }
                        break;
                    case "slideRight":
                        if (wyde.browser.css3) {
                            $el.css(wyde.browser.prefix + "transition", wyde.browser.prefix + "transform 1s cubic-bezier(0.785, 0.135, 0.150, 0.860)").css(wyde.browser.prefix + "transform", "translateX(0)");
                            setTimeout(function () {
                                $el.css(wyde.browser.prefix + "transition", "").css(wyde.browser.prefix + "transform", "");
                                $("body").css({ overflow: "" });
                            }, duration + 500);
                        } else {
                            $el.css({ left: -(windowWidth) }).animate({ left: 0 }, duration, "easeInOutCirc", function () {
                                $("body").css({ overflow: "" });
                            });
                        }
                        break;
                    case "slideUp":
                        if (wyde.browser.css3) {
                            $el.css(wyde.browser.prefix + "transition", wyde.browser.prefix + "transform 1s cubic-bezier(0.785, 0.135, 0.150, 0.860), opacity 0.5s ease-in 0.1s").css(wyde.browser.prefix + "transform", "translateY(0)").css("opacity", 1);
                            setTimeout(function () {
                                $el.css(wyde.browser.prefix + "transition", "").css(wyde.browser.prefix + "transform", "").css("opacity", "");
                                $("body").css({ overflow: "" });
                            }, duration + 500);
                        } else {
                            $el.css({ top: windowHeight }).animate({ top: 0, opacity: 1 }, duration, "easeInOutCirc", function () {
                                $("body").css({ overflow: "" });
                            });
                        }
                        break;
                    case "slideDown":
                        if (wyde.browser.css3) {
                            $el.css(wyde.browser.prefix + "transition", wyde.browser.prefix + "transform 1s cubic-bezier(0.785, 0.135, 0.150, 0.860), opacity 0.5s ease-in 0.1s").css(wyde.browser.prefix + "transform", "translateY(0)").css("opacity", 1);
                            setTimeout(function () {
                                $el.css(wyde.browser.prefix + "transition", "").css(wyde.browser.prefix + "transform", "").css("opacity", "");
                                $("body").css({ overflow: "" });
                            }, duration + 500);
                        } else {
                            $el.css({ top: -(windowHeight) }).animate({ top: 0, opacity: 1 }, duration, "easeInOutCirc", function () {
                                $("body").css({ overflow: "" });
                            });
                        }
                        break;
                }

                setTimeout(function () {

                    if (typeof callback == "function") {
                        callback();
                    }

                }, duration);


            });

        },
        getDocumentHtml: function (html) {

            var result = String(html)
                .replace(/<\!DOCTYPE[^>]*>/i, '')
                .replace(/<(html|head|body|title|meta)([\s\>])/gi, '<div class="document-$1"$2')
                .replace(/<\/(html|head|body|title|meta)\>/gi, '</div>');

            return $.trim(result);
        },
        updateContent: function (data) {

            window.$ = jQuery;

            var $doc = null;
            var $body = null;
            if (window.DOMParser) { // all browsers, except IE before version 9
                try {
                    var parser = new DOMParser();
                    $doc = $(parser.parseFromString(data, "text/html"));
                    $body = $doc.find("body");
                    parser = $doc = null;
                } catch (e) {
                    $doc = $(this.getDocumentHtml(data));
                    $body = $doc.find(".document-body:first");
                }
            } else {
                $doc = $(this.getDocumentHtml(data));
                $body = $doc.find(".document-body:first");
            }

            // Update content
            $(this.settings.scope).html($body.find(this.settings.scope).html());

            // Update Stylesheet
            var self = this;
            var $cssLinks = $body.find("link[rel='stylesheet']");
            $cssLinks.each(function () {
                if ($("body link[id='" + $(this).attr("id") + "']").length == 0) {
                    $(self.settings.scope).append(this);
                }
            });

            // Update VC Custom CSS
            $("head").find("style[data-type='vc_shortcodes-custom-css'], style[data-type='vc_custom-css']").remove();
            var $vc_custom_styles = $(data).filter("style[data-type='vc_shortcodes-custom-css'], style[data-type='vc_custom-css']");
            if ($vc_custom_styles.length) $("head").append($vc_custom_styles);

            // Update the title
            $("head").find("title").replaceWith($(data).filter("title"));

            // Update Body Classes
            var oldClasses = $("body").attr("class");

            var newClasses = $body.attr("class");
            if (newClasses) $("body").removeClass(oldClasses).addClass(newClasses);            

            // Update WP Admin Bar
            if ($("#wpadminbar").length > 0) {
                var $adminBar = $body.find("#wpadminbar");
                if ($adminBar.length) $("#wpadminbar").html($adminBar.html());
            }

            // Update Navigation
            this.updateNavigation($body);

            // Update Footer
            this.updateFooter($body);

            // Inform Google Analytics of the change
            this.googleTracking();

            // Inform ReInvigorate of a state change
            if (typeof window.reinvigorate !== 'undefined' && typeof window.reinvigorate.ajax_track !== 'undefined') {
                reinvigorate.ajax_track(this.absoluteURL);
            }

        },
        updateNavigation: function ($body) {
                
            // Update header class
            var headerClasses = $body.find("#header").attr("class");
            $("#header").attr("class", headerClasses).addClass("inactive");

            if (this.onePage) {
                //Update menu items
                $(".menu-item").each(function(){
                    var newItem = $body.find("#" + $(this).attr("id"));                    
                    if(newItem.length){
                        $(this).attr("class", newItem.attr("class"));
                        $(this).find("> a").replaceWith(newItem.find(">a"));
                    }                    
                });
                this.updateMenuLinks();
                this.updateLink($(".menu-item"));
            } else {
                 // Clear menu state
                $(".top-menu li, .vertical-menu li").removeClass("current-menu-ancestor current-menu-parent current-menu-item current_page_parent current_page_ancestor current_page_item");
                // Update current dropdown menu
                $body.find(".top-menu .current-menu-ancestor, .top-menu .current-menu-parent, .top-menu .current-menu-item").each(function () {
                    $(".top-menu ." + $(this).attr("id")).removeClass().addClass($(this).attr("class"));
                });
                // Update current vertical menu
                $body.find("#vertical-nav .current-menu-ancestor, #vertical-nav .current-menu-parent, #vertical-nav .current-menu-item").each(function () {
                    $(".vertical-menu ." + $(this).attr("id").replace("vertical-", "")).removeClass().addClass($(this).attr("class"));
                });
            }
        },
        updateFooter: function ($body) {
            // Update header class
            var $newFooter = $body.find("#footer");
            if($newFooter.length){
                $("#footer").html($newFooter.html());
            }
            
        },
        googleTracking: function () {
            var self = this;
            // Check new version of Google Analytics
            if (typeof ga == "function") {
                ga(function () {
                    var trackers = ga.getAll();
                    $.each(trackers, function (i, v) {
                        v.send("pageview", {
                            "page": self.relativeURL,
                            "title": document.title
                        });
                        //v.send("pageview");
                    });
                });
            } else if (typeof window._gaq !== 'undefined') { // Old version of Google Analytics
                window._gaq.push(['_trackPageview', self.relativeURL]);
            }

        },
        submitSearch: function (param) {
            this.loadContent(this.searchPath, param);
        }
    });

    /*****************************************
    Wyde Ajax Search
    /*****************************************/
    $.extend(wyde.page, {

        ajaxSearch: function (options) {

            var settings = {
                delay: 500,
                element: "#live-search",
                minlength: 1
            };

            settings = $.extend(true, settings, options);

            if (!this.ajaxURL) return;

            var self = this;

            return $(settings.element).each(function () {

                var ajaxTimer = null;
                var $el = $(this);

                var $wrapper, $autocomplete, $searchlist, $more = false;

                function createAutoComplete() {
                    $wrapper = $el.find(".container");
                    $wrapper.append($("<div class=\"autocomplete\"><ul class=\"search-list\"></ul></div>"));

                    $autocomplete = $wrapper.find(".autocomplete");

                    $searchlist = $autocomplete.find(".search-list");

                    $more = $("<div class=\"search-more\"></div>");
                    $wrapper.append($more);

                    $autocomplete.wydeScroller();

                    $(window).smartresize(function () {
                        updateSearchList();
                    });

                }

                function updateSearchList() {

                    var h = ($(window).height() - $more.outerHeight()) - ($autocomplete.offset().top - $(window).scrollTop());
                    $autocomplete.css("height", h);

                    $autocomplete.wydeScroller("refresh");
                }

                function getListItems(name, items) {
                    var list = $("<ul></ul>").attr("id", String.format("{0}-list", name.toLowerCase()));
                    $.each(items, function () {
                        var image = "";
                        if (this.post_image) image = String.format("<span class=\"thumb\"><img src=\"{1}\" alt=\"{2}\"></span>", this.post_link, this.post_image, this.post_title);
                        var author = "";
                        if (this.post_author) author = String.format("<span>{0}</span> ", this.post_author);
                        list.append(String.format("<li><a href=\"{1}\">{0}<span><strong>{2}</strong><span class=\"post-meta\">{3}<span>{4}</span></span></span></a></li>", image, this.post_link, this.post_title, author, this.post_date));
                    });
                    return list;
                }

                function clearSearchList() {
                    $searchlist.html("");
                    $more.html("");
                }

                function loadResults(ajaxURL) {

                    if (!$autocomplete) {
                        createAutoComplete();
                    }

                    var data = { 
                        action: "wyde_search", 
                        wyde_search_post_types: $el.find("input[name='wyde_search_post_types']").val(),
                        wyde_search_suggestions: $el.find("input[name='wyde_search_suggestions']").val(),
                        wyde_search_keyword: $el.find("input[name='s']").val() 
                    };

                    if (data.wyde_search_keyword.length == 0) {
                        $autocomplete.hide().removeClass("open");
                        clearSearchList();
                        return;
                    }

                    $autocomplete.show().addClass("open");
                    $autocomplete.css("height", "20px");
                    $more.removeClass("selected").html("<p class=\"search-loading\"><span class=\"w-loader\"></span></p>");

                    $.post(ajaxURL, data, function (response) {

                        $searchlist.html("");

                        var results = $.parseJSON(response);

                        if (results && results.length > 0) {
                            results.sort(function (a, b) {
                                var x = a['title'];
                                var y = b['title'];
                                return ((x < y) ? -1 : ((x > y) ? 1 : 0));
                            });
                            $.each(results, function () {
                                var $list = $(String.format("<li><h4>{0}</h4></li>", this.title));
                                $list.append(getListItems(this.name, this.items));
                                $searchlist.append($list);
                            });


                        }

                        if (results.length == 0) $more.addClass("selected");
                        $more.html(String.format("<a href=\"{0}/?s={1}\">See more results for \"{1}\"</a>", self.siteURL, data.wyde_search_keyword));

                        if (self.ajax_page) self.updateLink($autocomplete.not("#product-list").find("li"));

                        if ($el.hasClass("active")) {
                            $autocomplete.show().addClass("open");
                            $searchlist.focus();
                        }

                        updateSearchList();

                    });
                }

                createAutoComplete();

                $el.find("input[name='s']").attr("autocomplete", "off");
                $el.find("input[name='s']").on("input", function (event) {                   
                    if ($(this).val().length < settings.minlength) {
                        clearSearchList();
                        return;
                    }
                    if (ajaxTimer != null) {
                        clearTimeout(ajaxTimer);
                    }
                    ajaxTimer = setTimeout(function () { loadResults(self.ajaxURL); }, settings.delay);
                    
                });

            });

        }
    });

    wyde.page.init();


    /*****************************************
    Title Area Scrolling Effects
    /*****************************************/
    function titleScrollEffects(){

        if (!wyde.browser.touch && !(wyde.browser.xs || wyde.browser.sm) ) {
            
            var $el = $(".title-wrapper");

            var isBusy = false;
            var lastScrollPos, titleHeight, headerHeight;

            var refresh = function () {
                titleHeight = wyde.page.titleArea ? wyde.page.titleArea.outerHeight(true) : 0;
                headerHeight = wyde.page.header.height();
            }

            var render = function () {

                lastScrollPos = $(window).scrollTop();

                if (lastScrollPos < headerHeight + titleHeight) {

                    var yPos = Math.round(lastScrollPos * 0.3);

                    var transOut = 1 - (lastScrollPos / titleHeight);
                    var transIn = 1 + (lastScrollPos / titleHeight);

                    $(".title-wrapper.w-parallax .bg-image").css(wyde.browser.prefix + "transform", "translate3d(0px, " + yPos + "px, 0px)");

                    $(".title-wrapper.w-fadeOut .bg-image").css("opacity", transOut);
                  

                    if ($el.data("effect")) {
                        switch ($(".title-wrapper").data("effect")) {                            
                            case "split":
                                $(".title-wrapper .container .title").css(wyde.browser.prefix + "transform", "translate3d(0px, " + (yPos * 0.1) + "px, 0px)").css("opacity", transOut);
                                $(".title-wrapper .container .subtitle").css(wyde.browser.prefix + "transform", "translate3d(0px, " + (yPos * 1.2) + "px, 0px)").css("opacity", transOut);
                                break;
                            case "fadeOut":
                                $(".title-wrapper .container").css(wyde.browser.prefix + "transform", "translate3d(0px, " + (yPos * 1.1) + "px, 0px)").css("opacity", transOut);
                                break;
                            case "fadeOutUp":
                                $(".title-wrapper .container").css(wyde.browser.prefix + "transform", "translate3d(0px, " + (yPos * 0.1) + "px, 0px)").css("opacity", transOut);
                                break;
                            case "fadeOutDown":
                                $(".title-wrapper .container").css(wyde.browser.prefix + "transform", "translate3d(0px, " + (yPos * 1.8) + "px, 0px)").css("opacity", transOut);
                                break;
                            case "zoomOut":
                                $(".title-wrapper .container").css(wyde.browser.prefix + "transform", "translate3d(0px, " + (yPos * 1.1) + "px, 0px) scale3d(" + transOut + ", " + transOut + ", 1)").css("opacity", transOut);
                                break;
                            case "zoomOutUp":
                                $(".title-wrapper .container").css(wyde.browser.prefix + "transform", "translate3d(0px, " + (yPos * 0.1) + "px, 0px) scale3d(" + transOut + ", " + transOut + ", 1)").css("opacity", transOut);
                                break;
                            case "zoomOutDown":
                                $(".title-wrapper .container").css(wyde.browser.prefix + "transform", "translate3d(0px, " + (yPos * 1.8) + "px, 0px) scale3d(" + transOut + ", " + transOut + ", 1)").css("opacity", transOut);
                                break;
                            case "zoomIn":
                                $(".title-wrapper .container").css(wyde.browser.prefix + "transform", "translate3d(0px, " + (yPos * 1.1) + "px, 0px) scale3d(" + transIn + ", " + transIn + ", 1)").css("opacity", transOut);
                                break;
                            case "zoomInUp":
                                $(".title-wrapper .container").css(wyde.browser.prefix + "transform", "translate3d(0px, " + (yPos * 0.1) + "px, 0px) scale3d(" + transIn + ", " + transIn + ", 1)").css("opacity", transOut);
                                break;
                            case "zoomInDown":
                                $(".title-wrapper .container").css(wyde.browser.prefix + "transform", "translate3d(0px, " + (yPos * 1.8) + "px, 0px) scale3d(" + transIn + ", " + transIn + ", 1)").css("opacity", transOut);
                                break;
                        }
                    }
                }
            }


            var requestRender = function () {
                if (!isBusy) {
                    isBusy = true;
                    window.requestAnimationFrame(function () {
                        render();
                        isBusy = false;
                    });
                }
            }

            $(window).on("scroll", function () {
                requestRender();
            });

            $(window).smartresize(function () {
                refresh();
            });

            refresh();

            requestRender();
        }
    }

    function titleAreaScroll() {        

        var $el = $(".title-wrapper");

        if( !$el.length ) return;

        var enableScroll = function() {
            $(window).off("mousewheel");
            $(document).off("mousewheel");
            $(document).off("keydown");                
            $el.off("mousewheel");                
        };

        if( $el.hasClass("w-gradient") ){  

            var keys = [32, 37, 38, 39, 40];

            var scrollPos, isActive, isScrolled, isAnimating, pause = false;   

            var toggleAnimation = function(active) {
                isAnimating = true;
                if (active) {
                    $el.addClass("w-effect-active");
                } else {
                    isScrolled = false;
                    disableScroll();
                    $el.removeClass("w-effect-active");
                } 
                
                setTimeout(function() { 
                    isActive = !!active;
                    isAnimating = false;
                    if (active) {
                        isScrolled = true;
                        enableScroll();
                        $(window).scrollTop(1);
                    }
                }, 500);
            };  

            var isScrollEnabled = !(navigator.userAgent.indexOf("Safari") > -1 && navigator.userAgent.indexOf('Chrome') == -1);

            if( !isScrollEnabled ){
                toggleAnimation(1);
                return;
            }                        

            var preventDefault = function(e) {
                e = e || window.event;
                if (e.preventDefault) e.preventDefault();
                e.returnValue = false;  
            };          

            var disableScroll = function() {

                $(window).on("mousewheel", function(){
                    return false;
                });
                $(document).on("mousewheel", function(){
                    return false;
                });
                $(document).on("keydown", function(e) {
                    for (var i = keys.length; i--;) {
                        if (e.keyCode === keys[i]) {
                            preventDefault(e);
                            return;
                        }
                    }
                });

                $el.off("off").on("mousewheel", function (e) {

                    if(isAnimating) return false;

                    e = window.event || e;

                    var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.deltaY || -e.detail)));

                    if (delta < 0) { //scrolling down   

                        if (!isActive) {
                            isActive = true;
                            toggleAnimation(1);                               
                        }                            

                    }

                    return false;

                });
                

            };
            
            scrollPos = $(window).scrollTop();

            isScrolled = (scrollPos > 0);

            if (scrollPos) {
                isActive = true;
                $el.addClass("w-effect-active");
            }else{
                disableScroll();         
            } 
  
            $(window).off("scroll.wyde_fulltitle").on("scroll.wyde_fulltitle", function() {

                if (pause) return;

                scrollPos = $(window).scrollTop();

                if (!isScrolled && !wyde.browser.msie) {
                    if (scrollPos < 0) return false;
                    window.scrollTo(0, 0);
                }

                if (isAnimating) {
                    return false;
                }

                if (scrollPos <= 0 && isActive) {
                    toggleAnimation(0);
                } else if (scrollPos > 0 && !isActive) {
                    toggleAnimation(1);
                }

            });

            var lastScroll;
            
            $(document).on("webkitfullscreenchange mozfullscreenchange fullscreenchange MSFullscreenChange", function() {
             
                var scrollTop = $(window).scrollTop();
                if (scrollTop == 0) {
                    pause = true;
                } else {
                    lastScroll = scrollTop;
                    setTimeout(function() {
                        pause = false;
                        $(window).scrollTop(lastScroll);
                    }, 1000);
                }

            });   

            $(window).on("menu", function(e, data) {
                if (data == "open") {
                    enableScroll();
                }else if (data == "close") {
                    disableScroll();
                }
            });      

        }else{
            if( wyde.page.ajax_page ){
                $(window).off("scroll.wyde_fulltitle");
                enableScroll();
            }
            
        }                  

        titleScrollEffects();           

    }

    /*****************************************
    Call on Wyde Page Ready event 
    /*****************************************/
    $(window).on("wyde.page.ready", function () {
       
        titleAreaScroll();              

    });

})(jQuery);