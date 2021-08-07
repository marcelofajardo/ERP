function formatRepo(repo) {
        if (repo.loading) {
            return repo.text;
        }
    
        var markup = "";
    
        if (repo.id === 'all') {
            markup = '<span  class="all_site_red" >' + repo.text + '</span>'
        } else if (repo.bold === 'bold') {
            markup = '<strong>' + repo.text + '</strong>'
            if (repo.name) {
    
                markup += ' ( ' + repo.name + ' ) '
            }
            markup += '<strong> ' + repo.offset + ' </strong>'
        } else {
            markup = repo.text
        }
    
        return markup;
    }
    
    function formatRepoSelection(repo) {
    
        return repo.full_name || repo.text;
    }

function initialize_select2(initial_skip = false) {

        $(document).find('select.globalSelect2:not(.initialized)').each(function (index, elem) {
    
            if ($(elem).hasClass('skip')) {
                return true;
            }
    
            if (initial_skip && $(elem).hasClass('initial_skip')) {
                return true;
            }
    
            $(elem).addClass('initialized');
    
            var options = {
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            };
    
            let ajax_url = $(elem).data('ajax');
    
            var multiple = typeof $(elem).attr('multiple') !== 'undefined' ? true : false;
    
            if (typeof ajax_url !== 'undefined') {
    
                options['ajax'] = {
                    url: ajax_url,
                    dataType: 'json',
                    delay: 100,
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
    
                        params.page = params.page || 1;
    
                        return {
                            results: data.items,
                            pagination: {
                                more: (params.page * 10) < data.total_count
                            }
                        };
                    },
                    cache: true
                }
    
            }
    
            if (multiple) {
                options['multiple'] = true;
            }
    
            options['minimumResultsForSearch'] = typeof $(elem).data('minimumresultsforsearch') !== 'undefined' ? $(elem).data('minimumresultsforsearch') : 10;
    
            options['allowClear'] = typeof $(elem).data('allowclear') !== 'undefined' && $(elem).data('allowclear') == false ? false : true;
            options['preventOpenAfterClear'] = true;
        
            if ($(elem).data('tags') === true) {
                options['tags'] = $(elem).data('tags');
            }
            if ($(elem).hasClass('merge_brand_close_e')) {

                options['allowClear'] = false
            }
            
            $(elem).select2(options);
    
        })
    
    }

initialize_select2();

$(document).find( ".addToAutoComplete" ).autocomplete({
    source: function (request, response) {

        jQuery.post("/list/autoCompleteMessages", {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "keyword": request.term,
        }, function (data) {
            // assuming data is a JavaScript array such as
            // ["one@abc.de", "onf@abc.de","ong@abc.de"]
            // and not a string

            response(data.data);
        });
    },
  });


$(document).on('change', '.quickCategory.select-child', function(e){
    
    if ($(this).val() != "") {

        var replies = JSON.parse($(this).val());
        
        $(this).parents(".row").find('.quickComment').html($('<option>', {
            value: '',
            text: 'Quick Reply'
        }));
        replies.forEach((reply) => {
            $(this).parents(".row").find('.quickComment').append($('<option>', {
                value: reply.reply,
                text: reply.reply,
                'data-id': reply.id
            }));
        });
    }
});

 