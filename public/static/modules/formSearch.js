/**
 * Created by qinxuening on 2018/10/28.
 */

layui.define(['jquery'], function(exports){
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
                            console.log(value.searchList);
                            switch (value.searchList.type){
                                case 'input':
                                    html += '<div class="layui-inline">'+
                                        '<label class="layui-form-label">'+value.title+'</label>'+
                                        '<div class="layui-input-inline">'+
                                        '<input type="text" name="'+value.field+'" placeholder="" autocomplete="off" class="layui-input">'+
                                        '</div>'+
                                        '</div>';
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
                    $('.search-card-body').html(html);
                }

            }
        }
    }
    exports('formSearch', formSearch);
})