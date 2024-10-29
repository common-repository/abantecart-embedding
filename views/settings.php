<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
} ?>

<form action="options.php" method="post" id="settings" class="abc abc-settings">
    <?php
    $prefix = abantecart()->alias;
    $lang = abantecart()->lang;
    settings_fields($prefix.'_settings');
    $options = (array)get_option($prefix.'_settings');

    $options['abantecart_store_url'] =
        isset($options['abantecart_store_url'])
            ? (array)$options['abantecart_store_url']
            : array();
    foreach ($options['abantecart_store_url'] as $k => $store_url) {
        if (!$store_url) {
            unset($options['abantecart_store_url'][$k]);
        }
    }
    if (!$options['abantecart_store_url']) {
        $options['abantecart_store_url'][] = '';
    }
    settings_errors();
    function getTitle($url)
    {
        if(!($url ?? '') ){
            return '';
        }
        $page = file_get_contents($url);
        $title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $page, $match) ? $match[1] : null;
        return $title;
    }

    ?>

    <div class="container container-narrow">
        <div class="panel margin-top-4 margin-bottom-2">
            <div class="padding-3">
                <div class="text-center padding-4">
                    <img class="align-middle"
                         src="<?php echo plugins_url('../assets/img/abc_logo.png', __FILE__); ?>"
                         alt="Abantecart logo">
                </div>
                <label for="<?php echo $prefix."_admin_url" ?>" class="padding-top-4 ">
                    <?php _e('Your AbanteCart Store URL:', $lang); ?>
                    <small class="help-block"><?php _e('Wordpress will recognize this URL and replace with embed-code automatically',
                            $lang); ?></small>
                </label>
                <fieldset>
                    <?php
                    $exists = array();
                    foreach ($options['abantecart_store_url'] as $k => $store_url) {
                    //prevent duplicates
                    if (in_array($options['abantecart_store_url'][$k], $exists)) {
                        continue;
                    }
                    ?>
                    <div class="padding-2 margin-top-4 alignleft abc_url_div">
                        <p>
                            <?php echo getTitle($options['abantecart_store_url'][$k]); ?>
                        </p>
                        <input type="text"
                               id="<?php echo $prefix.'_admin_url'.$k; ?>"
                               name="<?php echo $prefix.'_settings[abantecart_store_url]['.$k.']'; ?>"
                               placeholder="https://{your-abantecart-store-url}/"
                               value="<?php echo $options['abantecart_store_url'][$k]; ?>"
                               class=""/>
                        <?php
                        if ($k != 0) {
                            echo '<a href="Javascript:void(0);" class="btn btn-block abc_remove_url">[-]</a>';
                        }
                        $exists[] = $options['abantecart_store_url'][$k];
                        echo '</div>';
                        } ?>
                </fieldset>
                <div class="abc_add_url_div alignright margin-top-4 margin-bottom-3">
                    <button id="abc_add_url" type="button"
                            class="components-button block-editor-inserter has-icon alignright"
                            title="Add another store url">
                        + Add URL
                    </button>
                </div>
                <div class=" margin-top-4 padding-top-3 padding-bottom-3 padding-left-4 padding-right-4 text-center">
                    <?php printf(
                        __('Read our %s on how to get the best out of your %s WordPress plugin.', $lang),
                        '<a href="'.admin_url().'admin.php?page='.$prefix.'_help">'.__('guide', $lang).'</a>',
                        abantecart()->name
                    ); ?>
                </div>
            </div>
        </div>
        <?php include( abantecart()->dir . '/includes/env.php' ); ?>
        <?php include( abantecart()->dir . '/includes/version.php' ); ?>
    </div>
</form>
