<?php
/**
 * Setting Class.
 *
 * @package cb-invoice
 */

if (!class_exists('CB_Setting')) {

    /**
     * Class CB_Setting.
     */
    class CB_Setting
    {

        /**
         * Register actions.
         */
        public function __construct()
        {
            add_action('admin_menu', array($this, 'register_cb_menu'));
            add_action('admin_notices', array($this, 'show_admin_notice'), 99);
        }

        /**
         * Register Admin Menu.
         */
        public function register_cb_menu()
        {
            add_menu_page('CB Settings', 'CB Settings', 'administrator', 'cb_invoice', array($this, 'configuration_page'), 'dashicons-admin-tools', 100);
        }

        /**
         * Show Admin notices.
         */
        public function show_admin_notice()
        {
            if (isset($_POST['cb_invoice_nonce'])) {
                $nonce = sanitize_text_field(wp_unslash($_POST['cb_invoice_nonce']));
                if (!wp_verify_nonce($nonce, 'cb_invoice_action')) {
                    return;
                }
            }

            $screen = get_current_screen();

            if ('toplevel_page_cb_invoice' !== $screen->id) {
                return;
            }

            if (isset($_POST['settings-updated'])) {
                if (1 == $_POST['settings-updated']):
                ?>
                    <div class="notice notice-success is-dismissible">
                        <p><?php esc_attr_e('Setting saved.', 'cb-invoice');?></p>
                    </div>
                <?php else: ?>
                    <div class="notice notice-warning is-dismissible">
                        <p><?php esc_attr_e('Sorry, I can not go through this.', 'cb-invoice');?></p>
                    </div>
                    <?php
endif;
            }
        }

        /**
         * Configuration page function.
         */
        public function configuration_page()
        {
            if (isset($_POST['cb_invoice_nonce'])) {

                $nonce = sanitize_text_field(wp_unslash($_POST['cb_invoice_nonce']));
                if (!wp_verify_nonce($nonce, 'cb_invoice_action')) {
                    return;
                }
            }

            if (isset($_POST['cb_invoice'])) {

                $config = $_POST['cb_invoice'];
                update_option('cb_invoice_config', $config);
            }

            $config = get_option(
                'cb_invoice_config',
                array(
                    'cb_api_key' => '',
                    'cb_site' => '',

                )
            );
            include CB_PLUGIN_DIR . '/templates/setting.php';
        }

    }

}

new CB_Setting();
