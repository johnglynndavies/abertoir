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
<select id="slider_select" name="<?=$this->plugin_name; ?>_name[sliderselect]">
    <option value="">-- Non active please select --</option>
    <?php foreach ($this->festivals() as $id => $festival): ?>
        <option value="<?= $id; ?>"<?php echo (!empty($this->options['sliderselect']) && $this->options['sliderselect'] == $id ? ' selected' : ''); ?>><?= $festival; ?></option>
    <?php endforeach; ?>
</select>
<p class="form-item-description">Select a current/upcoming programme and the slider will be populated with featured images from its exhibits. </p>

