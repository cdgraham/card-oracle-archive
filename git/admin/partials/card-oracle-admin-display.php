<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://cdgraham.com
 * @since      0.5.0
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php add_thickbox(); ?>
<div class="wrap admin-display">
    <h1 class="card-oracle-h1"><?php esc_html_e( 'Card Oracle', 'card-oracle' ); ?></h1>
    <span class="card-oracle-version">Version: <?php echo $this->version; ?></span>
    <hr class="wp-header-end">

    <?php $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'dashboard'; ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=card-oracle-admin-menu&tab=dashboard" class="nav-tab 
            <?php echo $active_tab == 'dashboard' ? 'nav-tab-active' : ''; ?>">Dashboard</a>
            <a href="?page=card-oracle-admin-menu&tab=general" class="nav-tab 
                <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>
            <a href="?page=card-oracle-admin-menu&tab=validation" class="nav-tab 
                <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">Validation</a>
    </h2>
    <?php if ( $active_tab === 'dashboard' ) { ?>
    <div id="co_dashboard" class="card-oracle-dashboard-content">
        <div class="card-oracle-cards">
            <div class="card-oracle-card">
                <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_readings' ); ?>">
                    <div class="card-oracle-card-header">
                        <?php esc_html_e( 'Readings', 'card-oracle' ); ?>
                    </div>
                    <div class="card-oracle-card-body">
                        <div class="count"><?php echo ( $readings_text ); ?></div>
                        <div class="card-oracle-icon-container dashicons dashicons-welcome-view-site"></div>
                    </div>
                </a>
            </div>
            <div class="card-oracle-card">
                <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_positions' ); ?>">
                    <div class="card-oracle-card-header">
                    <?php esc_html_e( 'Positions', 'card-oracle' ); ?>
                    </div>
                    <div class="card-oracle-card-body">
                        <div class="count"><?php echo ( $positions_text ); ?></div>
                        <div class="card-oracle-icon-container dashicons dashicons-editor-ol"></div>
                    </div>
                </a>
            </div>
            <div class="card-oracle-card">
                <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_cards' ); ?>">
                    <div class="card-oracle-card-header">
                    <?php esc_html_e( 'Cards', 'card-oracle' ); ?>
                    </div>
                    <div class="card-oracle-card-body">
                        <div class="count"><?php echo ( $cards_text ); ?></div>
                        <div class="card-oracle-icon-container dashicons dashicons-admin-page"></div>
                    </div>
                </a>
            </div>

            <div class="card-oracle-card">
                <a class="stats-link" href="<?php echo admin_url( 'edit.php?post_type=co_descriptions' ); ?>">
                    <div class="card-oracle-card-header">
                    <?php esc_html_e( 'Descriptions', 'card-oracle' ); ?>
                    </div>
                    <div class="card-oracle-card-body">
                        <div class="count"><?php echo ( $descriptions_text ); ?></div>
                        <div class="card-oracle-icon-container dashicons dashicons-media-text"></div>
                    </div>
                </a>
            </div>
        </div> <!-- Cards -->
        <div class="card-oracle-dashboard">
            <span class="dashicons dashicons-chart-bar"></span>
            <h2><?php esc_html_e( 'Reading Statistics', 'card-oracle' ); ?></h2>
        </div>
            <div class="card-oracle-stats"> <!-- Statistics for each Reading -->
                <?php 
                    $shortcode_text = __( 'Reading Shortcodes', 'card-oracle' );
                    $clippy_alt = __( 'Copy to clipboard', 'card-oracle' );

                    for ( $i = 0; $i < count( $readings ); $i++ ) {
                        /* translators: %d is a number */
                        $position_text = esc_html( sprintf( _n( '%d position', '%d positions', 
                            $reading_array[$i]->positions, 'card-oracle' ), number_format_i18n( $reading_array[$i]->positions ) ) );
                        /* translators: %d is a number */
                        $card_text = esc_html( sprintf( _n( '%d card', '%d cards', 
                            $reading_array[$i]->cards, 'card-oracle' ), number_format_i18n( $reading_array[$i]->cards ) ) );
                        /* translators: %d is a number */
                        $description_text = esc_html( sprintf( _n( '%d description', '%d descriptions', 
                            $reading_array[$i]->descriptions, 'card-oracle' ), number_format_i18n( $reading_array[$i]->descriptions ) ) );

                        $shortcode_name = sprintf('card-oracle-shortcodes-%d', $i );
                    ?>
                
                    <div class="card-oracle-stat">
                        <div class="card-oracle-stat-header">
                            <?php echo esc_html( $readings[$i]->post_title ); ?>
                        </div>
                        <div class="card-oracle-stat-body">
                            <p><?php echo $position_text ?></p>
                            <p><?php echo $card_text; ?></p>
                            <p><?php echo $description_text; ?></p>
                            <div class="card-oracle-shortcode-links">
                                <?php $shortcode_name = sprintf('card-oracle-shortcodes-%d', $i ); ?>
                                <a href="#TB_inline?&width=274&height=350&inlineId=card-oracle-shortcodes-<?php echo $i?>" 
                                class="thickbox" title="<?php echo $shortcode_text; ?>"><?php echo $shortcode_text; ?></a>
                                <!-- class="thickbox" name="<?php echo $shortcode_name ?>"><?php echo $shortcode_text; ?></a> -->
                            </div>
                            <div id="card-oracle-shortcodes-<?php echo $i?>" style="display:none;">
                                <p class="card-oracle-shortcode-header"><?php echo $shortcode_text; ?></p>
                                <div class="card-oracle-shortcode-body">
                                    <input class="card-oracle-shortcode" id="copy<?php echo $readings[$i]->ID;?>"  size="24"
                                        value="[card-oracle id=&quot;<?php echo $readings[$i]->ID;?>&quot;]">
                                    <button class="copyAction copy-action-btn button"
                                        title="<?php esc_html_e( 'Click to copy shortcode', 'card-oracle' ); ?>"
                                        value="[card-oracle id=&quot;<?php echo $readings[$i]->ID;?>&quot;]">
                                        <img src="<?php echo CARD_ORACLE_PLUGIN_URL ?>assets/images/clippy.svg" alt="<?php echo $clippy_alt ?>">
                                    </button>
                                </div>
                                <p class="card-oracle-shortcode-header"><?php esc_html_e( 'Daily Card Shortcode', 'card-oracle' ); ?></p>
                                <div class="card-oracle-shortcode-body">
                                    <input class="card-oracle-shortcode" id="copy<?php echo $readings[$i]->ID;?>-daily"  size="24"
                                        value="[card-oracle-daily id=&quot;<?php echo $readings[$i]->ID;?>&quot;]">
                                    <button class="copyAction copy-action-btn button"
                                        title="<?php esc_html_e( 'Click to copy shortcode', 'card-oracle' ); ?>"
                                        value="[card-oracle-daily id=&quot;<?php echo $readings[$i]->ID;?>&quot;]">
                                        <img src="<?php echo CARD_ORACLE_PLUGIN_URL ?>assets/images/clippy.svg" alt="<?php echo $clippy_alt ?>">
                                    </button>
                                </div>
                                <p class="card-oracle-shortcode-header"><?php esc_html_e( 'Random Card Shortcode', 'card-oracle' ); ?></p>
                                <div class="card-oracle-shortcode-body">
                                    <input class="card-oracle-shortcode" id="copy<?php echo $readings[$i]->ID;?>-random" size="24"
                                        value="[card-oracle-random id=&quot;<?php echo $readings[$i]->ID;?>&quot;]">
                                    <button class="copyAction copy-action-btn button"
                                        title="<?php esc_html_e( 'Click to copy shortcode', 'card-oracle' ); ?>"
                                        value="[card-oracle-random id=&quot;<?php echo $readings[$i]->ID;?>&quot;]">
                                        <img src="<?php echo CARD_ORACLE_PLUGIN_URL ?>assets/images/clippy.svg" alt="<?php echo $clippy_alt ?>">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div> <!-- Statistics for each Reading -->
    </div> <!-- co_dashboard -->
    <?php } elseif ( $active_tab === 'general' ) { ?>
        <div id="co_settings_general" class="wrap settingscontent">
            
            <form method="post" action="<?php echo esc_url( add_query_arg( 'tab', $active_tab, admin_url( 'options.php' ) ) ); ?>">
                <?php
                    settings_fields( 'card-oracle-admin-menu' );
                    do_settings_sections( 'card-oracle-admin-menu' );
                    submit_button();
                ?>
            </form>

        </div> <!-- active_tab general -->
    <?php } elseif ( $active_tab === 'validation' ) { ?>
        <div id="co_settings_validation" class="wrap settingscontent">
            
            

        </div> <!-- active_tab validation -->
    <?php } else { ?>
        <!-- Unknown active_tab -->
    <?php } ?>

</div> <!-- the-admin-display -->