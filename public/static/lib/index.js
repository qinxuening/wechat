;layui.extend({setter: "config", admin: "lib/admin", view: "lib/view"}).define(["setter", "admin"], function (a) {
    var e = layui.setter, i = layui.element, n = layui.admin, t = n.tabsPage, d = layui.view, l = function (a, d) {
        var l, b = r("#LAY_app_tabsheader>li"), y = a.replace(/(^http(s*):)|(\?[\s\S]*$)/g, "");
        // if (b.each(function (e) {
        //         var i = r(this), n = i.attr("lay-id");
        //         n === a && (l = !0, t.index = e)
        //     }), d = d || "新标签页", e.pageTabs) l || (r(s).append(['<div class="layadmin-tabsbody-item layui-show">', '<iframe src="' + a + '" frameborder="0" class="layadmin-iframe"></iframe>', "</div>"].join("")), t.index = b.length, i.tabAdd(o, {
        //     title: "<span>" + d + "</span>",
        //     id: a,
        //     attr: y
        // })); else {
        //     var u = n.tabsBody(n.tabsPage.index).find(".layadmin-iframe");
        //     u[0].contentWindow.location.href = a
        // }
        // i.tabChange(o, a), n.tabsBodyChange(t.index, {url: a, text: d})
    }, s = "#LAY_app_body", o = "layadmin-layout-tabs", $ = r = layui.$;
    $(window);
    $().ready(function () {
        var urlArr = location.href.split("#");
        console.log(urlArr)
        // if(urlArr[1] !== '' && urlArr.length == 2) {
            var current = urlArr[1] ? urlArr[1] : "tab1";
            $("#tabs").find("li[id^=li]").find("a[class=" + current + "]").parent().addClass("currenttab");
            if(urlArr[1] !== '') {
                $("#content").find("div[id^=tab]").not("div[id=" + urlArr[1] + "]").hide();
            }
            $("#content").find("div[id=" + current + "]").fadeIn();
            $("#tabs").find("li[id^=li]").find("a").bind("click", function () {
                var title = $(this).attr("class");
                location.href = urlArr[0] + "#" + title;
                $("#tabs").find("li[id^=li]").not("li[id=" + title + "]").removeClass("currenttab");
                $(this).parent().addClass("currenttab");
                // if(title !== '') {
                    $("#content").find("div[id^=tab]").not("div[id=" + title + "]").hide();
                // }
                $("#content").find("div[id=" + title + "]").fadeIn();
                return false;
            })
        // }

        /**
         * 新增导航管理
         */
        $(".nav_menu a").on("click", function () {
            console.log(window.location.pathname);
            var this_path_name = window.location.pathname;
            console.log($(this).attr('data-id'));
            var data_id = $(this).attr('data-id');
            // console.log(data_id);
            // if(data_id && this_path_name == "/admin/dashboard/detail"){
            //     var lay_href  = $(this).attr('lay-href');
            //     console.log(lay_href);
            //     $(this).parent().addClass('layui-this');
                // window.location.href = "/admin/index/index?return_url=" + encodeURIComponent(lay_href);
                // window.location.href = "/admin/index/index";
            // }
            // if(data_id != null && data_id != ''){
            //     $("#LAY-system-side-menu").find("li[data-id]").addClass('layui-hide');
            //     $("#LAY-system-side-menu").find("li[data-id="+data_id+"]").removeClass('layui-hide');
            // }
        });

        /**
         * 下拉显示
         */
        $("body").on("mouseenter",'.nav_menu', function () {
            $('.layui-dropdown').hide();
            $(this).next().show();
        });

        /**
         * 鼠标离开操作
         */
        $("body").on("mouseleave",'.layui-dropdown', function () {
            $('.layui-dropdown').hide();
        });

        $("body").on("click",'.layui-dropdown ul li a', function () {
            $('.layui-dropdown').hide();
        });

        $(document).mouseup(function(e){
            var _con = $('.layui-nav,.layui-dropdown');   // 设置目标区域
            if(!_con.is(e.target) && _con.has(e.target).length === 0){ // Mark 1
                $('.layui-dropdown').hide();
            }
        });
    });
    n.screen() < 2 && n.sideFlexible(), layui.config({base: e.base + "modules/"}), layui.each(e.extend, function (a, i) {
        var n = {};
        n[i] = "{/}" + e.base + "lib/extend/" + i, layui.extend(n)
    }), d().autoRender(), layui.use("common"), a("index", {openTabsPage: l})
});