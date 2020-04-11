<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://cdgraham.com
 * @since      0.4.4
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php add_thickbox(); ?>
<div class="wrap admin-display">
    <div class="co__dashboard">
        <span class="dashicons dashicons-dashboard"></span>
        <h2><?php esc_html_e( 'Card Oracle', 'card-oracle' ); ?></h2>
        <p>Version: <?php echo $this->version; ?></p>
    </div>

    <?php $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'dashboard_options'; ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=card-oracle-admin-menu&tab=dashboard_options" class="nav-tab <?php echo $active_tab == 'dashboard_options' ? 'nav-tab-active' : ''; ?>">Dashboard</a>
        <a href="?page=card-oracle-admin-menu&tab=settings_options" class="nav-tab <?php echo $active_tab == 'settings_options' ? 'nav-tab-active' : ''; ?>">Settings</a>
    </h2>
    <div id="co_dashboard" class="dashboardcontent">
        <div class="co__cards">
            <div class="co__card">
                <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_readings' ); ?>">
                    <div class="co__card-header">
                        <?php esc_html_e( 'Readings', 'card-oracle' ); ?>
                    </div>
                    <div class="co__card-body">
                        <div class="count"><?php echo ( $readings_text ); ?></div>
                        <div class="co__icon-container dashicons dashicons-welcome-view-site"></div>
                    </div>
                </a>
            </div>
            <div class="co__card">
                <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_positions' ); ?>">
                    <div class="co__card-header">
                    <?php esc_html_e( 'Positions', 'card-oracle' ); ?>
                    </div>
                    <div class="co__card-body">
                        <div class="count"><?php echo ( $positions_text ); ?></div>
                        <div class="co__icon-container dashicons dashicons-editor-ol"></div>
                    </div>
                </a>
            </div>
            <div class="co__card">
                <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_cards' ); ?>">
                    <div class="co__card-header">
                    <?php esc_html_e( 'Cards', 'card-oracle' ); ?>
                    </div>
                    <div class="co__card-body">
                        <div class="count"><?php echo ( $cards_text ); ?></div>
                        <div class="co__icon-container dashicons dashicons-admin-page"></div>
                    </div>
                </a>
            </div>

            <div class="co__card">
                <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_descriptions' ); ?>">
                    <div class="co__card-header">
                    <?php esc_html_e( 'Descriptions', 'card-oracle' ); ?>
                    </div>
                    <div class="co__card-body">
                        <div class="count"><?php echo ( $descriptions_text ); ?></div>
                        <div class="co__icon-container dashicons dashicons-media-text"></div>
                    </div>
                </a>
            </div>
        </div> <!-- Cards -->
        <div class="co__dashboard">
            <span class="dashicons dashicons-chart-bar"></span>
            <h2><?php esc_html_e( 'Reading Statistics', 'card-oracle' ); ?></h2>
        </div>
            <div class="co__stats"> <!-- Statistics for each Reading -->
                <?php for ( $i = 0; $i < count( $readings ); $i++ ) {
                    
                    /* translators: %d is a number */
                    $position_text = esc_html( sprintf( _n( '%d position', '%d positions', $reading_array[$i]->positions, 'card-oracle' ), 
                        number_format_i18n( $reading_array[$i]->positions ) ) );
                    /* translators: %d is a number */
                    $card_text = esc_html( sprintf( _n( '%d card', '%d cards', $reading_array[$i]->cards, 'card-oracle' ), 
                        number_format_i18n( $reading_array[$i]->cards ) ) );
                    /* translators: %d is a number */
                    $description_text = esc_html( sprintf( _n( '%d description', '%d descriptions', $reading_array[$i]->descriptions, 'card-oracle' ), 
                        number_format_i18n( $reading_array[$i]->descriptions ) ) );
                    ?>
                
                    <div class="co__stat">
                        <div class="co__stat-header">
                            <?php echo esc_html( $readings[$i]->post_title ); ?>
                        </div>
                        <div class="co__stat-body">
                            <p><?php echo $position_text ?></p>
                            <p><?php echo $card_text; ?></p>
                            <p><?php echo $description_text; ?></p>
                            <div class="co__shortcode-links">
                                <a href="#TB_inline?&width=274&height=350&inlineId=co-shortcodes-<?php echo $i?>" 
                                    class="thickbox" name="Card Oracle Shortcodes"><?php esc_html_e( 'Reading Shortcodes' ); ?></a>
                            </div>
                            <div id="co-shortcodes-<?php echo $i?>" style="display:none;">
                                <p class="co__shortcode-header"><?php esc_html_e( 'Reading Shortcode' ); ?></p>
                                <div class="co__shortcode-body">
                                    <input class="co-shortcode" id="copy<?php echo $readings[$i]->ID;?>"  size="24"
                                        value="[card-oracle id=&quot;<?php echo $readings[$i]->ID;?>&quot;]">
                                    <button class="copyAction copy-action-btn button"
                                        title="<?php esc_html_e( 'Click to copy shortcode' ); ?>"
                                        value="[card-oracle id=&quot;<?php echo $readings[$i]->ID;?>&quot;]">
                                        <img src="<?php echo PLUGIN_URL ?>assets/images/clippy.svg" alt="Copy to clipboard">
                                    </button>
                                </div>
                                <p class="co__shortcode-header"><?php esc_html_e( 'Daily Card Shortcode' ); ?></p>
                                <div class="co__shortcode-body">
                                    <input class="co-shortcode" id="copy<?php echo $readings[$i]->ID;?>-daily"  size="24"
                                        value="[card-oracle-daily id=&quot;<?php echo $readings[$i]->ID;?>&quot;]">
                                    <button class="copyAction copy-action-btn button"
                                        title="<?php esc_html_e( 'Click to copy shortcode' ); ?>"
                                        value="[card-oracle-daily id=&quot;<?php echo $readings[$i]->ID;?>&quot;]">
                                        <img src="<?php echo PLUGIN_URL ?>assets/images/clippy.svg" alt="Copy to clipboard">
                                    </button>
                                </div>
                                <p class="co__shortcode-header"><?php esc_html_e( 'Random Card Shortcode' ); ?></p>
                                <div class="co__shortcode-body">
                                    <input class="co-shortcode" id="copy<?php echo $readings[$i]->ID;?>-random" size="24"
                                        value="[card-oracle-random id=&quot;<?php echo $readings[$i]->ID;?>&quot;]">
                                    <button class="copyAction copy-action-btn button"
                                        title="<?php esc_html_e( 'Click to copy shortcode' ); ?>"
                                        value="[card-oracle-random id=&quot;<?php echo $readings[$i]->ID;?>&quot;]">
                                        <img src="<?php echo PLUGIN_URL ?>assets/images/clippy.svg" alt="Copy to clipboard">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div> <!-- Statistics for each Reading -->

    </div> <!-- co_dashboard -->

    <div id="co_settings" class="settingscontent">
    </div> <!-- co_settings -->

</div> <!-- the-admin-display -->