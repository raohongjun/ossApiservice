<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8"/>
    <title>图片上传</title>
    <link href="//sm.ms/css/bootstrap.min.css" rel="stylesheet">
    <link href="//sm.ms/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="//cdn.css.net/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="{{asset('js/fileinput.min.js')}}" type="text/javascript"></script>
    <script src="//cdn.css.net/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>
    <style>
        html {
            position: relative;
            min-height: 100%;
        }

        body {
            margin-bottom: 60px;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60px;
            background-color: #f5f5f5;
        }

        body > .container {
            padding: 60px 15px 0;
        }

        .container .text-muted {
            margin: 20px 0;
        }

        .footer > .container {
            padding-right: 15px;
            padding-left: 15px;
        }

        code {
            font-size: 80%;
        }
    </style>
</head>
<body>

@yield('content')

</body>
</html>
