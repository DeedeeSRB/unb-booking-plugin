<div class="container unb-rooms-list">
    <div class="row justify-content-center">
<?php
    foreach ( $posts as $post) {
        $price = get_post_meta( $post->ID, 'room_price', true );
        $desc = get_post_meta( $post->ID, 'room_desc', true );
        $maxnv = get_post_meta( $post->ID, 'room_max_num_vis', true );
        $minbd = get_post_meta( $post->ID, 'room_min_booking_days', true );
        $amen = get_post_meta( $post->ID, 'room_amenities', true );
        $currency = get_option( 'unb_product_currency_icon' ) ? get_option( 'unb_product_currency_icon' ) : '<i class="fas fa-dollar-sign"></i>';

        $currencyOptions = get_option( 'currency_options' );
        $pos = isset( $currencyOptions['pos'] ) ? $currencyOptions['pos'] : 'Right'; 
        $symbol = isset( $currencyOptions['symbol'] ) ? $currencyOptions['symbol'] : '$'; 
        
        $price_symbol = strcmp( $pos, 'Left' ) == 0 ? $symbol . ' ' . $price :  $price . ' ' . $symbol;

        $img = get_the_post_thumbnail_url( $post->ID, 'post-thumbnail' );
        ?>
        <div class="col-6 p-0 unb-rooms-items">
            <a href="<?= $post->guid ?>">
                <img src="<?= $img ?>" alt="<?= $post->post_title ?>">
            </a>
            <div class="unb-room-overlay">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between align-items-center align-content-center ">
                        <div class="unb-room-title d-flex align-self-center">
                            <a href="<?= $post->guid ?>">
                                <?= esc_html__( $post->post_title ) ?>
                            </a>
                        </div>
                        <div class="unb-room-price d-flex align-self-center">
                            <?= esc_html( $price_symbol ) ?>
                        </div>
                    </div>
                </div>
                <div class="unb-room-description">
                    <?= esc_html__( $desc ) ?>
                </div>
                <div class="unb-room-info">
                    <div class="unb-room-max-people">
                        <i class="fas fa-users"></i> <?= $maxnv ?>
                    </div>
                    <div class="unb-room-max-booking-days">
                        <i class="fas fa-calendar-day"></i> <?= $minbd ?>
                    </div>
                    <div class="unb-room-amenities">
                        <i class="fas fa-glass-cheers"></i> <?= $amen ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
?>
    </div>
</div>