<?php
use Library\Util\Ajax;

$msg = Ajax::getErrorMsg($this->errcode);

?>
<div class="content">
    <section class="left-section">
        <div class="err-page">
            <?php echo $msg; ?>
        </div>
    </section>

    <aside class="sidebar">
        <?php $this->render('side/panel', false); ?>
    </aside>
    <div class="clear"></div>
</div>
