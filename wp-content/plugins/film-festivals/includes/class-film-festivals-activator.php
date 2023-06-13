<?php

require_once plugin_dir_path( __FILE__ ) . 'class-film-festivals-taxonomy.php';

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Film_Festivals
 * @subpackage Film_Festivals/includes
 * @author     John Davies <johnglynndavies@gmail.com>
 */
class Film_Festivals_Activator {

  private $terms = [
    'create' => [
      'term' => 'Create',
      'taxonomy' => 'category',
      'description' => 'We can support you in your filmmaking practise by offering mentoring, coaching and script development support. Find out more about the BFI Film Academy courses that run across the UK. As a student of a Film Academy course, you’ll gain experience working alongside industry professionals and will be offered hands-on filmmaking experience to help develop practical knowledge and skills.',
    ],
    'skills-development' => [
      'term' => 'Skills development',
      'taxonomy' => 'category',
      'parent' => 'create',
    ],
    'filmmaking-courses' => [
      'term' => 'Filmmaking courses',
      'taxonomy' => 'category',
      'parent' => 'create',
    ],
    'further-resources' => [
      'term' => 'Further Resources',
      'taxonomy' => 'category',
      'description' => 'Other websites that have great resources for aspiring filmmakers.',
    ],
    'talent-development' => [
      'term' => 'Talent development opportunities',
      'taxonomy' => 'category',
      'description' => 'Talent development opportunities from Watershed and our partners.',
    ],
    'exposure' => [
      'term' => 'Exposure',
      'taxonomy' => 'category',
      'description' => 'Posts relating to Exposure.',
    ],
  ];

  /**
   * Add terms to default categories taxonomy. 
   */
  public function activate() {    
    $film_festivals_taxonomy = new Film_Festivals_Taxonomy($this->terms);
    $film_festivals_taxonomy->add();
  }

}
