var template = {
    init: function(settings) {
        
        template.config = {
            bodyView: settings.bodyView
        };
        
        $.extend(template.config, settings);
        
        this.getResults();

        //initialize pagination
        template.config.bodyView.on("click",".page-link",function(e) {
        	e.preventDefault();
        	template.getResults($(this).attr("href"));
        });

        //create producte template
        template.config.bodyView.on("click",".create-product-template-btn",function(e) {
            template.openForm();
        });

        // delete product templates
        template.config.bodyView.on("click",".btn-delete-template",function(e) {
            if(!confirm("Are you sure you want to delete record?")) {
                return false;
            }else {
                template.deleteRecord($(this));
            }
        });

        $(document).on("click",".imgAdd",function(e) {
            $(this).closest(".row").find('.imgAdd').before('<div class="col-sm-3 imgUp"><div class="imagePreview"></div><label class="btn btn-primary">Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width:0px;height:0px;overflow:hidden;"></label><i class="fa fa-times del"></i></div>');
        });

        $(document).on("click","i.del",function(e) {
            $(this).parent().remove();
        });



        $(document).on("change",".uploadFile", function() {
            var uploadFile = $(this);
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
     
            if (/^image/.test( files[0].type)){ // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file
     
                reader.onloadend = function(){ // set image data as background of div
                    //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
                    uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url("+this.result+")");
                }
            }
        });

        $(document).on("click",".create-product-template",function(e){
            if ($("#product-template-from").valid()) {
                template.submitForm($(this));
            }
        });
    },
    validationRule : function() {
         $(document).find("#product-template-from").validate({
            rules: {
                name     : "required",
            },
            messages: {
                name     : "Template name is required",
            }
        })
    },
    getResults: function(href) {
    	var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/templates/response",
            method: "get",
        }
        this.sendAjax(_z, "showResults");
    },
    showResults : function(response) {
    
    	var addProductTpl = $.templates("#product-templates-result-block");
        var tplHtml       = addProductTpl.render(response);
    	template.config.bodyView.find("#page-view-result").html(tplHtml);

    },
    openForm : function() {
        var addProductTpl = $.templates("#product-templates-create-block");
        var tplHtml       = addProductTpl.render({});
        $("#display-area").html(tplHtml);
        $("#product-template-create-modal").modal("show");
        template.validationRule();
        $( ".show-product-image" ).sortable();
    },
    selectProductId : function(response) {
         $('.show-product-image').html(response.data);
    },
    submitForm : function(ele) {
        var form = ele.closest("#product-template-create-modal").find("form");
        var formData = new FormData(form[0]);
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/templates/create",
            method: "post",
            data : formData
        }
        this.sendFormDataAjax(_z, "closeForm");
    },
    closeForm : function(response) {
        if(response.code == 1) {
            location.reload();
        }
    },
    deleteRecord : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/templates/destroy/"+ele.data("id"),
            method: "get",
        }
        this.sendAjax(_z, 'getResults', true);
    }
}

$.extend(template, common);