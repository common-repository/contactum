<?php
namespace Contactum;

use Contactum\Forms_List_Table;
use Contactum\Entries_List_Table;
use Contactum\Contactum_Settings_API;

class Admin {

    private $settings_api;

    public function __construct() {
        
        $this->settings_api = new Contactum_Settings_API();

        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_filter( 'parent_file', [ $this, 'fix_parent_menu' ] );
        add_action( 'admin_init', [ $this, 'admin_init' ] );
    }

    public function admin_init() {
        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    public function get_settings_sections() {

        $sections = array(
            array(
                'id'    => 'contactum_settings',
                'title' => '',
                'name' => __( 'General Settings', 'contactum' ),
                'icon'  => 'dashicons-admin-appearance'
            ),
            array(
                'id'    => 'contactum_reCaptcha',
                'title' => '',
                'name' => __( 'ReCaptcha', 'contactum' ),
                'icon'  => 'dashicons-admin-appearance'
            ),
        );

        return apply_filters( 'contactum_settings_sections', $sections );
    }

    public function get_settings_fields() {
        $settings_fields = array(
            'contactum_reCaptcha' => array(
                array(
                    'name'    => 'type',
                    'label'   => __( 'ReCaptcha Type', '' ),
                    'desc'    => __( '', '' ),
                    'type'    => 'checkbox',
                    'type' => 'radio',
                    'options' => array(
                        'v2' => 'V2',
                        'v3' => 'V3'
                    )
                ),
                array(
                    'name'    => 'key',
                    'label'   => __( 'Site Key', '' ),
                    'desc'    => __( '', '' ),
                    'type'    => 'text',
                    'default' => __( '', '' )
                ),
                array(
                    'name'    => 'secret',
                    'label'   => __( 'Site Secret', '' ),
                    'desc'    => __( '', '' ),
                    'type'    => 'text',
                    'default' => __( '', '' )
                ),
            ),
        );

        return apply_filters( 'contactum_settings_fields', $settings_fields );
    }

    public function fix_parent_menu( $parent_file ) {
        $current_screen = get_current_screen();
        $post_types     = [ 'contactum_forms' ];

        if ( in_array( $current_screen->post_type, $post_types ) ) {
            $parent_file = 'contactum';
        }

        return $parent_file;
    }

    /**
     * Register form post types
     *
     * @return void
     */
    public function register_post_type() {
        $capability = 'manage_options';

        register_post_type( 'contactum_forms', [
            'label'           => __( 'Forms', 'contactum' ),
            'public'          => false,
            'show_ui'         => false,
            'show_in_menu'    => false, //false,
            'capability_type' => 'post',
            'hierarchical'    => false,
            'query_var'       => false,
            'supports'        => ['title'],
            'capabilities'    => [
                'publish_posts'       => $capability,
                'edit_posts'          => $capability,
                'edit_others_posts'   => $capability,
                'delete_posts'        => $capability,
                'delete_others_posts' => $capability,
                'read_private_posts'  => $capability,
                'edit_post'           => $capability,
                'delete_post'         => $capability,
                'read_post'           => $capability,
            ],
            'labels' => [
                'name'               => __( 'Forms', 'contactum' ),
                'singular_name'      => __( 'Form', 'contactum' ),
                'menu_name'          => __( 'Forms', 'contactum' ),
                'add_new'            => __( 'Add Form', 'contactum' ),
                'add_new_item'       => __( 'Add New Form', 'contactum' ),
                'edit'               => __( 'Edit', 'contactum' ),
                'edit_item'          => __( 'Edit Form', 'contactum' ),
                'new_item'           => __( 'New Form', 'contactum' ),
                'view'               => __( 'View Form', 'contactum' ),
                'view_item'          => __( 'View Form', 'contactum' ),
                'search_items'       => __( 'Search Form', 'contactum' ),
                'not_found'          => __( 'No Form Found', 'contactum' ),
                'not_found_in_trash' => __( 'No Form Found in Trash', 'contactum' ),
                'parent'             => __( 'Parent Form', 'contactum' ),
            ],
        ] );

        register_post_type( 'contactum_input', [
            'public'          => false,
            'show_ui'         => false,
            'show_in_menu'    => false,
        ] );
    }

    public function admin_menu() {
        global $submenu;

        $capability = 'manage_options';
        $slug       = 'contactum';

        $hook = add_menu_page( __( 'Contactum', 'contactum' ), __( 'Contactum', 'contactum' ), $capability, $slug, [ $this, 'forms_page' ], 'dashicons-text' );
        add_submenu_page( $slug, __( 'Forms', 'contactum' ), __( 'Forms', 'contactum' ), $capability, 'contactum', [ $this, 'forms_page'] );
        $contactum_entries = add_submenu_page( $slug, __( 'Entries', 'contactum' ), __( 'Entries', 'contactum' ), $capability, 'contactum-entries', [ $this, 'entries_page'] );
        $tools = add_submenu_page( $slug, __( 'Tools', 'contactum' ), esc_html__( 'Tools', 'contactum' ),$capability, 'contactum-tools', [ $this, 'tools_page' ] );
        add_submenu_page( $slug, __( 'Settings', 'contactum' ), esc_html__( 'Settings', 'contactum' ),$capability, 'contactum-settings', [ $this, 'settings_page' ] );
        
        // $integration = add_submenu_page( $slug, __( 'Integrations', 'contactum' ), esc_html__( 'Integrations', 'contactum' ),$capability, 'contactum-integrations', [ $this, 'integration_page' ] ); 

        do_action( 'contactum_admin_menu', $slug );

        // add_action( 'load-' . $integration, array( $this, 'load_addon_scripts' ) );

        // add_action( 'load-' . $contactum_entries, array( $this, 'load_entries_scripts' ) );
        add_action( 'load-' . $tools, array( $this, 'load_tools_scripts' ) );
    }

    public function forms_page() {
        $action           = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : null;
        $add_new_page_url = admin_url( 'admin.php?page=contactum&action=add-new' );

        switch ( $action ) {
            case 'edit':
                require_once CONTACTUM_INCLUDES . '/html/form.php';
                break;
            case 'add-new':
                require_once CONTACTUM_INCLUDES . '/html/form.php';
                break;
            default:
                require_once CONTACTUM_INCLUDES . '/html/form-list-view.php';
                break;
        }
    }

    public function entries_page() {
        $action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : null;

        switch ( $action ) {
            case 'view':
                require_once CONTACTUM_INCLUDES . '/html/entry.php';
                break;
            default:
                require_once CONTACTUM_INCLUDES . '/html/entry-list-view.php';
                break;
        }
        ?>
        <div id="contactum-admin-entries"> </div>
        <?php
    }

    public function tools_page() {
        require_once CONTACTUM_INCLUDES . '/html/tools.php';
        ?>
        <!-- <div id="contactum-admin-tools"> </div> -->
        <?php
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e( 'Settings', 'contactum' ) ?></h1><br>
            <div class="contactum-settings-wrap">
                <?php
                    $this->settings_api->show_navigation();
                    $this->settings_api->show_forms();
                ?>
            </div>
        </div>
        <?php
    }
    
    public function integration_page() {
        ?>
        <div id="contactum-admin-modules"> </div>
        <?php
    }

    public function load_addon_scripts() {
        wp_register_script( 'contactum-addon', CONTACTUM_ASSETS . '/js/addon.js', ['jquery'], CONTACTUM_VERSION, true );
        wp_enqueue_script( 'contactum-addon' );
    }

    public function load_entries_scripts() {
        wp_register_script( 'contactum-entries', CONTACTUM_ASSETS . '/js/entries.js', ['jquery'], CONTACTUM_VERSION, true );
        wp_enqueue_script( 'contactum-entries' );

        wp_enqueue_style('contactum-admin');

        wp_localize_script( 'contactum-entries', 'contactum', [
            'forms'   => contactum()->forms->all(),
            'entries' => contactum_get_all_entries()
        ] );
    }

    public function load_tools_scripts() {
        wp_register_script( 'contactum-tools', CONTACTUM_ASSETS . '/js/atools.js', ['jquery'], CONTACTUM_VERSION, true );
        wp_enqueue_script( 'contactum-tools' );

        wp_enqueue_style('contactum-admin');

        wp_localize_script( 'contactum-tools', 'contactum', [
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'forms'   => contactum()->forms->all(),
            'entries' => contactum_get_all_entries(),
            'nonce'   => wp_create_nonce('contactum-export-forms')
        ] );
    }
}