var page = {
    init: function(settings) {
        
        page.config = {
            bodyView: settings.bodyView
        };

        $.extend(page.config, settings);

        $.views.helpers("inArray", function(k,v){
            if($.inArray(k,v) >= 0){
                return true;
            }
            return false;
        });
        
        page.config.mainUrl = page.config.baseUrl + "/country-group";
        
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

        page.config.bodyView.on("click",".add-country-group-btn",function(e) {
            e.preventDefault();
            page.createRecord();
        });

        page.config.bodyView.on("click",".btn-merge-status",function(e) {
            page.showMergeStatus();
        });
        
        $(".common-modal").on("click",".submit-form",function() {
            page.submitForm($(this));
        });

        $(".common-modal").on("click",".merge-status-btn",function(e) {
            e.preventDefault();
            page.submitForMerge($(this));
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
            url: this.config.mainUrl+"/records",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults");
    },
    getResults: function(href) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl+"/records",
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
            url: (typeof href != "undefined") ? href : this.config.mainUrl+ "/"+ele.data("id")+"/delete",
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
            toastr['success']('Group deleted successfully', 'success');
        }else{
            $("#loading-image").hide();
            toastr['error'](response.error, 'error');
        }

    },
    createRecord : function(response) {
        



        var createWebTemplate = $.templates("#template-create-form");
        var tplHtml = createWebTemplate.render({data:{}});
        
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 

            $(".option-mul-code-group").select2({tags:true});

            common.modal("show");
    },

    editRecord : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl+ "/"+ele.data("id")+"/edit",
            method: "get",
        }
        this.sendAjax(_z, 'editResult');
    },

    editResult : function(response) {
        var createWebTemplate = $.templates("#template-create-form");
        var tplHtml = createWebTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            $(".option-mul-code-group").select2({tags:true});
            common.modal("show");
    },

    submitForm : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl + "/save",
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
    showMergeStatus : function() {
        var createWebTemplate = $.templates("#template-merge-status");
        var tplHtml = createWebTemplate.render({});
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },
    submitForMerge : function(ele) {

        var selectedIds = [];
        $(".site-development-status-ckbx").each(function(k,v){
            if($(v).is(":checked")) {
                selectedIds.push($(v).val());
            }
        });

        var status = ele.closest("form").find(".merge-status").val();

        var _z = {
            url: this.config.mainUrl + "/merge-status",
            method: "post",
            data : {
                to_status : status,
                from_status : selectedIds
            },
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveSite");
    }
}

$.extend(page, common);