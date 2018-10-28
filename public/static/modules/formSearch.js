/**
 * Created by qinxuening on 2018/10/28.
 */

layui.define(['jquery','laydate','form'], function(exports){
    var laydate = layui.laydate
        ,form = layui.form;

    var options = {};
    var formSearch = {
        api:{
            init : function (defaults) {
                var search = defaults.search;
                var cols = defaults.cols[0];
                var html = '';
                options = defaults;
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
                                    html += '<div class="layui-inline">'+
                                        '<label class="layui-form-label">'+value.title+'</label>'+
                                        '<div class="layui-input-inline">'+
                                        '<select name="status">'+
                                        '<option value="">请选择'+value.title+'</option>';
                                        $.each(value.searchList.data, function (index, value) {
                                            html += ' <option value="'+index+'">'+value+'</option>';
                                        });
                                    html += '</select></div></div>';
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

                    /**
                     * html加载完成方可初始化时间插件
                     */
                    html = '';
                    $.each(cols, function (index, value) {
                        if(typeof value.searchList !== 'undefined') {
                            if (typeof value.searchList.event !== 'undefined' && value.searchList.event == 'date') {
                                laydate.render({
                                    elem: '#'+ value.field+'_start' //指定元素
                                    ,type: 'datetime'
                                });
                                laydate.render({
                                    elem: '#'+ value.field+'_end' //指定元素
                                    ,type: 'datetime'
                                });
                            }
                        }
                    })
                }
                form.render();
            },
            /**
             * 搜索事件
             */
            searchForm:function (form) {
                var type = form.attr("method").toUpperCase();
                type = type && (type === 'GET' || type === 'POST') ? type : 'GET';
                var url = form.attr("action");
                url = url ? url : location.href;
                var tableid = form.attr("tableid");
                var params = {};
                var multipleList = $("[name$='[]']", form);
                if (multipleList.size() > 0) {
                    var postFields = form.serializeArray().map(function (obj) {
                        return $(obj).prop("name");
                    });
                    $.each(multipleList, function (i, j) {
                        if (postFields.indexOf($(this).prop("name")) < 0) {
                            params[$(this).prop("name")] = '';
                        }
                    });
                }
                // console.log(options);
                $('body').on('click','.search-info',function () {
                    var op = {};
                    $.each(options.cols[0], function (index, value) {
                        // console.log(value);
                        if(typeof value.searchList !== 'undefined') {
                            op[value.field] = value.searchList.operate;
                        }
                    });
                    var where ={
                        filter:JSON.stringify(formSearch.api.getFormJson(form))
                        ,op:JSON.stringify(op)
                    };
                    // console.log(where);
                    var index = layer.load();
                    // return  console.log(form.serialize());
                    layui.table.reload(tableid
                        ,{
                            page: {
                                curr: 1
                            }
                            ,where: where,
                        }
                    );
                    layer.close(index);
                    return false;

                    /*we.api.ajax({
                     type: type,
                     url: url,
                     loading:true,
                     async:false,
                     data : weTable.api.getFormJson(form),
                     dataType: 'json',
                     tableid:tableid,
                     searchFlag:true,
                     });*/
                });
            },

            // 将form中的值转换为键值对。
            // 形如：{name:'aaa',password:'tttt'}
            // ps:注意将同名的放在一个数组里
            getFormJson : function(frm) {
                var o = {};
                var a = $(frm).serializeArray();
                $.each(a, function () {
                    if (o[this.name] !== undefined) {
                        if (!o[this.name].push) {
                            o[this.name] = [o[this.name]];
                        }
                        o[this.name].push(this.value || '');
                    } else {
                        if(this.name.indexOf('_start') != '-1') {
                            var start = this.value ? this.value : ''
                            this.name = this.name.slice(0,-6);
                            delete (this.name+'_start');
                            console.log(start);
                        }
                        if(this.name.indexOf('_end') != '-1') {
                            var end = this.value ? this.value : ''
                            this.name = this.name.slice(0,-4);
                            delete (this.name+'_end');
                            if(start || end) {
                                o[this.name] = $('#'+this.name+'_start').val() +'-'+end;
                            } else {
                                o[this.name] = '';
                            }
                        }else {
                            o[this.name] = this.value || '';
                        }

                    }
                });
                return o;
            }

        }
    }
    exports('formSearch', formSearch);
})