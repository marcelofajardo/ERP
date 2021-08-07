var dialogBoxData = "";
var allSuggestedOptions = "";
var searchForIntent = function(ele) {
    var intentBox = ele.find(".search-intent");
    if (intentBox.length > 0) {
        intentBox.select2({
            placeholder: "Enter intent name or create new one",
            width: "100%",
            tags: true,
            allowClear: true,
            ajax: {
                url: '/chatbot/question/search',
                dataType: 'json',
                processResults: function(data) {
                    return {
                        results: data.items
                    };
                }
            }
        }).on("change.select2", function() {
            var $this = $(this);
        }).on('select2:select', function (e) {
            var data = e.params.data;
            var replyBox = ele.find(".reply-insert");
            if(data.suggested_reply) {
                replyBox.val(data.suggested_reply);
            }
            else {
                replyBox.val('');
            }
        });
    }
};


$('.select-phrase-group').on('select2:select', function (e) {
    console.log("selected");
    var data = e.params.data;
    if(data.suggested_reply) {
        $("#addPhrases").find(".suggested_reply").val(data.suggested_reply);
    }
});

var searchForCategory = function(ele) {
    var categoryBox = ele.find(".search-category");
    if (categoryBox.length > 0) {
        categoryBox.select2({
            placeholder: "Enter category name or create new one",
            width: "100%",
            tags: true,
            allowClear: true,
            ajax: {
                url: '/chatbot/question/search-category',
                dataType: 'json',
                processResults: function(data) {
                    return {
                        results: data.items
                    };
                }
            }
        });
    }
};

var searchForKeyword = function(ele) {
    var keywordBox = ele.find(".search-keyword");
    if (keywordBox.length > 0) {
        keywordBox.select2({
            placeholder: "Enter keyword name or create new one",
            width: "100%",
            tags: true,
            allowClear: true,
            ajax: {
                url: '/chatbot/keyword/search',
                dataType: 'json',
                processResults: function(data) {
                    return {
                        results: data.items
                    };
                }
            }
        }).on("change.select2", function() {
            var $this = $(this);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "post",
                url: '/chatbot/question/submit',
                data: {
                    "name": $this.val(),
                    "question": $this.data("question")
                },
                dataType: "json",
                success: function(response) {
                    if (response.code != 200) {
                        toastr['error']('Can not store keyword please review or use diffrent name!');
                    } else {
                        toastr['success']('Success!');
                    }
                },
                error: function() {
                    toastr['error']('Can not store intent name please review!');
                }
            });
        });
    }
};
var previousDialog =  function(ele) {
	var dialogBox = ele.find(".previous-dialog-node");
    if (dialogBox.length > 0) {
        dialogBox.select2({
            placeholder: "Enter previous dialog name",
            width: "100%",
            allowClear: true
        });
    }    
};

var previousDialogSearch = function(ele,parentId)
{
    $.ajax({
        type: "get",
        url: '/chatbot/dialog/search',
        data: {
            "parent_id": parentId
        },
        dataType: "json",
        success: function(response) {
            ele.find(".previous-dialog-node").empty().select2({
                data: response.items,
                placeholder: "Enter previous dialog name",
                width: "100%",
                allowClear: true
            });
        },
        error: function() {
            toastr['error']('Can not store intent name please review!');
        }
    });
}

var parentDialog = function(ele) {
	var parentDialog = ele.find(".parent-dialog-node");
	if (parentDialog.length > 0) {
        previousDialogSearch(ele,0);
        parentDialog.select2({
            placeholder: "Enter Parent dialog name , leave empty if not needed",
            width: "100%",
            allowClear: true,
            ajax: {
                url: '/chatbot/dialog/search',
                dataType: 'json',
                processResults: function(data) {
                    return {
                        results: data.items
                    };
                }
            }
        }).on("change.select2", function() {
            var $this = $(this);
            previousDialogSearch(ele,$this.val());
        });
    }
};


$(document).on("change", ".dynamic-row .search-alias", function() {
    var selectedIntentOrEntity = $(this).val();
    if (selectedIntentOrEntity !== "" && !allSuggestedOptions.hasOwnProperty(selectedIntentOrEntity)) {
        var isEntity = selectedIntentOrEntity.match("^@") ? true : selectedIntentOrEntity.match("^#") ? false : undefined;
        var keyword_or_question = isEntity ? 'entity' : 'intent';
        if (!(isEntity === undefined)) {
            allSuggestedOptions[selectedIntentOrEntity] = selectedIntentOrEntity
            selectedIntentOrEntity = selectedIntentOrEntity.slice(1, selectedIntentOrEntity.length);
            $.ajax({
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/chatbot/question",
                data: { 
                    value: selectedIntentOrEntity , question : [$(".question-insert").val()], keyword_or_question : keyword_or_question
                },
                dataType: "json",
                success: function(response) {
                    var successMessage = isEntity ? "Entity Created Successfully" : "Intent Created SuccessFully"
                    toastr["success"](successMessage);
                },
                error: function() {
                    toastr["error"]("Could not add intent/entity!");
                }
            });
        }
        else {
            toastr["error"]("Invalid intent/entity format. Entities should be prefixed with @ and intents should be prefixed with #");
            var aliasTemplate = $.templates("#search-alias-template");
            var aliasTemplateHtml = aliasTemplate.render({
                "allSuggestedOptions": allSuggestedOptions
            });
            $("#leaf-editor-model").find(".search-alias").html(aliasTemplateHtml);
        }
    }
});

var searchForDialog = function(ele) {
    var dialogBox = ele.find(".search-dialog");
    var intentOrEntityBox = ele.find(".search-alias");
    intentOrEntityBox.select2({
        placeholder: "Enter entity or intent",
        width: "100%",
        tags: true,
        allowClear: true,
    }).on("change.select2", function(e) {
        var selectedIntentOrEntity = e.target.value;
        if (selectedIntentOrEntity !== "" && !allSuggestedOptions.hasOwnProperty(selectedIntentOrEntity)) {
            var isEntity = selectedIntentOrEntity.match("^@") ? true : selectedIntentOrEntity.match("^#") ? false : undefined;
            var keyword_or_question = isEntity ? 'entity' : 'intent';
            if (!(isEntity === undefined)) {
                allSuggestedOptions[selectedIntentOrEntity] = selectedIntentOrEntity
                selectedIntentOrEntity = selectedIntentOrEntity.slice(1, selectedIntentOrEntity.length);
                $.ajax({
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/chatbot/question",
                    data: { 
                        value: selectedIntentOrEntity , question : [$(".question-insert").val()], keyword_or_question : keyword_or_question
                    },
                    dataType: "json",
                    success: function(response) {
                        var successMessage = isEntity ? "Entity Created Successfully" : "Intent Created SuccessFully"
                        toastr["success"](successMessage);
                    },
                    error: function() {
                        toastr["error"]("Could not add intent/entity!");
                    }
                });
            }
            else {
                toastr["error"]("Invalid intent/entity format. Entities should be prefixed with @ and intents should be prefixed with #");
                var aliasTemplate = $.templates("#search-alias-template");
                var aliasTemplateHtml = aliasTemplate.render({
                    "allSuggestedOptions": allSuggestedOptions
                });
                $("#leaf-editor-model").find(".search-alias").html(aliasTemplateHtml);
            }
        }
    });
    if (dialogBox.length > 0) {
        dialogBox.select2({
            placeholder: "Enter dialog name or create new one",
            width: "100%",
            tags: true,
            allowClear: true,
            ajax: {
                url: '/chatbot/dialog/search',
                dataType: 'json',
                processResults: function(data) {
                    return {
                        results: data.items
                    };
                }
            }
        }).on("change.select2", function() {
            var $this = $(this);
            
            $.ajax({
		        type: "get",
		        url: "/chatbot/rest/dialog/" + $this.val(),
		        dataType: "json",
		        success: function(response) {
		        	if(response.code == 200) {

			        	var myTmpl = $.templates("#edit-dialog-form-section");
			        	var html = myTmpl.render({
			                "data": window.buildDialog
			            });

			            $("#leaf-editor-model").find(".dialog-editor-section").html(html);

			        	var aliasTemplate = $.templates("#search-alias-template");
			            var aliasTemplateHtml = aliasTemplate.render({
			                "allSuggestedOptions": response.data.allSuggestedOptions
			            });

			            $("#leaf-editor-model").find(".search-alias").html(aliasTemplateHtml);
			        	if( typeof response.data.id != "undefined") {
				            var html = myTmpl.render({
				                "data": response.data
				            });
			        		$("#leaf-editor-model").find(".dialog-editor-section").html(html);
				        }
			            $("[data-toggle='toggle']").bootstrapToggle('destroy')
			            $("[data-toggle='toggle']").bootstrapToggle();
			            searchForDialog($("#leaf-editor-model"));
			            previousDialog($("#leaf-editor-model"));
			            parentDialog($("#leaf-editor-model"));
				        $("#leaf-editor-model").find(".search-alias").select2();
		        	}
		        },
		        error: function() {
		            toastr['error']('Could not change module!');
		        }
		    });
        });
    }
};
var updateBoxEvent = function(parentId) {
    var parent_id = 0;
    if (typeof parentId != "undefined") {
        parent_id = parentId;
    }
    var child_nodes = '.parent_node_class_'+parent_id;
    var child_node_cls = $(child_nodes).length;
    console.log(child_node_cls);
    if(child_node_cls > 0) {
        $(child_nodes).remove();
        return;
    }

    let search = $(".search-query-value").val();

    $.ajax({
        type: "get",
        url: "/chatbot/rest/dialog/status",
        data: {
            "parent_id": parent_id,
            "search" : search
        },
        dataType: "json",
        success: function(response) {
            if (response.code == 200) {
                dialogBoxData = response.data.chatDialog;
                allSuggestedOptions = response.data.allSuggestedOptions;
                if (dialogBoxData.length > 0) {
                    var html = "";
                    $.each(dialogBoxData, function(k, v) {
                        var myTmpl = $.templates("#dialog-leaf");
                        var folderTemplate = $.templates("#dialog-folder-leaf");
                        html += (v.dialog_type == 'folder') ? folderTemplate.render({ "data": v}): myTmpl.render({"data": v});
                    });
                    if (parent_id > 0) {
                        var dialogTree = $(".node_child_" + parent_id);
                        html = "<tr class='parent_node_class_"+parent_id+"'><td style='padding:20px;' colspan='10'><table style='table-layout:fixed;' class='table table-striped table-bordered table-sm' cellspacing='0' width='100%'><tbody>" + html + '</tbody></table></td></tr>';
                        // var dialogTree = $(".node_child_" + parent_id).find(".node-children");
                        dialogTree.after(html);

                    } else {
                        var dialogTree = $("#dialog-tree");
                        dialogTree.html(html);

                    }
                    $("#leaf-editor-model").modal("hide");
                }
            }
        },
        error: function() {
            toastr['error']('Could not change module!');
        }
    });
};
updateBoxEvent(0);
$(document).on("click", "#create-dialog-btn-open", function() {
    console.log("open");
    $("#leaf-editor-model").modal("show");
    var myTmpl = $.templates("#add-dialog-form");
    var json = {
        "create_type": "intents_create"
    };
    var html = myTmpl.render({
        "data": json
    });
    $("#leaf-editor-model").find(".modal-body").html(html);
    $("[data-toggle='toggle']").bootstrapToggle('destroy')
    $("[data-toggle='toggle']").bootstrapToggle();
    searchForIntent($("#leaf-editor-model"));
});


$(document).on("click", "#create-dialog-folder-btn-rest", function(e) {
    e.preventDefault();
    var previous_node = 0;
    var previous = $("#dialog-tree").find("tr").last();
    if (previous.length > 0) {
        previous_node = previous.data("id");
    }
    $.ajax({
        type: "get",
        url: "/chatbot/rest/dialog/create",
        data: {
            "previous_node": previous_node,
            "dialog_type": "folder"
        },
        dataType: "json",
        success: function(response) {
            if (response.code == 200) {
                toastr['success']('Successfully created');
                updateBoxEvent();
            }
        },
        error: function() {
            toastr['error']('Could not create dialog folder!');
        }
    });
});


$(document).on("click", "#create-dialog-btn-rest", function(e) {
    e.preventDefault();
    var previous_node = 0;
    var previous = $("#dialog-tree").find("tr").last();
    if (previous.length > 0) {
        previous_node = previous.data("id");
    }
    $.ajax({
        type: "get",
        url: "/chatbot/rest/dialog/create",
        data: {
            "previous_node": previous_node
        },
        dataType: "json",
        success: function(response) {
            if (response.code == 200) {
                toastr['success']('Successfully created');
                updateBoxEvent();
            }
        },
        error: function() {
            toastr['error']('Could not change module!');
        }
    });
});


$(document).on("click", ".node__contents", function(e) {
    var node = $(this).data("id");
    $("#leaf-editor-model").modal("show");
    $.ajax({
        type: "get",
        url: "/chatbot/rest/dialog/" + node,
        dataType: "json",
        success: function(response) {
            var myTmpl = $.templates("#add-dialog-form");
            var html = myTmpl.render({
                "data": response.data
            });
            $("#leaf-editor-model").find(".modal-body").html(html);
            $("[data-toggle='toggle']").bootstrapToggle('destroy')
            $("[data-toggle='toggle']").bootstrapToggle();
            $(".search-alias").select2();
            searchForDialog($("#leaf-editor-model"));
            var eleLeaf = $("#leaf-editor-model");
            searchForIntent(eleLeaf);
            searchForCategory(eleLeaf);
            previousDialog(eleLeaf);
            parentDialog(eleLeaf);
        },
        error: function() {
            toastr['error']('Could not change module!');
        }
    });
});
$(document).on("click", ".add-more-condition-btn", function() {
    var buttonOptions = $.templates("#add-more-condition");
    $(".show-more-conditions").append(buttonOptions.render({
        "allSuggestedOptions": allSuggestedOptions
    }));
    $(".search-alias").select2({
        tags: true
    });
});
$(document).on("click", ".remove-more-condition-btn", function() {
    $(this).closest(".form-row").remove();
});
$(document).on("click", ".node__menu", function(e) {
    //e.stopPropagation();
});
$(document).click(function() {
    //$(".bx--overflow-menu-options").remove();
});
$(document).on("change", ".multiple-conditioned-response", function() {
    var hasChecked = $(this).prop('checked');
    if (hasChecked == true) {
        var identifier = "new_" + new Date().getTime();
        var tmpl = $.templates("#multiple-response-condition");
        $(".assistant-response-based").html(tmpl.render({
            "identifier": identifier,
            "allSuggestedOptions": allSuggestedOptions
        }));
        $(".assistant-response-based").find(".search-alias").select2({});
    } else {
        var tmpl = $.templates("#single-response-condition");
        $(".assistant-response-based").html(tmpl.render({}));
    }
});
$(document).on("click", ".btn-add-mul-response", function() {
    var identifier = "new_" + new Date().getTime();
    var tmpl = $.templates("#multiple-response-condition");
    $(".assistant-response-based").append(tmpl.render({
        "identifier": identifier,
        "allSuggestedOptions": allSuggestedOptions
    }))
});
$(document).on("click", ".btn-delete-mul-response", function() {
    $(this).closest(".form-row").remove();
});
$(document).on("click", ".bx--overflow-menu", function() {
    var hasPop = $(this).data("has-pop");
    if (hasPop === false) {
        var buttonOptions = $.templates("#dialog-leaf-button-options");
        var html = buttonOptions.render({});
        $(this).append(html);
        $(this).attr("data-has-pop", true);
        $(this).data('has-pop', true);
    } else {
        $(this).find(".bx--overflow-menu-options").remove();
        $(this).attr("data-has-pop", false);
        $(this).data('has-pop', false);
    }
});
$(document).on("click", ".node__expander", function(e) {
    var li = $(this).closest(".node-child");
    updateBoxEvent(li.data("id"));
});
$(document).on("change", ".search-alias", function() {
    var selectedValue = $(this).val();
    var res = selectedValue.match(/@/g);
    if (res != "" && res != null) {
        $(this).closest(".form-row").find(".extra_condtions").removeClass("dis-none");
    } else {
        $(this).closest(".form-row").find(".extra_condtions").addClass("dis-none");
    }
});

$(document).on('change', '.store_on_change', function() {
    var store_website_id = $(this).val();
    var dialog_id = $(this).data('dialog');
    $.ajax({
        type: 'GET',
        url: '/chatbot/dialog/get-response-only',
        data: {
            store_website_id : store_website_id,
            dialog_id : dialog_id
        },
        dataType: "json",
        success: function(response) {
            console.log(response);
            var myTmpl = $.templates("#add-dialog-form");
            var html = myTmpl.render({
                "data": response.data
            });
            $("#leaf-editor-model").find(".modal-body").html(html);
            $("[data-toggle='toggle']").bootstrapToggle('destroy')
            $("[data-toggle='toggle']").bootstrapToggle();
            $(".search-alias").select2();
            searchForDialog($("#leaf-editor-model"));
            var eleLeaf = $("#leaf-editor-model");
            searchForIntent(eleLeaf);
            searchForCategory(eleLeaf);
            previousDialog(eleLeaf);
            parentDialog(eleLeaf);
        },
        error: function() {
            toastr['error']('Could not change module!');
        }
    });
})
// $(document).on("click", ".bx--overflow-menu-options > li", function() {
//     var buttonRole = $(this).find("button").attr("role");
//     if (buttonRole == "add_child") {
//         var main = $(this).closest(".node-child");
//         /* var space = main.find(".node-children");
//          	  space.append(myTmpl.render({}));*/
//         $.ajax({
//             type: "get",
//             url: "/chatbot/rest/dialog/create",
//             dataType: "json",
//             data: {
//                 "parent_id": main.data("id")
//             },
//             success: function(response) {
//                 if (response.code == 200) {
//                     updateBoxEvent(main.data("id"));
//                 }
//             },
//             error: function() {
//                 toastr['error']('Could not change module!');
//             }
//         });
//     } else if (buttonRole == "add_above") {
//         var main = $(this).closest(".node-child");
//         //main.before(myTmpl.render({}));
//         var current_node = $(this).closest(".node-child").data("id");
//         var previous_node = 0;
//         var previousNodeChild = $(this).closest(".node-child").prev();
//         if (previousNodeChild.length > 0) {
//             previous_node = previousNodeChild.data("id");
//         }
//         var parent_id = main.data("parent-id");

//         $.ajax({
//             type: "get",
//             url: "/chatbot/rest/dialog/create",
//             dataType: "json",
//             data: {
//                 "current_node": current_node,
//                 "previous_node": previous_node,
//                 "parent_id": parent_id
//             },
//             success: function(response) {
//                 if (response.code == 200) {
//                     updateBoxEvent(parent_id);
//                 }
//             },
//             error: function() {
//                 toastr['error']('Could not change module!');
//             }
//         });
//     } else if (buttonRole == "add_below") {
//         var main = $(this).closest(".node-child");
//         var previous_node = $(this).closest(".node-child").data("id");
//         var current_node = 0;
//         var nextNodeChild = $(this).closest(".node-child").next();
//         if (nextNodeChild.length > 0) {
//             current_node = nextNodeChild.data("id");
//         }
//         var parent_id = main.data("parent-id");

//         $.ajax({
//             type: "get",
//             url: "/chatbot/rest/dialog/create",
//             dataType: "json",
//             data: {
//                 "current_node": current_node,
//                 "previous_node": previous_node,
//                 "parent_id": parent_id
//             },
//             success: function(response) {
//                 if (response.code == 200) {
//                     updateBoxEvent(parent_id);
//                 }
//             },
//             error: function() {
//                 toastr['error']('Could not change module!');
//             }
//         });
//     } else if (buttonRole == "delete") {
//         var main = $(this).closest(".node-child");
//         var node = $(this).closest(".node").data("id");
//         $.ajax({
//             type: "get",
//             url: "/chatbot/rest/dialog/" + node + "/delete",
//             dataType: "json",
//             success: function(response) {
//                 if (response.code == 200) {
//                     toastr['success']('data deleted successfully!');
//                     main.remove();
//                 }
//             },
//             error: function() {
//                 errorMessage = response.error ? response.error : 'data is not correct or duplicate!';
//                 toastr['error'](errorMessage);
//             }
//         });
//     }
// });


$(document).on("click", ".create-new-node", function() {
    var buttonRole = $(this).data("role");
    var id = $(this).data('id');
    if (buttonRole == "add_child") {
        var main_cls = ".node_child_"+id;
        var main = $(main_cls);
        $.ajax({
            type: "get",
            url: "/chatbot/rest/dialog/create",
            dataType: "json",
            data: {
                "parent_id": id
            },
            success: function(response) {
                if (response.code == 200) {
                    updateBoxEvent(id);
                }
            },
            error: function() {
                toastr['error']('Could not change module!');
            }
        });
    } else if (buttonRole == "add_above") {
        var main_cls = ".node_child_"+id;
        var main = $(main_cls);

        var current_node = id;
        var previous_node = 0;
        var previousNodeChild = $(main_cls).prev();
        if (previousNodeChild.length > 0) {
            previous_node = previousNodeChild.data("id");
        }
        var parent_id = $(this).data("parent_id");
        $.ajax({
            type: "get",
            url: "/chatbot/rest/dialog/create",
            dataType: "json",
            data: {
                "current_node": current_node,
                "previous_node": previous_node,
                "parent_id": parent_id
            },
            success: function(response) {
                if (response.code == 200) {
                    updateBoxEvent(parent_id);
                }
            },
            error: function() {
                toastr['error']('Could not change module!');
            }
        });
    } else if (buttonRole == "add_below") {
        var main_cls = ".node_child_"+id;
        var main = $(main_cls);

        var previous_node = id;
        var current_node = 0;
        var nextNodeChild = $(main_cls).next();
        if (nextNodeChild.length > 0) {
            current_node = nextNodeChild.data("id");
        }
        var parent_id = $(this).data("parent_id");
        $.ajax({
            type: "get",
            url: "/chatbot/rest/dialog/create",
            dataType: "json",
            data: {
                "current_node": current_node,
                "previous_node": previous_node,
                "parent_id": parent_id
            },
            success: function(response) {
                if (response.code == 200) {
                    updateBoxEvent(parent_id);
                }
            },
            error: function() {
                toastr['error']('Could not change module!');
            }
        });
    } else if (buttonRole == "delete") {
        var main_cls = ".node_child_"+id;
        var main = $(main_cls);
        console.log(id);
        $.ajax({
            type: "get",
            url: "/chatbot/rest/dialog/" + id + "/delete",
            dataType: "json",
            success: function(response) {
                if (response.code == 200) {
                    toastr['success']('data deleted successfully!');
                    main.remove();
                }
                if (response.code == 500) {
                    toastr['error'](response.error);
                }
            },
            error: function() {
                errorMessage = response.error ? response.error : 'data is not correct or duplicate!';
                toastr['error'](errorMessage);
            }
        });
    }
});






$(document).on("click", ".save-intent", function(e) {
    e.preventDefault();
    $.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: "post",
    url: '/chatbot/question/submit',
    data: {
        "group_id": $('.search-intent').val(),
        "question": $(".question-insert").val(),
        "category_id" : $(".search-category").val(),
        "suggested_reply" : $(".reply-insert").val(),
    },
    dataType: "json",
    success: function(response) {
        if (response.code != 200) {
            toastr['error']('Can not store intent please review or use diffrent name!');
        } else {
            $(".question-insert").val('');
            $(".reply-insert").val('');
            toastr['success']('Success!');
            var aliasTemplate = $.templates("#search-alias-template");
            var aliasTemplateHtml = aliasTemplate.render({
                "allSuggestedOptions": response.allSuggestedOptions
            });
            $("#leaf-editor-model").find(".search-alias").html(aliasTemplateHtml);
        }
    },
    error: function() {
        toastr['error']('Can not store intent name please review!');
    }
});
});

$(document).on("click", ".save-example", function(e) {
    e.preventDefault();
    var example = $(".example-insert").val();
    var suggested_reply = $(".reply-insert").val();
    var question = [$(".question-insert").val()];
    

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'post',
        url: '/chatbot/question',
        data: {
            question: question,
            value: example,
            suggested_reply: suggested_reply,
            keyword_or_question: 'intent'
        },
        dataType: "json",
        success: function(response) {
            console.log(response);
            $(".example-insert").val('');
            $(".question-insert").val('');

            if (response.code == 200) {
                toastr['success']('question created successfully!');
                $(".example-insert").val('');
                $(".question-insert").val('');
                $("#leaf-editor-model").modal("hide");
            } else {
                errorMessage = response.error ? response.error : 'data is not correct or duplicate!';
               	toastr['error'](errorMessage);
            }
        },
        error: function() {
            toastr['error']('Could not change module!');
        }
    });
});


$(document).on("click", ".save-dialog-btn", function(e) {
    e.preventDefault();
    var form = $("#dialog-save-response-form");

    $.ajax({
        type: form.attr("method"),
        url: form.attr("action"),
        data: form.serialize(),
        dataType: "json",
        success: function(response) {
            //location.reload();
            if (response.code == 200) {
                toastr['success']('data updated successfully!');
                if(typeof window.pageLocation != "undefined" && window.pageLocation == "autoreply") {
                	location.reload();
                }
                updateBoxEvent(form.find("#parent_id_form").val());
                //window.location.replace(response.redirect);
            } else {
                errorMessage = response.error ? response.error : 'data is not correct or duplicate!';
               	toastr['error'](errorMessage);
            }
        },
        error: function() {
            toastr['error']('Could not change module!');
        }
    });
});
$("#create-keyword-btn").on("click", function() {
    $("#create-dialog").modal("show");
});
$(".select2").select2();
$(".form-save-btn").on("click", function(e) {
    e.preventDefault();
    var form = $(this).closest("form");
    $.ajax({
        type: form.attr("method"),
        url: form.attr("action"),
        data: form.serialize(),
        dataType: "json",
        success: function(response) {
            //location.reload();
            if (response.code == 200) {
                toastr['success']('data updated successfully!');
                window.location.replace(response.redirect);
            } else {
                toastr['error']('data is not correct or duplicate!');
            }
        },
        error: function() {
            toastr['error']('Could not change module!');
        }
    });
});

$(document).on("change",".search-alias",function(){
    var $this = $(this);
    var n = $this.val().indexOf("#");
    if(n > -1) {
        $.ajax({
            type: "get",
            url: "/autoreply/phrases/reply-response",
            data: {keyword : $this.val()},
            dataType: "json",
            success: function(response) {
                if (response.code == 200) {
                     $(".response-value").val(response.data.message);
                } else {
                    toastr['error']('No reply set on group');
                }
            },
            error: function() {
                toastr['error']('Could not get data!');
            }
        });
    }
});

$(document).on("click",".call_child_node",function(e) {
    e.preventDefault();
    var parent_id = $(this).data('id');
    console.log(parent_id);
    updateBoxEvent(parent_id);
});

$(document).on("click", ".view-all-response", function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    $.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: "get",
    url: '/chatbot/dialog/all-reponses/'+id,
    dataType: "html",
    success: function(response) {
        $('#all-response-modal').modal("show");
        $('#all-response-data').html(response);
    },
    error: function() {
        toastr['error']('Can not store intent name please review!');
    }
});
});


$(document).on("click", ".update-response", function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var responseData = $('#response-'+id).val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        url: '/chatbot/dialog/submit-reponse/'+id,
        dataType: "json",
        data: {
            responseData:responseData
        },
        success: function(response) {
            if(response.code == 200) {
                toastr['success'](response.message);
            }
            else {
                toastr['error'](response.message);
            }
        },
        error: function() {
            toastr['error']('Can not store intent name please review!');
        }
    });
});

$(document).on("click", ".btn-search-query", function(e) {
    e.preventDefault();
    updateBoxEvent(0);
});