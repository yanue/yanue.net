<?php
$userInfo = $this->userInfo;
if($userInfo['level'] == 0){
    ?>
<script type="text/javascript">
    seajs.use('app/admin/admin.manage',function(api){
        api.backup();
    });
</script>
<form action=""
      method='post'>

    <table class='list'>
        <tr class="head">
            <th width='100px'>序号</th>
            <th width='60px'>操作</th>
            <th width='142px'>版本</th>
            <th>备份文件</th>
            <th>操作</th>
        </tr>
        <?php if(isset($this->error)){?>
            <tr>
                <th class="name">错误：</th>
                <td colspan='4' style='color: #f00;'><?php echo $this->error;?></td>
            </tr>
        <?php }?>
        <?php if(isset($this->done)){?>
            <tr>
                <th class="name">导入成功</th>
                <td colspan='4' style='color: #f00;'><?php echo '恭喜您！数据库导入成功！';?></td>
            </tr>
        <?php }?>
        <tr>
            <th class="name">操作提示</th>
            <td colspan='4'><?php echo $this->msg;?></td>
        </tr>
        <?
        function getSubDirs($dir) {
            $subdirs = array ();
            if (! $dh = opendir ( $dir ))
                return $subdirs;
            $i = 0;
            while ( $f = readdir ( $dh ) ) {
                if ($f == '.' || $f == '..')
                    continue;
                // 如果只要子目录名, path = $f;
                // $path = $dir.'/'.$f;
                $path = $f;
                $subdirs [$i] = $path;
                $i ++;
            }
            closedir ( $dh );
            return $subdirs;
        }
        function getSubFiles($dir) {
            $files = array ();
            $handle = @opendir ( $dir ) or die ( "Cannot open " . $dir );
            // 用 readdir 读出文件列表
            while ( $file = readdir ( $handle ) ) {
                // 将 "." 及 ".." 排除不显示
                if ($file != "." && $file != "..") {
                    $files [] = $file;
                }
            }
            // 关闭目录读取
            closedir ( $handle );
            return $files;
        }
        // 获取所有子目录
        $subdir = getSubDirs ( $this->bakDir );
        $i = 1;
        // 如果没有完成导入
        if (!isset($this->done)) {
            foreach ( $subdir as $dir ) {
                $id = $i ++;
                ?>
                <tr id='t<?php echo $id;?>' class='tb'>
                    <th><?php echo $id;?></th>
                    <td><input type="submit" name='sumbit' value="导入" class='restoredo' /></td>
                    <?php
                    echo '<td><input type="radio" id="r' . $id . '" name="bakdir" id="" value="' . $dir . '" tabindex="' . $id . '"/> ' . $dir . '</td>';
                    $files = getSubFiles ( $this->bakDir . $dir );
                    echo '<td>';
                    foreach ( $files as $file ) {
                        echo '<p><input type="checkbox" name="sqlfiles[]" tabindex="' . $id . '" value="' . $dir . '/' . $file . '" class="s' . $id . ' s"/> ' . $file . '</p>';
                    }
                    echo '</td><td><a href="">删除</a></td>';
                    ?>
                </tr>
            <?php
            }
        }
        ?>
    </table>

</form>
<?php
}else{
    echo '对不起，你没有权限！';
}
?>