/**
 * Created by qinxuening on 2018/10/2.
 */

layui.define(['jquery','we','toastr'], function(exports){
   var $ = layui.jquery
       ,we = layui.we
       ,toastr = layui.toastr
       ,table = layui.table;

   var weTable = {
       table: "",
       tableid:"",
       defaults : {
           extend: {
               index_url: '',
               add_url: '',
               edit_url: '',
               del_url: '',
               import_url: '',
               multi_url: '',
               stop_url: '',
               view_url : '',
           }
       },

       api:{
           init : function (defaults) {
               defaults = defaults ? defaults : {};
               $.extend(weTable.defaults,defaults);
               console.log(weTable.defaults);
           },
           bindevent:function () {
               //监听行工具事件
               table.on('tool('+weTable.defaults.table+')', function(obj){
                   var data = obj.data;
                   if(obj.event === 'del'){
                       layer.confirm('请确定是否删除？',{
                           skin: 'layui-layer-we',
                           title:'温馨提示',
                       }, function(index){
                           var url = weTable.defaults.extend.del_url
                           we.api.ajax({"url":url,"data":{'ids':data.id},'tableid':weTable.defaults.tableid,'action':'del'});
                           layer.close(index);
                       });
                   } else if(obj.event === 'view'){
                       var url = weTable.defaults.extend.view_url + data.id;
                       we.api.open(url,'查看');
                   } else if(obj.event === 'edit') {
                       weTable.api.toolevent(obj);
                       var url = weTable.defaults.extend.edit_url + data.id;
                       we.api.open(url,'编辑');
                   } else {
                       var url = $(this).attr('lay-url');
                       if(url) {
                           we.api.ajax({"url":url,"data":{'ids':data.id}});
                       } else {
                           return toastr.error('操作异常');
                       }
                   }

               });

               //头工具栏事件
               table.on('toolbar('+weTable.defaults.table+')', function(obj){
                   var checkStatus = table.checkStatus(obj.config.id);
                   console.log(obj.event);
                   switch(obj.event){
                       case 'refresh':
                           console.log('refresh');
                           table.reload(weTable.defaults.tableid)
                           break;
                       case 'add':
//                    var data = checkStatus.data;
//                    layer.alert(JSON.stringify(data));
                           var url = weTable.defaults.extend.add_url;
                            console.log("----------"+url);
                           we.api.open(url,'添加');
                           break;
                       case 'del':
                           var data = checkStatus.data;
                           var idsArr = [];
                           if(data != "") {
                               $.each(data,function (index, value) {
                                   idsArr.push(value.id);
                               });
                               layer.confirm('请确定是否删除？',{
                                   skin: 'layui-layer-we',
                                   title:'温馨提示',
                               }, function(index){
                                   we.api.ajax({"url":weTable.defaults.extend.del_url,"data":{'ids':idsArr.join(',')},'tableid':weTable.defaults.tableid,'action':'del'});
                                   layer.close(index);
                               });
                           } else {
                               toastr.error('没有选择数据');
                           }
                           break;
                       case 'export':
                           toastr.warning('功能正在完善');
                           break;
                       case 'import':
                           toastr.warning('功能正在完善');
                           break;
                   };
               });
           },
           toolevent:function (obj) {
                // console.log(obj.data);
               // return obj.data;
           }
       }

   };
    exports('weTable', weTable);
});