<?php
/**
 * Grid item.
 *
 * @package Film_Festivals
 * @subpackage Film_Festivals/public
 */
?>
<article class="ws-post-grid-item" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="ws-post-grid-inner">
    <a class="ws-post-grid-thumbnail alignwide" href="" aria-hidden="true" tabindex="-1">
      <?php the_post_thumbnail( '3_x_2_600' ); ?>
    </a>
    <h3><a href=""><?php the_title(); ?></a></h3>
    <?php the_excerpt(); ?>
    
  </div>
</article>
