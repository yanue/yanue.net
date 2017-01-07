<script type="text/javascript">
    seajs.use('app/admin/admin.manage',function(api){
        api.backup();
    });
</script>
<form action=""
      method='post'>
    <table class="list">
        <caption>
            <b class="captitle">数据库备份操作</b>
        </caption>

        <tr class="head">
            <th width='100px'>名称</th>
            <th width='142px'>操作</th>
            <th>备份选项</th>
        </tr>
        <?php if(isset($this->error)){?>
            <tr>
                <th class="name">错误：</th>
                <td colspan='3'><?php echo $this->error;?></td>
            </tr>
        <?php }?>
        <?php if($this->done){?>
            <tr>
                <th class="name">备份成功</th>
                <td colspan='3' style='color: #f00;'><?php echo '恭喜您！数据库备份成功！';?></td>
            </tr>
        <?php }?>
        <tr>
            <th class="name">操作提示</th>
            <td colspan='3'><?php echo $this->msg;?></td>
        </tr>
        <?php if(!$this->done){?>
            <tr>
                <th class="name" style="vertical-align: middle;">操作</th>
                <td style="vertical-align: middle;text-align: center;">
                    <p>
                        <input type="submit" class="btn-gray" name='backdo' value='点击备份' />
                    </p>
                </td>
                <td>
                    <p>
                        <input type="radio" name="backup" id="allTables" value='all'
                               checked="checked" /> 全部备份
                    </p>
                    <p>
                        <input type="radio" name="backup" id="single" value='single' />
                        备份单表
                    </p>

                    <div id="backmore" style='display: none;'>
                        <p>
                            数据表:
                        </p>
                        <?php if($this->tables){foreach ($this->tables as $table){?>
                            <p><input type="checkbox" name="tables[]" class="chk"
                                      value='<?php echo $table;?>' checked="checked" />  <?php echo $table;?> </p>
                        <?php
                        }
                        }
                        ?>
                    </div>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>

</form>


