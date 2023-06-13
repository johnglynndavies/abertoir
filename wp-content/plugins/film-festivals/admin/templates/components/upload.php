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
<input type="file" name="<?=$this->plugin_name; ?>_exhibitoptions[upload]" /> 
<?php echo $this->options['upload'] ?: ''; ?>