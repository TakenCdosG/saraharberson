(function ($) {


    function WydeGmapsParam(element, options, callback){

        var $el = $(element);

        var self = this;

        var settings = $.extend({}, {
            locations:[
                {
                    address: "",
                    position: { lat: 37.6, lng: -95.665 }                    
                }
            ],           
            zoom: 8,
            type: "roadmap",
            center: { lat: 37.6, lng: -95.665 }
        }, options || {});      

        var map, markers, infoWindows;

        this.initMap = function(){

            if( typeof google == "undefined") return;

            var mapCanvas = $el.find(".gmaps-canvas").get(0);
            var mapOptions = {
                zoom: settings.zoom,
                scrollwheel: false,
                center: settings.center,
                mapTypeId: settings.type
            };
            map = new google.maps.Map(mapCanvas, mapOptions); 

            map.addListener("zoom_changed", function() {
                settings.zoom = map.getZoom();
                self.saveChanges();
            });

            map.addListener("maptypeid_changed", function() {
                settings.type = map.getMapTypeId();  
                self.saveChanges();             
            });   

            map.addListener("center_changed", function() {
                settings.center = map.getCenter();  
                self.saveChanges();             
            });   

            markers = [];
            infoWindows = [];    
            
            $.each(settings.locations, function(i, v){
                self.addMarker(i, v);
            });

        };              

        this.saveChanges = function() {
            //console.log(JSON.stringify(settings));
            $el.find(".wyde_gmaps_field").val( encodeURIComponent( JSON.stringify(settings) ) );
        };

        this.addMarker = function(idx, location){

            if( !map ) return;

            var marker = new google.maps.Marker({
                    map: map,
                    position: location.position,
                    title: "Drag & Drop marker",
                    animation: google.maps.Animation.DROP,
                    draggable: true                
            });

            google.maps.event.addListener(marker, "dragend", function () {
                var position = marker.getPosition();
                settings.locations[idx].position = { lat: position.lat(), lng: position.lng() };
                self.saveChanges();
            });

            var infoWindow = new google.maps.InfoWindow({
                    content: location.address
            });              
            

            google.maps.event.addListener(marker, "click", function () {
                infoWindow.open(map, marker);
            });

            markers[idx] = marker;
            infoWindows[idx] = infoWindow;

        };

        this.updateMarker = function(idx, location){

            if( !markers ) return;

            var marker = markers[idx];
            
            if(marker){

                marker.setPosition(location.position);

                if (location.address) {

                    var infowindow = infoWindows[idx];
                    if(infowindow){
                        infowindow.setContent(location.address);
                    }                        
                
                }              

            }else{
                self.addMarker(idx, location);   
            }

        };

        this.removeMarker = function(idx){

            if( !markers ) return;

            var marker = markers[idx];
            
            if(marker){
                marker.setMap(null);                               
            }

            markers = $.grep(markers, function(n, i){
                return i != idx;
            });  

            var infoWindow = infoWindows[idx];
            
            if(infoWindow){
                infoWindow.close();                               
            }

            infoWindows = $.grep(infoWindows, function(n, i){
                return i != idx;
            });  


        };

        this.updateAddress = function(idx, address){

            var location = settings.locations[idx];

            if(location){
                settings.locations[idx].address = address;
            }else{                
                settings.locations[idx] = {
                    address:address,
                    position:{lat: 37.6, lng: -95.665}
                }
                self.addMarker(idx, settings.locations[idx]);
            }          
            
            self.saveChanges();

            if( !map ) return;

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address: address }, function (responses, status) {
                if (status === google.maps.GeocoderStatus.OK && responses.length > 0 && responses[0].geometry) {
                    var position = responses[0].geometry.location;
                    settings.locations[idx].position = { lat: position.lat(), lng: position.lng() };    
                                    
                    map.setCenter(settings.locations[idx].position);
                    self.updateMarker(idx, settings.locations[idx]);
                    self.saveChanges();
                } else {
                    //console.log("Cannot determine address at this location.");
                }
            });
        };


        this.removeAddress = function(address){

           settings.locations = $.grep(settings.locations, function(n, i){                             
                
                if( n.address === address ){
                    self.removeMarker(i);
                    return false;
                }else{
                    return true;
                }

            });           

            self.saveChanges();          


        };

        this.initMap();

    }

    //jQuery proxy
    $.fn.wydeGmapsParam = function (options, callbackMap) {
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
            var plugin = $.data(element, "wydeGmapsParam");

            if (!plugin && !method) {                
                // Create a new object if it doesn't exist yet
                plugin = $.data(element, "wydeGmapsParam", new WydeGmapsParam(element, options, callbackMap));
            } else if (plugin && method) {
                // Call method
                if (plugin[method]) {
                    plugin[method].apply(plugin, methodArgs);
                }
            }
        });
    };

    var $gmap = $(".wyde-gmaps");

    if($gmap.length){

        var options = $gmap.find(".wyde_gmaps_field").val();
        if(options) options = $.parseJSON(decodeURIComponent(options));
        $gmap.wydeGmapsParam(options);
        
    }    
    
    $.extend(window, {
        wyde_gmaps_addresses_added: function($el, action) {
            
            if(action){                

                $el.find(".values_address").each(function(){
                    $(this).change(function(){ 
                        if(!$gmap.length) return;
                        var $el = $(this);
                        var idx = $(".values_address").index($el);
                        $gmap.wydeGmapsParam("updateAddress", idx, $el.val());
                    });
                });    

                $el.find("> .wpb_element_wrapper").slideDown("fast", function(){
                    $el.removeClass("vc_param_group-collapsed");
                });
                             
            }
            
        },
        wyde_gmaps_addresses_deleted: function(){
            if(!$gmap.length) return;
  
            var addresses = [];

            $(".values_address").each(function(){
                addresses.push($(this).val());
            });
                   
            setTimeout(function(){      

                $.each(addresses, function(i, v){                
                    if( !$('.values_address').filter( function(){ return $(this).val() == v;} ).length ){
                        $gmap.wydeGmapsParam("removeAddress", v);
                    }
                });                               

            }, 1000);  
        }

    });     


    //ParamGroup default state
    $(".vc_param_group-collapsed").each(function(){
        var $el = $(this);
        $el.find("> .wpb_element_wrapper").slideDown("fast", function(){
            $el.removeClass("vc_param_group-collapsed");
        });
    });
    

})(jQuery);