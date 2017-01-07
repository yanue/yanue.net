<?php
if ($this->pageCount) {
	?>
<?php if($this->total){?><span style="float: right;">总<?php echo $this->pageCount;?>条记录,总<?php echo $this->total;?>页,每页<?php echo $this->perpage;?>条,当前<?php echo $this->total;?>/<?php echo @$this->page;?>页</span><?php }?>
<!-- First p link -->
<?php if (isset($this->previous)){?>
<a
	href="<?php echo $this->uri->setUrl(array('p' => $this->first),array(),true); ?>">
	首页 </a>
|
<?php }else{ ?>
<span class="disabled">首页</span>
|
<?php }; ?>

<!-- Previous p link -->
<?php if (isset($this->previous)){?>
<a
	href="<?php echo $this->uri->setUrl(array('p' => $this->previous),array(),true); ?>">
	&lt; 上一页 </a>
|
<?php }else{ ?>
<span class="disabled">&lt; 上一页</span>
|
<?php }; ?>

<!-- Numbered p links -->
<?php foreach ($this->pagesInRange as $page){
        if($page > 0 ){
        ?>
  <?php if ($page != $this->current){?>
<a href="<?php echo $this->uri->setUrl(array('p' => $page),array(),true); ?>"><?= $page; ?></a>
|
  <?php }else{ ?>
    <?= $page; ?> |
  <?php }}; ?>
<?php }; ?>

<!-- Next p link -->
<?php if (isset($this->next)){?>
<a
	href="<?php echo $this->uri->setUrl(array('p' => $this->next),array(),true); ?>">
	下一页 &gt; </a>
|
<?php }else{ ?>
<span class="disabled">下一页 &gt;</span>
|
<?php }; ?>

<!-- Last p link -->
<?php if (isset($this->next)){?>
<a
	href="<?php echo $this->uri->setUrl(array('p' => $this->last),array(),true); ?>">
	尾页 </a>
<?php }else{ ?>
<span class="disabled">尾页</span>
<?php }; ?>
<?php }; ?>

