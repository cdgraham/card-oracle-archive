<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://cdgraham.com
 * @since      0.4.1
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
        <h2>Statistics</h2>
    </div>
        <div class="co__cards"> <!-- Statistics for each Reading -->
            <div class="co__card">
                    <div class="co__card-header">
                        Readings
                    </div>
                    <div class="co__card-body">
                        <div class="count"><?php echo ( $reading_titles ); ?></div>
                    </div>
            </div>
            <div class="co__card">
                    <div class="co__card-header">
                        Positions
                    </div>
                    <div class="co__card-table">
                        <?php echo ( $reading_pos ); ?>
                    </div>
            </div>
            <div class="co__card">
                    <div class="co__card-header">
                        Cards
                    </div>
                    <div class="co__card-body">
                        <div class="count"><?php echo ( $reading_card ); ?></div>
                    </div>
            </div>
            <div class="co__card">
                    <div class="co__card-header">
                        Descriptions
                    </div>
                    <div class="co__card-body">
                        <div class="count"><?php echo ( $reading_desc ); ?></div>
                    </div>
            </div>
        </div> <!-- Statistics for each Reading -->




    </div> <!-- co__statistics -->

</div> <!-- the-admin-display -->