(function ($) {
    "use strict";
    function overlapImporter() {

        this.import_url = "";
        this.data_dir = "";
        this.demoId = "";
        this.tasks = [];
        this.steps = [];

        if (typeof overlap_ajax_importer_settings == "object") $.extend(this, overlap_ajax_importer_settings);

        var self = this;

        var $panel = null;

        this.initImporter = function () {

            // Demo Content
            $("#btn-import").on("click", function () {

                if (typeof overlap_ajax_importer_settings == "object") {

                    if ( confirm(self.messages.confirm_import_demo_content) ) {

                        var $el = $(this);

                        self.demoType = $("#demo-type").val();
                        self.demoId = 1;

                        $panel = $el.parents(".import-wrapper");

                        self.hideOptions();

                        $panel.append("<p class=\"import-message\"><strong>"+self.messages.loading+"</strong></p>");

                        self.progress = $("<ul class=\"import-progress\"></ul>");
                        $panel.append(self.progress);  

                        $(".content-options > p input").each(function(i, v){                                                        
                            if( $(this).is(":checked") ){  
                                var task = $(this).val();            
                                if(task) self.addTask(task, $(this).parent().text());                                                           
                            }
                        });

                        $panel.append("<p class=\"panel-cancel\"><a href=\"" + window.location.href + "&action=cancel\" class=\"button-cancel\"><i class=\"el el-remove-sign\"></i>Cancel</a></p>");

                    }

                } else {
                    alert("Cannot import now!");
                }

                return false;

            });

            // Settings
            $(".demo-item").on("click", function (event) {
                event.preventDefault();

                if (typeof overlap_ajax_importer_settings == "object") {

                    if (confirm(self.messages.confirm_import_settings)) {

                        var $el = $(this);

                        self.demoId = $el.attr("id").replace(/\D/g, '');                        

                        $panel = $el.parents(".import-wrapper");

                        self.hideOptions();

                        $panel.append("<p class=\"import-message\"><span class=\"status\"><span class=\"w-loader\"></span></span> <strong>"+self.messages.loading+"</strong></p>");   

                        self.importSettings();

                    }

                } else {
                    alert("Cannot import now!");
                }

                return false;
            });
            
        };

        this.hideOptions = function(){
            $(".import-wrapper").find("h4, .content-options, .demo-content-list, .import-buttons").slideUp(); 
        };

        this.showOptions = function(){
            $(".import-wrapper").find("h4, .content-options, .demo-content-list, .import-buttons").slideDown();
            $(".import-message").remove();
        };
       
        this.addTask = function (task, text) {            
            self.tasks.push(task);
            var id = self.tasks.length;
            self.progress.append("<li class=\"task-"+id+"\"><span class=\"status\"></span> " + text + "</li>");            
            self.importContent(id, task);            
        };

        this.importContent = function (id, task) {
            setTimeout(function () {
                self.progress.find("li.task-"+id).find(".status").html("<span class=\"w-loader\"></span>");
                self.beginImport(task, function () {
                    self.success(id);
                }, function () {
                    self.fail(id);
                });
            }, 1000 * id);

        };

        this.beginImport = function (task, success, fail) {

            var requests = task.split(',');            

            var responses = [];
            
            $.each(requests, function(i, v){    
                if(!v) return;            
                var data = { action: "overlap_importer", demo: self.demoId, demo_type: self.demoType, type: v };
                $.ajax({
                    url: self.import_url,
                    data: data
                }).done(function (response) {

                    var responseObj = jQuery.parseJSON(response);                   

                    responses.push(responseObj.code);

                    if(responses.length == requests.length){

                        if (responseObj.code == "1") {
                            if (typeof success == "function") {
                                success();
                            }
                        } else {
                            if (typeof fail == "function") {
                                fail();
                            }
                        }

                        self.steps.push(responseObj.code);

                        if( self.steps.length == self.tasks.length ){
                            $(".panel-cancel").remove();
                            self.showOptions();
                        }

                    }

                }).fail(function () {
                    if (typeof fail == "function") {
                        fail();
                    }
                    console.log("Import fail.");
                });
            });
            

        };

        this.importSettings = function () {

            self.beginImport("settings", function () {

                setTimeout(function () {

                    var demoType = "multi-pages";
                    if(self.demoId == '6'){
                        demoType = "one-page";
                    }

                    var optionsUrl = self.data_dir + "/" + demoType + "/" + self.demoId + "/theme_options.txt";
                    if (optionsUrl) {
                        $("#import-link-value").val(optionsUrl);
                        var formOptions = $("#redux-form-wrapper");
                        var hiddenAction = $("<input type=\"hidden\" id=\"import-hidden\" />");
                        hiddenAction.attr("name", $("#redux-import").attr("name")).val("true");
                        formOptions.append(hiddenAction);
                        formOptions.submit();
                    }

                }, 1000);   
                             
            });

        };

        this.success = function (name) {
            self.progress.find("li.task-"+name).find(".status").html("<i class=\"el el-ok-sign\"></i>");
        }

        this.fail = function (name) {
            self.progress.find("li.task-"+name).find(".status").html("<i class=\"el el-remove-sign\"></i>");
        };

        $(document).ready(function () {

            self.initImporter();

        });

    }

    new overlapImporter();


})(jQuery);
