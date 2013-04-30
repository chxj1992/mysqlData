
//用户登陆页
$(document).ready(function(){
    
    //用户登陆
    $("#user-login-submit").click(function(){

        var username = $("#user-name").val();
        var password = $("#user-pass").val();
        $.ajax({
            url:"/User/login/",
            type:"POST",
            dataType:'json',
            data:{
                username:username,
                password:password,
            },
            success:function(data,status){
                if (data.status != 0) {
                    $("#user-login-submit").text('Success');
                    $("#user-login-submit").attr("class","btn btn-success btn-large btn-block");
                    setTimeout(' window.location.href="/"; ',1500);
                } else {
                    $("#user-login-submit").text('Fail');
                    $("#user-login-submit").attr("class","btn btn-warning btn-large btn-block");
                    setTimeout(' $("#user-login-submit").text("Login"); $("#user-login-submit").attr("class","btn btn-primary btn-large btn-block"); ',1500);
                }
            },
        
        });
    });


    $('#user-pass').keydown(function(event){
        var pass = $(this).val();
        if (event.keyCode == "13" && pass != '') {
            $('#user-login-submit').trigger('click');
        }
    }); 

});
