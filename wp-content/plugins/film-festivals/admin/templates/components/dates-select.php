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
<select id="festivaldates_select" name="<?=$this->plugin_name; ?>_name[festivaldates]">
    <option value="">-- Non active please select --</option>
    <?php foreach ($this->main_events(['post_status' => 'any']) as $id => $event): ?>
        <option value="<?= $id; ?>"<?php echo (!empty($this->options['festivaldates']) && $this->options['festivaldates'] == $id ? ' selected' : ''); ?>><?= $event; ?></option>
    <?php endforeach; ?>
</select>
<p class="form-item-description">Select a programme to use for the next festival dates (A festival type exhibit allows you to set an end date).</p>
