@extends('layouts.app')

@section('content')

    <div class="container kv-main">
        <div class="page-header">
            <h1>图片上传</h1> 最大上传{{ ini_get('post_max_size')}}
        </div>
        <form enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-group">
                <input id="upload_img" type="file" multiple class="file" data-overwrite-initial="false"
                       data-min-file-count="1" data-max-file-count="10" name="upload_img" accept="image/*">
            </div>
        </form>

        <div id="showurl" style="display: none;">
            <ul id="navTab" class="nav nav-tabs">
                <li class="active"><a href="#urlcodes" data-toggle="tab">URL</a></li>
            </ul>
            <div id="navTabContent" class="tab-content">
                <div class="tab-pane fade in active" id="urlcodes">
                    <pre style="margin-top: 5px;"><code id="urlcode"></code></pre>
                </div>
            </div>
        </div>

        <script>
            $("#upload_img").fileinput({
                uploadUrl: '{{route('upload')}}',
                allowedFileExtensions: ['jpeg', 'jpg', 'png', 'gif', 'bmp'],
                overwriteInitial: false,
                maxFileSize: 5120,
                maxFilesNum: 10,
                maxFileCount: 10,
            });
            $('#upload_img').on('fileuploaded', function (event, data, previewId, index) {
                var form = data.form, files = data.files, extra = data.extra, response = data.response,
                    reader = data.reader;

                if (response.http_code == '2000') {

                    if ($("showurl").css("display")) {
                        console.log(1);
                        $('#urlcode').append(response.url + "\n");
                    } else if (response.url) {
                        $("#showurl").show();
                        $('#urlcode').append(response.url + "\n");
                    }
                }
            });
        </script>
    </div>

    <!-- Piwik -->
    <script type="text/javascript">
        var _paq = _paq || [];
        // tracker methods like "setCustomDimension" should be called before "trackPageView"
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function () {
            var u = "//s.doma.in/piwik/";
            _paq.push(['setTrackerUrl', u + 'piwik.php']);
            _paq.push(['setSiteId', '7']);
            var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
            g.type = 'text/javascript';
            g.async = true;
            g.defer = true;
            g.src = u + 'piwik.js';
            s.parentNode.insertBefore(g, s);
        })();
    </script>
@endsection


