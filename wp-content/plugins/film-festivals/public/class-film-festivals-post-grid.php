<?php

if ( ! class_exists( 'Film_Festivals_Template_Loader' ) ) {
  require FILM_FESTIVALS_PLUGIN_DIR . 'includes/class-film-festivals-template-loader.php';
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Film_Festivals
 * @subpackage Film_Festivals/public
 * @author     John Davies <johnglynndavies@gmail.com>
 */
class Film_Festivals_Post_Grid {

  /**
   * The query args.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $args    The query args.
   */
  private $args;

  /**
   * The query args.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $type    The query args.
   */
  private $type;

  /**
   * The template loader.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $templateLoader    The template loader.
   */
  private $templateLoader;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param    array    $args       The arguments.
   */
  public function __construct($args, $type) {
    $this->type = $type;
    $this->args = $args;
    $this->templateLoader = new Film_Festivals_Template_Loader();

  }

  /**
   * set function Parameters.
   */
  protected static function setParams($defaults, $params) {
    if (!is_array($params)) {
      throw new \Exception('Non-array passed as $params');
    }
    $mergedParams = array_merge($defaults, $params);

    if (count($mergedParams) !== count($defaults)) {
      $unknown_params = array_diff(array_keys($params), array_keys($defaults));
      throw new \Exception('Unknown params(s) passed: '.join(", ", $unknown_params));
    }

    return(array_intersect_key($mergedParams, $defaults));
  }

  /**
   * 
   * 
   */ 
  public function render() {

    $defaults = [ 
        'post_type' => 'post',
        'tax_query' => NULL,
        'meta_query' => NULL,
        'meta_key' => NULL,
        'meta_value' => NULL,
        'post__in' => NULL,
        'post__not_in' => NULL,
        'post_parent__in' => NULL,
        'post_status' => 'publish',
        'posts_per_page' => NULL, 
        'orderby' => 'date', 
        'order' => 'DESC', 
    ];

    $args = $this->setParams($defaults, $this->args);
    //error_log(print_r($args, true));
    $loop = new \WP_Query( $args );

    if ($loop->have_posts()) : 
      //error_log('Has posts');

      $content = '<div class="ws-post-grid">';
      
       while ( $loop->have_posts() ) : $loop->the_post();

        $type = $this->type ?? get_post_type();

        $content .= <get_the_title();

      endwhile; 

      $content .= '</div>';

      return $content;

    else:

      return FALSE;

    endif; 
    
    wp_reset_postdata();
  }

}
