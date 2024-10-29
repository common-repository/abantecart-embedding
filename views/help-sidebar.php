<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="selz-panel">
    <p><?php _e('A short review of our plugin would be awesome. If only a few words.', abantecart()->lang); ?></p>
    <a href="https://wordpress.org/support/plugin/abantecart-embedding/reviews/" class="btn btn-primary"
       target="_blank">
        <?php _e('Write a review', abantecart()->lang); ?>
    </a>
</div>

<div class="selz-panel">
    <p><?php printf(__('Read our guide on how to get the best out of your %s ecommerce WordPress plugin.',
            abantecart()->lang), abantecart()->name); ?></p>
    <a href="https://abantecart.atlassian.net/wiki/spaces/AD/pages/2375221441/WordPress+Embed+Plugin" class="btn btn-secondary" target="_blank">
        <?php _e('See guide', abantecart()->lang); ?>
    </a>
</div>

<div class="selz-panel">
    <p><?php _e('Need some help? Have a feature request?', abantecart()->lang); ?></p>
    <a href="https://forum.abantecart.com/index.php/board,42.0.html" class="btn" target="_blank">
        <?php _e('Visit our Community Forum', abantecart()->lang); ?>
    </a>
</div>
