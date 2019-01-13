;layui.extend({setter: "config", admin: "lib/admin", view: "lib/view"}).define(["setter", "admin"], function (a) {
    var e = layui.setter, i = layui.element, n = layui.admin, t = n.tabsPage, d = layui.view, l = function (a, d) {
        var l, b = r("#LAY_app_tabsheader>li"), y = a.replace(/(^http(s*):)|(\?[\s\S]*$)/g, "");
        if (b.each(function (e) {
                var i = r(this), n = i.attr("lay-id");
                n === a && (l = !0, t.index = e)
            }), d = d || "新标签页", e.pageTabs) l || (r(s).append(['<div class="layadmin-tabsbody-item layui-show">', '<iframe src="' + a + '" frameborder="0" class="layadmin-iframe"></iframe>', "</div>"].join("")), t.index = b.length, i.tabAdd(o, {
            title: "<span>" + d + "</span>",
            id: a,
            attr: y
        })); else {
            var u = n.tabsBody(n.tabsPage.index).find(".layadmin-iframe");
            u[0].contentWindow.location.href = a
        }
        i.tabChange(o, a), n.tabsBodyChange(t.index, {url: a, text: d})
    }, s = "#LAY_app_body", o = "layadmin-layout-tabs", $ = r = layui.$;
    $(window);
    $().ready(function () {
        var urlArr = location.href.split("#");
        var current = urlArr[1] ? urlArr[1] : "tab1";
        $("#tabs").find("li[id^=li]").find("a[class=" + current + "]").parent().addClass("currenttab");
        $("#content").find("div[id^=tab]").not("div[id=" + urlArr[1] + "]").hide();
        $("#content").find("div[id=" + current + "]").fadeIn();
        $("#tabs").find("li[id^=li]").find("a").bind("click", function () {
            var title = $(this).attr("class");
            location.href = urlArr[0] + "#" + title;
            $("#tabs").find("li[id^=li]").not("li[id=" + title + "]").removeClass("currenttab");
            $(this).parent().addClass("currenttab");
            $("#content").find("div[id^=tab]").not("div[id=" + title + "]").hide();
            $("#content").find("div[id=" + title + "]").fadeIn();
            return false;
        })

        /**
         * 新增导航管理
         */
        $(".nav_menu a").on("click", function () {
            console.log($(this).attr('data-id'));
            var data_id = $(this).attr('data-id');
            $("#LAY-system-side-menu").find("li[data-id]").addClass('layui-hide');
            $("#LAY-system-side-menu").find("li[data-id="+data_id+"]").removeClass('layui-hide');
        });
    });
    n.screen() < 2 && n.sideFlexible(), layui.config({base: e.base + "modules/"}), layui.each(e.extend, function (a, i) {
        var n = {};
        n[i] = "{/}" + e.base + "lib/extend/" + i, layui.extend(n)
    }), d().autoRender(), layui.use("common"), a("index", {openTabsPage: l})
});