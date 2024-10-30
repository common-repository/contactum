<?php
namespace Contactum;
use Contactum\Entry;

class EntryManager {

    private $id = 0;
    private $form;

    public function __construct( $form_id, $form ) {
        $this->id   = $form_id;
        $this->form = $form;
    }

    public function get( $entry_id ) {
        return new Entry( $entry_id, $this->form );
    }

    public static function create( $args, $fields = [] ) {
        global $wpdb;

        $defaults = [
            'form_id'     => 0,
            'user_id'     => get_current_user_id(),
            'user_ip'     => ip2long( contactum_get_client_ip() ),
            'created_at'  => current_time( 'mysql' )
        ];

        $args = wp_parse_args( $args, $defaults );

        if ( !$args['form_id'] ) {
            return new WP_Error( 'no-form-id', __( 'No form ID was found.', 'contactum' ) );
        }

        if ( !$fields ) {
            return new WP_Error( 'no-fields', __( 'No form fields were found.', 'contactum' ) );
        }

        $success = $wpdb->insert( "{$wpdb->prefix}contactum_entries", $args );

        if ( is_wp_error( $success ) || !$success ) {
            return new WP_Error( 'could-not-create', __( 'Could not create an entry', 'contactum' ) );
        }

        $entry_id = $wpdb->insert_id;

        foreach ( $fields as $key => $value ) {
             add_metadata( 'contactum_entry', $entry_id, $key, $value, false );
        }

        return $entry_id;
    }

    public static function delete_entry( $entry_id ) {
        global $wpdb;

        $deleted = $wpdb->delete(
            $wpdb->contactum_entries, [
                'id' => $entry_id,
            ], [ '%d' ]
          );

        if ( $deleted ) {
            $wpdb->delete(
                $wpdb->contactum_entrymeta, [
                    'contactum_entry_id' => $entry_id,
                ], [ '%d' ]
              );
        }

        return $deleted;
    }


    public static function change_entry_status( $entry_id, $status ) {
        global $wpdb;

        return $wpdb->update(
            $wpdb->contactum_entries,
            [
                'status' => $status,
            ],
            [
                'id' => $entry_id,
            ],
            [ '%s' ],
            [ '%d' ]
        );
    }
}
