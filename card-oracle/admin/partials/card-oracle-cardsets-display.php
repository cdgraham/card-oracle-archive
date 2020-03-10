<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://cdgraham.com
 * @since      0.3.0
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h1 class="wp-heading-inline">Card Sets</h1>
    <a href="<?php echo plugin_dir_path( __DIR__ ) . 'set-new.php' ?>" class="page-title-action">Add New</a>
        <?php
            $set_obj->display(); 
        ?>
</div>