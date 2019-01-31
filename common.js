
//1、获取所有表单数据
$("#form").serialize();

//2、判断复选框是否选中、获取选中值
("#checkbox").is(':checked');
$("input[name='check_param']:checked").val();

//3、解析json数据
$.parseJSON(data)

//4、数组合并
arr.concat(data)

//5、$.inArray( value, array [, fromIndex ]) 函数用于在数组中查找指定值，并返回它的索引值（如果没有找到，则返回-1）

//6、获取select选中值
$("select[name=test]").find("option:selected").val();
$('#test option:selected').val()

//7、循环操作
$.each(info, function (index, value) {})

//.prev(selector) 获得匹配元素集合中每个元素紧邻的前一个同胞元素，通过选择器进行筛选是可选的。

//7、事件全局绑定
$("body").on('click','#',function(){

})

//8、select change事件
$('select[name=]').change(function () {

})
$("#select").on("change",function(){

})

//9、获取全部选中ids
var ids_list = '';
$('.ids:checked').each(function () {
    ids_list += $(this).val() + ',';
});

//10、复选框全选、非全选
$(".check-all").click(function () {
    $(".ids").prop("checked", this.checked);
});
$(".ids").click(function () {
    var option = $(".ids");
    option.each(function (i) {
        if (!this.checked) {
            $(".check-all").prop("checked", false);
            return false;
        } else {
            $(".check-all").prop("checked", true);
        }
    });
});

//11、设置下拉框选中
$("#modal_update select[name=group_no]").find("option[value='"+device_info.group_no+"']").prop("selected",true);

//12、点击去掉下拉多选
$('.cancle_group').click(function () {
    $('#detection_device_other2 option').each(function () {
        if($(this).is(":selected") == true) {
            $(this).prop("selected", false);
        }
    });
});

//13、push
device_arr.push($(this).val());

//14、双击事件
$('#detection_device_other').dblclick(function () {
    $('#detection_device_other option').each(function () {
        if($(this).is(":checked") == true) {
            var detection_device = $(this).val();
            $("#detection_device_this").append($(this).prop("outerHTML"));
            $(this).remove();
        }
    });
});

//15、input[name=] 格式
$("#modal_update input[name=version_no]").val(device_info.version_no);

//16、回车键、form提交
$("input").keydown(function(){
    if(event.keyCode ==13){
        loginsubmit();
    }
})

//17、重新加载当前页面
location.reload();

//18、ajax基本操作
$.ajax({
    type: "post",
    url: "",
    async: true,
    dataType: "json",
    data:{},
    success: function (data) {
        if (data.status == 'success') {
            location.reload();
            alert_msg(data.msg.info, '', 2);
        } else if (data.status == 'error') {
            alert_msg(data.msg.info, '', 1);
            return;
        } else {
            alert_msg('操作异常', '', 1);
        }
    }
    error:function () {
        alert_msg('操作异常', '', 1);
    }
})

//19、提示信息
function alert_msg(msg, obj = null, type) {
    if (type == 1) {
        var type_show = 'alert-danger';
    } else {
        var type_show = 'alert-success';
    }
    var mimaerr = '<div id="action-tips" class="alert fade in ' + type_show + ' global-tips" role="alert">' + msg + '</div>';
    $(".block-wrapper").append(mimaerr);
    setTimeout(function () {
        $("#action-tips").alert("close");
    }, 3000)
    if (obj != null) {
        obj.focus();
    }
}

//20、string.split(separator,limit)  把一个字符串分割成字符串数组
var server_ip_arr =server_ip.split(":");

//21、websocket初始化
function socketInit(){
    var host = "ws://"+server_ip_arr['0']+":8000/";
    try{
        socket = new WebSocket(host);

        //发送数据
        socket.onopen    = function(msg){
            console.log("socket create success!");
        };

        //接收数据
        socket.onmessage = function(msg){
            console.log(msg.data);
            var data = JSON.parse(msg.data);
        };

        socket.onclose   = function(msg){
            console.log("close Success");
        }

        socket.onerror   = function(msg){
            console.log("Error!");
        };
    }catch(ex){
        log(ex);
    }
}

//22、设置目标区域
$(document).mouseup(function(e){
    var _con = $('.write, #jq-keyboard');
    // 设置目标区域
    if(!_con.is(e.target) && _con.has(e.target).length === 0){
        $("#jq-keyboard").removeClass('show');
    }
});

//23、parent()、children()
$("#get_equipment_list").parent().parent().children('.disabled-btn').removeClass('hide');

//23、设置定时器 timerover = setInterval(get_result_appvul_status, 3000);
//24、清除定时器 clearInterval(timerover);

//25、数组去重
/**
 * 数组去重
 * @param arr
 * @returns {Array}
 */
function unique(arr) {
    var result = [], hash = {};
    for (var i = 0, elem; (elem = arr[i]) != null; i++) {
        if (!hash[elem]) {
            result.push(elem);
            hash[elem] = true;
        }
    }
    return result;
}

//24、push()、pop()、shift()、unshift()方法简单整理
push()   //在数组的末尾添加一个或多个元素 返回数组新长度
pop()   //移除数组的最后一项，返回移除的项
shift()  //移除数组的第一项，返回移除项
unshift()  //在数组的第一项前面添加一个或多个元素，返回数组的长度

//26选中、非选中切换
$("body").on('click','#checkTable tr',function(){
    var ischoose =  $(this).find("input[name='appids[]']").prop('checked');
    $(this).find("input[name='appids[]']").prop('checked',!ischoose);
});

//27、stringObject.indexOf(searchvalue,fromindex)方法可返回某个指定的字符串值在字符串中首次出现的位置。如果要检索的字符串值没有出现，则该方法返回 -1。

//28、字节转换
format_bytes:function (size, delimiter) {
    var units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    for (var i = 0; size >= 1024 && i < 6; i++)
        size /= 1024;
    return size.toFixed(2) + delimiter + units[i];
}

//29、绑定ESC关闭窗口事件
$(window).keyup(function (e) {
    if (e.keyCode == 27) {
    }
}

//30、获取表单json数据
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
            o[this.name] = this.value || '';

        }
    });
    return o;
}







