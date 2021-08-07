var page = {
    init: function(settings) {
        page.config = {
            bodyView: settings.bodyView
        };
        $.extend(page.config, settings);
        this.getResults();
        //initialize pagination
            /*page.config.bodyView.on("click", ".page-link", function(e) {
                e.preventDefault();
                page.getResults($(this).attr("href"));
            });*/
        page.config.bodyView.on("click", ".btn-search-action", function(e) {
            e.preventDefault();
            page.getResults();
        });

        //initialize pagination
        page.config.bodyView.on("click",".page-link",function(e) {
            e.preventDefault();
            var activePage = $(this).closest(".pagination").find(".active").text();
            var clickedPage = $(this).text();

            console.log($(this).attr("href"));

            if(clickedPage == "‹" || clickedPage < activePage) {
                $('html, body').animate({scrollTop: ($(window).scrollTop() - 500) + "px"}, 200);
                page.getResults($(this).attr("href"));
            }else{
                page.getResults($(this).attr("href"));
            }
        });

        $(window).scroll(function() {
            if($(window).scrollTop() >= ($(document).height() - $(window).height())) {
                page.config.bodyView.find("#page-view-result").find(".pagination").find(".active").next().find("a").click();
            }
        });

        $(document).on("click",".checkbox-select-all",function() {
            $(".items-id").trigger("click");
        });

        $(document).on("click",".btn-add-whatsapp-list",function() {
            page.addInWhatsappList($(this));
        });

        

    },
    loadFirst: function() {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/quick-customer/records",
            method: "get",
            beforeSend: function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults");
    },
    getResults: function(href) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/quick-customer/records",
            method: "get",
            data: (typeof href != "undefined") ? {} : $(".message-search-handler").serialize(),
            beforeSend: function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults",{append : true});
    },
    showResults: function(response,params) {
        $("#loading-image").hide();
        //$("#page-view-result").append(response.data);
        var addProductTpl = $.templates("#template-result-block");
        var tplHtml = addProductTpl.render(response);
        //var tplHtml = response.data;
        $(".count-text").html("(" + response.total + ")");

        if(params && typeof params.append != "undefined" && params.append == true) {
           // remove page first  
           var removePage = response.page;
               if(removePage > 0) {
                  var pageList = page.config.bodyView.find("#page-view-result").find(".page-template-"+removePage);
                  pageList.nextAll().remove();
                  pageList.remove();
               }
               if(removePage > 1) {
                 page.config.bodyView.find("#page-view-result").find(".pagination").first().remove();
               }
           page.config.bodyView.find("#page-view-result").append(tplHtml);
        }else{
           page.config.bodyView.find("#page-view-result").html(tplHtml);
        }
    },
    addInWhatsappList : function(ele) {

        var items = [];
        var checkbox = $(".items-id:checked");
            $.each(checkbox,function(k,v) {
                items.push($(v).val());
            });

        var _z = {
            url: this.config.baseUrl + "/quick-customer/add-whatsapp-list",
            method: "post",
            data: { customer_ids : items},
            beforeSend: function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "afterAddinWhatsappList",{append : true});
    },
    afterAddinWhatsappList : function(response) {
        $("#loading-image").hide();
        if(response.code == 200){
            toastr["success"]("Success");
        }else{
            toastr["error"]("Error");
        }
    }
}
$.extend(page, common);