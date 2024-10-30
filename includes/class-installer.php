<?php
namespace Contactum;

class Installer {
    public function __construct() {
        $this->create_table();
        $this->set_default_settings();
        $this->create_default_form();

        $installed = get_option( 'contactum_installed' );

        if( !$installed ) {
            update_option( 'contactum_installed', time() );
        }

        update_option( 'contactum_version', CONTACTUM_VERSION );
    }

    public function create_table() {
        global $wpdb;

        $collate = '';

        if ( $wpdb->has_cap( 'collation' ) ) {
            if ( !empty( $wpdb->charset ) ) {
                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
            }

            if ( !empty( $wpdb->collate ) ) {
                $collate .= " COLLATE $wpdb->collate";
            }
        }

        $table_schema = [
            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}contactum_entries` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `form_id` bigint(20) unsigned DEFAULT NULL,
                `user_id` bigint(20) unsigned DEFAULT NULL,
                `user_ip` int(11) unsigned DEFAULT NULL,
                `user_device` varchar(50) DEFAULT NULL,
                `referer` varchar(255) DEFAULT NULL,
                `status` varchar(10) DEFAULT 'publish',
                `created_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `form_id` (`form_id`)
            ) $collate;",

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}contactum_entrymeta` (
                `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `contactum_entry_id` bigint(20) unsigned DEFAULT NULL,
                `meta_key` varchar(255) DEFAULT NULL,
                `meta_value` longtext,
                PRIMARY KEY (`meta_id`),
                KEY `meta_key` (`meta_key`),
                KEY `entry_id` (`contactum_entry_id`)
            ) $collate;",
        ];

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        foreach ( $table_schema as $table ) {
            dbDelta( $table );
        }
    }

    public function set_default_settings() {

    }

    public function create_default_form() {
        $version = get_option( 'contactum_version' );

        if( $version ) {
            return ;
        }

        contactum()->templates->create('contactum');
    }
}
