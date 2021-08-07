$(function() {
    $(document).on('click','.send-email-common-btn',function(e){
        e.preventDefault();
        var mailtype = $(this).data('object');
        var id = $(this).data('id');
        var toemail = $(this).data('toemail');
        $('#commonEmailModal').find('form').find('input[name="id"]').val(id);
        $('#commonEmailModal').find('form').find('input[name="sendto"]').val(toemail);
        $('#commonEmailModal').find('form').find('input[name="object"]').val(mailtype);
        $('#commonEmailModal').modal("show");
    });

    $(document).on('change','.getTemplateData',function(e){
        e.preventDefault();
        var mailtemplateid = $(this).val();
        var ele = $(this).parentsUntil('form').parent();
        var action = ele.find('.action').val();
        if(mailtemplateid==''){
            ele.find('textarea[name="message"]').val('');
            ele.find('input[name="subject"]').val('');
            return;
        }
        $.ajax({
            'url':action,
            'data':{mailtemplateid:mailtemplateid},
            'type':'GET',
            dataType:'json',
            success:function(response){
                if(typeof response!=='undefined' && response.success==true){
                    ele.find('textarea[name="message"]').val(response.template);
                    ele.find('input[name="subject"]').val(response.subject);
                }else{
                    console.log(response.error);
                }
            }
        });
    });
});
