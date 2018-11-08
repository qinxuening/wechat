/**
 * Created by qinxuening on 2018/10/6.
 */
layui.link( '__STATIC__/style/jquery.validator.css');
layui.define(['jquery', 'form','we','validator','zhCN','toastr'], function(exports){
    var  validator = layui.validator
         ,zhCN = layui.zhCN
         ,$ = layui.$
         ,toastr = layui.toastr
         ,form = layui.form
         ,we = layui.we;

    var Form = {
        events: {
            validator: function (form, success, error, submit) {
                if (!form.is("form"))
                    return;
                var msgClass = 'n-right';
                if(form.attr("msgClass") !== 'undefined') {
                    msgClass = form.attr("msgClass");
                }
                console.log(form.attr("msgClass"));
                //绑定表单事件
                form.validator($.extend({
                    theme: "simple_right",
                    validClass: 'has-succes',
                    invalidClass: 'has-error',
                    bindClassTo: '.layui-form-item',
                    msgClass: msgClass,
                    // showOk: "",
                    stopOnError: true,
                    // msgWrapper: 'div',
                    // msgMaker: function(opt){
                    //     return '<span class="'+ opt.type +'">' + opt.msg + '</span>';
                    // },
                    display: function (elem) {
                        // console.log($(elem).closest('.layui-form-item').children().children().children('label:eq(0)').text());
                        // console.log($(elem).closest('.layui-form-item').find('label:eq(0)').text());
                        return $(elem).closest('.layui-form-item').find('label:eq(0)').text(); //display: 是可选的，用于替换错误消息中的{0}，一般为显示的字段名。
                    },
                    dataFilter: function (data) { //前端转换服务端返回的结果格式
                        if (data.code === 1) {
                            return "";
                        } else {
                            return data.msg;
                        }
                    },
                    target: function (input) {
                        //target - 自定义消息的显示位置
                        var target = $(input).data("target");
                        if (target && $(target).size() > 0) {
                            return $(target);
                        }
                        var $formitem = $(input).closest('.form-group'), //closest() 方法获得匹配选择器的第一个祖先元素，从当前元素开始沿 DOM 树向上
                            $msgbox = $formitem.find('span.msg-box');
                        if (!$msgbox.length) {
                            return [];
                        }
                        return $msgbox;
                    },
                    valid: function (ret) { //表单验证通过时调用此函数
                        var that = this, submitBtn = $(".footer-form [type=submit]", form);
                        // return console.log(submitBtn);
                        // that.holdSubmit();
                        $(".footer-form [type=submit]", form).addClass("layui-btn-disabled");
                        // return ;
                        //验证通过提交表单
                        Form.api.submit($(ret), function (data, ret) {
                            // return console.log(ret);
                            that.holdSubmit(false); //防止表单重复提交的措施 After the form is submitted successfully, release hold.
                            submitBtn.removeClass("layui-btn-disabled");

                            if (false === $(this).triggerHandler("success.form", [data, ret])) {
                                return false;
                            }
                            if (typeof success === 'function') {
                                if (false === success.call($(this), data, ret)) {
                                    return false;
                                }
                            }
                            // return console.log(ret);
                            //提示及关闭当前窗口
                            /*var msg = ret.hasOwnProperty("msg") && ret.msg !== "" ? ret.msg :'操作完成';
                            console.log(msg);
                            var index = parent.Layer.getFrameIndex(window.name);
                            parent.Layer.close(index);
                            window.parent.location.reload();//刷新父页面
                            parent.Toastr.success(msg);*/

                            return true;
                        }, function (data, ret) {
                            that.holdSubmit(false);
                            submitBtn.removeClass("disabled");
                            if (false === $(this).triggerHandler("error.form", [data, ret])) {
                                return false;
                            }
                            if (typeof error === 'function') {
                                if (false === error.call($(this), data, ret)) {
                                    return false;
                                }
                            }
                            // console.log(ret);
                            toastr.clear()
                            toastr.error(ret.msg);
                        }, submit);
                        return false;
                    }
                }, form.data("validator-options") || {}));

                //移除提交按钮的disabled类
                $(".footer-form [type=submit],.fixed-footer [type=submit],.normal-footer [type=submit]", form).removeClass("disabled");
            },
            bindevent: function (form) {}
        },
        api: {
            submit: function (form, success, error, submit) {
                if (form.size() === 0)
                    return Toastr.error("表单未初始化完成,无法提交");
                if (typeof submit === 'function') {
                    if (false === submit.call(form)) {
                        return false;
                    }
                }
                var type = form.attr("method").toUpperCase(); //toUpperCase() 方法用于把字符串转换为大写
                // return console.log(type);
                type = type && (type === 'GET' || type === 'POST') ? type : 'GET';
                var url = form.attr("action");
                url = url ? url : location.href;
                var tableid = form.attr("tableid");
                var treegird = form.attr("treegird");
                // return console.log(url);
                //修复当存在多选项元素时提交的BUG
                var params = {};
                var multipleList = $("[name$='[]']", form);
                // return console.log(multipleList);
                if (multipleList.size() > 0) {
                    //serializeArray() 方法通过序列化表单值来创建对象数组（名称和值）
                    //map() 把每个元素通过函数传递到当前匹配集合中，生成包含返回值的新的 jQuery 对象
                    // console.log(form.serializeArray());
                    var postFields = form.serializeArray().map(function (obj) {
                        return $(obj).prop("name");
                    });
                    // return console.log(postFields);

                    $.each(multipleList, function (i, j) {
                        if (postFields.indexOf($(this).prop("name")) < 0) {
                            params[$(this).prop("name")] = '';
                        }
                    });
                }
                // return console.log(form.serialize());
                //调用Ajax请求方法
                we.api.ajax({
                    type: type,
                    url: url,
                    async:false,
                    data: form.serialize() + (Object.keys(params).length > 0 ? '&' + $.param(params) : ''),
                    dataType: 'json',
                    tableid:tableid,
                    treegird:treegird,
                    complete: function (xhr) {
                        var token = xhr.getResponseHeader('__token__');
                        // console.log(form.serialize() + (Object.keys(params).length > 0 ? '&' + $.param(params) : ''));
                        if (token) {
                            $("input[name='__token__']", form).val(token);
                        }
                    }
                }, function (data, ret) {
                    // console.log("-------"+data);
                    // console.log("##########"+ret);
                    // return console.log(ret); //操作成功返回结果集
                    if (data && typeof data === 'object') {
                        // console.log(data.token);
                        //刷新客户端token
                        if (typeof data.token !== 'undefined') {
                            $("input[name='row[__token__]']", form).val(data.token);
                        }

                        //调用客户端事件
                        if (typeof data.callback !== 'undefined' && typeof data.callback === 'function') {
                            data.callback.call(form, data);
                        }
                    }
                    if (typeof success === 'function') {
                        if (false === success.call(form, data, ret)) {
                            return false;
                        }
                    }

                }, function (data, ret) {
                    if (data && typeof data === 'object' && typeof data.token !== 'undefined') {
                        $("input[name='row[__token__]']", form).val(data.token);
                    }
                    if (typeof error === 'function') {
                        if (false === error.call(form, data, ret)) {
                            return false;
                        }
                    }
                });
                return false;
            },

            bindevent: function (form, success, error, submit) {
                form = typeof form === 'object' ? form : $(form);
                var events = Form.events;
                events.bindevent(form);
                events.validator(form, success, error, submit);
            },
        }
    }
    exports('formValidator', Form);
});