$(document).ready(function(){
    $("#rCopyLink").on('click', function(){
        var $tmp = $("<input>");
        $("body").append($tmp);
        $tmp.val($('#rLink').text()).select();
        document.execCommand("copy");
        $tmp.remove();
        alert($('#rLink').attr('message'));
    });
    
    $("#filterBalance :input").on('change', function(){
        var ajaxUpdateTimeout, ajaxRequest;
        
        ajaxRequest = $(this).serialize();
        clearTimeout(ajaxUpdateTimeout);
        ajaxUpdateTimeout = setTimeout(function(){
            $.fn.yiiGridView.update('operationList', {data: ajaxRequest})
        }, 300);
    });
    
    $("[id^='payLink']").on('click', function(){
        $("#hidescreen, #loadingData").fadeIn(10);
        $("#modalData").load('/finance/pay', function(){
            $.fancybox.open($("#modalWindow"), {touch: false, toolbar: false,hash: false,clickSlide: false});
            $("#hidescreen, #loadingData").fadeOut(10);
        });
    });
    $("[id^='outLink']").on('click', function(){
        $("#hidescreen, #loadingData").fadeIn(10);
        $("#modalData").load('/finance/out', function(){
            $.fancybox.open($("#modalWindow"));
            $("#hidescreen, #loadingData").fadeOut(10);
        });
    });
    $("[id^='buyCoins']").on('click', function(){
        $("#hidescreen, #loadingData").fadeIn(10);
        $("#modalData").load('/exchange/buyCoins', function(){
            $.fancybox.open($("#modalWindow"));
            $("#hidescreen, #loadingData").fadeOut(10);
        });
    });
    $("[id^='depPay']").on('click', function(){
        $("#hidescreen, #loadingData").fadeIn(10);
        $("#modalData").load('/finance/deposit?type=0', function(){
            $.fancybox.open($("#modalWindow"));
            $("#hidescreen, #loadingData").fadeOut(10);
        });
    });
    $("[id^='depOut']").on('click', function(){
        $("#hidescreen, #loadingData").fadeIn(10);
        $("#modalData").load('/finance/deposit?type=1', function(){
            $.fancybox.open($("#modalWindow"));
            $("#hidescreen, #loadingData").fadeOut(10);
        });
    });
    $("#infoDepFreeze").on('click', function(){
        $("#hidescreen, #loadingData").fadeIn(10);
        $("#modalData").load('/finance/depositFreezeView', function(){
            $.fancybox.open($("#modalWindow"));
            $("#hidescreen, #loadingData").fadeOut(10);
        });
    });
    
    $("[id^='buyStatusLink']").on('click', function(){
        $("#hidescreen, #loadingData").fadeIn(10);
        $("#modalData").load('/finance/buyStatus', function(){
            $.fancybox.open($("#modalWindow"));
            $("#hidescreen, #loadingData").fadeOut(10);
        });
    });
    
    
    $('#levelInfo').on('click', function(){
        $.ajax({
            url: '/rnetwork/getLevelInfo',
            type: 'get',
            cache: false,
            beforeSend: function(){
                $("#spinner_level").removeClass('d-none');
            },
            success: function(html){
                $("#spinner_level").addClass('d-none');
                $("#modalData").html(html);
                $.fancybox.open($("#modalWindow"));
            } 
        });
    });
    
    $('#statusInfo').on('click', function(){
        $.ajax({
            url: '/rnetwork/getStatusInfo',
            type: 'get',
            cache: false,
            beforeSend: function(){
                $("#spinner_status").removeClass('d-none');
            },
            success: function(html){
                $("#spinner_status").addClass('d-none');
                $("#modalData").html(html);
                $.fancybox.open($("#modalWindow"));
            } 
        });
    });
    
    $("#btn_invite").on('click', function(){
        $("#hidescreen, #loadingData").fadeIn(10);
        $("#modalData").load('/rnetwork/invite', function(){
            $.fancybox.open($("#modalWindow"));
            $("#hidescreen, #loadingData").fadeOut(10);
        });
    });
    
    $("#btn_mainRegister").on('click', function(){
        $("#hidescreen, #loadingData").fadeIn(10);
        $("#modalData").load('/rnetwork/mainRegister', function(){
            $.fancybox.open($("#modalWindow"));
            $("#hidescreen, #loadingData").fadeOut(10);
        });
    });
    
    $("#terms_agree_register").on('click', function(){
        $("#hidescreen, #loadingData").fadeIn(10);
        $("#modalData").load('/info/full_terms/?partial=1', function(){
            $.fancybox.open($("#modalWindow"));
            $("#hidescreen, #loadingData").fadeOut(10);
        });
    });
    
    $("#googleAuth_button").on('click', function(){
        $.ajax({
            url: '/user/profile/googleAuth',
            type: 'get',
            cache: false,
            success: function(html){  
                $("#modalData").html(html);
                $.fancybox.open($("#modalWindow"), {touch: false, toolbar: false, hash: false, clickSlide: false});
            } 
        });
    });
    
    $("#btn_order_buy").on('click', function(){
        $("#modalData").load('/exchange/orderBuy', function(){
            $.fancybox.open($("#modalWindow"));
        });
    });
    $("#btn_order_sell").on('click', function(){
        $("#modalData").load('/exchange/sell', function(){
            $.fancybox.open($("#modalWindow"));
        });
    });
    $("#fin_acc").on('click', function(){
        $("#modalData").load('/user/profile/setfinanceacc', function(){
            $.fancybox.open($("#modalWindow"));
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
                $.fancybox.open($("#modalWindow"), {touch: false,toolbar: false,hash: false,clickSlide: false});
            
            $("#hidescreen, #loadingData").fadeOut(10);
        } 
    });
}

function cpassword(close_button = true)
{
    $("#modalData").load('/user/profile/changePassword?return='+close_button, function(){
        if($.fancybox.getInstance() === false)
            $.fancybox.open($("#modalWindow"), {modal: close_button});
    });
}

function showwarning()
{
    $("#modalData").load('/user/profile/warning', function(){
        if($.fancybox.getInstance() === false)
            $.fancybox.open($("#modalWindow"));
    });
}

function cemail(close_button = true)
{
    $("#modalData").load('/user/profile/changeEmail', function(){
        if($.fancybox.getInstance() === false)
            $.fancybox.open($("#modalWindow"), {modal: close_button});
    });
}

function openDataWindow()
{
    $("#modalData").load('/user/profile/checkUserData', function(){
        if($.fancybox.getInstance() === false)
            $.fancybox.open($("#modalWindow"), {modal: true});
    });
}

function getOperationInfo(id)
{
    $.ajax({
        url: '/finance/getOpertaionInfo?id='+id,
        type: 'get',
        cache: false,
        success: function(html){  
            $("#modalData").html(html);
            $.fancybox.open($("#modalWindow"));
        } 
    });
}

function updateExchangeGrids()
{
    setTimeout(function(){
        $.fn.yiiGridView.update("orderBuy");
        $.fn.yiiGridView.update("orderSell");
        
        $.ajax({
            url: '/exchange/getNowCourse',
            type: 'get',
            cache: false,
            success: function(data){  
                $("#nowCourse").html(data.course);
                $("#nowCountBuy").html(data.count_buy);
                $("#nowCountSell").html(data.count_sell);
                $("#nowCountClosed").html(data.count_closed);
            } 
        });
        
        updateExchangeGrids();
    }, 3000);
}