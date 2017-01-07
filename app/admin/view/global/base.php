<?php
$data = $this->data;
?>
<script>
    seajs.use('app/admin/admin.site', function (n) {
        n.setFocus('.setBtn','mainFocus');
    })
    seajs.use('app/app.upload', function (api) {
        <?php
        for($i=1;$i<=count( $data );$i++){
            ?>
        var upCoverFunc = function (res) {
            $('.pic<?php echo $i; ?>').val( res.data.url);
            $('.preview<?php echo $i; ?>').attr('src', app.site_url + res.data.url);
        };
        var coverOpt<?php echo $i; ?> = {
            'filelist': 'coverList<?php echo $i; ?>',
            'submit': 'uploadCover<?php echo $i; ?>',
            'browse_button': 'pickCover<?php echo $i; ?>',
            'container': 'containerCover<?php echo $i; ?>'
        };
        api.load('pic', coverOpt<?php echo $i; ?>, upCoverFunc);
        <?php
        }
        ?>
    });

</script>
<div class="" id=''>
    <h2 class="title">网站资料设置</h2>
    <form action="javascript:;" class="form">
        <p>
            <span class="label">网站名称</span>
            <input type="text" value=""/> (前台显示)
        </p>
        <p>
            <span class="label">网站名称</span>
            <input type="text" value=""/>
        </p>
    </form>
</div>