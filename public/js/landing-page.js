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

        page.config.bodyView.on("click",".btn-push-icon-mangto",function(e) {
            e.preventDefault();
            page.pushMagentoProduct($(this));
        });

        page.config.bodyView.on("click",".btn-stock-status-magnto",function(e) {
            e.preventDefault();
            page.pushStockStatusMangto($(this));
        });

        page.config.bodyView.on("click",".btn-push-icon",function(e) {
            e.preventDefault();
            page.pushShopifyProduct($(this));
        });

        page.config.bodyView.on("click",".btn-stock-status",function(e) {
            e.preventDefault();
            page.pushStockStatus($(this));
        });

        page.config.bodyView.on("change",".store-website-change",function(e) {
            e.preventDefault();
            page.changeStoreWebsite($(this));
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

        page.config.bodyView.on("click",".btn-delete-image",function(e) {
            if(!confirm("Are you sure you want to delete image?")) {
                return false;
            }else {
                page.deleteImages($(this));
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
            url: this.config.baseUrl + "/landing-page/records",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults");
    },
    getResults: function(href) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/landing-page/records",
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
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/landing-page/"+ele.data("id")+"/delete",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, 'deleteResults');
    },
    deleteImages : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/landing-page/image/"+ele.data("id")+"/"+ele.data("productid")+"/delete",
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
        var createWebTemplate = $.templates("#form-create-landing-page");
        var tplHtml = createWebTemplate.render({data:{}});

        var common =  $(".common-modal");
        common.find(".modal-dialog").html(tplHtml);
        common.modal("show");

        let r_s = jQuery('input[name="start_date"]').val();
        let r_e = jQuery('input[name="end_date"]').val()

        if(r_s == "0000-00-00 00:00:00") {
            r_s = undefined;
        }

        if(r_e == "0000-00-00 00:00:00") {
            r_e = undefined;
        }

        let start = r_s ? moment(r_s, 'YYYY-MM-DD') : moment().subtract(6, 'days');
        let end = r_e ? moment(r_e, 'YYYY-MM-DD') : moment();

        // jQuery('input[name="range_start"]').val(start.format('YYYY-MM-DD'));
        // jQuery('input[name="range_end"]').val(end.format('YYYY-MM-DD'));

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            maxYear: 1,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

        $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
            jQuery('input[name="start_date"]').val(picker.startDate.format('YYYY-MM-DD'));
            jQuery('input[name="end_date"]').val(picker.endDate.format('YYYY-MM-DD'));
        });
    },

    editRecord : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/landing-page/"+ele.data("id")+"/edit",
            method: "get",
        }
        this.sendAjax(_z, 'editResult');
    },

    editResult : function(response) {
        var createWebTemplate = $.templates("#form-create-landing-page");
        var tplHtml = createWebTemplate.render(response);
        var common =  $(".common-modal");
        common.find(".modal-dialog").html(tplHtml);
        common.modal("show");

        let r_s = jQuery('input[name="start_date"]').val();
        let r_e = jQuery('input[name="end_date"]').val()

        if(r_s == "0000-00-00 00:00:00") {
            r_s = undefined;
        }

        if(r_e == "0000-00-00 00:00:00") {
            r_e = undefined;
        }

        let start = r_s ? moment(r_s, 'YYYY-MM-DD') : moment().subtract(6, 'days');
        let end = r_e ? moment(r_e, 'YYYY-MM-DD') : moment();

        // jQuery('input[name="range_start"]').val(start.format('YYYY-MM-DD'));
        // jQuery('input[name="range_end"]').val(end.format('YYYY-MM-DD'));

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            maxYear: 1,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

        $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
            jQuery('input[name="start_date"]').val(picker.startDate.format('YYYY-MM-DD'));
            jQuery('input[name="end_date"]').val(picker.endDate.format('YYYY-MM-DD'));
        });
    },
    submitPlatform : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/landing-page/store",
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
            toastr["success"]("Product added successfully");
            page.loadFirst();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.message,"");
        }
    },
    saveMagentoStatus : function(response) {
        if(response.code  == 200) {
            toastr["success"]("Status Change successfully!");
            page.loadFirst();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.message,"");
        }
    },
    pushShopifyProduct : function(ele) {
        var _z = {
            url: this.config.baseUrl + "/landing-page/"+ele.data("id")+"/push-to-shopify",
            method: "GET",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveSite");
    },
    pushMagentoProduct : function(ele) {
       var _z = {
            url: this.config.baseUrl + "/landing-page/"+ele.data("id")+"/push-to-magento",
            method: "GET",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveSite");
    },
    pushStockStatusMangto : function(ele) {
        var _z = {
            url: this.config.baseUrl + "/landing-page/"+ele.data("id")+"/push-to-magento-status",
            method: "GET",
            data : {stock_status : ele.data("value")},
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveMagentoStatus");
    },

    pushStockStatus : function(ele) {
        var _z = {
            url: this.config.baseUrl + "/landing-page/"+ele.data("id")+"/push-to-shopify",
            method: "GET",
            data : {stock_status : ele.data("value")},
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveSite");
    },

    changeStoreWebsite : function(ele)
    {
        var _z = {
            url: this.config.baseUrl + "/landing-page/"+ele.data("id")+"/change-store",
            method: "GET",
            data : {store_website_id : ele.val()},
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, 'pureFunction');
    },
    pureFunction : function(response) {
        $("#loading-image").hide();
        if(response.code  == 200) {
            toastr["success"]("Store website changed successfully");
        }else {
            toastr["error"](response.message,"");
        }
    }
}

$.extend(page, common);

$(document).ready(function(){
    $("body").delegate("#pr-start-time, #pr-end-time", "focusin", function(){
        $('#pr-start-time, #pr-end-time').datetimepicker({format: 'YYYY-MM-DD HH:mm:00'});
    });
});
$(document).on('click', '.check-product', function() {
    var productArr = [];
    $("input:checkbox[name='check-product']:checked").each(function(){
        productArr.push($(this).val());
    });
    if(productArr.length) {
        $('#update-time-btn').show();
    }else{
        $('#update-time-btn').hide();
    }
    $('input:hidden[name=product_id]').val(productArr);
});

$(document).on("click",'.open_images', function(){
    var block_id = $(this).data('attr');
    // $('.hideall').hide();

    $('#'+block_id).toggle();
});

$(document).on("submit",'#formCreateLandingPageStatus', function(e) {
    e.preventDefault();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type:'POST',
        url: '/landing-page/create_status',
        data:{
            status: $("input[name=landing_page_status]").val()
        },
        success:function(data){
            $("#createStatusModal").modal('hide');
            alert(data.message);
        }
    });
});

$(document).on("click",'.approveLandingPageStatus', function() {
    const that = this;
    $.ajax({
        type:'GET',
        url: '/landing-page/approve-status',
        data:{
            id: $(this).data('id'),
            approve: true
        },
        success:function(data){
            $(that).closest('td').find('span').text('Active');
            $(that).closest('div').hide();
        },
        error: function(data) {
            alert(data.message);
        }
    });
});