var page = {
    init: function(settings) {
        
        page.config = {
            bodyView: settings.bodyView
        };

        $.extend(page.config, settings);

        page.config.mainUrl = page.config.baseUrl + "/country-duty/list";

        page.getRecords();

        page.config.bodyView.on("click",".btn-search-action",function(e) {
            e.preventDefault();
            page.getRecords($(this));
        });

        page.config.bodyView.on("click",".group-copy-another-country",function(e) {
            e.preventDefault();
            page.copyGroup($(this));
        });

        page.config.bodyView.on("click",".group-delete-country",function(e) {
            e.preventDefault();
            if(confirm("Are you sure want to delete record ?")) {
                page.deleteGroup($(this));
            }
        });

        page.config.bodyView.on("keypress",".change-inline-field",function(e){
            console.log($(this));
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
               page.updateGroupField($(this)); 
            }
        });

        

        $(".common-modal").on("click",".submit-form",function(e) {
            e.preventDefault();
            page.submitForm($(this));
        });
        
    },
    getRecords : function(ele) {
        var form = $(".message-search-handler");
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl + "/records",
            method: "get",
            data:form.serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, 'displayResult');
    },
    displayResult :  function(response) {
        if(response.code == 200) {
            $("#loading-image").hide();
            var addProductTpl = $.templates("#template-result-block");
            var tplHtml       = addProductTpl.render(response);
            $(".count-text").html("("+response.total+")");
            page.config.bodyView.find("#page-view-result").html(tplHtml);
        }else{
            $("#loading-image").hide();
            toastr["error"](response.message);
        }
    },
    copyGroup : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl+ "/"+ele.data("id")+"/edit",
            method: "get",
        }
        this.sendAjax(_z, 'editResult');
    },

    editResult : function(response) {
        var createWebTemplate = $.templates("#template-duty-group");
        var tplHtml = createWebTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
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

        this.sendAjax(_z, "redirect");
    },
    redirect : function(response) {
        if(response.code  == 200) {
            page.getRecords();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    deleteGroup : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl+ "/"+ele.data("id")+"/delete",
            method: "get",
        }
        this.sendAjax(_z, 'redirect');
    },

    updateGroupField : function(ele) {

        var _z = {
            url: this.config.mainUrl + "/update-group-field",
            method: "post",
            data : {
                id : ele.data("field-master"),
                field : ele.data("field"),
                value : ele.val()
            },
            beforeSend : function() {
                $("#loading-image").show();
            }
        }

        this.sendAjax(_z, "redirect");
    }
}

$.extend(page, common);