/*
 * 第三方绑定功能 - 注册/登录并绑定过程
 * 说明: 这是跳转到第三方登录成功后的页面处理脚本 对应html文件oauth.php
 * 请注意自行修改ajaxEmail,ajaxName,login,reg等几个api地址和参数
 *
 * 使用方法:[seajs模块化]
 // 注册登录绑定
 seajs.use('app/looklo_sign_oauth', function(b){
 b.init();
 });
 *
 * @author yanue
 * @time 2012.09.16
 */
define(function(require, exports) {
    require('tools/encrypt');

    var baseUrl = app.site_url;

    /*
     * 未登录帐号绑定
     */
    exports.orRegBind = function() {
        new Bind();
    };

    /*
     * 登陆用户绑定第三方
     */
    exports.gobind = function() {
        $(".oauth-bind .bind").live('click',function(){
            var site = $(this).attr('data-site');
            var gobindurl = baseUrl + 'oauth/go?site=' + site;
            toOauthWindow(gobindurl);
        });
        $(".oauth-bind .unbind").live('click',function(){
            var code = $(this).attr('data-site');
            var unbindurl = baseUrl + 'oauth/unbind?site=' + code;
            toOauthWindow(unbindurl);
        });
        function toOauthWindow(url) {
            window.open(url,"OauthWindow","width=600,height=400,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");
        }


    };

    /*
    * 第三方绑定功能 - 注册/登录并绑定过程
    * 请注意自行修改ajaxEmail,ajaxName,login,reg等几个api地址和参数
    * */
    function Bind() {
        this.change();
        this.login();
        this.reg();
        this.checkName();
        this.checkEmail();
        this.url_args();
    }
    Bind.email_ok = false;
    Bind.name_ok = false;
    Bind.url = '';
    Bind.prototype = {
        change : function() {
            $("#bind_new").live('click',function() {
                $('#new').fadeIn();
                $('#old').hide();
                $(this).siblings().removeClass('current');
                $(this).addClass('current');
            });
            $("#bind_old").live('click',function() {
                $('#old').fadeIn();
                $('#new').hide();
                $(this).siblings().removeClass('current');
                $(this).addClass('current');
            });
            $('.bindOld').live('click', function() {
                $('#old').fadeIn();
                $('#new').hide();
                $('.tab a').removeClass('current');
                $('#bind_old').addClass('current');
                if ($(this).hasClass('oldemail')) {
                    $("#login_email").val($("#reg_email").val());
                }
            });
            $('#goReg').live('click', function() {
                $('#new').fadeIn();
                $('#old').hide();
                $('.tab a').siblings().removeClass('current');
                $('#bind_new').addClass('current');
                // 添加邮箱
                var email = $("#login_email").val();
                $("#reg_email").val(email);
            });
        },

        checkName : function() {
            var _this = this;
            $('#reg_name').live('change',function() {
                var name = $(this).val();
                if (name.length > 2) {
                    _this.ajaxName(name);
                }
            });
        },

        checkEmail : function() {
            var emailReg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
            var _this = this;
            $('#reg_email').live('change',function() {
                var email = $(this).val();
                if (email != '') {
                    if (emailReg.test(email)) {
                        _this.ajaxEmail(email);
                    } else {
                        $("#emalStatus").html('邮箱格式错误').addClass('err').removeClass('ok');
                        return false;
                    }
                }
            });
        },
        ajaxEmail : function(email) {
            // ajax请求检测昵称是否可以注册
            $.ajax({
                    url : baseUrl+'api/user/checkEmail',
                data : {'email' : email},
                dataType : 'json',
                type : 'post',
                success : function(data) {
                    // 存在则返回用户uid，不存在则0
                    if (data.error.code == 0) {
                        // 昵称可以注册
                        $('#emalStatus').html('邮箱恭喜你，可以注册').addClass('ok').removeClass('err');
                        Bind.email_ok = true;
                    } else {
                        $('#emalStatus').html('对不起，邮箱已被注册！<a href="javascript:;" class="bindOld oldemail">直接绑定</a>').addClass('err').removeClass('ok');
                        Bind.email_ok = false;
                    }
                }
            });
        },

        ajaxName : function(name) {
            // ajax请求检测昵称是否可以注册
            $.ajax({
                url : baseUrl+'api/user/checkNick',
                data : {'nick' : name},
                beforeSend : function() { },
                dataType : 'json',
                type : 'post',
                async : false,
                success : function(data) {
                    console.log(data);

                    // 存在则返回用户uid，不存在则0
                    if (data.error.code == 0) {
                        // 昵称可以注册
                        $('#nickstatus').html('恭喜你，可以注册').addClass('ok').removeClass('err');
                        Bind.name_ok = true;
                    } else {
                        $('#nickstatus').html('对不起，昵称已被注册！<a href="javascript:;" class="bindOld">直接绑定</a>').addClass('err').removeClass('ok');
                        Bind.name_ok = false;

                    }
                }
            });
        },

        login : function() {
            $('#login_do').live('click',function() {
                var emailReg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
                var email = $('#login_email').val();
                var pass = $('#login_pass').val();
                if (emailReg.test(email)) {
                    $('#loginEUStatus').html('ok').addClass('ok').removeClass('err');
                } else {
                    $('#loginEUStatus').html('邮箱格式不对').addClass('err').removeClass('ok');
                    return;
                }
                if (pass.length < 6) {
                    $('#loginPassStatus').html('密码长度为6位').removeClass('ok').addClass('err');
                    $('#login_pass').focus();
                    return false;
                } else {
                    $('#loginPassStatus').html('ok').removeClass('err').addClass('ok');
                }
                $.ajax({
                    url : baseUrl+'api/user/signin',
                    data : {
                        'email' : email,
                        'pwd' : pass
                    },
                    dataType : 'json',
                    type : 'post',
                    async : false,
                    success : function(data) {
                        if(data.error.code>0){
                            $('#loginStatus').html(data.error.msg+':'+data.error.more);
                            return;
                        }
                        if (data.uid != 0) {
                            self.location = Bind.url;
                        } else {
                            if ('password error!' == data.reason) {
                                $('#loginPassStatus').html('密码错误,请重新输入!').removeClass('ok').addClass('err');
                            } else if ('invalid user name!' == data.reason) {
                                $('#loginEUStatus').html('邮箱还未注册！<a href="javascript:;" id="goReg">立即注册？</a>').removeClass('ok').addClass('err');
                            } else if ('invalid argument!' == data.reason) {
                                $('#loginEUStatus').html('邮箱格式不正确！').removeClass('ok').addClass('err');
                            }
                        }
                    }
                });
            });

        },

        reg : function() {
            var _this = this;
            $('#reg_do').live('click',function() {
                var name = $('#reg_name').val();
                var gender = $('input.gender:checked').val();
                var email = $('#reg_email').val();
                var pass = $("#reg_pass").val();
                var emailReg = /^\w{2,16}@[0-9a-zA-Z]{2,10}\.\w{1,5}$/;
                if (!Bind.name_ok) {
                    // 验证昵称
                    if (name.length < 2 || name.length > 16) {
                        // 昵称不可注册
                        $('#nickstatus').html('昵称长度不得小于2个字符并且不得大于16字符').addClass('err').removeClass('ok');
                        Bind.name_ok = false;
                        return false;
                    } else {
                        // 昵称可以注册
                        _this.ajaxName(name);
                    }
                }
                if (!Bind.email_ok) {
                    // 验证邮箱
                    if (email != '') {
                        if (emailReg.test(email)) {
                            _this.ajaxEmail(email);
                        } else {
                            $("#emalStatus").html('邮箱格式错误').addClass('err').removeClass('ok');
                            Bind.email_ok = false;
                            return false;
                        }
                    } else {
                        $("#emalStatus").html('邮箱格式错误').addClass('err').removeClass('ok');
                        Bind.email_ok = false;
                        return false;
                    }
                }
                // 验证密码
                if (pass.length < 6) {
                    $('#regPassStatus').html('密码长度为6位').removeClass('ok').addClass('err');
                    return false;
                } else {
                    $('#regPassStatus').html('ok').removeClass('err').addClass('ok');
                }
                var data = {
                    email : email,
                    pwd : pass,
                    nick : name
                };
                // disable btn
                $('#reg_do').attr('disable',true);
                if (Bind.email_ok && Bind.name_ok) {
                    // 验证成功后,注册,绑定操作
                    $.ajax({
                        'url' : baseUrl+'api/user/signup',
                        data : {data:data},
                        dataType : 'json',
                        type : 'post',
                        async : false,
                        success : function(data) {
                            if(data.error.code>0){
                                $('#regStatus').html(data.error.msg+':'+data.error.more);
                                return false;
                            }else{
                                self.location = Bind.url;
                            }
                        }
                    });
                }
            });
        },

        url_args : function() {
            // site为模版里面定义的
            Bind.url = baseUrl+'oauth/bind';
        }

    };
});
