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
       tableCommon:"",
       form:"",
       is_display:false,
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
               $.extend(weTable.defaults,defaults);
               // console.log(weTable.defaults);
           },
           bindevent : function () {
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
                       if(weTable.defaults.is_display == true) {
                           window.location.href = url;
                       } else {
                           we.api.open(url,'查看');
                       }
                   } else if(obj.event === 'edit') {
                       var url = weTable.defaults.extend.edit_url + data.id;
                       if(weTable.defaults.is_display == true) {
                           window.location.href = url;
                       } else {
                           we.api.open(url,'编辑');
                       }
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
                   // console.log(obj.event);
                   switch(obj.event){
                       case 'refresh':
                           console.log('refresh');
                           layer.load(0, {shade: false});
                           table.reload(weTable.defaults.tableid)
                           break;
                       case 'add':
//                    var data = checkStatus.data;
//                    layer.alert(JSON.stringify(data));
                           var url = weTable.defaults.extend.add_url;
                            console.log("----------"+url);
                            if(weTable.defaults.is_display == true) {
                                window.location.href = url;
                            } else {
                                we.api.open(url,'添加');
                            }

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
                           // table.render();
                           // console.log(obj.config.cols);
                           if(!weTable.defaults.extend.export_url) {
                               return toastr.error('操作异常');
                           }
                           var op = {};
                           var filter = {};
                           $.each(obj.config.cols[0], function (index, value) {
                               console.log(value);
                               if(typeof value.searchList !== 'undefined') {
                                   op[value.field] = value.searchList.operate;
                               }
                           });

                           filter = JSON.stringify(weTable.api.getFormJson(weTable.defaults.form));
                           we.api.ajax({"url":weTable.defaults.extend.export_url,
                               data:{'cols' : JSON.stringify(obj.config.cols),
                                       'op':JSON.stringify(op),
                                       'filter':filter
                                    }
                           });
                           // window.location.href = weTable.defaults.extend.export_url;
                           // layer.close(index);
                           // toastr.warning('功能正在完善');
                           break;
                       case 'import':
                           we.api.open(weTable.defaults.extend.import_url,'导入');
                           // toastr.warning('功能正在完善');
                           break;
                   };
               });

           },

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

   };
    exports('weTable', weTable);
});