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
        
        $title = strcmp( $pos, 'Left' ) == 0 ? $symbol . ' ' . $price :  $price . ' ' . $symbol;

        $img = get_the_post_thumbnail_url( $post->ID, 'post-thumbnail' );
        ?>
            <div class="row my-4">
                <div class="col text-start">
                    <a href="<?= $post->guid ?>"><?= esc_html__( $post->post_title ) ?></a>
                    <div><?= esc_html__( $desc ) ?></div>  
                    <div><?= esc_html( $title ) ?> </div>
                    <div><i class="fas fa-users"></i> <?= $maxnv ?> </div>
                    <div><i class="fas fa-calendar-day"></i> <?= $minbd ?> </div>
                    <div><i class="fas fa-glass-cheers"></i> <?= $amen ?></div>
                </div>
                <div class="col text-center">
                    <a href="<?= $post->guid ?>"><img src="<?= $img ?>" alt=""></a>
                </div>
            </div>
        <?php
    }
?>