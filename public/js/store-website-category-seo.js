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

        page.config.bodyView.on("click",".btn-push",function(e) {
            page.push($(this));
        });


        $(".common-modal").on("click",".submit-store-category-seo",function() {
            page.submitFormSite($(this));
        });

        $(document).on("click",".btn-translate-for-other-language",function(e) {
            e.preventDefault();
            page.translateForOtherLanguage($(this));
        });

        $(document).on("click",".push-pages-store-wise",function(e) {
            e.preventDefault();
            page.pushPageInLive($(this));
        });

        $(document).on("change", ".website-form-page", function (e) {
            e.preventDefault();
            page.loadPage($(this));
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
            url: this.config.baseUrl + "/category-seo/records",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults");
    },
    getResults: function(href) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/category-seo/records",
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
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/category-seo/"+ele.data("id")+"/delete",
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
        var value = $(ele).closest('tr').children('td:eq(1)').text();
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/category-seo/" + ele.data("id") + "/edit?category="+value,
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
            $('textarea[name="meta_keyword"]').trigger('change');
            $('textarea[name="meta_description"]').trigger('change');

            $('textarea[name="meta_keyword"]').trigger('change');
            $('textarea[name="meta_description"]').trigger('change');

            $('textarea[name="meta_keyword"]').trigger('input');
            $('textarea[name="meta_keyword_avg_monthly"]').trigger('input');

            $('#keyword-search-btn').trigger('click');
            $('#google_translate_element').summernote();

        //new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
    },

    submitFormSite : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/category-seo/save",
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveSite");
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
    translateForOtherLanguage :function(ele) {
        let store_website_category_seo = ele.data("id");
        
        var _z = {
            url: this.config.baseUrl + "/category-seo/"+store_website_category_seo+"/translate-for-other-langauge",
            method: "get"
        }

        this.sendAjax(_z, 'afterTranslateForOtherLanguage');
    },
    afterTranslateForOtherLanguage : function(response) {
        if(response.code  == 200) {
            toastr["success"](response.message,'');
        }else{
            toastr["error"]('something went wrong!',"");
        }
    },
    push : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/category-seo/"+ele.data("id")+"/push",
            method: "get",
        }
        this.sendAjax(_z, 'afterPush');
    },
    afterPush : function(response) {
        if(response.code  == 200) {
            toastr["success"](response.message,"");
            location.reload();
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    pushPageInLive : function(ele) {
        let page     = $(".push-website-store-id").val();
        
        var _z = {
            url: this.config.baseUrl + "/category-seo/"+page+"/push-website-in-live",
            method: "get"
        }

        this.sendAjax(_z, 'afterPushPageInLive');
    },
    afterPushPageInLive : function (response) {
        if(response.code == 200) {
            toastr["success"](response.message,"");
            location.reload();
        }else{
            toastr["error"](response.message,"");
        }
    },
    loadPage: function (ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/category-seo/" + ele.val() + "/load-page",
            method: "get",
            beforeSend: function () {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, 'afterLoadPage');
    },
    afterLoadPage: function (response) {
        if (response.code == 200) {
            $("#loading-image").hide();
            if ($('#ctitle').is(':checked')) {
                $('#meta_title').empty().val(response.meta_title);
            }

            if ($('#ckeyword').is(':checked')) {
                $('#meta_keywords').empty().val(response.meta_keyword);
            }

            if ($('#cdesc').is(':checked')) {
                $('textarea[name="meta_description"]').empty().text(response.meta_desc);
            }
        }
    }
}

$.extend(page, common);

function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight+1) + "px";
}

$(document).on('change', '#meta_title', function () {
    getGoogleKeyWord($('#meta_title').val());
});

$(document).on('click', '#extra-keyword-search-btn', function () {
    $(document).find('.suggestList').hide();
    getGoogleKeyWord($('#extra-keyword-search').val());
});

$(document).on('click', '#keyword-search-btn', function () {
    $(document).find('.suggestList').hide();
    getGoogleKeyWord($('#meta_title').val());
});

$(document).on('click', '.keyword-list', function () {
    var keywords = $(this).text();
    var avg = $(this).data('avg');
    
    if (keywords) {
        if ($(this).hasClass('badge-green')) {
            $('#meta_keywords').val($('#meta_keywords').val().replace("," + keywords, ""));
            $(this).removeClass('badge-green').find('i').remove()
            $('#meta_keyword_avg_monthly').val($('#meta_keyword_avg_monthly').val().replace("," + keywords+'-'+avg, ""));
        } else {
            $('#meta_keywords').val($('#meta_keywords').val() + ',' + keywords);
            $('#meta_keyword_avg_monthly').val($('#meta_keyword_avg_monthly').val() + ',' + keywords + '-' + avg);
            $(this).addClass('badge-green').append('<i class="fa fa-remove pl-2"></i>')
        }
    }

    $('input[name="meta_keyword"]').trigger('change');
    $('textarea[name="meta_keyword"]').trigger('input');
    $('textarea[name="meta_keyword_avg_monthly"]').trigger('input');

});

$(document).on('click', '.copy-to-btn', function () {
    console.log($(this));
    console.log($('#website-page-copy-to').val());
    var entire_category = 'false';
    var cttitle = 'false';
    var ctkeyword = 'false';
    var ctdesc = 'false';
    if ($('#entire_category').is(':checked')) {
        entire_category = 'true';
    }
    if ($('#cttitle').is(':checked')) {
        cttitle = 'true';
    }
    if ($('#ctkeyword').is(':checked')) {
        ctkeyword = 'true';
    }
    if ($('#ctdesc').is(':checked')) {
        ctdesc = 'true';
    }

    $.ajax({

        type   : 'POST',
        url    : '/store-website/category-seo/copy-to',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data   : {
            entire_category: entire_category,
            to_page  : $('#store_copy_id').val(),
            page     : $('input[name="id"]').val(),
            cttitle  : cttitle,
            ctkeyword: ctkeyword,
            ctdesc   : ctdesc
        },

        beforeSend: function () {
            $("#loading-image").show();
        },
        success: function (data) {
            $("#loading-image").hide();
            if (data.error) {
                toastr['error'](data.error, 'Error');
            }
            if (!$.isEmptyObject(data.error)) {
                $.each(data.error, function (key, value) {
                    toastr['error'](value, 'Error');
                });
            } else {
                toastr['success'](data.success, 'Success');
            }
        },
        complete: function () {
            $("#loading-image").hide();
        },
    });
});

$(document).on('click', '.reload-page-data', function () {
    $('.website-form-page').trigger('change');
});

$(document).on('change , keyup', 'input[name="meta_keyword"]', function () {
    $('#meta_keywords_count').text( 'Length: '+ $(this).val().length);
});

$(document).on('change , keyup', 'textarea[name="meta_description"]', function () {
    $('#meta_desc_count').text('Length: ' + $(this).val().length);
});

function getGoogleKeyWord(title) {
    $(document).find('.suggestList-table').empty();
    var lan = $('.website-language-change').val();

    var words = $(document).find('#meta_keywords').val();
    words = words.split(',');
    $.ajax({
       
        type: 'get',
        url: '/google-keyword-search-v6',
        data: { keyword: title, google_search: 'true' },

        beforeSend: function () {
            $("#loading-image").show();
        },
        success: function (response) {
            if (response.length > 0) {
                $(document).find('#extra-keyword-search-btn').removeClass('hide');
                $(document).find('#extra-keyword-search').removeClass('hide');
                var t = '';
                $.each(response, function (index, data) {
                    if ($.inArray(data.keyword, words) > -1) {
                        t += `<tr><td class="keyword-list badge-green" data-avg="` + data.avg_monthly_searches+`" >` + data.keyword + `<i class="fa fa-remove pl-2"></i></td>`;
                    } else {
                        t += `<tr><td class="keyword-list" data-avg="` + data.avg_monthly_searches +`" >` + data.keyword + `</td>`;
                    }
                    t += `<td>` + data.avg_monthly_searches + `</td>`;
                    t += `<td>` + data.competition + `</td>`;
                    t += `<td>` + data.translate_text + `</td></tr>`;
                });
                $(document).find('.suggestList-table').html(t);
                $(document).find('.suggestList').show();
            } else {
                $(document).find('#extra-keyword-search-btn,#extra-keyword-search').hide();
            }

        },
        complete: function () {
            $("#loading-image").hide();
        },
    });
}

$(document).on("click", ".btn-history-list", function (e) {
    console.log($(this).data());
    // e.preventDefault();
    var product_id = $(this).data("id");
    $.ajax({
        url: '/store-website/category-seo/'+ product_id + '/history',
        type: 'get',
        dataType: 'json',
        beforeSend: function () {
            $("#loading-image").show();
        },
        success: function (result) {
            console.log(result);
            $("#loading-image").hide();

            if (result.code == 200) {
                var t = '';
                $.each(result.data, function (k, v) {
                    t += `<tr><td>` + v.id + `</td>`;
                    t += `<td>` + v.old_keywords + `</td>`;
                    t += `<td>` + v.new_keywords + `</td>`;
                    t += `<td>` + v.old_description + `</td>`;
                    t += `<td>` + v.new_description + `</td>`;
                    t += `<td>` + v.user_name + `</td>`;
                    t += `<td>` + v.created_at + `</td></tr>`;
                });
            }
            $("#category-history-modal").find(".show-list-records").html(t);
            $("#category-history-modal").modal("show");
        },
        error: function () {
            $("#loading-image").hide();
        }
    });
});