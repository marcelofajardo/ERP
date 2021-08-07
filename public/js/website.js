var page = {
    init: function(settings) {
        
        page.config = {
            bodyView: settings.bodyView
        };

        settings.baseUrl += "/store-website";
        
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

        $(".common-modal").on("click",".submit-store-site",function() {
            page.submitFormSite($(this));
        });

        page.config.bodyView.on("click",".btn-push",function(e) {
            page.push($(this));
        });

        $(document).on("click",".create-default-stores",function(e) {
            page.createDefaultStores($(this));
        });

        $(document).on("click",".move-stores",function(e) {
            page.moveStores($(this));
        });


        $(document).on("click",".copy-websites",function(e) {
            page.copyWebsites($(this));
        });

        page.config.bodyView.on("click",".btn-copy-template",function(e) {
            $("#copy-website-modal").find("#copy-website-field").val($(this).data("id"));
            $("#copy-website-modal").modal("show");
        });

        $(document).on("click",".copy-stores",function(e) {
            page.copyStores($(this));
        });

        $(document).on("click",".change-status",function(e) {
            page.changeStatus($(this));
        });

        $(document).on("click",".change-is_price_ovveride",function(e) {
            page.changePriceOvveride($(this));
        });

        $(".select2").select2({tags:true});

        $(document).on("click",".check-all",function(e) {
           $(".groups").trigger("click");
        });

        $(document).on("click",".push-stores",function(e) {
            page.pushStores($(this));
        });

        $(document).on("click",".copy-website-struct",function(e) {
            page.copyWebsitesStruct($(this));
        });

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
            url: this.config.baseUrl + "/websites/records",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults");
    },
    getResults: function(href) {
    	var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/websites/records",
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
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/websites/"+ele.data("id")+"/delete",
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
            toastr['success']('Request deleted successfully', 'success');
            location.reload();
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
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/websites/"+ele.data("id")+"/edit",
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

            common.find(".select-2").select2({tags:true});
    },

    submitFormSite : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/websites/save",
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
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
    push : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/websites/"+ele.data("id")+"/push",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, 'afterPush');
    },
    afterPush : function(response) {
        $("#loading-image").hide();
        if(response.code  == 200) {
            toastr["success"](response.message,"");
            location.reload();
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    createDefaultStores : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/websites/create-default-stores",
            method: "post",
            data : {
                store_website_id : $(".default-store-website-select").val(),
                country_codes : $(".default-store-country-code").val()
            }
        }
        this.sendAjax(_z, 'afterCreateDefaultStores');
    },
    afterCreateDefaultStores : function(response) {
        if(response.code  == 200) {
            toastr["success"](response.message,"");
            location.reload();
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    moveStores : function(ele) {

        var groups = [];
        var checkedGroups = $(".groups:checked");

        $.each(checkedGroups,function(k,v) {
            groups.push($(v).val());
        });

        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/websites/move-stores",
            method: "post",
            data : {
                store_website_id : $(".move-store-website-select").val(),
                ids : groups,
                group_name : $(".move-store-group-change").val(),
            }
        }

        this.sendAjax(_z, 'afterMoveStores');
    },
    afterMoveStores : function(response) {
        if(response.code  == 200) {
            toastr["success"](response.message,"");
            location.reload();
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    copyStores : function(ele) {

        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/websites/copy-stores",
            method: "post",
            data : {
                store_website_id : $(".copy-store-website-select").val(),
                copy_id : $("#copy-website-field").val(),
            }
        }

        this.sendAjax(_z, 'afterMoveStores');
    },
    afterMoveStores : function(response) {
        if(response.code  == 200) {
            toastr["success"](response.message,"");
            location.reload();
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    changeStatus : function(ele) {

        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/websites/change-status",
            method: "post",
            data : {
                id : ele.data("id"),
                value : ele.data("value"),
            }
        }

        this.sendAjax(_z, 'afterChangeStatus');
    },
    afterChangeStatus : function(response) {
        if(response.code  == 200) {
            toastr["success"](response.message,"");
            location.reload();
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    copyWebsites : function(ele) {

        var ids = [];
        var websites = $(".groups:checked");
            $.each(websites,function(k,r){
                ids.push($(r).val());
            });
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/websites/copy-websites",
            method: "post",
            data : {
                store_website_id : $(".copy-store-websites-select").val(),
                ids : ids,
            }
        }

        this.sendAjax(_z, 'afterCopyWebsites');
    },
    afterCopyWebsites : function(response) {
        if(response.code  == 200) {
            toastr["success"](response.message,"");
            location.reload();
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },

    pushStores : function(ele) {

        var id = $(".push-website-store-id").val();

        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/websites/"+id+"/push-stores",
            method: "get",
            data : {}
        }

        this.sendAjax(_z, 'afterPushStores');

    }, 

    afterPushStores : function(response) {
        if(response.code == 200) {
            toastr["success"](response.message,"");
            location.reload();
        }

    },
    copyWebsitesStruct : function (ele) {
        var id = $(".copy-website-id").val();
        var toStoreWebsite = $(".to-copy-website-id").val();
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/websites/"+id+"/copy-website-struct",
            method: "get",
            data : {to_store_website_id : toStoreWebsite}
        }

        this.sendAjax(_z, 'afterCopyWebsitesStruct');
    },
    afterCopyWebsitesStruct  : function (response) {
        if(response.code == 200) {
            toastr["success"](response.message,"");
            location.reload();
        }
    },
    changePriceOvveride : function(ele) {

        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/websites/change-price-ovveride",
            method: "post",
            data : {
                id : ele.data("id"),
                value : ele.data("value"),
            }
        }

        this.sendAjax(_z, 'afterChangePriceOvveride',ele);
    },
    afterChangePriceOvveride : function(response,ele) {
        if(response.code  == 200) {
            toastr["success"](response.message,"");
            if(response.data.is_price_ovveride == 1) {
                var html = `<span class="badge badge-success change-is_price_ovveride" data-id="`+response.data.id+`" data-value="0">Yes</span>`;
            }else{
                var html = `<span class="badge badge-danger change-is_price_ovveride" data-id="`+response.data.id+`" data-value="1">No</span>`;
            }
            ele.closest("td").html(html);
            
            //location.reload();
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    }
}

$.extend(page, common);