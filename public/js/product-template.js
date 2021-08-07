var productTemplate = {
    init: function(settings) {
        
        productTemplate.config = {
            bodyView: settings.bodyView
        };
        
        $.extend(productTemplate.config, settings);
        
        this.getResults();

        //initialize pagination
        productTemplate.config.bodyView.on("click",".page-link",function(e) {
        	e.preventDefault();
        	productTemplate.getResults($(this).attr("href"));
        });

        //create producte template
        productTemplate.config.bodyView.on("click",".create-product-template-btn",function(e) {
            productTemplate.openForm();
        });

        // delete product templates
        productTemplate.config.bodyView.on("click",".btn-delete-template",function(e) {
            if(!confirm("Are you sure you want to delete record?")) {
                return false;
            }else {
                productTemplate.deleteRecord($(this));
            }
        });

        $(document).on("click",".imgAdd",function(e) {
            $(this).closest(".row").find('.imgAdd').before('<div class="col-sm-3 imgUp"><div class="imagePreview"></div><label class="btn btn-primary">Upload<input type="file" name="files[]" class="uploadFile img" value="Upload Photo" style="width:0px;height:0px;overflow:hidden;"></label><i class="fa fa-times del"></i></div>');
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
                var noOfImages = $("#product-template-from").find(".template_no option:selected").data('no-of-images');
                var uploadImage = 0;
                
                $("#product-template-from").find(".product_media_list").each(function(){
                    if ($(this).prop('checked')) {
                        uploadImage++;
                    }
                });
                
                $("#product-template-from").find(".uploadFile").each(function(){
                    if ($(this).val()) {
                        uploadImage++;
                    }
                });
                
                /*if (noOfImages != uploadImage) {
                    alert('Please '+noOfImages+' Image Upload');
                    return false;
                }*/

                productTemplate.submitForm($(this));

            }
        });
        if (productTemplate.config.isOpenCreateFrom == 'true') {
            productTemplate.openForm();
        }

    },
    validationRule : function() {
         $(document).find("#product-template-from").validate({
            rules: {
                template_no     : "required",
                //product_title   : "required",
                //brand_id        : "required",
                currency        : {
                                    required: true,
                                    minlength: 3,
                                    maxlength: 3
                                }
            },
            messages: {
                template_no     : "Please Select Template No",
                //product_title   : "Please Enter Product Title",
                //brand_id        : "Please Select Brand",
                currency        : {
                                    required: "Please Enter Currency",
                                    minlength: "Please Enter least {0} characters",
                                    maxlength: "Please Enter {0} characters"
                                }
            }
        })
    },
    getResults: function(href) {
        var search = $(".keyword-text").val();
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/product-templates/response?keyword="+search,
            method: "get",
        }
        this.sendAjax(_z, "showResults");
    },
    showResults : function(response) {
    
    	var addProductTpl = $.templates("#product-templates-result-block");
        var tplHtml       = addProductTpl.render(response);
    	productTemplate.config.bodyView.find("#page-view-result").html(tplHtml);

    },
    openForm : function() {
        var addProductTpl = $.templates("#product-templates-create-block");
        var tplHtml       = addProductTpl.render({});
        $("#display-area").html(tplHtml);
        $("#product-template-create-modal").modal("show");
        productTemplate.validationRule();
        $(".select-2-brand").select2({
            width:"100%"
        });
        $(".select2").select2({
            width:"100%",
            tags : true
        });
        productTemplate.productSearch();

        productTemplate.changeTemplateNo();
        $('.template_no').trigger('change');
        $(document).on("change",".ddl-select-product",function(e){
            var _z = {
                url: "/product-templates/select-product-id",
                data : {
                    'product_ids' : $(this).val()
                }
            }
            productTemplate.sendAjax(_z, "selectProductId");

            $(document).find("#product-template-from").find('.product_title').val('');
            $(document).find("#product-template-from").find('.select-2-brand').val('').trigger('change');
            
            $(this).find('option:selected').each(function(){
                if ($(this).data('brand')) {
                    $(document).find("#product-template-from").find('.select-2-brand').val($(this).data('brand')).trigger('change');
                }
                if ($(this).data('product-title')) {
                    $(document).find("#product-template-from").find('.product_title').val($(this).data('product-title'));
                }
                
            });
        });

        $( ".show-product-image" ).sortable();

        if (productTemplate.config.isOpenCreateFrom == 'true') {
            $('.ddl-select-product').trigger('change');
            /*
            $('.div-select-product').find('li').each(function() {
                $(this).find('span').before($(this).attr('title') + ' ');
            })*/
        }
    },
    selectProductId : function(response) {
         $('.show-product-image').html(response.data);
    },
    changeTemplateNo : function(response) {
        
        $(document).on("change",".template_no",function(e){
            var id = $(this).val();
            var html = '';
            var changeTemplateImage = $(this).find("option:selected").data('image');
            if (changeTemplateImage) {
                html = '<img src="'+changeTemplateImage+'" width="100%">';
            }
            $('.image_template_no').html(html);
        });
    },
    submitForm : function(ele) {
        var form = ele.closest("#product-template-create-modal").find("form");
        var formData = new FormData(form[0]);
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/product-templates/create",
            method: "post",
            data : formData
        }
        this.sendFormDataAjax(_z, "closeForm");
    },
    closeForm : function(response) {
        if(response.code == 1) {
            location.href = '/product-templates';
        }
        if( response.code == 0 ){
            toastr["error"](response.message,'Error');
        }
    },
    deleteRecord : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/product-templates/destroy/"+ele.data("id"),
            method: "get",
        }
        this.sendAjax(_z, 'getResults', true);
    },
    productSearch : function() {
        $('.ddl-select-product').select2({
            data: productTemplate.config.ddlSelectProduct,
            ajax: {
                url: '/productSearch',
                dataType: 'json',
                delay: 750,
                data: function (params) {
                    return {
                        q: params.term, // search term
                    };
                },
                processResults: function (data, params) {

                    params.page = params.page || 1;

                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
            },
            placeholder: 'Search for Product by id, Name, Sku',
            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: 2,
            width: '100%',
            templateResult: function (product) {
                if (product.loading) {
                    return product.sku;
                }

                if (product.sku) {
                    return "<p> <b>Id:</b> " + product.id + (product.name ? " <b>Name:</b> " + product.name : "") + " <b>Sku:</b> " + product.sku + " </p>";
                }

            },
            templateSelection: function (product, container) {
                $(product.element).attr('data-brand', product.brand);
                $(product.element).attr('data-product-title', product.name);
                return product.name;
            }
        });
    }
}

$.extend(productTemplate, common);