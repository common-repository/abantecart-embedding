<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="abc abantecart-help">
    <div class="container">
        <div class="panel margin-top-4 margin-bottom-2">
            <div class="padding-4">
                <h1><?php _e('Embed your AbanteCart shopping cart', abantecart()->lang); ?></h1>
                <p><?php printf(__('Start selling products online from your WordPress site.')); ?></p>

                <h2><?php _e('How to', abantecart()->lang); ?></h2>
                <p><?php _e('Add AbanteCart store URL to WordPress plugin in <a href="?page=Abantecart">settings</a>.',
                        abantecart()->lang); ?></p>
                <p><img width="100%" height="auto" style="max-width:400px;"
                        src="<?php echo plugins_url('../assets/img/wp-3.png',__FILE__); ?>"></p>
                <p><?php _e('Requriements:',
                        abantecart()->lang); ?></p>
                <ul class="ul-disc">
                    <li><?php _e("Your store website needs an SSL certificate. If you're not sure whether your site has SSL, you can easily find out by checking the URL of the site. If it starts with HTTP, you aren't secure, and if it begins with HTTPS, then your website has an SSL certificate.",
                            abantecart()->lang); ?></li>
                    <li><?php _e("Your store URL should be on a publicly available domain. Not localhost or private IP",
                            abantecart()->lang); ?></li>
                    <li><?php _e("AbanteCart store must be upgraded to 1.3.0 or higher version.",
                            abantecart()->lang); ?></li>
                </ul>

                <h2><?php _e('Get embed url', abantecart()->lang); ?></h2>
                <p><?php printf(__('Login to your AbanteCart store admin panel',
                        abantecart()->lang), abantecart()->name, abantecart()->alias); ?></p>
                <p><img width="100%" height="auto" style="max-width:400px;"
                        src="<?php echo plugins_url('../assets/img/wp-4.png',__FILE__); ?>"></p>
                <p><?php printf(__('Copy the embed URL',
                        abantecart()->lang), abantecart()->name, abantecart()->alias); ?></p>
                <p><img width="100%" height="auto" style="max-width:400px;"
                        src="<?php echo plugins_url('../assets/img/wp-5.png',__FILE__); ?>"></p>

                <h2><?php _e('Insert', abantecart()->lang); ?></h2>
                <p><?php _e('To embed block please insert the copied URL in a new line on the WordPress page and Publish.',
                        abantecart()->lang); ?></p>
                <p><img width="100%" height="auto" style="max-width:500px;"
                        src="<?php echo plugins_url('../assets/img/wp-7.png',__FILE__); ?>"></p>

                <h2><?php _e('Additional settings', abantecart()->lang); ?></h2>
                <p><?php printf(__('More configuration options (e.g., currency, language) are available in the AbanteCart store %s.',
                        abantecart()->lang),
                        '<a href="'.esc_url(abantecart()->embeds).'" target="_blank">'.__('Embed Editor',
                            abantecart()->lang).'</a>'); ?></p>

                <h2><?php _e('SSL/HTTPS Security', abantecart()->lang); ?></h2>
                <p><?php printf(__('Contact your hosting provider for more information.', abantecart()->lang),
                        abantecart()->name); ?></p>
                <p><?php _e('If you need help setting up HTTPS on your WordPress site, check out the <a href="http://wordpress.org/plugins/wordpress-https/" target="_blank">WordPress HTTPS Plugin</a> that can help.',
                        abantecart()->lang); ?></p>
            </div>

            <aside class="padding-4">
                <?php include(abantecart()->dir.'/views/help-sidebar.php'); ?>
            </aside>
        </div>

        <?php include(abantecart()->dir.'/includes/version.php'); ?>
    </div>
</div>
