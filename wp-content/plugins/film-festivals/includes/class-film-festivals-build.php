<?php

/**
 * Build taxonomy terms.
 *
 * Inserts terms into the relevant taxonomy.
 *
 * @since      1.0.0
 * @package    Film_Festivals
 * @subpackage Film_Festivals/includes
 * @author     John Davies <johnglynndavies@gmail.com>
 */
class Film_Festivals_Build {

  private $categories;

  public function __construct($categories = []) {
    $this->categories = $categories;
  }

  public function build() {
    foreach ($this->categories as $slug => $category) {
      $args = [
        'slug' => $slug, 
        'description' => $category['description'],
      ];

      $current_term_obj = get_term_by('slug', $slug, $category['taxonomy']);
      // skip if current term already exists
      if ($current_term_obj) continue;

      if (isset($category['parent'])) {
        // get parent term id from slug
        $parent_term_obj = get_term_by('slug', $category['parent'], $category['taxonomy']);
        if ($parent_term_obj instanceof WP_Term) {
          $args['parent'] = $parent_term_obj->term_id;
        }
      }
      //create categories
      wp_insert_term(
        // the term name
        $category['term'], 
        // the taxonomy
        $category['taxonomy'], 
        $args
      );
    }
  }

}
