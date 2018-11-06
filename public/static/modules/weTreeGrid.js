/**
 * Created by qinxuening on 2018/11/6
 */

layui.define(['jquery','we','toastr','treeGrid'], function(exports){
   var $ = layui.jquery
       ,we = layui.we
       ,toastr = layui.toastr
       ,treeGrid = layui.treeGrid;

   var weTreeGrid  = {
       table: "",
       tableid:"",
       defaults : {
           extend: {
               index_url: '',
               add_url: '',
               edit_url: '',
               del_url: '',
               import_url: '',
               export_url:'',
               multi_url: '',
               stop_url: '',
               view_url : '',
           }
       },

       api:{
           init : function (defaults) {
               defaults = defaults ? defaults : {};
               $.extend(weTreeGrid.defaults,defaults);
               // console.log(weTable.defaults);
           },
           bindevent : function () {
               //监听行工具事件
               treeGrid.on('tool('+weTreeGrid.defaults.table+')', function(obj){
                   var data = obj.data;
                   if(obj.event === 'del'){
                       layer.confirm('请确定是否删除？',{
                           skin: 'layui-layer-we',
                           title:'温馨提示',
                       }, function(index){
                           var url = weTreeGrid.defaults.extend.del_url
                           we.api.ajax({"url":url,"data":{'ids':data.id},'tableid':weTreeGrid.defaults.tableid,'action':'del'});
                           layer.close(index);
                       });
                   } else if(obj.event === 'view'){
                       var url = weTreeGrid.defaults.extend.view_url + data.id;
                       we.api.open(url,'查看');
                   } else if(obj.event === 'edit') {
                       var url = weTreeGrid.defaults.extend.edit_url + data.id;
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
               treeGrid.on('toolbar('+weTreeGrid.defaults.table+')', function(obj){
                   var checkStatus = table.checkStatus(obj.config.id);
                   console.log(obj.event);
                   switch(obj.event){
                       case 'refresh':
                           console.log('refresh');
                           layer.load(0, {shade: false});
                           table.reload(weTreeGrid.defaults.tableid)
                           break;
                       case 'add':
//                    var data = checkStatus.data;
//                    layer.alert(JSON.stringify(data));
                           var url = weTreeGrid.defaults.extend.add_url;
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
                                   we.api.ajax({"url":weTreeGrid.defaults.extend.del_url,"data":{'ids':idsArr.join(',')},'tableid':weTreeGrid.defaults.tableid,'action':'del'});
                                   layer.close(index);
                               });
                           } else {
                               toastr.error('没有选择数据');
                           }
                           break;
                       case 'export':
                           // we.api.ajax({"url":weTable.defaults.extend.export_url});
                           window.location.href = weTreeGrid.defaults.extend.export_url;
                           layer.close(index);
                           // toastr.warning('功能正在完善');
                           break;
                       case 'import':
                           toastr.warning('功能正在完善');
                           break;
                   };
               });
           },

       }

   };
    exports('weTreeGrid', weTreeGrid);
});