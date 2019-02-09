/**
 * Created by qinxuening on 2018/10/28.
 */

layui.define(['jquery','laydate','form'], function(exports){
    var laydate = layui.laydate
        ,form = layui.form;
    // var Search = "{:__('Search')}";
    var options = {};
    var formSearch = {
        api:{
            init : function (defaults) {
                var search = defaults.search;
                var cols = defaults.cols[0];
                var data = defaults.data;
                var html = '';
                options = defaults;
                if(search == true) {
                    console.log(123);
                    html += '<form class="layui-form" method="post" action="'+defaults.url+'" tableid="tableReload" id="formRule" lay-filter="rule">';
                    html += '<ul class="nav pull-left">';
                    $.each(cols, function (index, value) {
                        if(typeof value.searchList !== 'undefined') {
                            // console.log(value.searchList);
                            switch (value.searchList.type){
                                case 'input':
                                    if(typeof value.searchList.event !== 'undefined' && value.searchList.event == 'date') {
                                        html += '<li>'+value.title+'：'+
                                            '<input type="text" name="'+value.field+'_start" id="'+value.field+'_start" placeholder="" autocomplete="off" class="layui-input">'+
                                            ' — '+
                                            '<input type="text" name="'+value.field+'_end" id="'+value.field+'_end" placeholder="" autocomplete="off" class="layui-input">'+
                                            '</li>';
                                    }else {
                                        html += '<li>'+ value.title+'：'+
                                            '<input type="text" name="'+value.field+'" id="'+value.field+'" placeholder="" autocomplete="off" class="layui-input">'+
                                            '</li>';
                                    }
                                    break;
                                case 'select':
                                    html += '<li>'+value.title+'：'+
                                        '<select name="status">'+
                                        '<option value="">请选择'+value.title+'</option>';
                                        $.each(value.searchList.data, function (index, value) {
                                            html += ' <option value="'+index+'">'+value+'</option>';
                                        });
                                    html += '</select></li>';
                                    break;
                                default:;
                            }
                        }
                    });

                    html += '<li>'+
                        '<button class="layui-btn layui-btn-sm layui-btn-normal search-info" type="button" lay-submit="" lay-filter="component-form-element"><i class="layui-icon layui-icon-search"></i>'+data.Search+'</button>'+
                        '<button type="reset" class="layui-btn layui-btn-normal layui-btn-sm layui-btn-sm"><i class="layui-icon layui-icon-refresh-3"></i>'+data.Reset+'</button>'+
                        '</li>';
                    html += '</ul></form>';
                    $('.searchbody').html(html);

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
                $('body').on('click',"#"+form.attr("id")+' .search-info',function () {
                    var op = {};
                    if(typeof options.cols !== 'undefined') {
                        $.each(options.cols[0], function (index, value) {
                            // console.log(value);
                            if (typeof value.searchList !== 'undefined') {
                                op[value.field] = value.searchList.operate;
                            }
                        });
                    }
                    var where ={
                        filter:JSON.stringify(formSearch.api.getFormJson(form))
                        ,op:JSON.stringify(op)
                    };
                    console.log(where);
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
                                o[this.name] = $('#'+this.name+'_start').val() +' - '+end;
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