var common = {
    sendAjax: function(params, callback, isPassArg) {
        var self = this;
        var sendUrl = this.checkTypeOf(params, 'url', null);
        if (!sendUrl) {
            return true;
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: this.checkTypeOf(params, 'method', "GET"),
            dataType: this.checkTypeOf(params, 'dataType', "json"),
            data: this.checkTypeOf(params, 'data', []),
            url: this.checkTypeOf(params, 'url', "/"),
            beforeSend: function() {
                //$(".loading").show();
                if (common.checkTypeOf(params, 'beforeSend', false)) {
                    params.beforeSend();
                }
            },
            complete: function() {
                if (common.checkTypeOf(params, 'complete', false)) {
                    params.complete();
                }
            }
        }).done(function(result) {
            if (common.checkTypeOf(params, 'doneAjax', false)) {
                params.doneAjax(result);
            }
            if (callback) {
                if (isPassArg) {
                    self[callback](result,isPassArg)
                } else {
                    self[callback](result);
                }
            }
        }).fail(function(jqXhr) {
            toastr["error"](jqXhr.responseText,"");
        });
    },
    sendFormDataAjax: function(params, callback, fallback) {
        var self = this;
        var sendUrl = this.checkTypeOf(params, 'url', null);
        if (!sendUrl) {
            return true;
        }
        $.ajax({
            method: this.checkTypeOf(params, 'method', "GET"),
            data: this.checkTypeOf(params, 'data', []),
            url: this.checkTypeOf(params, 'url', "/"),
            contentType: false,
            processData: false,
            beforeSend: function() {
                //$(".loading").show();
                if (common.checkTypeOf(params, 'beforeSend', false)) {
                    params.beforeSend();
                }
            },
            complete: function() {
                //$(".loading").hide();
            }
        }).done(function(result) {
            if (common.checkTypeOf(params, 'doneAjax', false)) {
                params.doneAjax(result);
            }
            if (callback) {
                self[callback](result);
            }
        }).fail(function(jqXhr) {
            toastr["error"](jqXhr.responseText,"");
        });
    },
    checkTypeOf: function(params, key, defaultVal) {
	     return (params && typeof params[key] != "undefined") ? params[key] : defaultVal;
	}
};