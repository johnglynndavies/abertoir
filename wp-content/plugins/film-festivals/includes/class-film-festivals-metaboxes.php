<?php

/**
 * Inserts metaboxes into editor.
 *
 * @since      1.0.0
 * @package    Film_Festivals
 * @subpackage Film_Festivals/includes
 * @author     John Davies <johnglynndavies@gmail.com>
 */
class Film_Festivals_Metaboxes {

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
  private $event_dates_meta_key = 'start_date';

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
    // add our meta box
    add_meta_box(
      'exhibit_event_dates',
      __( 'Event dates', $this->plugin_name ),
      [ $this, 'event_dates' ],
      $this->post_type_name,
      'side'
    );
  }

  /**
   * Adds a meta box to exhibit edit screens.
   *
   * @since 1.0.0
   *
   * @param WP_Post $post The object for the current post/page
   */
  public function event_dates($post)
  {
    // Use nonce for verification
    wp_nonce_field( $this->event_dates_meta_key, $this->event_dates_meta_key.'_nonce' );

    // set key
    $db_key = $this->event_dates_meta_key;
    $timezone = new DateTimeZone('Europe/London');
    $events = [];

    // get value if the custom field already has one
    if (!empty($vals = $post->{$this->event_dates_meta_key})) {

      if (is_serialized($vals)) {
        $vals = unserialize($vals);
      }

      foreach($vals as $val) {
        $start_time = DateTime::createFromFormat(DATE_ATOM, $val['start_time'].'+00:00', $timezone);
        $end_time = DateTime::createFromFormat(DATE_ATOM, $val['end_time'].'+00:00', $timezone);

        if ($start_time && $end_time) {
          $events[] = ['start_time' => $start_time, 'end_time' => $end_time];
        }
      }
    } 
    else {
      $events[] = ['start_time' => new DateTime(), 'end_time' => new DateTime()];
    }

    $c = 0;
    foreach($events as $k => $event) {
      $c++;
      echo '<style>.event-date {margin-bottom: 1em;}</style>';
      echo '<div id="events">';
      echo '<div class="event-date event_'.$k.'"><label for="'.$this->event_dates_meta_key.'['.$k.'][start_time]">Start time:</label>';
      echo '<input type="datetime-local" id="'.$this->event_dates_meta_key.'_start" name="'.$this->event_dates_meta_key.'['.$k.'][start_time]" value="'.$event['start_time']->format('Y-m-d H:i:s').'" min="'.date('Y-m-d H:i:s').'" max="'.date('Y-m-d H:i:s', strtotime('+ 1 year')).'"><br />';
      echo '<label for="'.$this->event_dates_meta_key.'['.$k.'][end_time]">End time:</label>';
      echo '<input type="datetime-local" id="'.$this->event_dates_meta_key.'_end" name="'.$this->event_dates_meta_key.'['.$k.'][end_time]" value="'.$event['end_time']->format('Y-m-d H:i:s').'" min="'.date('Y-m-d H:i:s').'" max="'.date('Y-m-d H:i:s', strtotime('+ 1 year')).'"></div>';
      echo '</div>';
    }

    $c--;

    ?>
    <button type="button" class="add-event button"><?php _e('Add event'); ?></button>
    <button type="button" class="remove-event button"><?php _e('Remove event'); ?></button>
    <script>
    var $=jQuery.noConflict();
    $(document).ready(function() {
      var count = <?= $c; ?>;

      $(".add-event").click(function() {
        count = count + 1;
    
        $('#events').append('<div class="event-date event_'+count+'"><label for="<?= $this->event_dates_meta_key; ?>['+count+'][start_time]">Start time:</label><input type="datetime-local" id="<?= $this->event_dates_meta_key; ?>['+count+'][start_time]" name="<?= $this->event_dates_meta_key; ?>['+count+'][start_time]" value="<?= date('Y-m-d H:i:s'); ?>" min="<?= date('Y-m-d H:i:s'); ?>" max="<?= date('Y-m-d H:i:s', strtotime('+ 1 year')); ?>"><br /><label for="<?= $this->event_dates_meta_key; ?>['+count+'][end_time]">End time:</label><input type="datetime-local" id="<?= $this->event_dates_meta_key; ?>['+count+'][end_time]" name="<?= $this->event_dates_meta_key; ?>['+count+'][end_time]" value="<?= date('Y-m-d H:i:s'); ?>" min="<?= date('Y-m-d H:i:s'); ?>" max="<?= date('Y-m-d H:i:s', strtotime('+ 1 year')); ?>"><br /></div>' );
        
        return false;
      });

      $(".remove-event").on('click', function() {
        //console.log(count);
        $('.event_'+count).remove();
      });
    });
    </script>
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
    $result = $this->_save_event_dates( $post );

  }

  /**
   * When a post is saved, this also saves the metadata.
   *
   * @since 0.1
   *
   * @param WP_Post $post_obj The object for the post (or revision)
   */
  private function _save_event_dates( $post_obj ) {

    // if no post, kick out
    if ( ! $post_obj ) return;

    // authenticate
    $nonce = isset( $_POST[$this->event_dates_meta_key.'_nonce'] ) ? $_POST[$this->event_dates_meta_key.'_nonce'] : '';
    if ( ! wp_verify_nonce( $nonce, $this->event_dates_meta_key ) ) return;

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

    // now process metadata

    // define key
    $db_key = $this->event_dates_meta_key;

    // get value
    $event_dates = ( isset( $_POST[$this->event_dates_meta_key] ) ) ?  $_POST[$this->event_dates_meta_key]  : [];
    $values = [];

    foreach ($event_dates as $k => $event) {
      if (empty($event['start_time']) || empty($event['end_time'])) continue;

      $values[] = ['start_time' => esc_sql($event['start_time']), 'end_time' => esc_sql($event['end_time'])];
    }

    // save for this post
    $this->_save_meta( $post, $db_key, $values );

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
  private function _save_meta( $post, $key, $data = '' ) {

    // if the custom field already has a value...
    $existing = get_post_meta( $post->ID, $key, true );
    if ( false !== $existing ) {

      // update the data
      update_post_meta( $post->ID, $key, $data );

    } else {

      // add the data
      add_post_meta( $post->ID, $key, $data, true );

    }

    // --<
    return $data;

  }
  

}
