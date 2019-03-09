/**
 * Created by Administrator on 2019/3/9.
 */
layui.define(['jquery','form','formSearch','we'], function(exports) {
    var $ = layui.$
        ,form = layui.form
        ,table = layui.table
        ,init_i = init_j = init_m = 0
        ,formSearch = layui.formSearch
        ,element = layui.element
        , we = layui.we;
    var sms_modal_init = {
        init:function() {

            /***********************************************短信模板begin************************************/
            /**
             * 选择模板短信
             * */
            $("body").on("click","#sms_module",function (){
                sms_table_init('', {'sms_type':0}); //sms_type：0推荐场景，sms_type：1模板库，sms_type：2历史发送，sms_type：2近期节日这几个参数传递给后台
                element.tabChange('sms-href-tab', 'recommended_scene');//默认显示推荐场景
                modal_sms_layer_index = we.api.open_current_page_layer($(".sms-module-layer"),'选择短信模版',{zIndex:19891016
                },['80%' , '80%']);
            })

            /**
             * 短信模板切换初始化
             * */
            element.on('tab(sms-href-tab)', function(data){
                var where = {'sms_type':data.index}; //data.index,Tab的所在下标
                switch (data.index) {
                    case 0:
                        sms_table_init('', where);
                        break;
                    case 1:
                        sms_table_init('', where);
                        break;
                    case 2:
                        sms_table_init('', where);
                        break;
                    case 3:
                        sms_table_init('', where);
                        break;
                }
            });

            /**
             * 选择短信模板初始化，//sms_type：0推荐场景，sms_type：1模板库，sms_type：2历史发送，sms_type：2近期节日这几个参数传递给后台
             * */
            function sms_table_init(form,where,flag){
                var tableCommon = {
                    elem: '#table-sms'
                    ,url:"/sms_data.json" //对接请求后台数据
                    ,id : 'tableReload'
                    ,skin:'line'
                    ,limit:10
                    ,defaultToolbar: ['filter']
                    // ,toolbar : '#toolbar'
                    ,data:{'Search':"{:__('Search')}",'Reset':"{:__('Reset')}"}
                    ,search: false
                    ,cols: [[
                        {type:'checkbox', fixed: 'left', hide:true}
                        ,{field:'sms_id', title: 'id', hide:true}
                        ,{field:'sms_content', title: '短信内容'}
                        ,{fixed: 'right', title:'操作', toolbar: '#actionbar', width:250}
                    ]]
                    ,page: {
                        layout: ['limit', 'count', 'prev', 'page', 'next', 'skip'] //自定义分页布局
                        ,first: false //不显示首页
                        ,last: false //不显示尾页

                    }
                    ,where: where   //搜索条件
                    ,done: function(res, curr, count){
                        if(res.status == 'success') {
                            // layer.closeAll();
                        }
                    }
                };

                table.render(tableCommon); //数据表格初始化
                if(init_i == 0){
                    $.each($(".sms-module-content .layui-tab-item"),function (index, value) {
                        formSearch.api.searchForm($("form#" + $(this).find("form").attr("id"))); //短信模板查询事件初始化
                    })
                }
                init_i ++;
                /**
                 * 绑定监听事件
                 * */
            }

            //选择短信模板头工具栏事件
            table.on('tool(table_sms)', function(obj){
                console.log(obj);
                var data = obj.data;
                console.log(data.sms_id);
                if(obj.event === 'use_sms_module'){
                    layer.close(modal_sms_layer_index)
                    $(".sms-content").val(data.sms_content)
                    $(".synchronize_sms_content").text(data.sms_content)
                } else if(obj.event === 'del_sms_module') {
                    var sms_id = data.sms_id;
                    var url = "";
                    we.api.ajax({"url":url,"data":{'sms_id':sms_id}});   //对接后台删除
                }
            })



            /**
             * 点击短链接弹出框
             * */
            $("body").on("click","#short_href",function (){
                sms_short_table_init('', {'short_sms_type':0}); //0店铺常用链接，1宝贝链接，2宝贝分类，3优惠券，4搭配套餐，5金店长活动，这几个参数传递给后台
                element.tabChange('short-href-tab', 'common_link');//店铺常用链接
                short_sms_layer_index = we.api.open_current_page_layer($(".short-href-layer"),'添加短链',{zIndex:19891016
                },['80%' , '80%']);
            })

            /**
             * 短链tab切换监听
             * */
            element.on('tab(short-href-tab)', function(data){
                var where = {'short_sms_type':data.index}; //data.index,Tab的所在下标
                switch (data.index) {
                    case 0:
                        sms_short_table_init('', where);
                        break;
                    case 1:
                        sms_short_table_init('', where);
                        break;
                    case 2:
                        sms_short_table_init('', where);
                        break;
                    case 3:
                        sms_short_table_init('', where);
                        break;
                    case 4:
                        sms_short_table_init('', where);
                        break;
                    case 5:
                        sms_short_table_init('', where);
                        break;
                }
            });
            /***********************************************短信模板end************************************/

            /***********************************************短链begin************************************/
            /**
             * 短链接表格初始化
             * */
            function sms_short_table_init(form,where,flag){
                //店铺常用链接、宝贝链接、宝贝分类、优惠券、搭配套餐表头
                var cols1 = [[
                    {type:'checkbox', fixed: 'left', hide:true}
                    ,{field:'sms_id', title: 'id', hide:true}
                    ,{field:'sms_content', title: '短链内容'}
                    ,{fixed: 'right', title:'操作', toolbar: '#short_sms_actionbar', width:250}
                ]];

                //金店长活动表头
                var cols2 = [[
                    {type:'checkbox', fixed: 'left', hide:true}
                    ,{field:'sms_id', title: 'id', hide:true}
                    ,{field:'activity_name',title:'活动名称'}
                    ,{field:'sms_content', title: '活动标题'}
                    ,{field:'sms_create_time', title: '创建时间'}
                    ,{fixed: 'right', title:'操作', toolbar: '#short_sms_actionbar', width:250}
                ]];
                var cols = cols1;
                if(typeof where !== 'undefined'){
                    var cols = where.short_sms_type == 5 ? cols2 : cols1;
                }
                // console.log(where)
                var tableCommon = {
                    elem: '#table-short-link-sms'
                    ,url:"/sms_data.json" //对接请求后台数据
                    ,id : 'tableReload'
                    ,skin:'line'
                    ,limit:10
                    ,defaultToolbar: ['filter']
                    ,search: false
                    ,cols: cols
                    ,page: {
                        layout: ['limit', 'count', 'prev', 'page', 'next', 'skip'] //自定义分页布局
                        ,first: false //不显示首页
                        ,last: false //不显示尾页

                    }
                    ,where:where
                    ,done: function(res, curr, count){
                        if(res.status == 'success') {

                        }
                    }
                };
                table.render(tableCommon); //数据表格初始化
                if(init_j == 0){
                    formSearch.api.searchForm($("form#form-sms-good-link"));//宝贝链接查询初始化
                    formSearch.api.searchForm($("form#form-sms-jdz-list"));//金店长查询初始化
                }
                init_j ++;
            }

            //短链工具栏事件
            table.on('tool(table-short-link-sms)', function(obj){
                var data = obj.data;
                console.log(data.sms_id);
                if(obj.event === 'use_sms_short_link'){
                    layer.close(short_sms_layer_index)
                    insertText(document.getElementById("sms-content"),'c.tb.cn/c.02iCLm')//测试数据
                } else if(obj.event === 'del_sms_module') {

                }
            })
            /***********************************************短信模板end************************************/


            /**
             * tips-icon 【计费规则(重要)】弹出信息、销毁信息
             */
            $("body").on("mouseenter",".tips-icon",function (){
                var title = $(this).attr('title-icon');
                index = layer.tips(title,this, {tips:[1,"#506def"],time:0,area: '400px',zIndex:1000000000})
            })
            $("body").on("mouseleave",".tips-icon",function (){
                layer.close(index);
            })

            /**
             * 短信标签设置，并同步到短信内容和右侧手机短信内容
             */
            $("body").on("click",".label_sms ul li",function (){
                console.log($(this).text());
                var label_id = $(this).children().attr('id');
                var content = "{" +$(this).text()+ "}";
                if(typeof label_id !== 'undefined'){
                    console.log(label_id);
                    var title = '';
                    if(label_id == 'order_id'){
                        title = '{订单编号} 实际替换后长度约为16个字，实际扣费以发出字数为准，您是否确定添加？';
                    } else if(label_id == 'product_name'){
                        title = "{商品简称} 需要对宝贝设置对应的商品简称，未设置简称的宝贝加入该标签将不展示标签内容，您可以选择继续添加或者<a class='astyle' href='javascript:void(0);'>设置商品简称</a>？";
                    } else if(label_id == 'sms_module' || label_id == 'short_href') {
                        return false;
                    }
                    if(title != '') {
                        layer.confirm(title,{
                            title:'温馨提示',area: ['400px','250px'],zIndex:19891016
                        }, function(index){
                            we.insertText(document.getElementById("sms-content"),content)
                            we.layer.close(index);
                        });
                    } else {
                        we.insertText(document.getElementById("sms-content"),content)
                    }
                } else {
                    we.insertText(document.getElementById("sms-content"),content)
                }
            });

            /**
             * 监听短信内容输入框值的变化
             */
            $('#sms-content').bind('input porpertychange',function(){
                $('.synchronize_sms_content').text($(this).val()); //同步右侧手机短信内容
                $('.sms-content-length').text($(this).val().length);//实时监听输入短信内容长度
            });

            /**
             * 获取文本域光标并插入内容
             * @param obj
             * @param str
             */
            function insertText(obj,str) {
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
                }
                $(".synchronize_sms_content").text(obj.value);
                $('.sms-content-length').text(obj.value.length);
            }

        }
    }
    exports('sms_modal_init', sms_modal_init);
})