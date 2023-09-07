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

<div class="gallery-items" id="gallery-items">
<?php 
foreach($this->gallery() as $k => $item): ?>
    <div class="gallery-item gallery-item-<?=$k;?>">
        <div class="gallery-item__image">
            <img src="<?= $item['src']??null; ?>" width="<?= $item['width']??'100'; ?>px" height="<?= $item['height']??'100'; ?>px" />
            <div class="gallery-item__btns"><input type="hidden" class="gallery-item__item" name="<?=$this->plugin_name; ?>_image[<?=$k;?>][item]" id="<?=$this->plugin_name; ?>_image[<?=$k;?>][item]" value="<?= $item['value']??null; ?>" />
            <button type="button" class="upload_image_button button"><? echo __( 'Upload', $this->plugin_name ); ?></button>
            <button type="button" class="remove_image_button button">&times;</button></div>
        </div>
        <div class="gallery-item__meta">
            <label for="<?=$this->plugin_name; ?>_image[<?=$k;?>][order]"><? echo __( 'Slide order', $this->plugin_name ); ?></label>
            <input type="number" name="<?=$this->plugin_name; ?>_image[<?=$k;?>][order]" id="<?=$this->plugin_name; ?>_image[<?=$k;?>][order]" value="<?= $item['order']??null; ?>" />
            <label for="<?=$this->plugin_name; ?>_image[<?=$k;?>][title]"><? echo __( 'Slide caption', $this->plugin_name ); ?></label>
            <textarea class="gallery-item__caption" rows="3" cols="50" name="<?=$this->plugin_name; ?>_image[<?=$k;?>][title]" id="<?=$this->plugin_name; ?>_image[<?=$k;?>][title]"><?= $item['title']??null; ?></textarea>
        </div>
    </div>
<?php 
endforeach;
?>

</div>
<button type="button" class="add-gallery-item button"><? echo __( 'Add slide', $this->plugin_name ); ?></button>

<script>
(function($){
    var $=jQuery.noConflict();

    $(document).ready(function() {
        $(".add-gallery-item").click(function() {
            var count = $('#gallery-items .gallery-item').length;

            var item = '<div class="gallery-item gallery-item-'+count+'">'+
                '<div class="gallery-item__image">'+
                    '<img src="<?= $this->default_image()??null; ?>" width="<?= $item['width']??'100'; ?>px" height="<?= $item['height']??'100'; ?>px" />'+
                    '<div class="gallery-item__btns"><input class="gallery-item__item" type="hidden" name="<?=$this->plugin_name; ?>_image['+count+'][item]" id="<?=$this->plugin_name; ?>_image['+count+'][item]" value="" />'+
                    '<button type="button" class="upload_image_button button"><? echo __( 'Upload', $this->plugin_name ); ?></button>'+
                    '<button type="button" class="remove_image_button button">&times;</button></div>'+
                '</div>'+
                '<div class="gallery-item__meta">'+
                    '<label for="<?=$this->plugin_name; ?>_image['+count+'][order]"><? echo __( 'Slide order', $this->plugin_name ); ?></label>'+
                    '<input type="number" name="<?=$this->plugin_name; ?>_image['+count+'][order]" id="<?=$this->plugin_name; ?>_image['+count+'][order]" value="" />'+
                    '<label for="<?=$this->plugin_name; ?>_image['+count+'][title]"><? echo __( 'Slide caption', $this->plugin_name ); ?></label>'+
                    '<textarea class="gallery-item__caption" rows="3" cols="50" name="<?=$this->plugin_name; ?>_image['+count+'][title]" id="<?=$this->plugin_name; ?>_image['+count+'][title]"></textarea>'+
                '</div>'+
            '</div>';
        
            $('#gallery-items').append(item);
            
            return false;
        });
    });
}(jQuery));
</script>