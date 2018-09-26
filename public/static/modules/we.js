/**
 * Created by qinxuening on 2018/9/24.
 */
layui.define(['jquery', 'layer'], function(exports){
    var $ = layui.jquery
    ,layer = layui.layer;
    var we = {
        events: {
            //请求成功的回调
            onAjaxSuccess: function (ret, onAjaxSuccess) {
                var data = typeof ret.data !== 'undefined' ? ret.data : null;
                var msg = typeof ret.msg !== 'undefined' && ret.msg ? ret.msg : '操作完成';
                if (typeof onAjaxSuccess === 'function') {
                    var result = onAjaxSuccess.call(this, data, ret);
                    if (result === false)
                        return;
                }
                layer.msg(msg);
            },

            //请求错误的回调
            onAjaxError: function (ret, onAjaxError) {
                var data = typeof ret.data !== 'undefined' ? ret.data : null;
                if (typeof onAjaxError === 'function') {
                    var result = onAjaxError.call(this, data, ret);
                    if (result === false) {
                        return;
                    }
                }
                layer.msg(ret.msg);
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
                options = typeof options === 'string' ? {url: options} : options;
                var index = layer.load();
                // return console.log(options);
                //jQuery.extend() 函数用于将一个或多个对象的内容合并到目标对象
                options = $.extend({
                    type: "POST",
                    dataType: "json",
                    success: function (ret) {
                        layer.close(index);
                        ret = we.events.onAjaxResponse(ret);
                        if (ret.code === 1) {
                            we.events.onAjaxSuccess(ret, success);
                        } else {
                            we.events.onAjaxError(ret, error);
                        }
                    },
                    error: function (xhr) {
                        layer.close(index);
                        var ret = {code: xhr.status, msg: xhr.statusText, data: null};
                        we.events.onAjaxError(ret, error);
                    }
                }, options);
                $.ajax(options);
            },

            //打开一个弹出窗口
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
                    shade: 0.6,
                    maxmin: true,
                    moveOut: true,
                    area: area,
                    content: [url],
                    // content: [url,'no'],
                    scrollbar:false,
                    moveOut:false,
                    skin: 'layui-layer-we',
                    zIndex: layer.zIndex,
                    success: function (layero, index) {
                        var that = this;
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
        }
    };
    we.init();
    exports('we', we);
});