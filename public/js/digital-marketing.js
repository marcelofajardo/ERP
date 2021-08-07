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

        $(".common-modal").on("click",".submit-platform",function() {
            page.submitPlatform($(this));
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

        page.config.bodyView.on("click",".btn-add-components",function(e) {
            page.addComponents($(this));
        });

        page.config.bodyView.on("click",".get_Files",function(e) {
            page.addFilesComponent($(this));
        });

        $(".common-modal").on("click",".update-components-btn",function(e) {
            e.preventDefault();
            page.submitComponents($(this));
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
            url: this.config.baseUrl + "/digital-marketing/records",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults");
    },
    getResults: function(href) {
    	var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/digital-marketing/records",
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
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/digital-marketing/"+ele.data("id")+"/delete",
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
        var createWebTemplate = $.templates("#template-create-platform");
        var tplHtml = createWebTemplate.render({data:{}});
        
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },

    editRecord : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/digital-marketing/"+ele.data("id")+"/edit",
            method: "get",
        }
        this.sendAjax(_z, 'editResult');
    },

    editResult : function(response) {
        var createWebTemplate = $.templates("#template-create-platform");
        var tplHtml = createWebTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },
    submitPlatform : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/digital-marketing/save",
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
    addComponents : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/digital-marketing/"+ele.data("id")+"/components",
            method: "get",
        }
        this.sendAjax(_z, 'afterResponsecomponents');
    },
    afterResponsecomponents : function(response) {
        var createWebTemplate = $.templates("#template-create-components");
        var tplHtml = createWebTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
        $(".select2-components-tags").select2({tags : true});
    },
    addFilesComponent: function(ele){
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/digital-marketing/"+ele.data("id")+"/files",
            method: "get",
        }
        this.sendAjax(_z, 'afterResponsefilecomponents');
    },
    afterResponsefilecomponents: function(response){
        console.log(response)
        var createWebTemplate = $.templates("#template-files-components");
        var tplHtml = createWebTemplate.render(response);
        var tr="";
        if(response.data.components.length > 0){
            $.each(response.data.components,function(i,e){
                tr += "<tr><td><a href='"+e.downloadUrl+"'>"+e.file_name+"</a></td><td>"+e.created_at+"</td><td>"+e.user+"</td></tr>";
            })
        }else{
            tr = "<tr><td colspan=3 style='text-align:center'>No Data</td></tr>"
        }
       
      $(".common-modal table tbody tr").remove()
     // $(".common-modal table tbody").html(tr)
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.find(".modal-dialog tbody").html(tr);
            common.modal("show");
    },
    submitComponents : function(ele) {
        var _z = {
            url: this.config.baseUrl + "/digital-marketing/"+ele.data("id")+"/components",
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "afterSubmitComponents");
    },
    afterSubmitComponents : function(response) {
        if(response.code  == 200) {
            page.loadFirst();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
}

$.extend(page, common);