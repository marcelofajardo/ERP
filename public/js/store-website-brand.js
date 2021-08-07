var page = {
    init: function(settings) {
        
        page.config = {
            bodyView: settings.bodyView
        };

        $.extend(page.config, settings);
        
        page.config.mainUrl = page.config.baseUrl + "/store-website/brand";
        
        page.config.bodyView.on("click",".push-brand",function(e) {
            page.pushBrand($(this));
        });

        

        //initialize pagination
        page.config.bodyView.on("click",".page-link",function(e) {
        	e.preventDefault();
        	page.getResults($(this).attr("href"));
        });

    },
    pushBrand : function(ele) {
        var brand = ele.data("brand");
        var store = ele.data("sw");
        var _z = {
            url: this.config.mainUrl + "/push-to-store",
            method: "post",
            data : {
                brand : brand,
                store : store, 
                active : ele.is(":checked")
            },
            beforeSend : function() {
                $("#loading-image").show();
            },
            complete : function() {
                $("#loading-image").hide();
            }
        }
        this.sendAjax(_z, "doneAjax",ele);

    },
    doneAjax :function(response,ele) {
        $("#loading-image").hide();
        if(response.code == 200) {
            toastr["success"](response.message,"");
        }else {
            toastr["error"](response.message,"");
            ele.prop("checked",false);
        }
    }
}

$.extend(page, common);