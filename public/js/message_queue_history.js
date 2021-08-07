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

        page.config.bodyView.on("click",".message_load",function(e) {
            e.preventDefault();
            page.getLoadData("message",$(this).data("logid"));
        });

        page.config.bodyView.on("click",".request_data_load",function(e) {
            e.preventDefault();
            page.getLoadData("request_data",$(this).data("logid"));
        });

        page.config.bodyView.on("click",".response_data_load",function(e) {
            e.preventDefault();
            page.getLoadData("response_data",$(this).data("logid"));
        });
    },
    loadFirst: function() {
        var _z = {
            url: this.config.baseUrl + "/message-queue-history/records",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults");
    },
    getResults: function(href) {
    	var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/message-queue-history/records",
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

    },
    getLoadData : function(elename,id) {
        var _z = {
            url: this.config.baseUrl + "/message-queue-history/loadfiled",
            method: "post",
            data : {
                _token:$('meta[name="csrf-token"]').attr('content'),
                field:elename,
                id:id,
            },
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, 'showLoadData');
    },
    showLoadData : function(response) {
        $("#loading-image").hide();
        var createWebTemplate = $.templates("#template-load-data");
        var tplHtml = createWebTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml);
            common.modal("show");
    },
}

$.extend(page, common);