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
<div class="wrap" id="film-festivals-admin">
    <div id="icon-tools" class="icon32"><br></div>
    <h2><?php echo $this->get_title(); ?></h2>
    <?php if (!empty($_GET['updated'])) : ?>
        <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
            <p><strong><?php _e('Settings saved.') ?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
        </div>
    <?php endif; ?>
    <form action="options.php" method="POST">
        <?php settings_fields($this->plugin_name . '_settings'); ?>
        <?php do_settings_sections($this->plugin_name); ?>
        <?php submit_button(__('Save')); ?>
    </form>

    <h2>Create a Programme</h2>
    <p>When adding a Programme, remember to also add a translation for it so that any exhibit translations can be added to the translated programme later.</p>
    <table class="form-table" role="presentation">
        <tbody>
        <tr>
          <th>
    <a href="<?php echo admin_url('edit-tags.php?taxonomy=festival_category&post_type=exhibit'); ?>" class="page-title-action show">Add New Programme</a>
  </th>
  </tr>
</tbody>
</table>

    <h2><?php echo __('Import exhibit list'); ?></h2>
    <ol>
    <li>Build your exhibit list in a spreadsheet application like Excel with 4 columns that represent: "id" "title" "language" "start datetime,end datetime". </li>
    <li>Export your list as a Tab-delimited Text (.txt) file</li>
    <li>Upload the txt file using the form below.</li>
  </ol>
    <form method="post" enctype="multipart/form-data">
      <table class="form-table" role="presentation">
        <tbody>
        <tr>
          <th scope="row"><label for="programme">
          Select Programme</label>
          </th>
          <td>
          <select id="programme" name="programme" class="regular-text">
          <?php foreach ($this->categories() as $id => $category): ?>
            <option value="<?= $id; ?>"><?= $category; ?></option>
          <?php endforeach; ?>
          </select>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="file">
        Upload txt file</label>
       </th>
       <td>
         <input type="file" name="file" required />
      <input type="submit" name="film-festivals_upload" value="Upload" class="button button-primary" />
    </td>
        </tr>
      </tbody>
  </table>
    </form>
</div>