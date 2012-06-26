<?php if ( !defined( 'HABARI_PATH' ) ) { die('No direct access'); } 
// We disable the fieldset by default ?>
<fieldset<?php echo ($class) ? ' class="' . $class . '"' : ''?><?php echo ($id) ? ' id="' . $id . '"' : ''?> disabled="disabled">
	<legend><?php echo $caption; ?></legend>
	<?php echo $contents; ?>
</fieldset>
