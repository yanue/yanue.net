<script>
    seajs.use('app/app.upload', function (api) {
        api.upload('.upload-widget[data-unique="1"]', {'type': 'pic'}, function (res) {
            console.log(res);
        });

    });
</script>
<span class="upload-widget" data-unique="1"></span>
<span class="upload-widget" data-unique="2"></span>
