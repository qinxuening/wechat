/**
 * Created by qinxuening on 2018/9/24.
 */
layui.define(['jquery', 'layer','toastr'], function(exports){
    var $ = layui.$
        ,toastr = layui.toastr
        ,layer = layui.layer;

    toastr.options=
    {
        "closeButton":true,//显示关闭按钮
        "debug":false,//启用debug
        "positionClass":"toast-center-center",//弹出的位置
        "showDuration":"300",//显示的时间
        "hideDuration":"1000",//消失的时间
        "timeOut":"5000",//停留的时间
        "extendedTimeOut":"1000",//控制时间
        "showEasing":"swing",//显示时的动画缓冲方式
        "hideEasing":"linear",//消失时的动画缓冲方式
        "showMethod":"fadeIn",//显示时的动画方式
        "hideMethod":"fadeOut",//消失时的动画方式
        "progressBar":true,
    };
    var we = {
        events: {
            //请求成功的回调
            onAjaxSuccess: function (ret, options,onAjaxSuccess) {
                var data = typeof ret.data !== 'undefined' ? ret.data : null;
                var msg = typeof ret.msg !== 'undefined' && ret.msg ? ret.msg : '操作完成';
                if (typeof onAjaxSuccess === 'function') {
                    // return console.log(options);
                    console.log('执行步奏：成功1');
                    var result = onAjaxSuccess.call(this, data, ret);
                    if (result === false)
                        return;
                }

                var tablereloadid = options.tableid; //获取重加载table
                var treegird  = options.treegird;
                // return console.log(tablereloadid);
                if(options.searchFlag != 'undefined' && options.searchFlag == true) {
                    layui.table.reload(tablereloadid
                        ,{
                            where: options.data
                            ,method:"post" //post方式传参
                         }
                    );
                    // return console.log(options);
                    return false;
                } else {
                    toastr.clear()
                    toastr.success(ret.msg);
                    if (typeof tablereloadid != 'undefined') {
                        if(options.action == 'del') {
                            // return console.log(options.treegird);
                            layer.closeAll();
                            if(tablereloadid && typeof treegird != 'undefined') {
                                location.reload(); //刷新父页面
                            } else {
                                layui.table.reload(tablereloadid);
                            }
                        } else {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                            layer.closeAll();
                            if(tablereloadid && typeof treegird != 'undefined') {
                                window.parent.location.reload(); //刷新父页面
                            } else {
                                parent.layui.table.reload(tablereloadid);
                            }
                        }
                    }
                }

                console.log('执行步奏：成功2')
                if(ret.url != false && typeof ret.url !="undefined") {
                    layer.closeAll();
                    // console.log(12345678);
                    window.location.href = ret.url;
                }
            },

            //请求错误的回调
            onAjaxError: function (ret, options,onAjaxError) {
                var data = typeof ret.data !== 'undefined' ? ret.data : null;
                if (typeof onAjaxError === 'function') {
                    var result = onAjaxError.call(this, data, ret);
                    if (result === false) {
                        return;
                    }
                }else {
                    toastr.clear()
                    toastr.error(ret.msg);
                }
                // cons
                // ole.log(ret);

            },
            //服务器响应数据后
            onAjaxResponse: function (response) {
                try {
                    var ret = typeof response === 'object' ? response : JSON.parse(response);
                    if (!ret.hasOwnProperty('code')) {
                        $.extend(ret, {code: -2, msg: response, data: null});
                    }
                } catch (e) {
                    var ret = {code: -1, msg: e.message, data: null};
                }
                return ret;
            }
        },
        api: {
            //发送Ajax请求
            ajax: function (options, success, error) {
                // return console.log(options);
                options = typeof options === 'string' ? {url: options} : options;
                var index = layer.load();
                options = $.extend({
                    type: "POST",
                    dataType: "json",
                    async:true,
                    success: function (ret) {
                        layer.close(index);
                        ret = we.events.onAjaxResponse(ret);
                        // console.log(ret);
                        if (ret.status === 'success') {
                            we.events.onAjaxSuccess(ret, options,success);
                        } else {
                            we.events.onAjaxError(ret, options,error);
                        }
                    },
                    error: function (xhr) {
                        layer.close(index);
                        var ret = {code: xhr.status, msg: xhr.statusText, data: null};
                        we.events.onAjaxError(ret, error);
                    }
                }, options);
                // return console.log(options);
                $.ajax(options);
            },

            //打开一个弹出iframe窗口
            open: function (url, title, options,windowSize) {
                title = title ? title : "";
                if (windowSize != null) {
                    var area = windowSize;
                } else {
                    var area = ['80%' , '80%'];
                }
                options = $.extend({
                    type: 2,
                    title: title,
                    shadeClose: true,
                    // shade: 0.6,
                    maxmin:false,
                    moveOut: true,
                    area: area,
                    content: [url],
                    // content: [url,'no'],
                    scrollbar:false,
                    moveOut:false,
                    // skin: 'layui-layer-we',
                    zIndex: layer.zIndex,
                    success: function (layero, index) {
                        var that = this;
                    },
                    end: function () {
                        // location.reload();
                    }
                }, options ? options : {});
                return layer.open(options);
            },

            open_current_page_layer:function (id, title, options,windowSize) {
                title = title ? title : "";
                if (windowSize != null) {
                    var area = windowSize;
                } else {
                    var area = ['80%' , '80%'];
                }
                options = $.extend({
                    type: 1,
                    title: title,
                    shadeClose: true,
                    // shade: 0.6,
                    maxmin:false,
                    moveOut: true,
                    area: area,
                    content: id,
                    // content: [url,'no'],
                    scrollbar:false,
                    moveOut:false,
                    // skin: 'layui-layer-we',
                    zIndex: layer.zIndex,
                    success: function (layero, index) {
                        // layer.closeAll();
                    },
                    end: function () {
                        // layer.closeAll();
                    }
                }, options ? options : {});
                return layer.open(options);
            },
            //关闭窗口并回传数据
            close: function (data) {
                var index = parent.layer.getFrameIndex(window.name);
                var callback = parent.$("#layui-layer" + index).data("callback");
                //再执行关闭
                parent.layer.close(index);
                //再调用回传函数
                if (typeof callback === 'function') {
                    callback.call(undefined, data);
                }
                console.log('关闭layer');
            },

            success: function (options, callback) {
                var type = typeof options === 'function';
                if (type) {
                    callback = options;
                }
                return layer.msg('操作完成', $.extend({
                    offset: 0, icon: 1
                }, type ? {} : options), callback);
            },
            error: function (options, callback) {
                var type = typeof options === 'function';
                if (type) {
                    callback = options;
                }
                return layer.msg('操作失败', $.extend({
                    offset: 0, icon: 2
                }, type ? {} : options), callback);
            },
            layer: layer
        },
        init: function () {
            layer.config({
                skin: 'layui-layer-fast'
            });
            // 绑定ESC关闭窗口事件
            $(window).keyup(function (e) {
                if (e.keyCode == 27) {
                    if ($(".layui-layer").size() > 0) {
                        var index = 0;
                        $(".layui-layer").each(function () {
                            index = Math.max(index, parseInt($(this).attr("times")));
                        });
                        if (index) {
                            layer.close(index);
                        }
                    }
                }
            });
        },
        format_bytes:function (size, delimiter) {
            var units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
            for (var i = 0; size >= 1024 && i < 6; i++)
                size /= 1024;
            return size.toFixed(2) + delimiter + units[i];
        },
        tips:function (content) {
            layer.open({
                type: 1,
                offset: '25%',
                shade: false,
                title: false,
                content: "<ul class='layer_notice'>" + content +"</ul>",
            });
        },
        monitor_input:function (origin_obj, target_obj) {
            origin_obj.bind('input porpertychange',function(){
                console.log($(this).val().length);
                target_obj.text($(this).val());
            });
        },
        /**
         * 获取文本域光标并插入内容
         * @param obj
         * @param str
         */
        insertText:function(obj,str) {
            obj.focus();
            if (document.selection) {
                var sel = document.selection.createRange();
                sel.text = str;
            } else if (typeof obj.selectionStart === 'number' && typeof obj.selectionEnd === 'number') {
                var startPos = obj.selectionStart,
                    endPos = obj.selectionEnd,
                    cursorPos = startPos,
                    tmpStr = obj.value;
                obj.value = tmpStr.substring(0, startPos) + str + tmpStr.substring(endPos, tmpStr.length);
                cursorPos += str.length;
                obj.selectionStart = obj.selectionEnd = cursorPos;
            } else {
                obj.value += str;
                // console.log(obj.value)
            }
            $(".synchronize_sms_content").text(obj.value);
            $('.sms-content-length').text(obj.value.length);
        },
        /**
         * 把光标移动文本域末尾
         * @param obj
         */
        moveEnd:function(obj){
            obj.focus();
            var len = obj.value.length;
            if (document.selection) {
                var sel = obj.createTextRange();
                sel.moveStart('character',len);
                sel.collapse();
                sel.select();
            } else if (typeof obj.selectionStart == 'number' && typeof obj.selectionEnd == 'number') {
                obj.selectionStart = obj.selectionEnd = len;
            }
        },

        addDate:function (date, days) {
            if (days == undefined || days == '') {
                days = 1;
            }
            var date = new Date(date);
            date.setDate(date.getDate() + days);
            var month = date.getMonth() + 1;
            //要注意的是返回的月份是从0开始计算的，也就是说返回的月份要比实际月份少一个月，因此要相应的加上1。
            var day = date.getDate();
            return date.getFullYear() + '-' + we.getFormatDate(month) + '-' + we.getFormatDate(day);
        },

        // 日期月份/天的显示，如果是1位数，则在前面加上'0'
        getFormatDate:function (arg) {
            if (arg == undefined || arg == '') {
                return '';
            }
            var re = arg + '';
            if (re.length < 2) {
                re = '0' + re;
            }
            return re;
        }
    };
    we.init();

    //将Layer暴露到全局中去
    window.Layer = layer;
    //将Toastr暴露到全局中去
    window.Toastr = toastr;
    exports('we', we);
});