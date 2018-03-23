layui.config({
    base: "js/"
}).use(['form'], function () {
    var form = layui.form,
        layer = parent.layer === undefined ? layui.layer : parent.layer,
        $ = layui.jquery;
    //登录按钮事件
    form.on("submit(login)", function (data) {
        var field = data.field;
        // var datas = "username=" + data.field.username + "&password=" + data.field.password + "&captcha=" + data.field.captcha;
        $.post("/login",field, function (rev) {
            if(rev.status === 0) {
                parent.location.href = rev.data;
            } else {
                $("#captcha").attr("src", '/captcha/default?' + Math.random());
                Msg.error(rev.msg);
            }
        })
        // $.ajax({
        //     type: "POST",
        //     url: "/sys/login",
        //     data: datas,
        //     dataType: "json",
        //     success: function (result) {
        //         if (result.code == 0) {//登录成功
        //             parent.location.href = '/index.html';
        //         } else {
        //             layer.msg(result.msg, {icon: 5});
        //             refreshCode();
        //         }
        //     }
        // });
        return false;
    })
});

