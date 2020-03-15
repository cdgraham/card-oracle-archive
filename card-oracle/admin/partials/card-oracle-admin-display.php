<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://cdgraham.com
 * @since      0.4.0
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-lg-5 col-md-6 col-sm-5">
                <h3 class="panel-title"><span class="dashicons dashicons-chart-bar"></span> <?php _e( 'Your Card Oracle Stats', 'card-oracle' ); ?></h3>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="registered-count">
            <div class="row">
                <div class="col">
                    <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_readings' ); ?>">
                        <div class="card mb-3 -stats -wp-blue">
                            <div class="card-body">
                                <span class="icon-container pull-right dashicons dashicons-admin-page"></span>
                                <div class="card-title">
                                    <span class="count"><?php echo ( $readings_count ); ?></span>
                                </div>
                                <p class="card-text" data-original-title="Total active members">
                                    <?php _e( 'ALL Readings', 'card-oracle' ); ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_readings' ); ?>">
                        <div class="card mb-3 -stats -wp-blue">
                            <div class="card-body">
                                <span class="icon-container pull-right dashicons dashicons-admin-page"></span>
                                <div class="card-title">
                                    <span class="count"><?php echo ( $positions_count ); ?></span>
                                </div>
                                <p class="card-text" data-original-title="Total active members">
                                    <?php _e( 'ALL Positions', 'card-oracle' ); ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_readings' ); ?>">
                        <div class="card mb-3 -stats -wp-blue">
                            <div class="card-body">
                                <span class="icon-container pull-right dashicons dashicons-admin-page"></span>
                                <div class="card-title">
                                    <span class="count"><?php echo ( $cards_count ); ?></span>
                                </div>
                                <p class="card-text" data-original-title="Total active members">
                                    <?php _e( 'ALL Cards', 'card-oracle' ); ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_readings' ); ?>">
                        <div class="card mb-3 -stats -wp-blue">
                            <div class="card-body">
                                <span class="icon-container pull-right dashicons dashicons-admin-page"></span>
                                <div class="card-title">
                                    <span class="count"><?php echo ( $descriptions_count ); ?></span>
                                </div>
                                <p class="card-text" data-original-title="Total active members">
                                    <?php _e( 'ALL Descriptions', 'card-oracle' ); ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>



            </div>
        </div>
    </div> 
</div>