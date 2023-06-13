<?php
/**
 * The template for displaying faevents single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @since      1.0.0
 * @package    Filmacademy_Categories
 * @subpackage Filmacademy_Categories/public
 * @author     Watershed <john.d@watershed.co.uk>
 */

get_header();

/* Start the Loop */
while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/content/content-single-faevents' );

  ?>

  <?php

endwhile; // End of the loop.

?>

<?php
get_footer();
