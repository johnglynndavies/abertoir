<?php

/**
 * Inserts metaboxes into editor.
 *
 * @since      1.0.0
 * @package    Film_Festivals
 * @subpackage Film_Festivals/includes
 * @author     John Davies <johnglynndavies@gmail.com>
 */
class Film_Festivals_Taxonomy_Metaboxes {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * Custom Post Type name.
   *
   * @since 1.0.0
   * @access private
   * @var string The name of the Custom Post Type
   */
  private $post_type_name = 'exhibit';

  /**
   * Meta key.
   *
   * @since 1.0.0
   * @access private
   * @var str
   */
  private $taxonomy = 'festival_category';

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param    string    $plugin_name       The name of this plugin.
   */
  public function __construct($plugin_name)
  {
   $this->plugin_name = $plugin_name;
  }

  /**
   * Adds meta boxes to admin screens.
   *
   * @since 1.0.0
   */
  public function add()
  {
    $taxonomy = get_taxonomy( $this->taxonomy );
    add_meta_box(
      'tagsdiv-' . $this->taxonomy, $taxonomy->labels->name,
      [ $this, 'programme_select' ],
      $this->post_type_name,
      'side',
      'default',
      [
          'tax'       =>  $taxonomy,
          'post_type' =>  $this->post_type_name,
          'options'   =>  [],
          '__back_compat_meta_box' => false,
      ]
    );
  }

  public function programme_select($post, $taxonomy)
  {
    $terms = get_terms(['taxonomy' => $this->taxonomy,'hide_empty' => false, 'order' => 'DESC', 'hierarchical' => true]);
    $popular = get_terms(['taxonomy' => $this->taxonomy, 'orderby' => 'count', 'number' => 10, 'order' => 'DESC', 'hierarchical' => true ] );
    $postterms = wp_get_post_terms($post->ID, $this->taxonomy,['fields' => 'all']);

    if ( is_array( $postterms ) ) {
      foreach ( $postterms as $single_term ) {
          $current_terms_array[] = $single_term->term_id;
      }
    }

    $_postterms = $postterms;
    $current = ( $postterms ? array_pop($_postterms) : false);
    $current = ( $current ? $current->term_id : 0 );

    $programmes = [];
    $children = [];
    foreach ($terms as $term) {
      // only get top level categories
      if ($term->parent) continue;
      $programmes[$term->term_id] = $term;
    }

    foreach ($terms as $term) {
      // only get child categories
      if (!$term->parent) continue;
      $children[$term->parent][] = $term;
    }

    krsort($programmes);
    ?>

<label for="programme-select">Select the programme this exhibit is part of:</label>
<select id="programme-select" name="programme-select">
  <?php
  foreach($programmes as $programme) :
  ?>
  <optgroup label="<?= $programme->name; ?>">
    <?php
    if (isset($children[$programme->term_id])):
      foreach($children[$programme->term_id] as $child) :
    ?>
      <option value="<?= $child->term_id; ?>"<?= ($current === $child->term_id ? ' selected' : ''); ?>><?= $child->name ?></option>
    <?php
      endforeach;
    endif;
    ?>
  </optgroup>
  <?php
  endforeach;
  ?>
</select>

    <?php
  }

  /**
   * Stores our additional params.
   *
   * @since 1.0.0
   *
   * @param integer $post_id the ID of the post (or revision)
   * @param integer $post the post object
   */
  public function save( $post_id, $post ) {

    // we don't use post_id because we're not interested in revisions

    // store our page meta data
    $result = $this->_save_festival_category( $post );

  }

  /**
   * When a post is saved, this also saves the metadata.
   *
   * @since 0.1
   *
   * @param WP_Post $post_obj The object for the post (or revision)
   */
  private function _save_festival_category( $post_obj ) {

    // if no post, kick out
    if ( ! $post_obj ) return;

    // is this an auto save routine?
    if ( defined('DOING_AUTOSAVE') AND DOING_AUTOSAVE ) return;

    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_obj->ID ) ) return;

    // check for revision
    if ( $post_obj->post_type == 'revision' ) {

      // get parent
      if ( $post_obj->post_parent != 0 ) {
        $post = get_post( $post_obj->post_parent );
      } else {
        $post = $post_obj;
      }

    } else {
      $post = $post_obj;
    }

    // bail if not specified post type
    if ( $post->post_type != $this->post_type_name ) return;

    error_log('post: '.print_r($_POST, true));

    // now process metadata
    if( !isset( $_POST['programme-select'] ) ) return;
    
    $term_id = $_POST['programme-select'];

    // save for this post
    $this->_save_term( $post, $term_id );

  }



  /**
   * Utility to automate metadata saving.
   *
   * @since 0.1
   *
   * @param WP_Post $post_obj The WordPress post object
   * @param string $key The meta key
   * @param mixed $data The data to be saved
   * @return mixed $data The data that was saved
   */
  private function _save_term( $post, $term_id ) {
    
    wp_set_object_terms($post->ID, (int) $term_id ,$this->taxonomy);

  }
  

}
