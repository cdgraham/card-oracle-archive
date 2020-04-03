<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://cdgraham.com
 * @since      0.4.3
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="admin-display">
    <div class="co__dashboard">
        <span class="dashicons dashicons-dashboard"></span>
        <h2>Card Oracle</h2>
    </div>

    <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'dashboard_options'; ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=sandbox_theme_options&tab=dashboard_options" class="nav-tab <?php echo $active_tab == 'dashboard_options' ? 'nav-tab-active' : ''; ?>">Dashboard</a>
        <a href="?page=sandbox_theme_options&tab=settings_options" class="nav-tab <?php echo $active_tab == 'settings_options' ? 'nav-tab-active' : ''; ?>">Settings</a>
    </h2>
    <div id="co_dashboard" class="dashboardcontent">
        <div class="co__cards">
            <div class="co__card">
                <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_readings' ); ?>">
                    <div class="co__card-header">
                        Readings
                    </div>
                    <div class="co__card-body">
                        <div class="count"><?php echo ( $readings_count ); ?> Total</div>
                        <div class="icon-container push-right dashicons dashicons-welcome-view-site"></div>
                    </div>
                </a>
            </div>
            <div class="co__card">
                <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_positions' ); ?>">
                    <div class="co__card-header">
                        Positions
                    </div>
                    <div class="co__card-body">
                        <div class="count"><?php echo ( $positions_count ); ?> Total</div>
                        <div class="icon-container push-right dashicons dashicons-editor-ol"></div>
                    </div>
                </a>
            </div>
            <div class="co__card">
                <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_cards' ); ?>">
                    <div class="co__card-header">
                        Cards
                    </div>
                    <div class="co__card-body">
                        <div class="count"><?php echo ( $cards_count ); ?> Total</div>
                        <div class="icon-container push-right dashicons dashicons-admin-page"></div>
                    </div>
                </a>
            </div>

            <div class="co__card">
                <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_descriptions' ); ?>">
                    <div class="co__card-header">
                        Descriptions
                    </div>
                    <div class="co__card-body">
                        <div class="count"><?php echo ( $descriptions_count ); ?> Total</div>
                        <div class="icon-container push-right dashicons dashicons-media-text"></div>
                    </div>
                </a>
            </div>
        </div> <!-- Cards -->
        <div class="co__dashboard">
            <span class="dashicons dashicons-chart-bar"></span>
            <h2>Reading Statistics</h2>
        </div>
            <div class="co__stats"> <!-- Statistics for each Reading -->
                <?php for ( $i = 0; $i < count( $readings ); $i++ ) { ?>
                
                    <div class="co__stat">
                        <div class="co__stat-header">
                            <?php echo ( $readings[$i]->post_title ); ?>
                        </div>
                        <div class="co__stat-body">
                            <p><?php echo $reading_array[$i]->positions . ' positions'; ?></p>
                            <p><?php echo $reading_array[$i]->cards . ' cards'; ?></p>
                            <p><?php echo $reading_array[$i]->descriptions . ' descriptions'; ?></p>
                        </div>
                    </div>
                <?php } ?>

            </div> <!-- Statistics for each Reading -->

    </div> <!-- co_dashboard -->

    <div id="co_settings" class="settingscontent">
    </div> <!-- co_settings -->

</div> <!-- the-admin-display -->