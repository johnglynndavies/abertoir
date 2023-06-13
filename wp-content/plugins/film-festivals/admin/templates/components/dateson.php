<?php
/**
 * The template for displaying the admin page
 *
 * @since      1.0.0
 * @package    Film_Festivals
 * @subpackage Film_Festivals/public
 * @author     John Davies <johnglynndavies@gmail.com>
 */
?>
<input type="checkbox" name="<?=$this->plugin_name; ?>_name[dateson]" <?php echo (!empty($this->options['dateson']) ? 'checked' : ''); ?> />