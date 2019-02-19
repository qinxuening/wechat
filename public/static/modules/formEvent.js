/**
 * Created by qinxuening on 2019/02/19
 */

layui.define(['jquery','form'], function(exports){
    var $ = layui.$
        ,form = layui.form;
    var formEvent = {
        api:{
            /**
             * 点击”所有”则其他按钮都被取消，其他选中，则“所有”按钮被取消
             * @param event_obj
             */
            all_check_not:function (event_obj) {
                form.on(event_obj, function(data){
                    var value = data.value;
                    console.log(data.elem.checked);
                    if(value != 0) {
                        if (data.elem.checked){
                            $(this).parent("td").children('input').first().prop('checked', false);
                            $(this).parent("td").children('.layui-form-checkbox').first().removeClass('layui-form-checked');
                        }
                    } else {
                        if (data.elem.checked){
                            $(this).parent("td").children('input').not(':first').prop('checked', false);
                            $(this).parent("td").children('.layui-form-checkbox').not(':first').removeClass('layui-form-checked');
                        }
                    }
                });
            },

            /**
             * 全选、取消全选
             * @param obj
             */
            all_check_none:function (obj) {
                $("body").on("click",obj,function (){
                    var p = $(this).prev();
                    if(p.attr('class') == 'all' && p.attr('class') !== 'undefined') {
                        if($(this).hasClass('layui-form-checked')){
                            $(this).parent(".layui-form").children('input').prop('checked', true);
                            $(this).parent(".layui-form").children('.layui-form-checkbox').addClass('layui-form-checked');
                        } else {
                            $(this).parent(".layui-form").children('input').prop('checked', false);
                            $(this).parent(".layui-form").children('.layui-form-checkbox').removeClass('layui-form-checked');
                        }
                    }else {
                        if(!$(this).hasClass('layui-form-checked')){
                            $(this).parent(".layui-form").find('input').first().prop('checked', false);
                            $(this).parent(".layui-form").find('.layui-form-checkbox').first().removeClass('layui-form-checked');
                        }else{
                            var this_ = $(this);
                            this_.parent(".layui-form").find('.layui-form-checkbox').not(':first').each(function (i) {
                                if (!$(this).hasClass('layui-form-checked')) {
                                    this_.parent(".layui-form").find('input').first().prop('checked', false);
                                    this_.parent(".layui-form").find('.layui-form-checkbox').first().removeClass('layui-form-checked');
                                    return false;
                                } else {
                                    this_.parent(".layui-form").find('input').first().prop('checked', true);
                                    this_.parent(".layui-form").find('.layui-form-checkbox').first().addClass('layui-form-checked');
                                }
                            });
                        }
                    }
                });
            },

        }
    }
    exports('formEvent', formEvent);
})