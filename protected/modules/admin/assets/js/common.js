$(document).ready(function(){
    $("#countActiv button").on('click', function(){
        $("#countActiv button").removeClass("active");
        $(this).addClass("active");
        
        $("#hidescreen, #loadingData").fadeIn(10);
        $("#countData").load('/admin/dashboard/getCountActiv?type='+$(this).attr('type'), function(){$("#hidescreen, #loadingData").fadeOut(10);});
    });
    
    var ajaxUpdateTimeout, ajaxRequest;
    $('#filterUserForm :input').change(function(){
        ajaxRequest = $(this).serialize();
        clearTimeout(ajaxUpdateTimeout);
        ajaxUpdateTimeout = setTimeout(function(){
            $.fn.yiiGridView.update('userList', {data: ajaxRequest})
        }, 300);
    });
    $('#filterPayForm :input').change(function(){
        ajaxRequest = $(this).serialize();
        clearTimeout(ajaxUpdateTimeout);
        ajaxUpdateTimeout = setTimeout(function(){
            $.fn.yiiGridView.update('payList', {data: ajaxRequest})
        }, 300);
    });
    $('#filterOutForm :input').change(function(){
        ajaxRequest = $(this).serialize();
        clearTimeout(ajaxUpdateTimeout);
        ajaxUpdateTimeout = setTimeout(function(){
            $.fn.yiiGridView.update('outList', {data: ajaxRequest})
        }, 300);
    });
    $('#filterProfitForm :input').change(function(){
        ajaxRequest = $(this).serialize();
        clearTimeout(ajaxUpdateTimeout);
        ajaxUpdateTimeout = setTimeout(function(){
            $.fn.yiiGridView.update('profitList', {data: ajaxRequest})
        }, 300);
    });
    $('#filterFeedbackForm :input').change(function(){
        ajaxRequest = $(this).serialize();
        clearTimeout(ajaxUpdateTimeout);
        ajaxUpdateTimeout = setTimeout(function(){
            $.fn.yiiGridView.update('feedbackList', {data: ajaxRequest})
        }, 300);
    });
    
    $("#btn_addWeight").on('click', function(){
        $("#hidescreen, #loadingData").fadeIn(10);
        $("#modalData").load('/admin/profit/workProfit?type=weight', function(){
            $.fancybox.open($("#modalWindow"));
            $("#hidescreen, #loadingData").fadeOut(10);
        });
    });
    $("#btn_addGold").on('click', function(){
        $("#hidescreen, #loadingData").fadeIn(10);
        $("#modalData").load('/admin/profit/workProfit?type=summ', function(){
            $.fancybox.open($("#modalWindow"));
            $("#hidescreen, #loadingData").fadeOut(10);
        });
    });
});

function showNoty(message, type, position = false)
{
    $.notify(message, {
        position: position ? position : "top left",
        className: type,
        autoHideDelay: 3000
    });
}

function getReferralShortInfo(data, short = false)
{
    var rID = short ? data : data.html();
    
    $.ajax({
        url: '/user/profile/getShortInfo?referral_id='+rID,
        type: 'get',
        cache: false,
        beforeSend: function(){$("#hidescreen, #loadingData").fadeIn(10);},
        success: function(html){  
            $("#modalData").html(html);
            if($.fancybox.getInstance() === false)
                $.fancybox.open($("#modalWindow"));
            
            $("#hidescreen, #loadingData").fadeOut(10);
        } 
    });
}

function cpassword(user_id)
{
    $("#modalData").load('/admin/users/changePassword?id='+user_id, function(){
        if($.fancybox.getInstance() === false)
            $.fancybox.open($("#modalWindow"));
    });
}