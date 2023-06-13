<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Film_Festivals
 * @subpackage Film_Festivals/public
 * @since 1.0.0
 */

if ( ! class_exists( 'Film_Festivals_Template_Loader' ) ) {
  require FILM_FESTIVALS_PLUGIN_DIR . 'includes/class-film-festivals-template-loader.php';
}

$film_festivals_template_loader = new Film_Festivals_Template_Loader();

get_header();

$description = get_the_archive_description();

?>
<?php if ( have_posts() ) : ?>

	<header class="page-header alignwide">
		<h1 class="page-title"><?php post_type_archive_title(); ?></h1>
		<?php if ( $description ) : ?>
			<div class="archive-description"><?php  echo wp_kses_post( wpautop( $description ) ); ?></div>
		<?php endif; ?>
	</header><!-- .page-header -->

  <?php if ( have_posts() ) : ?>
  <div class="fc-grid fc-grid-sm wide-max-width">
	<?php while ( have_posts() ) : ?>
  
		<?php the_post(); 

    $film_festivals_template_loader
          // $data = ['foo' => 'bar', 'baz' => 'boom'];
          //->set_template_data( $data, 'context' ) // access data within template as $context->foo
          ->get_template_part( 'grid/item' );

    ?>
  
	<?php endwhile; ?>
  </div>
  <?php endif; ?>

	<?php the_posts_pagination(); ?>

<?php else : ?>
	<?php get_template_part( 'template-parts/content/content-none' ); ?>
<?php endif; ?>

<h2 class="wide-max-width align-center">Further Resources</h2>
<div class="fc-grid fc-grid-lg wide-max-width">
<?php
/**
 * Get post grid
 * 
 * @todo: move this into a Block passing customised query args
 */
$args = [
  'post_type' => 'post',
  'category_name' => 'further-resources',
];
film_festivals_get_post_grid($args);
?>
</div>

<?php get_footer(); ?>
