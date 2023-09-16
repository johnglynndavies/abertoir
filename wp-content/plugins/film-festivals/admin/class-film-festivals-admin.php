<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * This class defines 
 *
 * @since      1.0.0
 * @package    Film_Festivals
 * @subpackage Film_Festivals/admin
 * @author     John Davies <johnglynndavies@gmail.com>
 */
class Film_Festivals_Admin {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  /**
   * The default terms to create for custom taxonomies.
   */
  private $terms = [
    'welsh-premiere' => [
      'term' => 'Welsh Premiere',
      'taxonomy' => 'exhibit_tags',
    ],
    'uk-premiere' => [
      'term' => 'UK Premiere',
      'taxonomy' => 'exhibit_tags',
    ],
    'euro-premiere' => [
      'term' => 'European Premiere',
      'taxonomy' => 'exhibit_tags',
    ],
    'world-premiere' => [
      'term' => 'World Premiere',
      'taxonomy' => 'exhibit_tags',
    ],
    'q-and-a' => [
      'term' => 'Q&A',
      'taxonomy' => 'exhibit_tags',
    ],
  ];

  /**
   * Add terms to custom taxonomies. 
   */
  public function add_terms() {

    if ( ! class_exists( 'Film_Festivals_Taxonomy' ) ) {
      require FILM_FESTIVALS_PLUGIN_DIR . 'includes/class-film-festivals-taxonomy.php';
    }

    $film_festivals_taxonomy = new Film_Festivals_Taxonomy($this->terms);
    $film_festivals_taxonomy->add();
  }

  /**
   * Add custom metaboxes. 
   */
  public function add_film_festival_metaboxes() {
    if ( ! class_exists( 'Film_Festivals_Metaboxes' ) ) {
      require FILM_FESTIVALS_PLUGIN_DIR  . 'includes/class-film-festivals-metaboxes.php';
    }

    $film_festivals_metaboxes = new Film_Festivals_Metaboxes($this->plugin_name);
    $film_festivals_metaboxes->add();

    if ( ! class_exists( 'Film_Festivals_Taxonomy_Metaboxes' ) ) {
      require FILM_FESTIVALS_PLUGIN_DIR  . 'includes/class-film-festivals-taxonomy-metaboxes.php';
    }

    $film_festivals_metaboxes = new Film_Festivals_Taxonomy_Metaboxes($this->plugin_name);
   // $film_festivals_metaboxes->add();
  }

  /**
   * Add custom metaboxes. 
   */
  public function save_film_festival_metaboxes($post_id, $post) {
    if ( ! class_exists( 'Film_Festivals_Metaboxes' ) ) {
      require FILM_FESTIVALS_PLUGIN_DIR  . 'includes/class-film-festivals-metaboxes.php';
    }

    $film_festivals_metaboxes = new Film_Festivals_Metaboxes($this->plugin_name);
    $film_festivals_metaboxes->save($post_id, $post);

    if ( ! class_exists( 'Film_Festivals_Taxonomy_Metaboxes' ) ) {
      require FILM_FESTIVALS_PLUGIN_DIR  . 'includes/class-film-festivals-taxonomy-metaboxes.php';
    }

    $film_festivals_metaboxes = new Film_Festivals_Taxonomy_Metaboxes($this->plugin_name);
    //$film_festivals_metaboxes->save($post_id, $post);
  }

  /**
   * Create custom post types. 
   */
  public function create_post_types() {

    // register events taxonomy
    register_taxonomy(
      'festival_category',
      'exhibit',
      [
        'labels' => [
          'name' => __('Types'),
          'add_new_item' => __('Add New Type'),
          'new_item_name' => __("New Type")
        ],
        'hierarchical' => true,
        'public' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'show_in_quick_edit' => false,
        'meta_box_cb' => false,
        'query_var' => 'programme',
        'rewrite' => ['slug' => 'programme', 'with_front' => false],
      ]
    );

    register_taxonomy(
      'exhibit_tags',
      'exhibit',
      [
        'labels' => [
          'name' => __('Tags'),
          'add_new_item' => __('Add New Tag'),
          'new_item_name' => __('New Tag')
        ],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_tagcloud' => true,
        'rewrite' => false,
      ]
    );

    // register resources type
    register_post_type(
      'exhibit', 
      [
        'labels' => [
          'name' => __('Exhibits'),
          'singular_name' => __('Exhibit'),
          'add_new' => __('Add Exhibit'),
          'add_new_item' => __('Add Exhibit'),
          'edit_item' => __('Edit Exhibit'),
        ],
        'public' => true,
        //'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_in_menu'=> $this->plugin_name,
        'hierarchical' => true,
        'has_archive' => false,
        'publicaly_queryable' => true,
        'query_var' => true,
        'menu_position' => 5,
        'supports' => ['title','editor','thumbnail','excerpt','custom-fields','page-attributes'],
        //'menu_icon' => 'dashicons-media-video',
        'taxonomies' => ['festival_category', 'exhibit_tags'],
        'rewrite' => ['slug' => 'event'],
        /*'template' => [
          [
            'core/pattern',
            [
              'slug' => 'abertoir2022/exhibit',
            ],
          ],
        ],*/
      ]
    );

    // register resources type
   /* register_post_type(
      'programmes', 
      [
        'labels' => [
          'name' => __('Programmes'),
          'singular_name' => __('Programme'),
          'add_new' => __('Add Programme'),
          'add_new_item' => __('Add Programme'),
          'edit_item' => __('Edit Programme'),
        ],
        'public' => true,
        //'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_in_menu'=> $this->plugin_name,
        'hierarchical' => true,
        'has_archive' => 'false',
        'publicaly_queryable' => true,
        'query_var' => true,
        'menu_position' => 4,
        'supports' => ['title','editor','thumbnail','excerpt','custom-fields','page-attributes'],
        //'menu_icon' => 'dashicons-media-video',
        'taxonomies' => ['festival_category', 'exhibit_tags'],
        'rewrite' => ['slug' => '%festival_category%'],
      ]
    );*/

  }

  /**
   * register custom meta tag field
   */ 
  public function create_meta_fields() {
    register_post_meta( 'exhibit', 'start_date', array(
        'show_in_rest' => array(
          'schema' => array(
              'type'  => 'array',
              'items' => array(
                  'type' => 'object',
                  'properties' => array(
                    'start_time'    => array(
                        'type' => 'string',
                    ),
                    'end_time' => array(
                        'type'   => 'string',
                    ),
                ),
              ),
          ),
        ),
        'single' => true,
        'type' => 'array',
        'auth_callback' => function() {
          return current_user_can('edit_posts');
        }
    ) );

    register_post_meta( 'exhibit', '_event_date', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'auth_callback' => function() {
          return current_user_can('edit_posts');
        }
    ) );
  }

  /**
   * Rewrite permalinks.
   */
  public function rewrite_permalinks($post_link, $post) {
    $post_types = ['exhibit'];

    foreach($post_types as $post_type) {
      if ( is_object($post) && get_post_type($post) == $post_type ) {
        $terms = wp_get_object_terms($post->ID, "festival_category");
        $replacement = "%programme%"; 
        $slugs = [];

        foreach($terms as $term) {
         
          $slugs[] = $this->rewriteSlug($term->slug);
          $this->getParentTerms($term, $slugs);

        }
        
        krsort($slugs);
        $slugs = implode('/', $slugs);

        if ( $terms && (!$terms instanceof WP_Error)) {
          return str_replace($replacement , $slugs, $post_link);
        }
      }
    }

    return $post_link;
  }

  /**
   * Add rewrite rules.
   */
  function add_rewrite_rules() {
    $terms = get_terms([
      'taxonomy' => 'festival_category',
      'hide_empty' => false,
    ]);

    foreach($terms as $term) {
      $slugs = [];
      $selected_slug = $term->slug;
      $slug = $this->rewriteSlug($term->slug);
      $slugs[] = $slug;
      $this->getParentTerms($term, $slugs);
      krsort($slugs);
      $slugs = implode('\/', $slugs);

      add_rewrite_rule('^'.$slugs.'/([^/]*)$', 'index.php?exhibit=$matches[1]&festival_category='.$selected_slug, 'top');
    }
  }

  private function rewriteSlug($slug) {
    if (preg_match("/(-\d{4})$/", $slug, $matches)) {
      $year = $matches[0];
      $slug = substr($slug, 0, strrpos($slug, $year));
    }

    return $slug;
  }

  private function getParentTerms($term, &$slugs) : void
  {
    if ($term->parent) {
      $parent_term = get_term_by('term_id', $term->parent, 'festival_category');
      $slug = $this->rewriteSlug($parent_term->slug);
      $slugs[] = $slug;

      if ($parent_term->parent) {
        $this->getParentTerms($parent_term, $slugs);
      }
    }
  }

  /**
   * Add film festivals menu page
   */
  function create_film_festivals_admin() {
    if ( ! class_exists( 'Film_Festivals_Admin_Page' ) ) {
      require FILM_FESTIVALS_PLUGIN_DIR . 'admin/class-film-festivals-admin-page.php';
    }

    $admin_page = new Film_Festivals_Admin_Page( $this->plugin_name );
    call_user_func_array('add_menu_page', $admin_page->get_page_arguments());
  }

  /**
   * Add taxonomy submenu page
   */
  function setup_post_type_submenu() {
    add_submenu_page(
      $this->plugin_name,
      'Programmes',
      'Programmes',
      'manage_categories',
      'edit-tags.php?taxonomy=festival_category&post_type=exhibit'
    );
  }

  /**
   * Fix active menu for taxonomy submenu
   */
  function tax_menu_correction($parent_file) {
    global $current_screen;
    $taxonomy = $current_screen->taxonomy;

    if ($taxonomy == 'festival_category')
        $parent_file = $this->plugin_name;
    return $parent_file;
  }

  /**
   * Fix active menu for taxonomy submenu
   */
  function film_festivals_upload() {
    if ( isset($_POST['film-festivals_upload']) ) {
      if (is_uploaded_file($_FILES['file']['tmp_name'])) {
        $mime_type = mime_content_type($_FILES['file']['tmp_name']);
        $allowed_file_types = ['text/plain'];

        if ( in_array($mime_type, $allowed_file_types) ) {
          define('MB', 1048576);

          if ($_FILES['file']['size'] < 2*MB) {
            $filename = preg_replace('/\s+/', '', $_FILES['file']['name']);
            $destination = ABSPATH.'../tmp/'.strtolower($filename);

            if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
              $programme = isset($_POST['programme']) ? $_POST['programme'] : '';
              $this->insert_posts_from_csv($destination, $programme);
            }
            else {
              error_log('Failed to move uploaded file to: '.$destination);
            }
          }
          else {
            // print error msg to screen
          }
          
        }

        //upload = wp_upload_bits($_FILES['file']['name'], null, $_FILES['file']['tmp_name']);
       // save into database $upload['url]
      }
    }
  }

  function insert_posts_from_csv($filepath, $programme)
  {
    if(!file_exists($filepath)) {
      throw new Exception(sprintf('CSV file not found: "%s"', $filepath));
    } else if(FALSE === ($file = fopen($filepath, 'r'))) {
      throw new Exception('Failed to open CSV file');
    } else if(FALSE === ($sample = fread($file, 8192))) {
      throw new Exception('Failed to read a sample of the CSV file');
    } else if(fseek($file, 0) !== 0) {
      throw new Exception('Failed to seek to the beginning of the CSV file');
    } else if(FALSE === ($encoding = $this->_detectCSVFileEncoding($sample))) {
      throw new Exception('Failed to determine the CSV file encoding');
    }/* 
// this is messing up data for some reason
    else if(!is_resource(stream_filter_prepend($file, "convert.iconv.{$encoding}/UTF-8//TRANSLIT"))) {
      throw new Exception('Failed to filter the CSV-file stream');
    }
*/


    $inserted = [];
    
    // Read the CSV file line by line
    while (($data = fgetcsv($file, 0, "\t")) !== FALSE) {   

      if (empty($data[0])) continue;
      //error_log('data: '. print_r($data, true));

      $post_content = '';
      /*
       * Create a event block programmatically and serialize it.
       */
      $block_name = 'core/paragraph';
      $innerHTML  = 'Sample paragraph text.';
      
      $pattern =[
        'core/pattern',
        [
          'slug' => 'abertoir2022/exhibit',
        ],
      ];
      $converted_block = new WP_Block_Parser_Block( $block_name, array(), array(), $innerHTML, array( $innerHTML ) );
      //error_log( print_r( $converted_block, true ) );
      $post_content .= serialize_block( (array) $converted_block );
      //error_log( $serialized_block );

      $id = $data[0];
      $title = $data[1];
      $lang = $data[2];
      $dates = explode(',', $data[3]);
      
      // Create a new post object and set its properties
      $post = array(
        'post_title' => $title,
        'post_content' => $post_content,
        'post_status' => 'draft',
        'post_type' => 'exhibit',
        'meta_input'  => [
          'end_date' => $dates[1],
          'start_date' => $dates[0],
        ]
      );
    
      // Insert the post into the database
      $post_id = wp_insert_post($post);
    
      // Check if the post was inserted successfully
      if ($post_id > 0) {
        // set the post translation
        pll_set_post_language( $post_id, $lang );

        // set translation of the term
        $taxonomy = 'festival_category';
        $term = pll_get_term( $programme, $lang );
        error_log('$programme: '.print_r($programme, true));
        error_log('$term: '.print_r($term, true));

        wp_set_object_terms( $post_id, $term, $taxonomy );
        
        // Post was inserted successfully, do something else if needed
        // add to inserted array and post a msg on screen
        $inserted[$lang][(string) $id] = $post_id;
      } else {
        // Post was not inserted, handle the error if needed
      }
    }

    fclose($file);

    $langs = array_keys($inserted);

    if (count($langs) > 1) {
      // we have translations to tie together!
      $save_exhibit_translations = [];
      
      foreach($inserted as $key => $exhibits) {
        foreach($exhibits as $id => $post_id) {
          $exhibits_translation = [];

          foreach($langs as $lang) {
            if (isset($inserted[$lang][$id])) {
              // create array of translated posts
              $exhibits_translation[$lang] = $inserted[$lang][$id];
            }
          }

          if (count($exhibits_translation) > 1) {
            $save_exhibit_translations[$id] = $exhibits_translation;
          }
        }

        // no need to loop again as we do this in the nested loop!
        break;
      }

      //error_log('save_exhibit_translations: '.print_r($save_exhibit_translations, true));

      if (!empty($save_exhibit_translations)) {
        foreach ($save_exhibit_translations as $arr) {
          pll_save_post_translations( $arr );
        }
      }
    }
    
    do_action('admin_notices', $inserted);
  }

  function upload_completion_notice($arg)
  {
    require_once(ABSPATH . 'wp-admin/includes/screen.php');
    $screen = get_current_screen();

    //var_dump($screen);
  
    if ($screen->id === 'toplevel_page_film-festivals"') {
              
        if (!empty($arg)) : ?>
          
          <div class="notice notice-success is-dismissible">
            <p><?php _e('Default settings restored.', $this->plugin_name); ?></p>
          </div>
          
        <?php else : ?>
          
          <div class="notice notice-error is-dismissible">
            <p><?php _e('No changes made.', $this->plugin_name); ?></p>
          </div>
          
        <?php endif;
      
    }

/* $class = 'notice notice-error';
    $message = __( 'Irks! An error has occurred.', $this->plugin_name );

    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );*/
  }

  /*
   * _detectCSVFileEncoding
   *
   * Attempts to determiene the characted-encoding of the text sample
   *
   * @param String $sample    : A sample string to test
   * @param Array  $encodings : An optional list of encodings to test
   *
   * @return Mixed : FALSE if no encoding matched, otherwise the encoding
   */
  private function _detectCSVFileEncoding($sample, $encodings = null) {
    if(is_null($encodings)) {
      $encodings = array('UTF-16LE', 'Mac', 'Windows-1250', 'ISO-8859-15', 'ISO-8859-1', 'UTF-8');
    }
    $sampleMD5 = md5($sample);
    foreach($encodings as $encoding) {
      if(md5(iconv($encoding, $encoding, $sample)) === $sampleMD5) {
        return $encoding;
      }
    }
    return FALSE;
  }

}
