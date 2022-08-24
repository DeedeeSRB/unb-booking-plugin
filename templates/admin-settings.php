<div class="wrap">
    <?php settings_errors( 'unb_booking_plugin_settings' ); ?>

    <h1>Dashboard</h1>
    <form action="options.php" method="post">
        <?php
        settings_fields( 'unb_booking_plugin_default_room_vals' );
        do_settings_sections( 'unb_booking_plugin_settings' );
        submit_button( 'Save Settings' );
        ?>
    </form>
</div>