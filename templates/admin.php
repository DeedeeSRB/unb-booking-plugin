<div class="wrap">
    <?php settings_errors( 'unb_booking_plugin' ); ?>

    <h1>Dashboard</h1>
    <form action="options.php" method="post">
        <?php
        settings_fields( 'unb_booking_plugin_currency_options' );
        do_settings_sections( 'unb_booking_plugin' );
        submit_button( 'Save Settings' );
        ?>
    </form>
</div>