// admin user,group,menus,permission settings
define(function(require, exports) {

    var base = require('app/admin/admin.base');//公共函数
    var site_url = app.site_url;

    function checkNull(str,field){
        if(str.length == 0){
            $(field).focus().parent().find('#status').html('不能为空');
            return false;
        }else{
            $(field).parent().find('#status').html('');
        }
        return true;
    }

    /**
     * login access
     *
     * @param btn
     * @param referer
     */
    exports.adminLogin = function(btn,referer){
        $(btn).live('click',function(){
            // params
            var user = $('#loginForm #user').val();
            var passwd = $('#loginForm #passwd').val();

            if(checkNull(user,$('#loginForm #user'))==true){
            }else{
                return false;
            }

            if(checkNull(passwd,$('#loginForm #passwd'))==true){
            }else{
                return false;
            }

            var data  = {
                'user':user,
                'passwd':passwd
            }
            var url = referer ? referer : site_url+'/index';

            // disable the button
            $(btn).attr('disabled',true);
            // api request
            base.requestApi('/api/admin/login',data,btn,true,function(res){
                console.log(res);
                if(res.error.code==0){
                    base.showTip('ok','登陆成功，即将跳转');
                    setTimeout(function(){
                        window.location.href = site_url+'/';
                    },1500);
                }
            })
        });
    }

    /**
     * update Admin userInfo
     * @param btn
     */
    exports.updateAdminInfo = function(btn){
        $(btn).live('click',function(){
            // params
            var user = $('#updateAdminForm #user').val();
            var true_name = $('#updateAdminForm #true_name').val();
            var email = $('#updateAdminForm #email').val();
            var data = {'user':user,'true_name':true_name,'email':email};

            // disable the button
            $(btn).attr('disabled',true);
            // api request
            base.requestApi('/api/admin/uinfo',data,btn,true,function(res){
                if(res.error.code==0){
                    base.showTip('ok','恭喜您，个人信息修改成功！');
                    $('#updateAdminForm #user').parent().find('#status').text('用于登陆');
                    $('#updateAdminForm #email').parent().find('#status').text('');
                }else if(res.error.code==10005){
                    $('#updateAdminForm #email').focus();
                    $('#updateAdminForm #email').parent().find('#status').text('邮箱已经存在~！');
                }else if(res.error.code==10003){
                    $('#updateAdminForm #user').focus();
                    $('#updateAdminForm #user').parent().find('#status').text('用户已经存在~！');
                }

                // unDisable the button
                $(btn).attr('disabled',false);
            });
        });
    };

    /**
     * update Admin userInfo
     *
     * @param btn
     */
    exports.changeAdminPasswd = function(btn){
        $(btn).live('click',function(){
            // params
            var oldPasswd = $('#changePasswdForm #oldPasswd').val();
            var newPasswd = $('#changePasswdForm #newPasswd').val();
            var data = {'oldPasswd':oldPasswd,'newPasswd':newPasswd};

            // disable the button
            $(btn).attr('disabled',true);
            // api request
            base.requestApi('/api/admin/upass',data,btn,true,function(res){

                if(res.error.code==0){
                    base.showTip('ok','密码更新成功，下次登录有效！');
                    $('#changePasswdForm #oldPasswd').parent().find('#status').text('');
                }else{
                    $('#changePasswdForm #oldPasswd').focus();
                    $('#changePasswdForm #oldPasswd').parent().find('#status').text('原始密码不正确~！');
                }

                // unDisable the button
                $(btn).attr('disabled',false);
            });
        });
    };

    /**
     * add admin user
     *
     * @param btn
     */
    exports.addAdminUser = function (btn){
        $(btn).live('click',function(){
            // params
            var passwd = $('#addAdminForm #newPasswd').val();
            var user = $('#addAdminForm #user').val();
            var uid = $('#addAdminForm #infoBtn').attr('uid');
            var true_name = $('#addAdminForm #true_name').val();
            var email = $('#addAdminForm #email').val();
            var group_id = $('#addAdminForm #group_id').val()
            var data = {'user':user,'true_name':true_name,'email':email,'group_id':group_id,'passwd':passwd};

            // disable the button
            $(btn).attr('disabled',true);
            // api request
            base.requestApi('/api/admin/addAdmin',data,btn,true,function(res){
                if(res.error.code==0){
                    if(res.error.code==0){
                        base.showTip('ok','管理员添加成功！即将跳转');
                        setTimeout(function(){
                            window.location.href=app.site_url+'/admin';
                        },1000);
                    }
                }else if(res.error.code==10005){
                    $('#addAdminForm #email').focus();
                    $('#addAdminForm #email').parent().find('#status').text('邮箱已经存在~！');
                }else if(res.error.code==10003){
                    $('#addAdminForm #user').focus();
                    $('#addAdminForm #user').parent().find('#status').text('用户已经存在~！');
                }

                // cancel to disable the btn
                $(btn).attr('disabled',false);
            });
        })
    };

    /**
     * update another admin info
     *
     * @param btn
     */
    exports.updateAdmin = function (btn){
        $(btn).live('click',function(){
            var user = $('#updateAdminForm #user').val();
            var uid = $('#updateAdminForm #infoBtn').attr('uid');
            var true_name = $('#updateAdminForm #true_name').val();
            var email = $('#updateAdminForm #email').val();
            var group_id = $('#updateAdminForm #group_id').val()
            var data = {'user':user,'true_name':true_name,'email':email,'group_id':group_id,'uid':uid};

            // disable the button
            $(btn).attr('disabled',true);
            // api request
            base.requestApi('/api/admin/update',data,btn,true,function(res){
                base.showTip('ok','恭喜您，个人信息修改成功！');
                if(res.error.code == 0){
                    $('#content').hide().fadeIn(300);

                    $('#updateAdminForm #user').parent().find('#status').text('用于登陆');
                    $('#updateAdminForm #email').parent().find('#status').text('');
                }else if(res.error.code==10005){
                    $('#updateAdminForm #email').focus();
                    $('#updateAdminForm #email').parent().find('#status').text('邮箱已经存在~！');
                }else if(res.error.code==10003){
                    $('#updateAdminForm #user').focus();
                    $('#updateAdminForm #user').parent().find('#status').text('用户已经存在~！');
                }
                // unDisable button
                $(btn).attr('disabled',true);
            })
        });
    };

    /**
     * update another admin passwd
     *
     * @param btn
     */
    exports.resetPasswd = function upInfo(btn){
        $(btn).live('click',function(){
            // params
            var newPasswd = $('#changePasswdForm #newPasswd').val();
            var uid = $('#changePasswdForm #passwdBtn').attr('uid');
            var data = {'uid':uid,'newPasswd':newPasswd};

            // disable the button
            $(btn).attr('disabled',true);
            // api request
            base.requestApi('/api/admin/repass',data,btn,true,function(res){
                if(res.error.code==0){
                    base.showTip('ok','密码更新成功，下次登录有效！');
                    $('#changePasswdForm #oldPasswd').parent().find('#status').text('');
                }

                // unDisable button
                $(btn).attr('disabled',true);
            })

        });
    };
    exports.backup = function() {
        $(function() {
            if ($("#single").attr('checked') == true) {
                $("#backmore").show();
            }
            $("#single").click(function() {
                $("#backmore").show();
            });
            $("#allTables").click(function() {
                $("#backmore").hide();
            });
        });
    };
});