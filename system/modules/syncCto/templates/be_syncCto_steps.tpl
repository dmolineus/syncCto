<div id="tl_buttons">
<a onclick="Backend.getScrollOffset();" accesskey="b" title="<?php echo $GLOBALS['TL_LANG']['tl_syncCto_steps']['back']; ?>" class="header_back" href="<?php echo $this->goBack; ?>"><?php echo $GLOBALS['TL_LANG']['tl_syncCto_steps']['back']; ?></a>
</div>

<h2 class="sub_headline"><?php echo $this->headline; ?></h2>

<div class="tl_formbody_edit">

<?php if (strlen($this->information) != 0): ?>
<p class="info tl_info"><?php echo $this->information; ?></p>
<?php endif; ?>

<?php foreach ($this->data as $key => $value) : ?>

<div class="<?php echo ($key == 1) ? "tl_tbox" : "tl_box"; ?> block">
<h1 id="step<?php echo $key; ?>"><?php echo $value["title"] ?></h1>
<p class="tl_help">
<?php echo $value["description"]; ?> - <?php echo $value["state"]  ?>
</p>

<?php if (strlen($value["msg"])) : ?>
<p class="tl_help"><?php echo $value["msg"]; ?></p>
<?php endif; ?>

<?php if (strlen($value["html"]) != 0) : ?>
<?php echo $value["html"]; ?>
<?php endif; ?>
</div>

<?php endforeach; ?>

<?php if ($this->refresh == true && $this->error == false && $this->finished == false): ?>
<meta http-equiv="refresh" content="1; URL=<?php echo $this->Environment->base; ?><?php echo $this->url; ?>&amp;step=<?php echo $this->step + 1 ?>" />
<img style="margin-bottom:20px;" src="system/modules/syncCto/html/ajax-loader.gif" alt="" />
<?php endif; ?>

<?php if ($this->error) : ?>
<div class="tl_tbox block">
<h1><?php echo $GLOBALS['TL_LANG']['syncCto']['error']; ?></h1>
<p class="tl_help"><?php echo $this->error_msg; ?></p>
</div>
<?php endif; ?>

<?php if ($GLOBALS['TL_CONFIG']['syncCto_debug_filelist'] == true): ?>
<p class="debug tl_help"><?php echo vsprintf($GLOBALS['TL_LANG']['syncCto']['run_time'], array(number_format(microtime(true) - $this->start, 5))); ?><br />
Auslastung: <?php echo round(memory_get_peak_usage(true) / 1048576, 4); ?> MBytes</p>
<?php endif; ?>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
window.scrollTo(null, ($('step<?php echo $this->step; ?>').getPosition().y - 20));
//--><!]]>
</script>

</div>

<?php echo $this->showTemplateVars(); ?>