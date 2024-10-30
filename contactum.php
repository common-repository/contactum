<?php
/*
Plugin Name: Contactum
Description: WordPress contact form plugin. Use Drag & Drop form builder to create your WordPress forms.
Version:     3.9.9
Author:      Md Kamrul islam
Author URI:  https://profiles.wordpress.org/rajib00002/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: contactum
Domain Path: languages
*/

if ( !defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/vendor/autoload.php';

final class Contactum {

    public $version    = '3.9.9';
    private $container = [];

    public function __construct() {
        $this->includes();
        $this->define_constants();
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

        add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
    }

    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new Self();
        }

        return $instance;
    }

    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    public function define_constants() {
        define( 'CONTACTUM_VERSION', $this->version );
        define( 'CONTACTUM_SEPARATOR', ' | ');
        define( 'CONTACTUM_FILE', __FILE__ );
        define( 'CONTACTUM_ROOT', __DIR__ );
        define( 'CONTACTUM_PATH', dirname( CONTACTUM_FILE ) );
        define( 'CONTACTUM_INCLUDES', CONTACTUM_PATH . '/includes' );
        define( 'CONTACTUM_URL', plugins_url( '', CONTACTUM_FILE ) );
        define( 'CONTACTUM_ASSETS', CONTACTUM_URL . '/assets' );
    }

    /**
     * Load the plugin after all plugis are loaded
     *
     * @return void
     */
    public function init_plugin() {
        $this->init_classes();
        $this->init_hooks();
        do_action( 'contactum_loaded' );
    }

    public function includes() {
        require_once CONTACTUM_INCLUDES . '/class-field-manager.php';
        require_once CONTACTUM_INCLUDES . '/fields/class-field-submit.php';
        require_once CONTACTUM_INCLUDES . '/fields/class-field-password.php';
        require_once CONTACTUM_INCLUDES . '/admin/class-pro-upgrades.php';

        require_once CONTACTUM_INCLUDES . '/class-importer-manager.php';

        require_once CONTACTUM_INCLUDES . '/class-integration-managers.php';
        require_once CONTACTUM_INCLUDES . '/integrations/class-abstract-integration.php';

        require_once CONTACTUM_INCLUDES . '/templates/class-template-support.php';
        require_once CONTACTUM_INCLUDES . '/templates/class-template-volunteer-application.php';
        require_once CONTACTUM_INCLUDES . '/templates/class-template-conference-proposal.php';
        require_once CONTACTUM_INCLUDES . '/templates/class-template-event-registration.php';
        require_once CONTACTUM_INCLUDES . '/templates/class-template-leave-request.php';
    }

    public function activate() {

        if ( !array_key_exists( 'fields', $this->container ) ) {
            $this->container['fields'] = new Contactum\FieldManager();
        }

        if ( !array_key_exists( 'forms', $this->container ) ) {
            $this->container['forms'] = new Contactum\FormManager();
        }

        if ( !array_key_exists( 'templates', $this->container ) ) {
            $this->container['templates'] = new Contactum\TemplateManager();
        }

        $installer = new Contactum\Installer();
    }

    public function deactivate() {

    }

    public function init_classes() {
        if ( is_admin() ) {
            $this->container['admin']              = new Contactum\Admin();
            $this->container['admin_template']     = new Contactum\Admin_Template();
            $this->container['admin_form_handler'] = new Contactum\Admin_Form_Handler();
            $this->container['importer']           = new Contactum\Importer_Manager();
            // $this->container['pro_upgrades']       = new Contactum\Contactum_Pro_Upgrades();
        }

        $this->container['assets']    = new Contactum\Assets();
        $this->container['ajax']      = new Contactum\Ajax();
        $this->container['fields']    = new Contactum\FieldManager();
        $this->container['templates'] = new Contactum\TemplateManager();
        $this->container['forms']     = new Contactum\FormManager();
        $this->container['preview']   = new Contactum\Form_Preview();
        $this->container['frontend']  = new Contactum\Frontend();
        $this->container['smarttags'] = new Contactum\SmartTags();
        $this->container['integrations'] = new Contactum\IntegrationManager();

    }

    public function init_hooks() {
        add_action( 'init', array( $this, 'localization_setup' ) );
        add_action( 'init', array( $this, 'table_shorthand' ) );
        add_action( 'widgets_init', [ $this, 'contat_register_widget' ] );
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
    }

    public function localization_setup() {
        load_plugin_textdomain( 'contactum', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    public function table_shorthand() {
        global $wpdb;
        $wpdb->contactum_entries   = $wpdb->prefix . 'contactum_entries';
        $wpdb->contactum_entrymeta = $wpdb->prefix . 'contactum_entrymeta';
    }

    public function contat_register_widget() {
        register_widget( 'Contactum\Widgets\Contactum_Widget' );
    }

    public function init_widgets() {
        $widgets_manager = Elementor\Plugin::instance()->widgets_manager;
        $widgets_manager->register_widget_type( new Contactum\Widgets\ContactumElementorFormWidget() );
    }
}

if( !function_exists('contactum') ) {
    function contactum() {
        return Contactum::init();
    }
}

contactum();