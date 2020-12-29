<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>

hello {{$name}}

<form action="/simple-register" method="post">
    请输入你的名称:<input type="text" name="account" ><br>
    请输入你的密码:<input type="password" name="password" ><br>

    <input type="submit" value="提交">
</form>
</body>
</html>
