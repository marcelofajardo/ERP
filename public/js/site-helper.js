var siteHelpers = {
    verifyInstruction: function(ele) {
        let instructionId = ele.attr('data-instructionId');
        var params = {
            data: {
                id: instructionId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            url: '/instruction/verify',
            method: 'post',
            dataType: "html"
        }
        siteHelpers.sendAjax(params, "afterVerifyInstrunction", ele);
    },
    afterVerifyInstrunction: function(ele) {
        toastr['success']('Instruction verified successfully', 'success');
        $(ele).html('Verified');
    },
    completeInstruction: function(ele) {
        var params = {
            data: {
                id: ele.data('id'),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            url: '/instruction/complete',
            method: 'post',
            beforeSend: function() {
                ele.text('Loading');
            },
            doneAjax: function(response) {
                ele.parent().append(moment(response.time).format('DD-MM HH:mm'));
                ele.remove();
            },
        }
        siteHelpers.sendAjax(params);
    },
    changeMessageStatus: function(ele) {
        var params = {
            url: ele.data('url'),
            dataType: "html"
        };
        siteHelpers.sendAjax(params, "afterChangeMessageStatus", ele);
    },
    afterChangeMessageStatus: function(ele) {
        ele.closest('tr').removeClass('text-danger');
        ele.closest('td').html('Read');
        ele.remove();
    },
    approveMessage: function(ele) {
        var params = {
            method: 'post',
            data: {
                messageId: ele.data('id'),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            url: "/whatsapp/approve/customer"
        };
        siteHelpers.sendAjax(params, "afterApproveMessage", ele);
    },
    afterApproveMessage: function(ele) {
        ele.parent().html('Approved');
        ele.closest('tr').removeClass('row-highlight');
    },
    changeLeadStatus: function(ele) {
        var lead_id = ele.data('leadid');
        var params = {
            method: 'post',
            data: {
                status: ele.data('id'),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            url: "/leads/" + lead_id + "/changestatus",
            dataType: "html"
        };
        siteHelpers.sendAjax(params, "afterChangeLeadStatus", ele);
    },
    afterChangeLeadStatus: function(ele) {
        ele.parent('div').children().each(function(index) {
            $(this).removeClass('active-bullet-status');
        });
        ele.addClass('active-bullet-status');
    },
    changeOrderStatus: function(ele) {
        var orderId = ele.data('orderid');
        var params = {
            method: 'post',
            data: {
                status: ele.attr('title'),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            url: "/order/" + orderId + "/changestatus",
            dataType: "html"
        };
        siteHelpers.sendAjax(params, "afterChangeLeadStatus", ele);
    },
    afterChangeOrderStatus: function(ele) {
        toastr['success']('Status changed successfully!', 'Success');
        ele.siblings('.change-order-status').removeClass('active-bullet-status');
        ele.addClass('active-bullet-status');
        if (ele.attr('title') == 'Product shiped to Client') {
            $('#tracking-wrapper-' + id).css({
                'display': 'block'
            });
        }
    },
    sendPdf: function(ele) {
        var selectedBox = ele.closest(".send_pdf_selectbox_box");
        var allPdfs = selectedBox.find(".send_pdf_selectbox").select2("val");
        if (allPdfs.length > 0) {
            var params = {
                method: 'post',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    send_pdf: true,
                    customer_id: ele.data("customerid"),
                    images: JSON.stringify([allPdfs]),
                    status: 1,
                    json: 1
                },
                url: "/attachImages/queue"
            };
            siteHelpers.sendAjax(params, "afterSendPdf", ele);
        }
    },
    afterSendPdf: function(response) {
        var closestSelect = response.closest(".send_pdf_selectbox_box");
        if (closestSelect.length > 0) {
            var selectbox = closestSelect.find(".send_pdf_selectbox");
            /*selectbox.val("");
            selectbox.select2("val", "");*/
        }
        toastr["success"]("Message sent successfully!", "Message");
    },
    sendGroup: function(ele, send_pdf) {
        $("#confirmPdf").modal("hide");
        var customerId = ele.data('customerid');
        var groupId = $('#group' + customerId).val();
        var params = {
            method: 'post',
            data: {
                groupId: groupId,
                customerId: customerId,
                _token: $('meta[name="csrf-token"]').attr('content'),
                status: 1,
                send_pdf: send_pdf
            },
            url: "/whatsapp/sendMessage/quicksell_group_send"
        };
        siteHelpers.sendAjax(params, "afterSendGroup", ele);
    },
    afterSendGroup: function(ele) {
        $('#group' + ele.data('customerid')).val('').trigger('change');
        toastr["success"]("Group Message sent successfully!", "Message");
    },
    quickCategoryAdd: function(ele) {
        var textBox = ele.closest("div").find(".quick_category");
        if (textBox.val() == "") {
            alert("Please Enter Category!!");
            return false;
        }
        var params = {
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: textBox.val()
            },
            url: "/add-reply-category"
        };
        siteHelpers.sendAjax(params, "afterQuickCategoryAdd");
    },
    afterQuickCategoryAdd: function(response) {
        $(".quick_category").val('');
        $(".quickCategory").append('<option value="[]" data-id="' + response.data.id + '">' + response.data.name + '</option>');
    },
    deleteQuickCategory: function(ele) {
        var quickCategory = ele.closest(".communication").find(".quickCategory");
        if (quickCategory.val() == "") {
            alert("Please Select Category!!");
            return false;
        }
        var quickCategoryId = quickCategory.children("option:selected").data('id');
        if (!confirm("Are sure you want to delete category?")) {
            return false;
        }
        var params = {
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: quickCategoryId
            },
            url: "/destroy-reply-category"
        };
        siteHelpers.sendAjax(params, "pageReload");
    },
    deleteQuickComment: function(ele) {
        var quickComment = ele.closest(".communication").find(".quickComment");
        if (quickComment.val() == "") {
            alert("Please Select Quick Comment!!");
            return false;
        }
        var quickCommentId = quickComment.children("option:selected").data('id');
        if (!confirm("Are sure you want to delete comment?")) {
            return false;
        }
        var params = {
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            url: "/reply/" + quickCommentId,
        };
        siteHelpers.sendAjax(params, "pageReload");
    },
    pageReload: function(response) {
        location.reload();
    },
    quickCommentAdd: function(ele) {
        var textBox = ele.closest("div").find(".quick_comment");
        var quickCategory = ele.closest(".communication").find(".quickCategory");
        if (textBox.val() == "") {
            alert("Please Enter New Quick Comment!!");
            return false;
        }
        if (quickCategory.val() == "") {
            alert("Please Select Category!!");
            return false;
        }
        var quickCategoryId = quickCategory.children("option:selected").data('id');
        var formData = new FormData();
        formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
        formData.append("reply", textBox.val());
        formData.append("category_id", quickCategoryId);
        formData.append("model", 'Approval Lead');
        var params = {
            method: 'post',
            data: formData,
            url: "/reply"
        };
        siteHelpers.sendFormDataAjax(params, "afterQuickCommentAdd");
    },
    afterQuickCommentAdd: function(reply) {
        $(".quick_comment").val('');
        $('.quickComment').append($('<option>', {
            value: reply,
            text: reply
        }));
    },
    changeQuickCategory: function(ele) {
        if (ele.val() != "") {
            var replies = JSON.parse(ele.val());
            ele.closest(".communication").find('.quickComment').empty();
            ele.closest(".communication").find('.quickComment').append($('<option>', {
                value: '',
                text: 'Quick Reply'
            }));
            replies.forEach(function(reply) {
                ele.closest(".communication").find('.quickComment').append($('<option>', {
                    value: reply.reply,
                    text: reply.reply,
                    'data-id': reply.id
                }));
            });
        }
    },
    changeQuickComment: function(ele) {
        ele.closest('.customer-raw-line').find('.quick-message-field').val(ele.val());
    },
    leadsChart: function() {
        var params = {
            url: '/erp-customer/lead-data?pageType=' + pageType,
        };
        siteHelpers.sendAjax(params, "afterLeadsChart");
    },
    afterLeadsChart: function(datasets) {
        var leadsChart = $('#leadsChart');
        var leadsChartExample = new Chart(leadsChart, {
            type: 'horizontalBar',
            data: {
                labels: ['Status'],
                datasets: datasets
            },
            options: {
                scaleShowValues: true,
                responsive: true,
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontFamily: "'Open Sans Bold', sans-serif",
                            fontSize: 11
                        },
                        stacked: true
                    }],
                    yAxes: [{
                        ticks: {
                            fontFamily: "'Open Sans Bold', sans-serif",
                            fontSize: 11
                        },
                        stacked: true
                    }]
                },
                tooltips: {
                    enabled: false
                },
                animation: {
                    onComplete: function() {
                        var chartInstance = this.chart;
                        var ctx = chartInstance.ctx;
                        ctx.textAlign = "left";
                        ctx.fillStyle = "#fff";
                        Chart.helpers.each(this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            Chart.helpers.each(meta.data.forEach(function(bar, index) {
                                data = dataset.data[index];
                                if (i == 0) {
                                    ctx.fillText(data, 50, bar._model.y + 4);
                                } else {
                                    ctx.fillText(data, bar._model.x - 25, bar._model.y + 4);
                                }
                            }), this)
                        }), this);
                    }
                },
            }
        });
    },
    orderStatusChart: function() {
        var params = {
            url: '/erp-customer/order-status-chart?pageType=' + pageType,
            dataType: "html"
        };
        siteHelpers.sendAjax(params, "afterOrderStatusChart");
    },
    afterOrderStatusChart: function(html) {
        $('.order-status-chart').html(html);
    },
    blockTwilio: function(ele) {
        var params = {
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            url: "/erp-customer/block/" + ele.data('id'),
            beforeSend: function() {
                ele.text('Blocking...');
            },
            doneAjax: function(response) {
                if (response.is_blocked == 1) {
                    ele.html('<img src="/images/blocked-twilio.png" />');
                } else {
                    ele.html('<img src="/images/unblocked-twilio.png" />');
                }
            },
        };
        siteHelpers.sendAjax(params);
    },
    customerSearch: function(ele) {
        ele.select2({
            tags: true,
            width: '100%',
            ajax: {
                url: '/erp-leads/customer-search',
                dataType: 'json',
                delay: 750,
                data: function(params) {
                    return {
                        q: params.term, // search term
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
            },
            placeholder: 'Search for Customer by id, Name, No',
            escapeMarkup: function(markup) {
                return markup;
            },
            minimumInputLength: 1,
            templateResult: function(customer) {
                if (customer.loading) {
                    return customer.name;
                }
                if (customer.name) {
                    return "<p> <b>Id:</b> " + customer.id + (customer.name ? " <b>Name:</b> " + customer.name : "") + (customer.phone ? " <b>Phone:</b> " + customer.phone : "") + "</p>";
                }
            },
            templateSelection: (customer) => customer.text || customer.name,
        });
    },
    userSearch: function(ele) {
        ele.select2({
            ajax: {
                url: '/user-search',
                dataType: 'json',
                delay: 750,
                data: function(params) {
                    return {
                        q: params.term, // search term
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
            },
            placeholder: 'Search for User by Name',
            escapeMarkup: function(markup) {
                return markup;
            },
            minimumInputLength: 2,
            width: '100%',
            templateResult: function(user) {
                return user.name;
            },
            templateSelection: function(user) {
                return user.name;
            },
        });
    },
    productSearch: function(ele) {
        ele.select2({
            ajax: {
                url: '/productSearch/',
                dataType: 'json',
                delay: 750,
                data: function(params) {
                    return {
                        q: params.term, // search term
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
            },
            placeholder: 'Search for Product by id, Name, Sku',
            escapeMarkup: function(markup) {
                return markup;
            },
            minimumInputLength: 2,
            width: '100%',
            templateResult: function(product) {
                if (product.loading) {
                    return product.sku;
                }
                if (product.sku) {
                    return "<p> <b>Id:</b> " + product.id + (product.name ? " <b>Name:</b> " + product.name : "") + " <b>Sku:</b> " + product.sku + " </p>";
                }
            },
            templateSelection: function(product) {
                return product.text || product.name;
            },
        });
    },
    loadCustomers: function(ele) {
        var first_customer = $('#first_customer').val();
        var second_customer = $('#second_customer').val();
        if (first_customer == second_customer) {
            alert('You selected the same customers');
            return;
        }
        var params = {
            data: {
                first_customer: first_customer,
                second_customer: second_customer
            },
            url: "/customers-load",
            beforeSend: function() {
                ele.text('Loading...');
            },
            doneAjax: function(response) {
                $('#first_customer_id').val(response.first_customer.id);
                $('#second_customer_id').val(response.second_customer.id);
                $('#first_customer_name').val(response.first_customer.name);
                $('#first_customer_email').val(response.first_customer.email);
                $('#first_customer_phone').val(response.first_customer.phone ? (response.first_customer.phone).replace(/[\s+]/g, '') : '');
                $('#first_customer_instahandler').val(response.first_customer.instahandler);
                $('#first_customer_rating').val(response.first_customer.rating);
                $('#first_customer_address').val(response.first_customer.address);
                $('#first_customer_city').val(response.first_customer.city);
                $('#first_customer_country').val(response.first_customer.country);
                $('#first_customer_pincode').val(response.first_customer.pincode);
                $('#second_customer_name').val(response.second_customer.name);
                $('#second_customer_email').val(response.second_customer.email);
                $('#second_customer_phone').val(response.second_customer.phone ? (response.second_customer.phone).replace(/[\s+]/g, '') : '');
                $('#second_customer_instahandler').val(response.second_customer.instahandler);
                $('#second_customer_rating').val(response.second_customer.rating);
                $('#second_customer_address').val(response.second_customer.address);
                $('#second_customer_city').val(response.second_customer.city);
                $('#second_customer_country').val(response.second_customer.country);
                $('#second_customer_pincode').val(response.second_customer.pincode);
                $('#customers-data').show();
                $('#mergeButton').prop('disabled', false);
                ele.text('Load Data');
            },
        };
        siteHelpers.sendAjax(params);
    },
    createBroadcast: function(model_id) {
        var customers = [];
        $(".customer_message").each(function() {
            if ($(this).prop("checked") == true) {
                customers.push($(this).val());
            }
        });
        if (all_customers.length != 0) {
            customers = all_customers;
        }
        if (customers.length == 0) {
            alert('Please select customer');
            return false;
        }
        $("#" + model_id).modal("show");
    },
    erpLeadsSendMessage: function() {
        var customers = [];
        $(".customer_message").each(function() {
            if ($(this).prop("checked") == true) {
                customers.push($(this).val());
            }
        });
        if (all_customers.length != 0) {
            customers = all_customers;
        }
        if (customers.length == 0) {
            alert('Please select customer');
            return false;
        }
        if ($("#send_message").find("#message_to_all_field").val() == "") {
            alert('Please type message ');
            return false;
        }
        if ($("#send_message").find(".ddl-select-product").val() == "" && $("#send_message").find("#product_start_date").val() == "") {
            alert('Please select product');
            return false;
        }
        var params = {
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                products: $("#send_message").find(".ddl-select-product").val(),
                sending_time: $("#send_message").find("#sending_time_field").val(),
                message: $("#send_message").find("#message_to_all_field").val(),
                product_start_date: $("#send_message").find("#product_start_date").val(),
                product_end_date: $("#send_message").find("#product_end_date").val(),
                customers: customers
            },
            url: "/erp-leads-send-message",
            doneAjax: function(response) {
                window.location.reload();
            },
        };
        siteHelpers.sendAjax(params);
    },
    instructionStore: function(ele) {
        var customer_id = ele.closest('form').find('input[name="customer_id"]').val();
        var instruction = ele.closest('form').find('input[name="instruction"]').val();
        var category_id = ele.closest('form').find('input[name="category_id"]').val();
        var assigned_to = ele.closest('form').find('input[name="assigned_to"]').val();
        var params = {
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                customer_id: customer_id,
                instruction: instruction,
                category_id: category_id,
                assigned_to: assigned_to,
            },
            url: ele.closest('form').attr('action')
        };
        siteHelpers.sendAjax(params);
    },
    updateBroadCastList: function(customerId, needtoShowModel) {
        var params = {
            data: {
                customer_id: customerId
            },
            url: "/customer/broadcast",
            doneAjax: function(response) {
                var html = "Sorry, There is no available broadcast";
                if (response.code == 1) {
                    html = "";
                    if (response.data.length > 0) {
                        $.each(response.data, function(k, v) {
                            html += '<button class="badge badge-default broadcast-list-rndr" data-customer-id="' + customerId + '" data-id="' + v.id + '">' + v.id + '</button>';
                        });
                    } else {
                        html = "Sorry, There is no available broadcast";
                    }
                }
                $("#broadcast-list").find(".modal-body").html(html);
                if (needtoShowModel && typeof needtoShowModel != "undefined") {
                    $("#broadcast-list").modal("show");
                }
            },
        };
        siteHelpers.sendAjax(params);
    },
    broadcastListCreateLead: function(ele) {
        var $this = ele;
        var checkedProducts = $("#broadcast-list").find("input[name='selecte_products_lead[]']:checked");
        var checkedProdctsArr = [];
        if (checkedProducts.length > 0) {
            $.each(checkedProducts, function(e, v) {
                checkedProdctsArr += "," + $(v).val();
            })
        }
        var selectionLead = $("#broadcast-list").find(".selection-broadcast-list").first();
        $("#broadcast-list-approval").find(".broadcast-list-approval-btn").data("customer-id", selectionLead.data("customer-id"));
        $("#broadcast-list-approval").modal("show");
        $(".broadcast-list-approval-btn").unbind().on("click", function() {
            var $this = $(this);
            var params = {
                data: {
                    customer_id: $this.data("customer-id"),
                    product_to_be_run: checkedProdctsArr
                },
                url: "/customer/broadcast-send-price",
                beforeSend: function() {
                    $this.html('Sending Request...');
                },
                doneAjax: function(response) {
                    $this.html('Yes');
                    $("#broadcast-list-approval").modal("hide");
                    $("#broadcast-list").modal("hide");
                },
            };
            siteHelpers.sendAjax(params);
        });
    },
    sendInstock: function(ele) {
        var customer_id = ele.data('id');
        var params = {
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                customer_id: customer_id
            },
            url: "/customer/send/instock",
            beforeSend: function() {
                ele.text('Sending...');
            },
            doneAjax: function(response) {
                ele.text('Send In Stock');
            },
        };
        siteHelpers.sendAjax(params);
    },
    sendScraped: function(ele) {
        var formData = $('#categoryBrandModal').find('form').serialize();
        var thiss = ele;
        if (!ele.is(':disabled')) {
            var params = {
                method: 'post',
                dataType: "html",
                data: formData,
                url: "/customer/sendScraped/images",
                beforeSend: function() {
                    ele.text('Sending...');
                    ele.attr('disabled', true);
                },
                doneAjax: function(response) {
                    $('#categoryBrandModal').find('.close').click();
                    ele.text('Send');
                    ele.attr('disabled', false);
                },
            };
            siteHelpers.sendAjax(params);
        }
    },
    changeStatus: function(ele) {
        var status = ele.val();
        if (ele.hasClass('order_status')) {
            var id = ele.data('orderid');
            var url = '/order/' + id + '/changestatus';
        } else {
            var id = ele.data('leadid');
            var url = '/erp-leads/' + id + '/changestatus';
        }
        var params = {
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                status: status
            },
            dataType: "html",
            url: url,
            doneAjax: function(response) {
                if (ele.hasClass('order_status') && status == 'Product shiped to Client') {
                    $('#tracking-wrapper-' + id).css({
                        'display': 'block'
                    });
                }
                ele.siblings('.change_status_message').fadeIn(400);
                setTimeout(function() {
                    ele.siblings('.change_status_message').fadeOut(400);
                }, 2000);
            },
        };
        siteHelpers.sendAjax(params);
    },
    sendMessage: function(ele) {
        var message = ele.siblings('textarea').val();
        var customer_id = ele.data('customerid');
        if (message.length > 0 && !ele.is(':disabled')) {
            var data = new FormData();
            data.append("_token", $('meta[name="csrf-token"]').attr('content'));
            data.append("customer_id", customer_id);
            data.append("message", message);
            data.append("status", 1);
            var params = {
                method: 'post',
                data: data,
                url: '/whatsapp/sendMessage/customer',
                beforeSend: function() {
                    ele.attr('disabled', true);
                },
                doneAjax: function(response) {
                    ele.siblings('textarea').val('');
                    ele.attr('disabled', false);
                    var messageBx = ele.closest("tr").find(".message-chat-txt");
                        messageBx.html(message.substring(0,20));
                        messageBx.attr("data-content",message);
                        messageBx.attr("data-content",message);
                }
            };
            siteHelpers.sendFormDataAjax(params);
        }
    },
    sendMessageMaltiCustomer: function(ele) {
        var customers = [];
        $(".customer_message").each(function() {
            if ($(this).prop("checked") == true) {
                customers.push($(this).val());
            }
        });
        if (all_customers.length != 0) {
            customers = all_customers;
        }
        if (customers.length == 0) {
            alert('Please select customer');
            return false;
        }
        var form = ele.closest('form');
        var message = form.find('.quick-message-field').val();
        if (!ele.is(':disabled')) {
            var data = new FormData();
            data.append("_token", $('meta[name="csrf-token"]').attr('content'));
            data.append("customers_id", customers.join());
            data.append("message", message);
            data.append("status", 1);
            data.append("brand", form.find("#product-brand").val());
            data.append("category", form.find("#category").val());
            data.append("number_of_products", form.find("#number_of_products").val());
            data.append("quick_sell_groups", form.find("#product-quick-sell-groups").val());
            var params = {
                method: 'post',
                data: data,
                url: '/selected_customer/sendMessage',
                beforeSend: function() {
                    ele.attr('disabled', true);
                },
                doneAjax: function(response) {
                    ele.attr('disabled', false);
                    $("#sendCustomerMessage").modal("hide");
                }
            };
            siteHelpers.sendFormDataAjax(params);
        }
    },
    flagCustomer: function(ele) {
        var customer_id = ele.data('id');
        var params = {
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                customer_id: customer_id
            },
            url: "/customer/flag",
            beforeSend: function() {
                ele.text('Flagging...');
            },
            doneAjax: function(response) {
                if (response.is_flagged == 1) {
                    ele.html('<img src="/images/flagged.png" />');
                } else {
                    ele.html('<img src="/images/unflagged.png" />');
                }
            },
        };
        siteHelpers.sendAjax(params);
    },
    addInWhatsappList: function(ele) {
        var customer_id = ele.data('id');
        var params = {
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                customer_id: customer_id
            },
            url: "/customer/in-w-list",
            beforeSend: function() {
                ele.text('Sending...');
            },
            doneAjax: function(response) {
                if (response.in_w_list == 1) {
                    ele.html('<img src="/images/completed-green.png" />');
                } else {
                    ele.html('<img src="/images/completed.png" />');
                }
            },
        };
        siteHelpers.sendAjax(params);
    },
    priorityCustomer: function(ele) {
        var customer_id = ele.data('id');
        var params = {
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                customer_id: customer_id
            },
            url: "/customer/prioritize",
            beforeSend: function() {
                ele.text('Prioritizing...');
            },
            doneAjax: function(response) {
                if (response.is_priority == 1) {
                    ele.html('<img src="/images/customer-priority.png" />');
                } else {
                    ele.html('<img src="/images/customer-not-priority.png" />');
                }
            },
        };
        siteHelpers.sendAjax(params);
    },
    storeReminder: function(ele) {
        var reminderModal = $('#reminderModal');
        var customerIdToRemind = reminderModal.find('input[name="customer_id"]').val();
        var frequency = reminderModal.find('#frequency').val();
        var message = reminderModal.find('#reminder_message').val();
        var reminder_from = reminderModal.find('#reminder_from').val();
        var reminder_last_reply = (reminderModal.find('#reminder_last_reply').is(":checked")) ? 1 : 0;
        var params = {
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                customer_id: customerIdToRemind,
                frequency: frequency,
                message: message,
                reminder_from: reminder_from,
                reminder_last_reply: reminder_last_reply
            },
            url: "/customer/reminder",
            doneAjax: function(response) {
                $(".set-reminder[data-id='" + customerIdToRemind + "']").data('frequency', frequency);
                $(".set-reminder[data-id='" + customerIdToRemind + "']").data('reminder_message', message);
                toastr['success']('Reminder updated successfully!');
                $("#reminderModal").modal("hide");
            },
        };
        siteHelpers.sendAjax(params);
    },
    sendContactUser: function(ele) {
        var $form = $("#send-contact-to-user");
        var params = {
            method: 'post',
            data: $form.serialize(),
            url: "/customer/send-contact-details",
            beforeSend: function() {
                ele.text('Sending message...');
            },
            doneAjax: function(response) {
                ele.html('<img style="width: 17px;" src="/images/filled-sent.png">');
                $("#sendContacts").modal("hide");
            },
        };
        siteHelpers.sendAjax(params);
    },
    approveMessageSession: function(ele) {
        var params = {
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                text: ele.text()
            },
            url: "/erp-customer/approve-message-session",
            doneAjax: function(response) {
                ele.text(response.text);
                ele.removeClass('btn-success').removeClass('btn-default').addClass(response.class);
            },
        };
        siteHelpers.sendAjax(params);
    },
    autoRefreshColumn: function() {
        var params = {
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                customers_id: $('input[name="paginate_customer_ids"]').val(),
                type: "{{ request()->get('type','any') }}"
            },
            url: "/erp-customer/auto-refresh-column",
            doneAjax: function(response) {
                $.each(response, function(k, customer) {
                    $.each(customer, function(k, td_data) {
                        var needaBox = false;
                        if (typeof td_data.last_message != "undefined" && typeof td_data.last_message.full_message != "undefined") {
                            var box = $(td_data.class).find(".message-chat-txt");
                            if (box.length > 0) {
                                box.attr("data-content", td_data.last_message.full_message);
                                $(td_data.class).find(".add-chat-phrases").attr("data-message", td_data.last_message.full_message);
                                box.html(td_data.last_message.short_message);
                            } else {
                                $(td_data.class).html(td_data.html);
                            }
                        } else {
                            $(td_data.class).html(td_data.html);
                        }
                    });
                });
                $('[data-toggle="popover"]').popover();
                setTimeout(function() {
                    if (!isTextMessageFocused) siteHelpers.autoRefreshColumn();
                }, 10000);
            },
        };
        siteHelpers.sendAjax(params);
    },
    selectAllCustomer: function(ele) {
        if (ele.text() == 'Unselect All Customers') {
            all_customers = [];
            $(".customer_message").prop("checked", false);
            ele.text('Select All Customers');
            return false;
        }
        var params = {
            method: 'get',
            data: $('#search_frm').serialize(),
            url: "/erp-customer/customer-ids?get_customer_ids=1&pageType=" + pageType,
            beforeSend: function() {
                ele.text('Select...');
                ele.attr('disabled', true);
            },
            doneAjax: function(response) {
                $(".customer_message").prop("checked", true);
                all_customers = response;
                ele.text('Unselect All Customers');
                ele.attr('disabled', false);
            },
        };
        siteHelpers.sendAjax(params);
    },
    updatedShoeSize: function(ele) {
        var params = {
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                shoe_size: ele.val()
            },
            url: "/erp-customer/" + ele.data('id') + "/update",
            beforeSend: function() {
                ele.attr('disabled', true);
            },
            doneAjax: function(response) {
                ele.attr('disabled', false);
            },
        };
        siteHelpers.sendAjax(params);
    },
    addErpLead: function(ele, thiss) {
        var url = ele.attr('action');
        if (ele.find('.multi_brand').val() == "") {
            alert('Please Select Brand');
            return false;
        }
        if (ele.find('input[name="category_id"]').val() == "") {
            alert('Please Select Category');
            return false;
        }
        if (ele.find('input[name="lead_status_id"]').val() == "") {
            alert('Please Select Status');
            return false;
        }
        var formData = new FormData(thiss);
        var params = {
            method: 'POST',
            data: formData,
            url: url,
            doneAjax: function(response) {
                toastr['success']('Lead add successfully!');
                $('#add_lead').modal('hide');
                if ($('#add_lead').find('input[name="product_id"]').length > 0 && $('#add_lead').find('input[name="product_id"]').val()) {
                    var dataSending = $('#add_lead').find('input[name="product_id"]').data('object');
                    if (typeof dataSending != 'object') {
                        dataSending = {};
                    }
                    var params = {
                        method: 'post',
                        data: $.extend({
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            customer_id: $('#add_lead').find('input[name="customer_id"]').val(),
                            selected_product: [$('#add_lead').find('input[name="product_id"]').val()],
                            auto_approve: 1
                        }, dataSending),
                        url: "/leads/sendPrices",
                    };
                    siteHelpers.sendAjax(params);
                }
            }
        };
        siteHelpers.sendFormDataAjax(params);
    },
    addNextAction: function(ele) {
        var textBox = ele.closest(".row_next_action").find(".add_next_action_txt");
        if (textBox.val() == "") {
            alert("Please Enter New Next Action!!");
            return false;
        }
        var params = {
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: textBox.val()
            },
            doneAjax: function(response) {
                toastr['success']('Successfully add!');
                textBox.val('');
                $(".next_action").append('<option value="' + response.id + '">' + response.name + '</option>');
            },
            url: "/erp-customer/add-next-actions"
        };
        siteHelpers.sendAjax(params);
    },
    deleteNextAction: function(ele) {
        var nextAction = ele.closest(".row_next_action").find(".next_action");
        if (nextAction.val() == "") {
            alert("Please Select Next Action!!");
            return false;
        }
        var nextActionId = nextAction.val();
        if (!confirm("Are sure you want to delete Next Action?")) {
            return false;
        }
        var params = {
            method: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: nextActionId
            },
            url: "/erp-customer/destroy-next-actions"
        };
        siteHelpers.sendAjax(params, "pageReload");
    },
    changeNextAction: function(ele) {
        var params = {
            method: 'post',
            data: {
                customer_next_action_id: ele.val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            url: "/erp-customer/" + ele.data('id') + "/update",
            doneAjax: function(response) {
                toastr['success']('Next Action changed successfully!', 'Success');
            },
        };
        siteHelpers.sendAjax(params);
    },
    addOrderFrm: function(ele, thiss) {
        var url = ele.attr('action');
        var formData = new FormData(thiss);
        var params = {
            method: 'POST',
            data: formData,
            url: url,
            doneAjax: function(response) {
                toastr['success']('Order add successfully!');
                if ($('#add_order').find('input[name="selected_product[]"]').length > 0 && $('#add_order').find('input[name="selected_product[]"]').val()) {
                    var params = {
                        method: 'post',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            customer_id: $('#add_order').find('input[name="customer_id"]').val(),
                            order_id: response.order.id,
                            selected_product: [$('#add_order').find('input[name="selected_product[]"]').val()]
                        },
                        url: "/order/send/Delivery",
                    };
                    siteHelpers.sendAjax(params);
                }
                $('#add_order').modal('hide');
            }
        };
        siteHelpers.sendFormDataAjax(params);
    }
};
$.extend(siteHelpers, common);