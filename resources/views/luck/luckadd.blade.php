<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>抽奖</title>
</head>
<body>
    <div style="width:360px;height:370px;margin:0 auto">
        <div style="width:333px;height;360px;margin:0 auto">
            <input style="margin-top:200px;" type="text" value="<?php echo mt_rand(1,999); ?>" id="uid"><br/><br/>
            <input style="width:400px;height:40px;font-size:20px;color:red;" type="button" value="开始抽奖" id="btn">
        </div>
    </div>

</body>
</html>
<script src="/js/jquery-3.2.1.min.js"></script>
<script>
    $(function () {
        $("#btn").click(function () {
            var uid=$("#uid").val();
            $.ajax({
                url:"/luck/luckmedo",
                method:"post",
                dataType:"json",
                data:{uid:uid},
                succes:function (res) {
                    console.log(res);
                }
            })
        })
    })
</script>