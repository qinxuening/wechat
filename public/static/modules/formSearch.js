/**
 * Created by qinxuening on 2018/10/28.
 */

layui.define(['jquery','laydate'], function(exports){
    var laydate = layui.laydate;
    var formSearch = {
        api:{
            init : function (defaults) {
                var search = defaults.search;
                var cols = defaults.cols[0];
                var html = '';
                if(search == true) {
                    html += '<form class="layui-form" method="post" action="'+defaults.url+'" tableid="tableReload" id="formRule" lay-filter="rule">';
                    html += ' <div class="layui-form-item">';
                    $.each(cols, function (index, value) {
                        if(typeof value.searchList !== 'undefined') {
                            // console.log(value.searchList);
                            switch (value.searchList.type){
                                case 'input':
                                    if(typeof value.searchList.event !== 'undefined' && value.searchList.event == 'date') {
                                        html += '<div class="layui-inline">'+
                                            '<label class="layui-form-label">'+value.title+'</label>'+
                                            '<div class="layui-input-inline">'+
                                            '<input type="text" name="'+value.field+'_start" id="'+value.field+'_start" placeholder="" autocomplete="off" class="layui-input">'+
                                            '</div>'+
                                            '<label class="layui-form-label-search-date">—</label>'+
                                            '<div class="layui-input-inline">'+
                                            '<input type="text" name="'+value.field+'_end" id="'+value.field+'_end" placeholder="" autocomplete="off" class="layui-input">'+
                                            '</div>'+
                                            '</div>';
                                            laydate.render({
                                                elem: '#'+ value.field+'_start' //指定元素
                                                ,type: 'datetime'
                                            });
                                            laydate.render({
                                                elem: '#'+ value.field+'_end' //指定元素
                                                ,type: 'datetime'
                                            });
                                    }else {
                                        html += '<div class="layui-inline">'+
                                            '<label class="layui-form-label">'+value.title+'</label>'+
                                            '<div class="layui-input-inline">'+
                                            '<input type="text" name="'+value.field+'" id="'+value.field+'" placeholder="" autocomplete="off" class="layui-input">'+
                                            '</div>'+
                                            '</div>';
                                    }
                                    break;
                                case 'select':
                                    break;
                                default:;
                            }
                        }
                    });
                    html += '<div class="layui-inline">'+
                        '<div class="layui-input-inline search-inline">'+
                        '<button class="layui-btn layui-btn-sm search-info" type="button" lay-submit="" lay-filter="component-form-element"><i class="layui-icon layui-icon-search"></i>搜索</button>'+
                        '<button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>'+
                        '</div>'+
                        '</div>';
                    html += '</div></form>';
                    $('.search-card-body').append(html);
                }

            }
        }
    }
    exports('formSearch', formSearch);
})