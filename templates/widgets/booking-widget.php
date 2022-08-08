<nav>
    <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
    <?php
        $index = 0;
        foreach($cpt_posts as $post_name => $posts) {
            $post_name_l = strtolower( $post_name );

            if ( 'yes' !== $settings[$post_name] ) continue;

            if ( $index == 0 ) {
            ?>
                <button class="nav-link active" id="nav-<?= $post_name_l ?>-tab" data-bs-toggle="tab" data-bs-target="#nav-<?= $post_name_l ?>" type="button" role="tab" aria-controls="nav-<?= $post_name_l ?>" aria-selected="true"><?= $post_name ?></button>
            <?php
            }
            else {
            ?>
                <button class="nav-link" id="nav-<?= $post_name_l ?>-tab" data-bs-toggle="tab" data-bs-target="#nav-<?= $post_name_l ?>" type="button" role="tab" aria-controls="nav-<?= $post_name_l ?>" aria-selected="false"><?= $post_name ?></button>
            <?php
            }
            $index++;
        }
    ?>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <?php
        $index = 0;
        foreach($cpt_posts as $post_name => $posts) {
            $post_name_l = strtolower( $post_name );

            if ( 'yes' !== $settings[$post_name] ) continue;

            if ( $index == 0 ) {
            ?>
                <div class="tab-pane fade show active" id="nav-<?= $post_name_l ?>" role="tabpanel" aria-labelledby="nav-<?= $post_name_l ?>-tab" tabindex="0">
                    <div class="text-center">
                        <?php
                        foreach ( $posts as $post) {
                            $img = get_the_post_thumbnail_url( $post->ID, 'post-thumbnail' );
                            ?>
                                <div class="row my-4">
                                    <div class="col text-start">
                                        <a href="<?= $post->guid ?>"><?= esc_html__( $post->post_title ) ?></a>
                                        <div><?= esc_html__( $post->post_content ) ?></div>   
                                    </div>
                                    <div class="col text-center">
                                        <a href="<?= $post->guid ?>"><img src="<?= $img ?>" alt=""></a>
                                    </div>
                                </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            else {
            ?>
                <div class="tab-pane fade" id="nav-<?= $post_name_l ?>" role="tabpanel" aria-labelledby="nav-<?= $post_name_l ?>-tab" tabindex="0">
                    <div class="text-center">
                        <?php
                        foreach ( $posts as $post) {
                            $img = get_the_post_thumbnail_url( $post->ID, 'post-thumbnail' );
                            ?>
                                <div class="row mb-4">
                                    <div class="col text-start">
                                        <a href="<?= $post->guid ?>"><?= esc_html__( $post->post_title ) ?></a>
                                        <div><?= esc_html__( $post->post_content ) ?></div>   
                                    </div>
                                    <div class="col text-center">
                                        <a href="<?= $post->guid ?>"><img src="<?= $img ?>" alt=""></a>
                                    </div>
                                </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            $index++;
        }
    ?>
</div>