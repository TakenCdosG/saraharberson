(function ($) {
    "use strict";

    window.$ = $;

    $.fn.wydeShortcode = function(){

    };
    /*****************************************
    Counter Box
    /*****************************************/
    $.fn.wydeCounterBox = function () {

        return this.each(function () {


            $(this).waypoint(function () {

                var $el = $(this).find(".counter-value");

                var start = parseInt($el.text());
                var value = parseInt($el.data("value"));
                var unit = $el.data("unit");

                $el.countTo({
                    from: start,
                    to: value,
                    unit: unit
                });

            }, {
                offset: "90%",
                triggerOnce: true
            });

        });

    };

    $.fn.countTo = function (options) {

        var defaults = {
            from: 0,
            to: 100,
            speed: 1000,
            refreshInterval: (1000 / 60) * 2,
            unit: ""
        };

        var settings = $.extend({}, defaults, options || {});

        var loops = Math.ceil(settings.speed / settings.refreshInterval);
        var step = (settings.to - settings.from) / loops;

        return this.each(function () {

            var $el = $(this);
            var value = settings.from;
            var unit = settings.unit;

            var loopCount = 0;
            var interval = setInterval(updateTimer, settings.refreshInterval);

            function updateTimer() {
                value += step;
                loopCount++;
                $el.html(value.toFixed(0) + unit);
                if (loopCount >= loops) {
                    clearInterval(interval);
                    value = settings.to;
                }
            }

        });
    };


    /*****************************************
    Link Icon
    /*****************************************/
    $.fn.wydeLinkIcon = function () {

        return this.each(function () {
            var $el = $(this);
            var color = hex2rgb($el.data("color"), "0.10");
            $el.css({ "color": color });
            $(":after", this).css({ "box-shadow-color": color });
            $el.hover(function () {
                $el.css("color", hex2rgb($el.data("color"), "1"));
            }, function () {
                $el.css("color", hex2rgb($el.data("color"), "0.10"));
            });

        });

    };


    /*****************************************
    Animated Element
    /*****************************************/
    $.fn.wydeAnimated = function () {

        return this.each(function () {

            var $el = $(this);

            var animation = $el.data("animation");
            if (!animation) return;

            $el.addClass("animated-element " + animation);

            var delay = $el.data("animationDelay");
            if (delay) delay = parseFloat(delay);

            if (!delay) delay = 0;

            delay = delay * 1000;

            var offset = "100%";

            $el.waypoint(function () {

                setTimeout(function () {

                    $el.removeClass("animated-element").addClass("animated").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
                        $(this).removeClass(animation + " animated");
                    });              

                }, delay);

            }, {
                offset: offset,
                triggerOnce: true
            });


        });

    };


    /*****************************************
    Google Maps
    /*****************************************/
    $.fn.wydeGMaps = function (options) {

        var defaults = {
                icon: "",
                gmaps: {
                    locations:[
                        {
                            address: "",
                            position: { lat: 37.6, lng: -95.665 }                    
                        }
                    ],
                    zoom: 8,
                    type: 2,
                    center: { lat: 37.6, lng: -95.665 }
                },
                color: "#ff0000",
                height: 500,
                mapStyles : []
        };

        return this.each(function () {

            if( typeof google == "undefined" ) return;

            var $el = $(this);           
            
            var settings = $.extend({}, defaults, options || {});            

            var mapCanvas = $el.find(".w-map-canvas").height(settings.height).get(0);
            var locations = settings.gmaps.locations;
            var center = new google.maps.LatLng(settings.gmaps.center.lat, settings.gmaps.center.lng);
            var zoom = settings.gmaps.zoom;
            var type = settings.gmaps.type;
            var mapStyles = settings.mapStyles;

            var wydeMapType = new google.maps.StyledMapType(mapStyles, { name: "Wyde Map" });

            var mapOptions = {
                center: center,
                zoom: zoom,
                scrollwheel: false,
                draggable: !wyde.browser.touch,
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL,
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                scaleControl: false,
                scaleControlOptions: {
                    position: google.maps.ControlPosition.LEFT_CENTER
                },
                streetViewControl: false,
                streetViewControlOptions: {
                    position: google.maps.ControlPosition.LEFT_CENTER
                },
                panControl: false,
                panControlOptions: {
                    position: google.maps.ControlPosition.LEFT_CENTER
                },
                mapTypeControl: false,
                mapTypeControlOptions: {
                    mapTypeIds: [type, "wyde_map"],
                    style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                    position: google.maps.ControlPosition.LEFT_CENTER
                },
                mapTypeId: "wyde_map"
            };

            var map = new google.maps.Map(mapCanvas, mapOptions);
            map.mapTypes.set("wyde_map", wydeMapType);          

            if (locations) {

                $.each(locations, function(){
                    var location = this;
                    var marker = new google.maps.Marker({
                        map: map,
                        position: location.position,
                        animation: google.maps.Animation.DROP,
                        icon: settings.icon
                    });

                    var infowindow = new google.maps.InfoWindow({
                        content: location.address
                    });

                    google.maps.event.addListener(marker, "click", function () {
                        infowindow.open(map, marker);
                    });

                });
                
            }

        });

    };


    /*****************************************
    Tabs
    /*****************************************/
    function WydeTabs(element, options, callback) {   

            var defaults = {
                interval: 0,
                selectedIndex: 0,
                changed: function(){}
            };

            var settings = $.extend({}, defaults, options || {});

            var $el = $(element);            
            var timer = null;
            var selectedIndex = settings.selectedIndex;
            var items = $el.find(".w-tabs-nav li").length;
            var playing = false;

            var self = this;

            this.select = function (idx) {

                if(idx >= 0) selectedIndex = idx;

                if (selectedIndex < 0 || selectedIndex >= items) selectedIndex = 0;            

                $el.find(".w-tabs-nav li").removeClass("active");
                $el.find(".w-tabs-nav li").eq(selectedIndex).addClass("active");

                $el.find(".w-tab").removeClass("active");
                $el.find(".w-tab").eq(selectedIndex).addClass("active");
                $el.find(".w-tab-wrapper").css("min-height", $el.find(".w-tab").eq(selectedIndex).height());
                if(typeof settings.changed == "function"){
                    settings.changed();
                }
            };

            this.play = function () {
                if (!playing) {
                    timer = setInterval(function () {
                        selectedIndex++;
                        self.select();
                    }, settings.interval * 1000);
                    playing = true;
                }
            };

            this.pause = function () {
                if (timer) clearInterval(timer);
                playing = false;
            };

            $el.find(".w-tabs-nav li").each(function(){
                var $nav = $(this);
                var idx = $el.find(".w-tabs-nav li").index($nav);
                var link = $nav.find("a").attr("href");
                //Find all links (including other buttons) matching tab id so allow them to open this tab section.
                var $button = $nav.find("a");

                if(link && link.length>1){

                    $("a[href*='"+link+"']").not($button).off("click").on("click", function (event) {
                        event.preventDefault();
                        self.pause();                    
                        wyde.page.scrollTo($nav, {
                            onAfter: function() {                       
                                self.select(idx);
                            }
                        });
                        
                        return false;
                    });

                }

                $button.off("click").on("click", function(event){
                    event.preventDefault();
                    self.pause();                                           
                    self.select(idx);                                         
                    return false;
                });

            });

            this.select(selectedIndex);

            if (settings.interval > 2) {
                self.play();
                $el.hover(function () {
                    self.pause();
                }, function () {
                    self.play();
                });
                $(window).scroll(function () {
                    if ($el.isOnScreen()) {
                        self.play();
                    } else {
                        self.pause();
                    }
                });
            }
    
    };
    //jQuery proxy
    $.fn.wydeTabs = function (options, callbackMap) {
        var method, methodArgs;

        // Attributes logic
        if (!$.isPlainObject(options)) {
            if (typeof options === "string" || options === false) {
                method = options === false ? "destroy" : options;
                methodArgs = Array.prototype.slice.call(arguments, 1);
            }
            options = {};
        }

        // Apply to all elements
        return this.each(function (i, element) {
            // Call with prevention against multiple instantiations
            var plugin = $.data(element, "wydeTabs");

            if (!plugin && !method) {                
                // Create a new object if it doesn't exist yet
                plugin = $.data(element, "wydeTabs", new WydeTabs(element, options, callbackMap));
            } else if (plugin && method) {
                // Call method
                if (plugin[method]) {
                    plugin[method].apply(plugin, methodArgs);
                }
            }
        });
    };


    /*****************************************
    Accordion
    /*****************************************/
    $.fn.wydeAccordion = function () {


        return this.each(function () {
            var $el = $(this);
            var color = $el.data("color");
            //if (color) color = hex2rgb(color, '0.3');

            if (color) $el.find(".acd-header").css("color", color);

            var activeTab = $el.data("active");
            if (activeTab !== "false" && activeTab != "") {
                $el.find(".acd-content").eq(parseInt(activeTab) - 1).slideDown(300, function () {
                    $(this).parent().addClass("active");
                    if (color) $(this).parent().find(".acd-header").css({ color: "", "background-color": color });
                });
            }



            var collapsible = $el.data("collapsible");

            $el.find(".acd-header").click(function (event) {
                var $parent = $(this).parents(".w-accordion-tab");

                if ($parent.hasClass("active")) {
                    return false;
                } else {
                    var $content = $parent.find(".acd-content");
                    if (!collapsible) {
                        $el.find(".acd-content").not($content).slideUp(300, function () {
                            $(this).parent().removeClass("active");
                            if (color) $(this).parent().find(".acd-header").css({ color: color, "background-color": "" });
                        });
                    }

                    $content.slideDown(300, function () {
                        $parent.addClass("active");
                        if (color) $parent.find(".acd-header").css({ color: "", "background-color": color });

                    });
                }

            });

        });
    };


    /*****************************************
    Toggle
    /*****************************************/
    $.fn.wydeToggle = function () {

        return this.each(function () {

            var $el = $(this);
            /*
            var color = $el.data("color");
            //if (color) color = hex2rgb(color, '0.3');
            $el.find(".acd-header").css("color", color);

            if (color && $el.hasClass("active")) {
                $el.find(">h3").css({ "color": color });
            }
            */
            $el.on("click", function () {
                $el.find("> div").slideToggle(300, function () {
                    $el.toggleClass("active ");
                    /*
                    if ($el.hasClass("active")) {
                        $el.find(">h3").css({ "color": color });
                    } else {
                        $el.find(">h3").css({ "color": "" });
                    }*/
                });
            });


        });
    };


    /*****************************************
    Progress Bar
    /*****************************************/
    $.fn.wydeProgressBar = function () {

        return this.each(function () {
            var $el = $(this);

            // Collect and sanitize user input
            var value = parseInt($el.data("value")) || 0;
            var unit = $el.data("unit");
            var xPos = 100 - value;
            $el.waypoint(function () {

                $el.find(".w-bar").css(wyde.browser.prefix + "transform", "translateX(-" + xPos + "%)");

                var $counter = $el.find(".w-counter");
                if ($counter.length) {

                    if(!wyde.browser.xs) {
                        $counter.css(wyde.browser.prefix + "transform", "translateX(-" + xPos + "%)");
                    } 

                    $({ counterValue: 0 }).animate({ counterValue: value }, {
                        duration: 1500,
                        easing: "easeOutCirc",
                        step: function () {
                            $counter.text(Math.round(this.counterValue) + " " + unit);
                        }
                    });
                }

            }, {
                offset: "90%",
                triggerOnce: true
            });

        });
    };

    /*****************************************
    Facebook Like Box
    /*****************************************/
    $.fn.wydeFacebookLike = function (options, callback) {

        var defaults = {
            page_url: "",
            height: 500,
            show_facepile: true,
            small_header: false,
            tabs:'timeline'
        };        

        return this.each(function () {

            var settings = $.extend({}, defaults, options || {});

            var self = this;

            var $el = $(this);          

            var $iframe = $("<iframe></iframe>");  



            this.refresh = function(){

                 if ($el.data("width")){
                    settings.width = parseInt($el.data("width"));
                }else{
                    settings.width = $el.width();
                }

                if ($el.data("height")){
                    settings.height = parseInt($el.data("height"));
                }else{
                    settings.height = 500;
                }

                if ($el.data("show_facepile")) settings.show_facepile = $el.data("show_facepile");
                if ($el.data("small_header")) settings.small_header = $el.data("small_header");
                if ($el.data("tabs")) settings.tabs = $el.data("tabs");
                if ($el.data("page_url")) settings.page_url = $el.data("page_url");
                

                $iframe.attr("src", String.format("https://www.facebook.com/v2.5/plugins/page.php?width={0}&height={1}&show_facepile={2}&small_header={3}&tabs={4}&href={5}",
                        settings.width,
                        settings.height,
                        settings.show_facepile,
                        settings.small_header,
                        settings.tabs,
                        encodeURI( settings.page_url )
                    )
                );

                $el.html("");

                $el.append( $iframe );

            };

            this.refresh();

            $(window).smartresize(function(){
                self.refresh();
            });

           
        });
    };

    /*****************************************
    Flickr Stream
    /*****************************************/
    $.fn.wydeFlickrStream = function (options, callback) {

        var defaults = {
            apiUrl: {
                user: "https://api.flickr.com/services/feeds/photos_public.gne",
                group: "https://api.flickr.com/services/feeds/groups_pool.gne"
            },
            count: 9,
            columns: 3,
            size: "_q",
            type: "user",
            lang: "en-us",
            format: "json",
            jsoncallback: "?"

        };        

        return this.each(function () {

            var settings = $.extend({}, defaults, options || {});

            var $el = $(this);

            if ($el.data("type")) settings.type = $el.data("type");
            if ($el.data("count")) settings.count = parseInt($el.data("count"));
            if ($el.data("columns")) settings.columns = parseInt($el.data("columns"));
            if ($el.data("id")) settings.id = $el.data("id");

            if (!settings.id){
                $el.find(".w-content").html("");
                return;
            } 

            var apiUri = settings.apiUrl.user;
            if (settings.type == "group") {
                apiUri = settings.apiUrl.group;
            }

            apiUri = String.format("{0}?lang={1}&format={2}&id={3}&jsoncallback={4}", apiUri, settings.lang, settings.format, settings.id, settings.jsoncallback);

            var colName = "";
            if (settings.columns != 5) {
                colName = "col-" + Math.abs(Math.floor(12 / settings.columns));
            } else {
                colName = "five-cols";
            }

            $.getJSON(apiUri, function (data) {
                var list = $("<ul></ul>").addClass("image-list clear");
                $el.find(".w-content").html("").append(list);
                $.each(data.items, function (i, item) {
                    if (i < settings.count) {
                        list.append(String.format("<li class=\"{0}\"><a href=\"{1}\" title=\"{2}\" target=\"_blank\"><img src=\"{3}\" alt=\"{2}\" /></a></li>", colName, item.link, item.title, item.media.m.replace("_m", settings.size)));
                    }
                });

                if (typeof callback == "function") {
                    callback();
                }

            });
        });
    };


    /*****************************************
    TwitterFeed
    /*****************************************/
    $.fn.wydeTwitterFeed = function (options) {

        var defaults = {
            username: "",
            count: 5,
            profileImage: false,
            showTime: false,
            mediaSize: ""  //values: thumb, small, medium, large 

        };

        function xTimeAgo(time) {
            var nd = new Date();
            //var gmtDate = Date.UTC(nd.getFullYear(), nd.getMonth(), nd.getDate(), nd.getHours(), nd.getMinutes(), nd.getMilliseconds());
            var gmtDate = Date.parse(nd);
            var tweetedTime = time * 1000; //convert seconds to milliseconds
            var timeDiff = (gmtDate - tweetedTime) / 1000; //convert milliseconds to seconds

            var second = 1, minute = 60, hour = 60 * 60, day = 60 * 60 * 24, week = 60 * 60 * 24 * 7, month = 60 * 60 * 24 * 30, year = 60 * 60 * 24 * 365;

            if (timeDiff > second && timeDiff < minute) {
                return Math.round(timeDiff / second) + " seconds ago";
            } else if (timeDiff >= minute && timeDiff < hour) {
                return Math.round(timeDiff / minute) + " minutes ago";
            } else if (timeDiff >= hour && timeDiff < day) {
                return Math.round(timeDiff / hour) + " hours ago";
            } else if (timeDiff >= day && timeDiff < week) {
                return Math.round(timeDiff / day) + " days ago";
            } else if (timeDiff >= week && timeDiff < month) {
                return Math.round(timeDiff / week) + " weeks ago";
            } else if (timeDiff >= month && timeDiff < year) {
                return Math.round(timeDiff / month) + " months ago";
            } else {
                return "over a year ago";
            }

        }


        return this.each(function () {

            var $el = $(this);

            var settings = $.extend({}, defaults, options || {});

            if ($el.data("username")) settings.username = $el.data("username");
            if ($el.data("count")) settings.count = parseInt($el.data("count"));
            if ($el.data("profileImage")) settings.profileImage = $el.data("profileImage");
            if ($el.data("showTime")) settings.showTime = $el.data("showTime");
            if ($el.data("mediaSize")) settings.mediaSize = $el.data("mediaSize");

            var urlpattern = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
            var media = "";

            $.getJSON("https://www.api.tweecool.com/?screenname=" + settings.username + "&count=" + settings.count, function (data) {

                if (data.errors || data == null) {
                    $el.html("No tweets available.");
                    return false;
                }

                $el.html("");

                var profileImage = "";

                if (settings.profileImage) {
                    profileImage = "<a href=\"https://twitter.com/" + settings.username + "\" target=\"_blank\"><img src=\"" + data.user.profile_image_url.replace("normal", "bigger") + "\" alt=\"" + settings.username + "\" /></a>";
                } else {
                    profileImage = "<i class=\"flora-icon-twitter\"></i>";
                }

                profileImage = "<div class=\"profile-image\">" + profileImage + "</div>";

                $el.append(profileImage);

                var $list = $("<div/>").appendTo($el);

                $.each(data.tweets, function (i, field) {

                    if (settings.showTime) {
                        var timestamp = xTimeAgo(field.timestamp);
                    } else {
                        var timestamp = '';
                    }

                    if (settings.mediaSize != "" && field.media_url) {
                        media = "<a href=\"https://twitter.com/" + settings.username + "\" target=\"_blank\" class=\"tweet-media\"><img src=\"" + field.media_url + ":" + settings.mediaSize + "\" alt=\"" + settings.username + "\" /></a>";
                    } else {
                        media = "";
                    }

                    $list.append("<div class=\"tweet-item\">" + field.text.replace(urlpattern, "<a href=\"$1\" target=\"_blank\">$1</a>") + media + " <span class=\"tweet-date\">" + timestamp + "</span></div>");
                });


                $list.owlCarousel({
                    autoHeight: true,
                    autoplayHoverPause: true,
                    navText: ["", ""],
                    items: 1,
                    autoplay: 5000,
                    nav: false,
                    dots: true,
                    loop: true,
                    themeClass: "",
                    animateIn: "fadeIn",
                    animateOut: "fadeOut"
                });

            }).fail(function (jqxhr, textStatus, error) {
                $el.html("No tweets available");
            });

        });

    };


    /*****************************************
    Fade SlideShow
    /*****************************************/
    $.fn.wydeFadeSlider = function (options) {

        var defaults = {
            autoplayTimeout: 100,
            interval: 1500,
            activeClass: "active",
            animateIn: "fadeIn",
            animateOut: "fadeOut",
            autoplay: false
        };

        var settings = $.extend({}, defaults, options || {});

        return this.each(function () {

            var $el = $(this);

            var $slides = $el.find("> *");

            if( $slides.length < 2){
                $el.removeClass("w-fadeslider");
                return;
            }

            $el.addClass("w-fadeslider");

            $slides.addClass("slide").addClass(settings.animateOut);

            var slideCount = $slides.length - 1;

            var activeSlide = 0;

            $slides.eq(activeSlide).addClass(settings.activeClass).removeClass(settings.animateOut).addClass(settings.animateIn);

            var timer = false;

            var play = function () {

                var timeOut = settings.autoplayTimeout;
                timer = setTimeout(function () {

                    $slides.eq(activeSlide).removeClass(settings.activeClass).removeClass(settings.animateIn).addClass(settings.animateOut);

                    if (activeSlide >= slideCount) {
                        activeSlide = 0;
                    } else {
                        activeSlide = activeSlide + 1;
                    }

                    $slides.eq(activeSlide).removeClass(settings.animateOut).addClass(settings.animateIn).addClass(settings.activeClass);

                    timeOut = settings.interval;

                    timer = setTimeout(function () {
                        play();
                    }, timeOut);

                }, timeOut);

            }

            var stop = function () {
                clearInterval(timer);
                timer = false;
            }

            if (settings.autoplay) {
                play();
                $el.hover(function () {
                    stop();
                }, function () {
                    play();
                });
            } else {
                $el.hover(function () {
                    play();
                }, function () {
                    stop();
                });
            }
        });

    }

    /*****************************************
    Donut Chart
    /*****************************************/
    $.fn.wydeDonutChart = function (options, callback) {

        var defaults = {
            startdegree: 0,
            color: "#21242a",
            bgcolor: "#eee",
            fill: false,
            width: 15,
            dimension: 250,
            value: 50,
            animationstep: 1.0,
            border: "default",
            complete: null
        };
       

        return this.each(function () {

            var settings = $.extend({}, defaults, options || {});

            var customSettings = ["color", "bgcolor", "fill", "width", "dimension", "animationstep", "endPercent", "border", "startdegree"];

            var percent;
            var endPercent = 0;
            var el = $(this);
            var fill = false;
            var type = "";

            checkDataAttributes(el);

            type = el.data("type");


            if (el.data("total") != undefined && el.data("part") != undefined) {
                var total = el.data("total") / 100;

                percent = ((el.data("part") / total) / 100).toFixed(3);
                endPercent = (el.data("part") / total).toFixed(3);
            } else {
                if (el.data("value") != undefined) {
                    percent = el.data("value") / 100;
                    endPercent = el.data("value");
                } else {
                    percent = defaults.value / 100;
                }
            }


            el.width(settings.dimension + "px");

            if (type == "half") {
                el.height(settings.dimension / 2);
            }

            var size = settings.dimension,
                canvas = $("<canvas></canvas>").attr({
                    width: size,
                    height: size
                }).appendTo(el).get(0);

            var context = canvas.getContext("2d");

            var dpr = window.devicePixelRatio;
            if (dpr) {
                var $canvas = $(canvas);
                $canvas.css("width", size);
                $canvas.css("height", size);
                $canvas.attr("width", size * dpr);
                $canvas.attr("height", size * dpr);

                context.scale(dpr, dpr);
            }

            var container = $(canvas).parent();
            var x = size / 2;
            var y = size / 2;
            var radius = size / 2.5;
            var degrees = settings.value * 360.0;
            var radians = degrees * (Math.PI / 180);
            var startAngle = 2.3 * Math.PI;
            var endAngle = 0;
            var counterClockwise = false;
            var curPerc = settings.animationstep === 0.0 ? endPercent : 0.0;
            var curStep = Math.max(settings.animationstep, 0.0);
            var circ = Math.PI * 2;
            var quart = Math.PI / 2;
            var fireCallback = true;
            var additionalAngelPI = (settings.startdegree / 180) * Math.PI;


            if (type == "half") {
                startAngle = 2.0 * Math.PI;
                endAngle = 3.13;
                circ = Math.PI;
                quart = Math.PI / 0.996;
            } else if (type == "angle") {
                startAngle = 2.25 * Math.PI;
                endAngle = 2.4;
                circ = 1.53 + Math.PI;
                quart = 0.73 + Math.PI / 0.996;
            }

            function checkDataAttributes(el) {
                $.each(customSettings, function (index, attribute) {
                    if (el.data(attribute) != undefined) {
                        settings[attribute] = el.data(attribute);
                    } else {
                        settings[attribute] = $(defaults).attr(attribute);
                    }

                    if (attribute == "fill" && el.data("fill") != undefined) {
                        fill = true;
                    }
                });
            }

            function animate(current) {

                context.clearRect(0, 0, canvas.width, canvas.height);

                context.beginPath();
                context.arc(x, y, radius, endAngle, startAngle, false);

                if (fill) {
                    context.fillStyle = settings.fill;
                    context.fill();
                }

                context.lineWidth = settings.width;

                context.strokeStyle = settings.bgcolor;
                context.stroke();                

                context.beginPath();
                context.arc(x, y, radius, -(quart) + additionalAngelPI, ((circ) * current) - quart + additionalAngelPI, false);

                var borderWidth = settings.width;
                if (settings.border == "outline") {
                    borderWidth = settings.width + 10;
                } else if (settings.border == "inline") {
                    borderWidth = settings.width - 10;
                }

                context.lineWidth = borderWidth;
                context.strokeStyle = settings.color;
                context.stroke();

                if (curPerc < endPercent) {
                    curPerc += curStep;
                    window.requestAnimationFrame(function () {
                        animate(Math.min(curPerc, endPercent) / 100);
                    });
                }

                if (curPerc == endPercent && fireCallback && typeof (settings) != "undefined") {
                    if ($.isFunction(settings.complete)) {
                        settings.complete();

                        fireCallback = false;
                    }
                }
            }

            el.waypoint(function () {
                animate(curPerc / 100);
            }, {
                offset: "100%",
                triggerOnce: true
            });





        });
    };


    /*****************************************
    Scroll More
    /*****************************************/
    $.fn.wydeScrollmore = function (options) {

        var defaults = {
            autoTriggers: 0,
            nextSelector: ".w-next",
            contentSelector: ".w-item",
            loaderClass: "ball-pulse",
            callback: false
        };

        var settings = $.extend({}, defaults, options || {});

        return this.each(function () {

            var $el = $(this);
            var loading = false;
            var $next;
            var elIndex = $(".w-scrollmore").index($el);

            function loadContent() {

                if (loading == true) return;

                var $loader = $("<div class=\"post-loader\"><div></div><div></div><div></div></div>");
                $loader.addClass(settings.loaderClass);

                var url = $next.attr("href");

                if (!url) return;

                $next.replaceWith($loader);

                loading = true;

                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "html",
                    success: function (response) {

                        var $data = $(response);

                        var $newItems = $data.find(".w-scrollmore:eq(" + elIndex + ") " + settings.contentSelector).css({ opacity: 0 });

                        if ($newItems.length > 0) {

                            var $parent = $el.find(settings.contentSelector).parent();
                            $parent.append($newItems);
                            $parent.waitForImages({
                                finished: function () {

                                    $newItems.css({ opacity: 1 });

                                    $loader.replaceWith($data.find(".w-scrollmore:eq(" + elIndex + ") " + settings.nextSelector));

                                    if (typeof settings.callback == "function") {
                                        settings.callback($newItems);
                                    }

                                    $loader.css("opacity", 0);                                    

                                    loading = false;

                                    setTimeout(function(){
                                        init();
                                    }, 500);

                                },
                                waitForAll: false
                            });
                        }


                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        loading = false;
                    }
                });



            }

            function init() {

                $next = $el.find(settings.nextSelector);

                if (settings.autoTriggers) {
                    $next.waypoint(function () {
                        loadContent();
                    }, {
                        offset: "100%"
                    });
                }

                $next.off("click").on("click", function (event) {
                    event.preventDefault();
                    loadContent();
                    return false;
                });
            }

            init();


        });
    };


    /*****************************************
    Parallax Background
    /*****************************************/
    $.fn.wydeParallax = function (options) {

        var defaults = {
            speed: 0.3
        };

        var settings = $.extend({}, defaults, options || {});

        var $doc = $(document), $win = $(window);

        var winHeight = $win.height();
        var docHeight = $doc.height();

        return this.each(function () {

            var $el = $(this);
            var isBusy = false;

            var boxPos = $el.offset().top;
            var boxHeight = $el.height();
            var speed = settings.speed;

            var reverse = $el.hasClass("w-reverse");
            var fade = $el.hasClass("w-fadeout");

            var $bg = $el.find(".bg-image");
            var $content, contentHeight, contentTop, contentHalf, winHalf;

            function refresh() {
                boxHeight = $el.height();
                if (reverse || fade) {
                    $content = $el.find(" > .row");
                    contentHeight = $content.height();
                    contentTop = $content.offset().top;
                    contentHalf = contentHeight / 2;
                } else {
                    speed = 44 / (winHeight + boxHeight);
                }
            }

            function render() {

                if ($el.isOnScreen()) {

                    var scrollPos = $win.scrollTop();
                    boxPos = $el.offset().top;
                    if (reverse || fade) {

                        var yPos = Math.round((scrollPos - (boxPos)) * speed);
                        $bg.css(wyde.browser.prefix + "transform", "translate3d(0px, " + yPos + "px, 0px)");

                        if (fade) {
                            var viewport = getViewPort();
                            winHalf = winHeight / 2;
                            if (viewport.bottom > (contentTop + contentHeight - contentHalf + winHalf)) {
                                var o = 1 - ((scrollPos + contentHeight - contentTop) / contentHeight);
                                $content.css('opacity', o);
                            }

                        } else {
                            $content.css('opacity', '1');
                        }

                    } else {
                        var yPos = Math.abs(scrollPos - (boxPos - winHeight)) * speed * -1;
                        $bg.css(wyde.browser.prefix + "transform", "translate3d(0px, " + yPos + "%, 0px)");
                    }
                }

            }

            function requestRender() {
                if (!isBusy) {
                    isBusy = true;
                    window.requestAnimationFrame(function () {
                        render();
                        isBusy = false;
                    });
                }
            }

            $win.on("scroll", function () {
                requestRender();
            });

            $(window).smartresize(function () {
                winHeight = $win.height();
                docHeight = $doc.height();
                refresh();
            });

            refresh();
            requestRender();

        });

    };

    /*****************************************
    Wyde Scroller
    /*****************************************/
    function WydeScroller(element, options, callback) {

        var defaults = {
            scrollbar: true
        };

        var settings = $.extend({}, defaults, options || {});

        var $el = $(element);

        $el.bind("mousewheel", function () {
            return false;
        });

        $el.wrapInner("<div class=\"w-scroller scroll-loading\"><div class=\"w-wrapper\"><div class=\"w-content-inner\"></div></div></div>");

        var $content = $el.find(".w-content-inner");
        var hasScroll = $content.outerHeight() > $el.height();

        var $container = $el.find(".w-scroller");
        var $scrollbar = $("<div class=\"w-scrollbar\"><div class=\"w-bar\"><div class=\"w-holder\"></div></div>");
        $container.append($scrollbar);

        if (hasScroll) {
            $container.addClass("active");
        } else {
            $scrollbar.hide();
        }

        if (!settings.scrollbar) {
            $scrollbar.hide();
        }

        var $wrapper = $el.find(".w-wrapper");
        $wrapper.height($el.height());

        $wrapper.sly({
            scrollBar: $scrollbar,
            easing: "easeOutExpo",
            speed: 800,
            scrollBy: 120,
            syncSpeed: 0.1,
            dragHandle: 1,
            dynamicHandle: 1,
            clickBar: 1,
            mouseDragging: 1,
            touchDragging: 1,
            releaseSwing: 1,
            elasticBounds: 1
        });

        this.refresh = function () {
            $container.addClass("scroll-loading");
            $wrapper.height($el.outerHeight());
            hasScroll = $content.outerHeight() > $el.height();
            if (hasScroll) {
                $container.addClass("active");
                if (settings.scrollbar) $scrollbar.show();
            } else {
                $container.removeClass("active");
                $scrollbar.hide();
            }

            setTimeout(function(){
                $wrapper.sly("reload");
            }, 500);
            

            setTimeout(function () {
                $container.removeClass("scroll-loading");
            }, 1500);
        };

        this.destroy = function () {
            $el.unbind("mousewheel");
            $wrapper.sly(false);
        };

        var self = this;
        $(window).smartresize(function () {
            self.refresh();
        });

        setTimeout(function () {
            $container.removeClass("scroll-loading");
        }, 1500);

    }
    //jQuery proxy
    $.fn.wydeScroller = function (options, callbackMap) {
        var method, methodArgs;

        // Attributes logic
        if (!$.isPlainObject(options)) {
            if (typeof options === "string" || options === false) {
                method = options === false ? "destroy" : options;
                methodArgs = Array.prototype.slice.call(arguments, 1);
            }
            options = {};
        }

        // Apply to all elements
        return this.each(function (i, element) {
            // Call with prevention against multiple instantiations
            var plugin = $.data(element, "wydeScroller");

            if (!plugin && !method) {
                // Create a new object if it doesn't exist yet
                plugin = $.data(element, "wydeScroller", new WydeScroller(element, options, callbackMap));
            } else if (plugin && method) {
                // Call method
                if (plugin[method]) {
                    plugin[method].apply(plugin, methodArgs);
                }
            }
        });
    };

    /*****************************************
    Vertical Menu
    /*****************************************/
    function WydeVerticalMenu(element, options, callback) {
   

        var defaults = {
            saveState: false,
            changed: function () { }
        };

        var settings = $.extend({}, defaults, options || {});       

        var $el = $(element);
        var self = this;

        var menuDepth = 0;

        this.menuSelected = function(menuItem, depth) {

            if(depth) menuDepth = depth;

            var $sub = $(menuItem);
            var h = $sub.height();
            $el.css("height", h);
            if (wyde.browser.css3) {
                $el.find(".vertical-menu").css(wyde.browser.prefix + "transform", "translateX(" + (-menuDepth * 100) + "%)");
            } else {
                $el.find(".vertical-menu").animate({ left: -(menuDepth * 100) + "%" }, 300);
            }

            setTimeout(function () {
                settings.changed($el);
            }, 300);
        }

        $el.find(".vertical-menu .sub-menu").each(function () {
            var $sub = $(this);
            var padTop = $el.offset().top + parseInt($el.css("padding-top"));
            $sub.css("top", -parseInt($sub.offset().top) + padTop);
        }).each(function () {
            $(this).hide();
        });

        this.refresh = function(){          

            $el.find(".vertical-menu ul > li.back-to-parent span").on("click", function (event) {
                menuDepth--;
                if (menuDepth < 0) menuDepth = 0;
                var $sub = $(this).closest(".sub-menu");
                $sub.fadeOut(300);
                self.menuSelected($sub.closest(".menu-item-has-children").closest("ul"));                
                return false;
            });

            $el.find(".vertical-menu li.menu-item-has-children > a").each(function(){

                var $menu = $(this);
                var $sub = $menu.parent().find("> ul");               
                if ($sub.length > 0) {  

                    var $button = $menu.find("> span");

                    if( $menu.attr("href") == "#" || ( settings.saveState && this.pathname == window.location.pathname ) ){
                        $button = $menu;
                    }
                    
                    $button.on("click", function (event) { 
                        event.preventDefault();
                        menuDepth++;
                        $sub.fadeIn(300);
                        self.menuSelected($sub);     
                        return false;                                         
                    });                                      
                }

            });                   
            
            $el.css("height", $el.height());
            var delay = 0;            
            delay = self.openSubMenu();            

            setTimeout(function(){
                if( typeof settings.changed == "function" ){
                    settings.changed($el);
                } 
            }, (delay*300+100) );

           
        };

        this.openSubMenu = function(){
            // Select sub menu item  
            var $selectedItem = $el.find(".current-menu-item");
            var $parents = $selectedItem.parents(".current-menu-ancestor");
            
            menuDepth = $parents.length;               

            if( menuDepth > 0 ){
                var $sub = $parents.first().find("> ul").first();
                $parents.find("> ul").fadeIn(300);
                
                if( $selectedItem.find("> ul").length ){
                    var $subs = $selectedItem.find("> ul");
                    $subs.fadeIn(300);
                    $sub = $subs.first();
                    menuDepth += 1;   
                }   

                this.menuSelected($sub, menuDepth); 

                return menuDepth; 

            } 
        };

        this.refresh();

     
    };
    //jQuery proxy
    $.fn.wydeVerticalMenu = function (options, callbackMap) {
        var method, methodArgs;

        // Attributes logic
        if (!$.isPlainObject(options)) {
            if (typeof options === "string" || options === false) {
                method = options === false ? "destroy" : options;
                methodArgs = Array.prototype.slice.call(arguments, 1);
            }
            options = {};
        }

        // Apply to all elements
        return this.each(function (i, element) {
            // Call with prevention against multiple instantiations
            var plugin = $.data(element, "wydeVerticalMenu");

            if (!plugin && !method) {
                // Create a new object if it doesn't exist yet
                plugin = $.data(element, "wydeVerticalMenu", new WydeVerticalMenu(element, options, callbackMap));
            } else if (plugin && method) {
                // Call method
                if (plugin[method]) {
                    plugin[method].apply(plugin, methodArgs);
                }
            }
        });
    };
    

})(jQuery);