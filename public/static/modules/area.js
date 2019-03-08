layui.define(['jquery','form','we'], function(exports) {
    var $ = layui.$
        ,form = layui.form
        , we = layui.we;
    var area = {
            /**
         * 省份点击弹窗
         */
        init:function() {
            $("body").on("click","#areaId",function (){
                we.api.open_current_page_layer($(".layer-form"),'选择所在省份',{
                },['80%' , '*']);
                var areaId = [];
                var areaIdCh = []
                //选中省份
                $("body").on("click",".submit",function (){
                    $(".body-form input[type=checkbox]").each(function (index,value) {
                        if($(this).is(':checked') && $(this).val() != 'on') {
                            areaId.push($(this).val());
                            areaIdCh.push($(this).attr('title'));
                        }
                    })
                    $("#areaId").val(areaIdCh.join(','));
                    $("#areaIdH").val(areaId.join(','));
                    layer.closeAll();
                });
                /**
                 * 重置
                 * */
                $("body").on("click",".reset",function (){
                    layer.closeAll();
                });
            })

            form.on('radio(location_address)', function(data){
                if(data.value == 1){
                    we.api.open_current_page_layer($(".layer-form"),'选择所在省份',{
                    },['80%' , '*']);
                    var areaId = [];
                    var areaIdCh = []
                    //选中省份
                    $("body").on("click",".submit",function (){
                        $(".body-form input[type=checkbox]").each(function (index,value) {
                            if($(this).is(':checked') && $(this).val() != 'on') {
                                areaId.push($(this).val());
                                areaIdCh.push($(this).attr('title'));
                            }
                        })
                        $("#areaId").val(areaIdCh.join(','));
                        $("#areaIdH").val(areaId.join(','));
                        layer.closeAll();
                    });
                    //重置
                    $("body").on("click",".reset",function (){
                        layer.closeAll();
                    });
                }
            })

            /**
             *省份全选、反选
             */
            form.on('radio(check_all)', function(data){
                var value = data.value;
                if(value == 'all') {
                    $(".location_address_layer").find("input[type=checkbox]").prop('checked', true);
                    $(".location_address_layer").find(".layui-form-checkbox").addClass('layui-form-checked');
                } else {
                    $(".location_address_layer").find("input[type=checkbox]").prop('checked', false);
                    $(".location_address_layer").find(".layui-form-checkbox").removeClass('layui-form-checked');
                }
            });
        }
    }
    exports('area', area);
});