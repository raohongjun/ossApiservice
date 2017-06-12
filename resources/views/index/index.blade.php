<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>测试上传</title>
    </head>
    <body>
        <form method="post" enctype="multipart/form-data" action="{{ route('upload') }}">
            {{ csrf_field() }}
            <input type="file" name="upload_img" accept="image/*"/>
            <input type="submit" value="上传">
        </form>
    </body>
</html>
