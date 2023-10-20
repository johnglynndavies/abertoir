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
<select id="promote_select" name="<?=$this->plugin_name; ?>_name[promoteselect]">
    <option value="">-- Non active please select --</option>
    <?php foreach ($this->main_events(['tax_query' => [[ 'taxonomy' => 'festival_category', 'field' => 'slug', 'terms' => ['festival', 'special-event']]]]) as $id => $event): ?>
        <option value="<?= $id; ?>"<?php echo (!empty($this->options['promoteselect']) && $this->options['promoteselect'] == $id ? ' selected' : ''); ?>><?= $event; ?></option>
    <?php endforeach; ?>
</select>
<p class="form-item-description">Select a programme to promote on the homepage.</p>

