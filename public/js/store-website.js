var page = {
    init: function(settings) {
        
        page.config = {
            bodyView: settings.bodyView
        };
        
        $.extend(page.config, settings);
        
        this.getResults();

        //initialize pagination
        page.config.bodyView.on("click",".page-link",function(e) {
        	e.preventDefault();
        	page.getResults($(this).attr("href"));
        });

        page.config.bodyView.on("click",".btn-search-action",function(e) {
            e.preventDefault();
            page.getResults();
        });

        page.config.bodyView.on("click",".btn-add-action",function(e) {
            e.preventDefault();
            page.createRecord();
        });

        // delete product templates
        page.config.bodyView.on("click",".btn-delete-template",function(e) {
            if(!confirm("Are you sure you want to delete record?")) {
                return false;
            }else {
                page.deleteRecord($(this));
            }
        });

        page.config.bodyView.on("click",".btn-edit-template",function(e) {
            page.editRecord($(this));
        });

        page.config.bodyView.on("click",".btn-edit-cancellation-template",function(e) {
            page.editCancellationRecord($(this));
        });

        page.config.bodyView.on("click",".btn-attach-category",function(e) {
            page.attachCategory($(this).data("id"));
        });

        $(".common-modal").on("click",".submit-store-site",function() {
            page.submitFormSite($(this));
        });

        $(".common-modal").on("click",".submit-store-site-cancellation",function() {
            page.submitFormSiteCancellation($(this));
        });

        $(".common-modal").on("click",".btn-edit-magento-user",function() {
            page.submitMagentoUserForm($(this));
        });

        $(".common-modal").on("click",".btn-add-magento-user",function() {
            page.AddMagentoUserForm($(this));
        });

        $(".common-modal").on("click",".btn-delete-magento-user",function() {
            page.deleteMagentoUserForm($(this));
        });

        $(".common-modal").on("click",".add-attached-category",function(e) {
            e.preventDefault();
            page.submitCategory($(this));
        });

        $(".common-modal").on("click",".btn-delete-store-website-category",function(e) {
            e.preventDefault();
            page.deleteCategory($(this));
        });

        page.config.bodyView.on("click",".btn-attach-brands",function(e) {
            e.preventDefault();
            page.attachBrands($(this).data("id"));
        });

        page.config.bodyView.on("click",".show-facebook-remarks",function(e) {
            e.preventDefault();
            page.showRemarks("facebook_remarks",$(this).data("id"),$(this).data("value"));
        });

        $(".common-modal").on("click",".update-remark-btn",function(e) {
            e.preventDefault();
            page.submitRemarks($(this));
        });

        page.config.bodyView.on("click",".show-instagram-remarks",function(e) {
            e.preventDefault();
            page.showRemarks("instagram_remarks",$(this).data("id"),$(this).data("value"));
        });

        page.config.bodyView.on("click",".btn-seo-format",function(e) {
            e.preventDefault();
            //page.showRemarks("instagram_remarks",$(this).data("id"),$(this).data("value"));
            page.editSeoRecord($(this));
        });


        

        $(".common-modal").on("click",".add-attached-brands",function(e) {
            e.preventDefault();
            page.submitAttachedBrands($(this));
        });

        $(".common-modal").on("click",".btn-delete-store-website-brand",function(e) {
            e.preventDefault();
            $cof = confirm("Are you sure you want to delete ?");
            if($cof == true) {
                page.deleteAttachedBrands($(this));
            }
        });

        $(".common-modal").on("click",".update-seo-format",function(e) {
            e.preventDefault();
            page.updateSeoFormat($(this));
        });

        

        $(document).on("change","select.select-searchable",function() {
            // now need to call for getting child 
            var id = $(this).val();
            page.getChildCategories(id);

        });


        $(document).on("click",".btn-show-password",function() {
             var block = $(this).closest(".subMagentoUser");
             var password = block.find(".user-password");
             const type = password.attr('type') === 'password' ? 'text' : 'password';
                          password.attr('type', type);
        });

        $(document).on("click",".btn-copy-password",function() {
             var block = $(this).closest(".subMagentoUser");
             var password = block.find(".user-password");
             
              var $temp = $("<input>");
              $("body").append($temp);
              $temp.val(password.val()).select();
              document.execCommand("copy");
              $temp.remove();

              alert("Copied!");
        });

        $(document).on("click",".generate-pem-file",function() {
            page.openGenerateFile();
        });

        $(document).on("click",".open-store-magento-user-lising",function(href) {
            page.openUserListing();
            
        });
    },
    openUserListing: function() {
        var _z = {
            url: this.config.baseUrl + "/store-website/magento-user-lising",
            method: "get",
        }
        this.sendAjax(_z, 'showUserLiting');
    },
    showUserLiting : function(response) {
        if(response.code == 200) {
            var createWebTemplate = $.templates("#template-magento-user-lising");
            var tplHtml = createWebTemplate.render(response);
            var common =  $(".common-modal");
                common.find(".modal-dialog").html(tplHtml);
                common.modal("show");
        }        
              
    },
    validationRule : function(response) {
         $(document).find("#product-template-from").validate({
            rules: {
                name     : "required",
            },
            messages: {
                name     : "Template name is required",
            }
        })
    },
    loadFirst: function() {
        var _z = {
            url: this.config.baseUrl + "/store-website/records",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults");
    },
    getResults: function(href) {
    	var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/store-website/records",
            method: "get",
            data : $(".message-search-handler").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults");
    },
    showResults : function(response) {
        $("#loading-image").hide();
    	var addProductTpl = $.templates("#template-result-block");
        var tplHtml       = addProductTpl.render(response);

        $(".count-text").html("("+response.total+")");

    	page.config.bodyView.find("#page-view-result").html(tplHtml);

    }
    ,
    deleteRecord : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/store-website/"+ele.data("id")+"/delete",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, 'deleteResults');
    },
    deleteResults : function(response) {
        if(response.code == 200){
            this.getResults();
            toastr['success']('Message deleted successfully', 'success');
        }else{
            toastr['error']('Oops.something went wrong', 'error');
        }

    },
    createRecord : function(response) {
        var createWebTemplate = $.templates("#template-create-website");
        var tplHtml = createWebTemplate.render({data:{}});
        
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },

    editRecord : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/store-website/"+ele.data("id")+"/edit",
            method: "get",
        }
        this.sendAjax(_z, 'editResult');
    },

    editResult : function(response) {
        var createWebTemplate = $.templates("#template-create-website");
        var tplHtml = createWebTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },

    editSeoRecord : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/store-website/"+ele.data("id")+"/seo-format",
            method: "get",
        }
        this.sendAjax(_z, 'afterSeoFormat');
    },

    afterSeoFormat: function(response) {
        var createWebTemplate = $.templates("#template-edit-seo");
        var tplHtml = createWebTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },

    updateSeoFormat : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/store-website/"+ele.data("id")+"/seo-format/save",
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "afterUpdateSeo");
    },

    afterUpdateSeo : function(response) {
        if(response.code  == 200) {
            $("#loading-image").hide();
            $(".common-modal").modal("hide");
            toastr["success"]("Added successfully","");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },

    editCancellationRecord : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/store-website/"+ele.data("id")+"/edit-cancellation",
            method: "get",
        }
        this.sendAjax(_z, 'editCancellationResult');
    },
    editCancellationResult: function(response) {
        var createWebTemplate = $.templates("#template-create-website-cancellation");
        var tplHtml = createWebTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },
    submitFormSite : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/store-website/save",
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveSite");
    },
    submitFormSiteCancellation : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/store-website/save-cancellation",
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveSiteCancellation");
    },

    submitMagentoUserForm : function(ele) {
        var username = ele.parents('.subMagentoUser').find('.userName').val();
        var userEmail = ele.parents('.subMagentoUser').find('.userEmail').val();
        var firstName = ele.parents('.subMagentoUser').find('.firstName').val();
        var lastName = ele.parents('.subMagentoUser').find('.lastName').val();
        
        var password = ele.parents('.subMagentoUser').find('.user-password').val();
        var websitemode = ele.parents('.subMagentoUser').find('.websiteMode').val();
        
       
        //var password = ele.parent().parent().children('.sub-pass').children('.user-password').val();
       
        var store_id = $('#store_website_id').val();
        
        //use in user-lising-popup
        if(!store_id){
            store_id = ele.parents('.subMagentoUser').find('.store_website_id').val();
        }


        var store_website_userid = ele.attr('data-id');
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/store-website/save-user-in-magento",
            method: "post",
            data : {
                _token:$('meta[name="csrf-token"]').attr('content'),
                websitemode:websitemode,
                username:username,
                userEmail:userEmail,
                firstName:firstName,
                lastName:lastName,
                password:password,
                store_id:store_id,
                store_website_userid:store_website_userid
            },
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveSite");
    },
    AddMagentoUserForm : function(ele) {
        var html = '<div class="subMagentoUser" style="border:1px solid #ccc;padding: 15px;margin-bottom:5px">'+
                      '<div class="form-group">'+
                        '<div class="row">'+
                            '<div class="col-sm-6">'+
                                '<label for="username">Username</label>'+
                                '<input type="text" name="username" value="" class="form-control userName" id="username" placeholder="Enter Username">'+
                            '</div>'+
                            '<div class="col-sm-6">'+
                                '<label for="userEmail">Email</label>'+
                                '<input type="email" name="userEmail" value="" class="form-control userEmail" id="userEmail" placeholder="Enter Email">'+
                            '</div>'+
                        '</div>'+
                      '</div>'+
                      '<div class="form-group">'+
                        '<div class="row">'+
                            '<div class="col-sm-6">'+
                                '<label for="firstName">First Name</label>'+
                                '<input type="text" name="firstName" value="" class="form-control firstName" id="firstName" placeholder="Enter First Name">'+
                            '</div>'+
                            '<div class="col-sm-6">'+
                                '<label for="lastName">Last Name</label>'+
                                '<input type="text" name="lastName" value="" class="form-control lastName" id="lastName" placeholder="Enter Last Name">'+
                            '</div>'+
                        '</div>'+
                      '</div>'+
                      '<div class="form-group">'+
                        '<div class="row">'+
                            '<div class="col-sm-6">'+
                                '<labelfor="password">Password</label>'+
                                '<input type="password" name="password" value="" class="form-control user-password" id="password" placeholder="Enter Password">'+
                             '</div>'+
                            '<div class="col-sm-6">'+
                                '<label for="website_mode">Website Mode</label>'+
                                '<select name="website_mode" id="website_mode" class="form-control websiteMode"><option value="production">Production</option><option value="staging">Staging</option></select>'+
                             '</div>'+    
                        '</div>'+
                      '</div>'+
                      '<div class="form-group">'+
                        '<div class="row">'+
                             '<div class="col-sm-5">'+
                                '<button type="button" data-id="" class="btn btn-show-password btn-sm" style="border:1px solid">'+
                                    '<i class="fa fa-eye" aria-hidden="true"></i>'+
                                '</button>'+
                                '<button type="button" data-id="" class="btn btn-copy-password btn-sm" style="border:1px solid">'+
                                    '<i class="fa fa-clone" aria-hidden="true"></i>'+
                                '</button>'+
                                '<button type="button" data-id="" class="btn btn-edit-magento-user btn-sm" style="border:1px solid">'+
                                    '<i class="fa fa-check" aria-hidden="true"></i>'+
                                '</button>'+
                                '<button type="button" data-id="" class="btn btn-delete-magento-user btn-sm" style="border:1px solid">'+
                                    '<i class="fa fa-trash" aria-hidden="true"></i>'+
                                '</button>'+
                             '</div>'+
                         '</div>'+
                      '</div>'+
                  '</div>';
        $('.MainMagentoUser').append(html);

    },

    deleteMagentoUserForm : function(ele) {
        var store_website_userid = ele.attr('data-id');
        if(store_website_userid != '') {
            var _z = {
                url: (typeof href != "undefined") ? href : this.config.baseUrl + "/store-website/delete-user-in-magento",
                method: "post",
                data : {
                    _token:$('meta[name="csrf-token"]').attr('content'),
                    store_website_userid:store_website_userid,
                },
                beforeSend : function() {
                    $("#loading-image").show();
                }
            }
        }
        ele.parents('.subMagentoUser').remove();
        this.sendAjax(_z, "saveSite");
    },
    
    assignSelect2 : function () {
        var selectList = $("select.select-searchable");
            if(selectList.length > 0) {
                $.each(selectList,function(k,v){
                    var element = $(v);
                    if(!element.hasClass("select2-hidden-accessible")){
                        element.select2({tags:true,width:"100%"});
                    }
                });
            }
    },
    saveSite : function(response) {
        if(response.code  == 200) {
            page.loadFirst();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    saveSiteCancellation : function(response) {
        if(response.code  == 200) {
            //page.loadFirst();
            $("#loading-image").hide();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    attachCategory : function(id) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/store-website/"+id+"/attached-category",
            method: "get",
        }
        this.sendAjax(_z, 'showAttachedCategory');
    },
    showAttachedCategory : function (response) {
        $("#loading-image").hide();
        if (response.code == 200) {
            var createWebTemplate = $.templates("#template-attached-category");
            var tplHtml = createWebTemplate.render(response);
            var common =  $(".common-modal");
                common.find(".modal-dialog").html(tplHtml);
                page.assignSelect2(); 
                common.modal("show");      
        }
    },
    submitCategory : function(ele) {
        var website_id = ele.closest("form").find('input[name="store_website_id"]').val();
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/store-website/"+website_id+"/attached-category",
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, 'afterSubmitCategory');
    },
    afterSubmitCategory : function(response) {
        if(response.code  == 200) {
            page.attachCategory(response.data.store_website_id);
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    deleteCategory : function(ele) {
        
        var storeWebsiteId = ele.data("store-website-id");
        var id = ele.data("id");

        var _z = {
            url: this.config.baseUrl + "/store-website/"+storeWebsiteId+"/attached-category/"+id+"/delete",
            method: "get",
        }

        this.sendAjax(_z, 'deleteCategoryResponse', ele);
    },
    deleteCategoryResponse: function(response,ele) {
        if(response.code == 200) {
            ele.closest("tr").remove();
        }
    },
    attachBrands : function (id) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/store-website/"+id+"/attached-brand",
            method: "get",
        }
        this.sendAjax(_z, 'showAttachedBrands');
    }, 
    showAttachedBrands : function (response) {
        $("#loading-image").hide();
        if (response.code == 200) {
            var createWebTemplate = $.templates("#template-attached-brands");
            var tplHtml = createWebTemplate.render(response);
            var common =  $(".common-modal");
                common.find(".modal-dialog").html(tplHtml);
                page.assignSelect2(); 
                common.modal("show");      
        }
    },
    submitAttachedBrands : function(ele) {
        var website_id = ele.closest("form").find('input[name="store_website_id"]').val();
        var _z = {
            url: this.config.baseUrl + "/store-website/"+website_id+"/attached-brand",
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, 'afterSubmitAttachedBrands');
    },
    afterSubmitAttachedBrands : function(response) {
        if(response.code  == 200) {
            page.attachBrands(response.data.store_website_id);
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    deleteAttachedBrands : function(ele) {
        var storeWebsiteId = ele.data("store-website-id");
        var id = ele.data("id");
        var _z = {
            url: this.config.baseUrl + "/store-website/"+storeWebsiteId+"/attached-brand/"+id+"/delete",
            method: "get",
        }
        this.sendAjax(_z, 'deleteBrandResponse', ele);
    },
    deleteBrandResponse : function(response, ele) {
        if(response.code == 200) {
            ele.closest("tr").remove();
        } 
    },
    getChildCategories : function(id) {
        var _z = {
            url: this.config.baseUrl + "/store-website/"+id+"/child-categories",
            method: "get",
        }
        this.sendAjax(_z, 'showChildCategoriesFrom');
    },
    showChildCategoriesFrom : function(response) {
        if(response.code == 200) {
            var template = $.templates("#template-category-list");
            var tplHtml = template.render(response);
            $(".preview-category").html(tplHtml);

            $(".preview-category").on("click",".btn-delete-preview-category",function() {
                $(this).closest("tr").remove();
            });

            $(".preview-category").on("click",".select-all-preview-category",function() {
                var table = $(this).closest("table");
                checkBoxes = table.find(".preview-checkbox");
                checkBoxes.prop("checked", !checkBoxes.prop("checked"));
            });

            $(".preview-category").on('click','.save-preview-categories',function() {
                page.storeMultipleCategories($(this));
            });
        }
    },
    storeMultipleCategories : function(ele) {
        var website_id = ele.closest(".modal-body").find('input[name="store_website_id"]').val();
        var categories = [];
        var selectedcategories      = ele.closest(".modal-body").find(".preview-checkbox:checked");
        if(selectedcategories.length > 0) {
            $.each(selectedcategories,function(k,v) {
                categories.push($(v).val());
            });
        }
        var _z = {
            url: this.config.baseUrl + "/store-website/"+website_id+"/attached-categories",
            method: "post",
            data : {
                _token:$('meta[name="csrf-token"]').attr('content'),
                website_id:website_id,
                categories:categories,
            },
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, 'afterMultipleCategories');
    },
    afterMultipleCategories : function(response) {
        if(response.code == 200) {
            page.attachCategory(response.data.store_website_id);
        }
    },
    showRemarks : function(field,id, remarks) {
        $("#loading-image").hide();
        var createWebTemplate = $.templates("#template-update-remarks");
        var tplHtml = createWebTemplate.render({
            "field":field,
            "id":id,
            "remarks":remarks
        });
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml);
            common.modal("show");
    },
    submitRemarks : function(ele) {
        var website_id = ele.closest("form").find(".frm_store_website_id").val();
        var _z = {
            url: this.config.baseUrl + "/store-website/"+website_id+"/submit-social-remarks",
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, 'afterSubmitRemarks');
    },
    afterSubmitRemarks : function(response) {
        $("#loading-image").hide();
        if(response.code == 200) {
            $(".common-modal").modal("hide");
            toastr['success'](response.message, 'success');
            page.loadFirst();
        }else{
            toastr['error'](response.message, 'error');   
        }
    },
    openGenerateFile : function() {

        var createWebTemplate = $.templates("#template-generate-file");
        var tplHtml = createWebTemplate.render({});
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml);
            common.modal("show");
              
    },
}

$.extend(page, common);