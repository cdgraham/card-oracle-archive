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
        <i class="dashicons dashicons-dashboard"></i>
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
    <div class="co__readings">
        <div class="co__readings-body">
            <table class="co__table-reading">
                <tr>
                    <th>Readings</th>
                    <th>Positions</th>
                    <th>Cards</th>
                    <th>Descriptions</th>
                </tr>
            <?php for($i = 0; $i < count( $readings ); $i++ ) {
                echo '<tr>';
                echo '<td>' . $readings[$i]->post_title . '</td>';
                echo '<td>' . count( $this->get_co_position_id_title( $readings[$i]->ID ) ) . '</td>';
                echo '<td>' . count( $this->get_co_card_id_title( $readings[$i]->ID ) ) . '</td>';
                echo '<td>' . count( $this->get_co_description_id_content( $readings[$i]->ID ) ) . '</td>';
                echo '</tr>';
            } ?>
            </table>
        </div>
    </div>

</div> <!-- the-admin-display -->