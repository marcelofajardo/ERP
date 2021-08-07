var page = {
    init: function(settings) {
        
        page.config = {
            bodyView: settings.bodyView
        };

        $.extend(page.config, settings);
        
        page.config.mainUrl = page.config.baseUrl + "/country-duty";

        page.config.bodyView.on("click",".btn-search-action",function(e) {
            e.preventDefault();
            page.searchDutyCriteria($(this));
        });

        page.config.bodyView.on("click",".btn-create-group-modal",function(e) {
            e.preventDefault();
            page.openCountryGroupModal($(this));
        });

        $(".common-modal").on("click",".create-country-group-btn",function(e) {
            e.preventDefault();
            page.createCountryGroup($(this));
        });
    },
    searchDutyCriteria : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl+ "/search",
            method: "post",
            data : ele.closest("form").serialize(),
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
            toastr["error"](response.message);
        }
    },
    openCountryGroupModal: function() {
        var createWebTemplate = $.templates("#template-create-country-group-form");
        var tplHtml = createWebTemplate.render({});
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },
    createCountryGroup : function(ele) {
        var groups = [];
        var form = ele.closest("form");
        var group_name = form.find(".group-name").val();

        $(".duty-rate-ckbx").each(function(k,v){
            var $this = $(v);
            if($this.is(":checked")) {
                groups.push({
                    "hs-code": $this.data("hs-code"),
                    "origin": $this.data("origin"),
                    "destination": $this.data("destination"),
                    "duty-val": $this.data("duty-val"),
                    "vat-val": $this.data("vat-val"),
                    "vat-rate": $this.data("vat-rate"),
                    "duty-rate": $this.data("duty-rate"),
                    "total": $this.data("total"),
                    "currency-origin": $this.data("currency-origin"),
                    "currency-destination": $this.data("currency-destination"),
                });
            }
        });

        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl+ "/save-country-group",
            method: "post",
            data : {name : group_name , groups : groups},
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, 'afterSaveContryGroup');
    },

    afterSaveContryGroup : function(response) {
        if(response.code == 200) {
            $("#loading-image").hide();
            toastr["success"](response.message);
        }else{
            $("#loading-image").show();
            toastr["error"](response.message);
        }
    }
}

$.extend(page, common);