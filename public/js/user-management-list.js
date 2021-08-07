var userRole;
var userPermission;
var teamLeads = "";
var members;

var page = {
    init: function(settings) {
        
        page.config = {
            bodyView: settings.bodyView
        };
        $.extend(page.config, settings);
        
        page.config.mainUrl = page.config.baseUrl + "/user-management";
        
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

        $(document).on("click",".btn-create-database",function(e) {
            e.preventDefault();
            page.createDatabase($(this));
        });

        $(document).on("click",".create-database-add",function(e) {
            e.preventDefault();
            page.createDatabaseAdd($(this));
        });

        $(document).on("change",".choose-db",function(e) {
            e.preventDefault();
            page.chooseDb($(this));
        });

        $(document).on("click",".delete-database-access",function(e) {
            e.preventDefault();
            if(!confirm("Are you sure you want to remove access for this user?")) {
                return false;
            }else {
                page.deleteDatabaseAccess($(this));
            }
        });

        $(".common-modal").on("click",".submit-goal",function() {
            page.submitSolution($(this));
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

        /*page.config.bodyView.on("click",".load-communication-modal",function(e) {
            page.chatListHistory($(this));
        });*/

        page.config.bodyView.on("click",".load-role-modal",function(e) {
            page.roleModalOpen($(this));
        });

        page.config.bodyView.on("click",".load-permission-modal",function(e) {
            page.permissionModalOpen($(this));
        });

        page.config.bodyView.on("click",".change-activation",function(e) {
            page.userActivate($(this));
        });

        page.config.bodyView.on("click",".load-task-modal",function(e) {
            page.taskHistory($(this));
        });

        page.config.bodyView.on("click",".load-team-add-modal",function(e) {
            page.teamAdd($(this));
        });

        page.config.bodyView.on("click",".load-team-modal",function(e) {
            page.getTeamInfo($(this));
        });
        page.config.bodyView.on("click",".search-team-member",function(e) {
            var keyword = $(this).data('keyword');
            console.log(keyword);
            $('.data-keyword').val(keyword);
            page.getResults();
        });

        $(document).on("keyup",".search-table",function(e) {
            var keyword = $(this).val();
            table = document.getElementById("database-table-list");
            tr = table.getElementsByTagName("tr");
            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                  txtValue = td.textContent || td.innerText;
                  if (txtValue.indexOf(keyword) > -1) {
                    tr[i].style.display = "";
                  } else {
                    tr[i].style.display = "none";
                  }
                }
            }
        });

        $(document).on("click",".assign-permission",function(e) {
            e.preventDefault();
            page.assignPermission($(this));
        });

        $(document).on("click",".user-generate-pem-file",function(e) {
            page.openGenerateFile($(this).data("userid"));
        });

        $(document).on("click",".user-pem-file-history",function(href) {
            page.userProfileHistoryListing($(this).data("userid"));
            
        });

        $(".common-modal").on("click",".submit-role",function() {
            page.submitRole($(this));
        });

        $(".common-modal").on("click",".submit-permission",function() {
            page.submitPermission($(this));
        });

        $(".common-modal").on("click",".submit-team",function() {
            page.submitTeam($(this));
        });

        $(".common-modal").on("click",".edit-team",function() {
            page.submitEditTeam($(this));
        });
        $(".common-modal").on("click",".delete-team",function() {
            page.submitDeleteTeam($(this));
        });

        $(".common-modal").on("keyup",".search-role",function() {
            page.roleSearch();
        });

        $(".common-modal").on("keyup",".search-permission",function() {
            page.permissionSearch();
        });

        $(".common-modal").on("keyup",".search-user",function() {
            page.userSearch();
        });

        $(".common-modal").on("click",".open-permission-input",function() {
            page.toggleDropdown();
        });

        $(".common-modal").on("click",".add-permission",function() {
            page.addPermission();
        });

        page.config.bodyView.on("click",".load-time-modal",function(e) {
            
            page.timeModalOpen($(this));
        });

        page.config.bodyView.on("click",".load-tasktime-modal",function(e) {
            page.taskTimeModalOpen($(this));
        });

        page.config.bodyView.on("click",".load-userdetail-modal",function(e) {
            page.userDetailModalOpen($(this));
        });

        $(".common-modal").on("click",".submit-time",function(e) {
            page.saveTime($(this));
        });

        page.config.bodyView.on("click",".load-avaibility-modal",function(e) {
            page.avaibilityModalOpen($(this));
        });

        $(".common-modal").on("click",".update-avaibility",function() {
            page.updateAvailability($(this));
        });

        page.config.bodyView.on("click",".approve-user",function(e) {
            page.approveUser($(this));
        });

        $(document).on("click",".delete-pem-user",function(e) {
            e.preventDefault();
            page.deletePemUser($(this));
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
        console.log("first");
        var _z = {
            url: this.config.mainUrl+"/records",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults");
    },
    getResults: function(href) {
        console.log(href);
    	var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl+"/records",
            method: "get",
            data : $(".message-search-handler").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults");
    },
    showResults : function(response) {
        // $.each(response.replies, function (k, v) {
        //     $("#page-view-result .quickComment").append("<option value='" + k + "'>" + v + "</option>");
        // });
        // $(".quickComment").select2({tags: true});
        // console.log(response.replies);
        $("#loading-image").hide();
    	var addProductTpl = $.templates("#template-result-block");
        var tplHtml       = addProductTpl.render(response);

        $(".count-text").html("("+response.total+")");
        $(".page_no").val(response.page);

    	page.config.bodyView.find("#page-view-result").html(tplHtml);

    }
    ,
    deleteRecord : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl+ "/"+ele.data("id")+"/delete",
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
        var createWebTemplate = $.templates("#template-create-goal");
        var tplHtml = createWebTemplate.render({data:{}});
        
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },

    editRecord : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl+ "/"+ele.data("id")+"/edit",
            method: "get",
        }
        this.sendAjax(_z, 'editResult');
    },

    editResult : function(response) {
        var createWebTemplate = $.templates("#template-create-goal");
        var tplHtml = createWebTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },
    createDatabase : function(ele) {
        var database_user_id = ele.data("id");
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl + "/"+database_user_id+"/get-database",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showDatabasePopup");

        
    },
    showDatabasePopup : function(response) { 
        $("#loading-image").hide();
        if(response.code == 200){
            var createWebTemplate = $.templates("#template-create-database");
            var tplHtml = createWebTemplate.render(response);
            var common =  $(".common-modal");
                common.find(".modal-dialog").html(tplHtml); 
                common.modal("show");
        }else{
             toastr['error'](response.message, 'error');
        }

    },
    createDatabaseAdd : function(ele) {
        var database_user_id = ele.data("id");
        var _z = {
            url: this.config.mainUrl + "/"+database_user_id+"/create-database",
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveAfterDatabase");
    },
    saveAfterDatabase : function(response) {
        $("#loading-image").hide();
       if(response.code == 200){
            toastr['success'](response.message, 'success');
        }else{
            toastr['error'](response.message, 'error');
        }
    },
    deleteDatabaseAccess : function(ele) {
        var database_user_id = ele.data("id");
        var _z = {
            url: this.config.mainUrl + "/"+database_user_id+"/delete-database-access",
            method: "post",
            data : {
                connection : ele.data("connection")
            },
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "afterDeleteDatabaseAccess");
    },
    afterDeleteDatabaseAccess : function(response) {
        $("#loading-image").hide();
       if(response.code == 200){
            toastr['success'](response.message, 'success');
        }else{
            toastr['error'](response.message, 'error');
        }
    },

    chooseDb :  function(ele) {
        var database_user_id = $("#database-user-id").val();
        var _z = {
            url: this.config.mainUrl + "/"+database_user_id+"/choose-database",
            method: "post",
            data : { 
                connection : ele.val()
            },
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "afterChooseDb");
    },

    afterChooseDb : function(response) {
        $("#loading-image").hide();
        if(response.code == 200){
            var createWebTemplate = $.templates("#template-create-database");
            var tplHtml = createWebTemplate.render(response);
            var common =  $(".common-modal");
                common.find(".modal-dialog").html(tplHtml); 
                common.modal("show");
        }else{
             toastr['error'](response.message, 'error');
        }
    },

    assignPermission : function(ele) {
        var database_user_id = ele.data("id");
        var _z = {
            url: this.config.mainUrl + "/"+database_user_id+"/assign-database-table",
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveAfterDatabase");
    },
    submitSolution : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl + "/save",
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
            page.loadFirst();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    chatListHistory : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/chat-messages/user/"+ele.data("id")+"/loadMoreMessages?limit=1000",
            method: "get",
        }
        this.sendAjax(_z, 'chatListHistoryResult');
    },
    chatListHistoryResult : function(response) {
        var communicationHistoryTemplate = $.templates("#template-communication-history");
        var tplHtml = communicationHistoryTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },
    taskHistory : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/user-management/task/user/"+ele.data("id"),
            method: "get",
        }
        this.sendAjax(_z, 'taskListHistoryResult');
    },
    taskListHistoryResult : function(response) {
        var communicationHistoryTemplate = $.templates("#template-task-history");
        console.log(response);
        var tplHtml = communicationHistoryTemplate.render(response);
        // $('.modal').css({
        //     "padding": "0 !important"
        // });
        // $('.modal .modal-dialog').css({
        //     "width": "100%",
        //     "max-width": "none",
        //     "margin": "0"
        // });
        // $('.modal .modal-content').css({
        //     "border": "0",
        //     "border-radius": "0"
        // });
        // $('.modal .modal-body').css({
        //     "overflow-y": "auto"
        // });
        // $.each(response.statusList, function(key, value) {
        //      $('.statusList')
        //           .append($('<option>', { value : key })
        //           .text(value));
        // });

        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },
    teamAdd : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/user-management/user/team/"+ele.data("id"),
            method: "get",
        }
        this.sendAjax(_z, 'teamResult');
    },
    teamResult : function(response) {
        var communicationHistoryTemplate = $.templates("#template-team-add");
        var tplHtml = communicationHistoryTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },
    submitTeam : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/user-management/user/team/"+ele.data("id"),
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveTeam");
    },
    saveTeam : function(response) {
        if(response.code  == 200) {
            $.each(response.data , function(key, val) { 
                if(val['status'] == "success"){
                    toastr['success'](val['msg'], 'success');
                }else{
                    toastr['error'](val['msg'], 'error');
                }
            });
            page.loadFirst();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.message,"");
        }
    },
    getTeamInfo : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/user-management/user/teams/"+ele.data("id"),
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, 'teamInfoResult');
    },
    teamInfoResult : function(response) {
        members = response.team.members;
        var communicationHistoryTemplate = $.templates("#template-team-edit");
        var tplHtml = communicationHistoryTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
            $("#loading-image").hide();
    },
    submitEditTeam : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/user-management/user/teams/"+ele.data("id"),
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveEditTeam");
    },
    saveEditTeam : function(response) {
        if(response.code  == 200) {
            $.each(response.data , function(key, val) { 
                if(val['status'] == "success"){
                    toastr['success'](val['msg'], 'success');
                }else{
                    toastr['error'](val['msg'], 'error');
                }
            });
            page.loadFirst();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    submitDeleteTeam : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/user-management/user/delete-team/"+ele.data("id"),
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveDeleteTeam");
    },
    saveDeleteTeam : function(response) {
        if(response.code  == 200) {
            toastr['success']('Team delete successfully', 'success');
            page.loadFirst();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    permissionModalOpen : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/user-management/permission/"+ele.data("id"),
            method: "get",
        }
        this.sendAjax(_z, 'permissionResult');
    },
    permissionResult : function(response) {
        userPermission = response.userPermission;
        var communicationHistoryTemplate = $.templates("#template-add-permission");
        var tplHtml = communicationHistoryTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },
    submitPermission : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl + "/permission/"+ele.data("id"),
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "savePermission");
    },
    savePermission : function(response) {
        if(response.code  == 200) {
            toastr['success']('Permission updated successfully', 'success');
            page.loadFirst();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    roleModalOpen : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/user-management/role/"+ele.data("id"),
            method: "get",
        }
        this.sendAjax(_z, 'roleResult');
    },
    roleResult : function(response) {
        userRole = response.userRole;
        var communicationHistoryTemplate = $.templates("#template-add-role");
        var tplHtml = communicationHistoryTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },
    submitRole : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl + "/role/"+ele.data("id"),
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveRole");
    },
    saveRole : function(response) {
        if(response.code  == 200) {
            toastr['success']('Role updated successfully', 'success');
            page.loadFirst();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    roleSearch : function() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("myInputRole");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myRole");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    },
    permissionSearch : function() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    },
    userSearch : function() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    },
    toggleDropdown : function() {
        if ($('#permission-from').hasClass('hidden')) {
            $('#permission-from').removeClass('hidden');
        } else {
            $('#permission-from').addClass('hidden');
        }
    },
    addPermission : function() {
        var name = $('#name').val();
        var route = $('#route').val();
        if(!name && !route) {
            toastr["error"]('Both field is required',"");
            return;
        }
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl + "/add-permission",
            method: "post",
            data : {
                name : name,
                route : route
            }
        }
        this.sendAjax(_z, "permissionAdded");
    },
    permissionAdded : function(response) {
        if(response.code  == 200) {
            toastr['success']('New Permission added successfully', 'success');
            $("#myUL").append('<li style="list-style-type: none;"><a><input type="checkbox" name="permissions[]" value="'+response.permission.id+'"/><strong>'+response.permission.name+'</strong></a></li>');
            $('#name').val('');
            $('#route').val('');
            $('#permission-from').addClass('hidden');
        }
    },
    userActivate : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl + "/"+ele.data("id")+"/activate?page="+$('.page_no').val(),
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveActivate");
    },
    saveActivate : function(response) {
        console.log(response);

        if(response.code  == 200) {
            toastr['success']('Successfully updated', 'success');
            page.getResults(this.config.mainUrl+"/records?page="+response.page);
        }else {
            toastr["error"](response.error,"");
        }
    },
    timeModalOpen : function(ele) {
        var user_id = ele.data("id");
        var _z = {
            url: this.config.baseUrl + "/user-management/user-avl-list/"+ele.data("id"),
            method: "get",
        }
        this.sendAjax(_z, 'resultTimeModal');
    },
    resultTimeModal : function(response) {
        
        var communicationHistoryTemplate = $.templates("#template-add-time");
        var tplHtml = communicationHistoryTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
        //$("#time_user_id").val(user_id);

    },
    taskTimeModalOpen : function(ele) {
        var user_id = ele.data("id");
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/user-management/task-hours/"+ele.data("id"),
            method: "get",
        }
        this.sendAjax(_z, 'avaibilityTaskHourResult');
    },
    userDetailModalOpen : function(ele) {
        var user_id = ele.data("id");
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/user-management/user-details/"+ele.data("id"),
            method: "get",
        }
        this.sendAjax(_z, 'userDetailsResult');
    },
    userDetailsResult : function(response) {
        var taskTimeTemplate = $.templates("#template-userdetails");
        var tplHtml = taskTimeTemplate.render(response);
        // console.log(response,"response");
        // console.log(tplHtml,"tplHtml");
        // console.log(taskTimeTemplate,"taskTimeTemplate");
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },
    avaibilityTaskHourResult : function(response) {
        var taskTimeTemplate = $.templates("#template-taskavaibility");
        var tplHtml = taskTimeTemplate.render(response);
        console.log(response,"response");
        console.log(tplHtml,"tplHtml");
        console.log(taskTimeTemplate,"taskTimeTemplate");
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },
    saveTime : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl + "/user-avaibility/submit-time",
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveTimeResult");
    },
    saveTimeResult : function(response) {
        if(response.code  == 200) {
            toastr['success']('Avaibility saved successfully', 'success');
            page.loadFirst();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    avaibilityModalOpen : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/user-management/user-avaibility/"+ele.data("id"),
            method: "get",
        }
        this.sendAjax(_z, 'avaibilityResult');
    },
    avaibilityResult : function(response) {
        var communicationHistoryTemplate = $.templates("#template-avaibility");
        var tplHtml = communicationHistoryTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },
    updateAvailability : function(ele) {
        var note = $(".note-"+ele.data("id")).val();
        var status = $(".status-"+ele.data("id")).val();
        note = note.trim();
        if(!status || status == '0') {
            if(!note || note == '') {
                toastr["error"]('please provide reason for your absence',"");
            return;
            }
        }
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl + "/user-avaibility/"+ele.data("id"),
            method: "post",
            data : {
                note : note,
                status : status
            },
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "updateAvailabilityResult");
    },
    updateAvailabilityResult : function(response) {
        if(response.code  == 200) {
            toastr['success']('Avaibility updated successfully', 'success');
            page.loadFirst();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    approveUser : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.mainUrl + "/approve-user/"+ele.data("id"),
            method: "post",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "approveUserResult");
    },
    approveUserResult : function(response) {
        console.log(response);
        if(response.code  == 200) {
            toastr['success'](response.message);
            page.loadFirst();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.message,"");
        }
    },
    openGenerateFile : function(userid) {
        var createWebTemplate = $.templates("#user-template-generate-file");
        var tplHtml = createWebTemplate.render({userid});
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml);
            common.modal("show");
              
    },
    userProfileHistoryListing: function(userid) {
        var _z = {
            url: this.config.baseUrl + "/user-management/user-generate-file-listing/"+userid,
            method: "get",
        }
        this.sendAjax(_z, 'showPemUserLiting');
    },
    showPemUserLiting : function(response) {
        if(response.code == 200) {
            var createWebTemplate = $.templates("#pem-file-user-history-lising");
            var tplHtml = createWebTemplate.render(response);
            var common =  $(".common-modal");
                common.find(".modal-dialog").html(tplHtml);
                common.modal("show");
        }        
              
    },

    deletePemUser : function(ele) {
        var _z = {
            url: this.config.baseUrl + "/user-management/delete-pem-file/"+ele.data("id"),
            method: "post"
        }
        this.sendAjax(_z, 'afterDeletePemUser',ele);
    },

    afterDeletePemUser : function(response,ele)  {
        if(response.code == 200) {
            toastr["success"](response.message);
            ele.closest("tr").remove();
        }else{
            toastr["error"](response.message);
            ele.closest("tr").remove();
        } 
    }
  
}


$.extend(page, common);
var template = $.templates("#template-add-role");
$.views.helpers({
    isSelected: function(role) {
        if (Object.values(userRole).indexOf(role) > -1) {
            return 'checked';
         }
         return '';
    }
  }, template);

  var template = $.templates("#template-add-permission");
$.views.helpers({
    isPermissionSelected: function(permission) {
        if (Object.values(userPermission).indexOf(permission) > -1) {
            return 'checked';
         }
         return '';
    }
  }, template);

  var template = $.templates("#template-team-edit");
  $.views.helpers({
    isMemberSelected: function(member) {
          if (Object.values(members).indexOf(member) > -1) {
              return 'checked';
           }
           return '';
      }
    }, template);

    $(document).on('click', '.expand-row', function () {
        var selection = window.getSelection();
        if (selection.toString().length === 0) {
            $(this).find('.div-team-mini').toggleClass('hidden');
            $(this).find('.div-team-max').toggleClass('hidden');
        }
    });

    $(document).on('keypress', '.estimate-time-change', function (e) {
        if (e.which == 13) {
            e.preventDefault();
            let issueId = $(this).data('id');
            let estimate_minutes = $("#estimate_minutes_" + issueId).val();
            let type = $(this).data('type');
            if(type == 'TASK') {
                $.ajax({
                    type: 'POST',
                    url: "/task/update/approximate",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        approximate: estimate_minutes,
                        task_id: issueId
                    },
                    success: function () {
                        toastr["success"]("Estimate Minutes updated successfully!", "Message")
                    }
                });
            }
            else {
                $.ajax({
                    url: "/development/issue/estimate_minutes/assign",
                    data: {
                        estimate_minutes: estimate_minutes,
                        issue_id: issueId
                    },
                    success: function (response) {
                        toastr["success"]("Estimate Minutes updated successfully!", "Message")
                    }
                });
            }
        }
    });

    $(document).on('keypress', '.priority-no-field-change', function (e) {
        if (e.which == 13) {
            e.preventDefault();
            let issueId = $(this).data('id');
            let priority = $(this).val();
            let type = $(this).data('type');
            if(type == 'TASK') {
                $.ajax({
                    type: 'POST',
                    url: "/task/update/priority-no",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        priority: priority,
                        task_id: issueId
                    },
                    success: function () {
                        toastr["success"]("Priority No updated successfully!", "Message")
                    }
                });
            }
            else {
                $.ajax({
                    url: "/development/issue/priority-no/assign",
                    data: {
                        priority: priority,
                        issue_id: issueId
                    },
                    success: function (response) {
                        toastr["success"]("Priority No updated successfully!", "Message")
                    }
                });
            }
        }
    });


    $(document).on('click', '.show-time-history', function() {
        var issueId = $(this).data('id');
        var type = $(this).data('type');
        $('#time_history_div table tbody').html('');
        $('#hidden_task_type').val(type);
        
        if(type == 'TASK') {
            $.ajax({
                url: "/task/time/history",
                data: {id: issueId},
                success: function (data) {
                    if(data != 'error') {
                        $("#developer_task_id").val(issueId);
                        $.each(data, function(i, item) {
                            if(item['is_approved'] == 1) {
                                var checked = 'checked';
                            }
                            else {
                                var checked = ''; 
                            }
                            $('#time_history_div table tbody').append(
                                '<tr>\
                                    <td>'+ moment(item['created_at']).format('DD/MM/YYYY') +'</td>\
                                    <td>'+ ((item['old_value'] != null) ? item['old_value'] : '-') +'</td>\
                                    <td>'+item['new_value']+'</td><td>'+item['name']+'</td><td><input type="radio" name="approve_time" value="'+item['id']+'" '+checked+' class="approve_time"/></td>\
                                </tr>'
                            );
                        });
                    }
                }
            });
        }
        else {
            $.ajax({
                url: "/development/time/history",
                data: {id: issueId},
                success: function (data) {
                    if(data != 'error') {
                        $("#developer_task_id").val(issueId);
                        $.each(data, function(i, item) {
                            if(item['is_approved'] == 1) {
                                var checked = 'checked';
                            }
                            else {
                                var checked = ''; 
                            }
                            $('#time_history_div table tbody').append(
                                '<tr>\
                                    <td>'+ moment(item['created_at']).format('DD/MM/YYYY') +'</td>\
                                    <td>'+ ((item['old_value'] != null) ? item['old_value'] : '-') +'</td>\
                                    <td>'+item['new_value']+'</td><td>'+item['name']+'</td><td><input type="radio" name="approve_time" value="'+item['id']+'" '+checked+' class="approve_time"/></td>\
                                </tr>'
                            );
                        });
                    }
                }
            });
        }
        $('#time_history_modal').modal('show');
    });


    $(document).on('submit', '#approve-time-btn', function(event) {
        event.preventDefault();
        var type = $('#hidden_task_type').val();
        if(type == 'TASK') {
             $.ajax({
                url: "/task/time/history/approve",
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    toastr['success']('Successfully approved', 'success');
                    $('#time_history_modal').modal('hide');
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message);
                }
            });
        }
        else {
            $.ajax({
                url: "/development/time/history/approve",
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    toastr['success']('Successfully approved', 'success');
                    $('#time_history_modal').modal('hide');
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message);
                }
            });
        }
   
    });
    /**
     * show hide description
     */
    $(document).on('click','.show_hide_description',function(){  
		if($(this).next('.description_content:visible').length){
			$(this).html("Show Description");
            $(this).next('.description_content').hide();
        }
		else{
			$(this).html("Hide Description");
            $(this).next('.description_content').show();  
        }      
    });
    
    /**
     * set due date
     */
    $(document).on('click', '.set-due-date', function (e) {
        e.preventDefault();
        var thiss = $(this);
        var task_id = $(this).data('taskid');
        var date = $(this).siblings().find('.due_date_cls').val();
        var type = $(this).siblings().find('.due_date_cls').data('type');
        if (date != '') {
            $.ajax({
                    url: '/task/update/due_date',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    "data": {
                        task_id : task_id,
                        date : date,
                        type : type
                    }
                }).done(function (response) {
                    toastr['success']('Successfully updated');
                }).fail(function (errObj) {
                
                });
        } else {
            alert('Please enter a date first');
        }
    });

$('body').on('focus',".due_date_cls", function(){
        $(this).datetimepicker({
                format: 'YYYY-MM-DD'
            }); 
});