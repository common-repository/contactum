<?php
namespace Contactum;

class EntryMeta {

    public static function get_entry_custom( $entry_id = 0 ) {
        $entry_id = absint( $entry_id );
        return get_entry_meta( $entry_id );
    }

    public static function get_entry_meta( $entry_id, $key = '', $single = false ) {
        return get_metadata( 'contactum_entry', $entry_id, $key, $single );
    }

    public static function add_entry_meta( $entry_id, $meta_key, $meta_value, $unique = false ) {
        return add_metadata( 'contactum_entry', $entry_id, $meta_key, $meta_value, $unique );
    }

    public static function update_entry_meta( $entry_id, $meta_key, $meta_value, $prev_value = '' ) {
        return update_metadata( 'contactum_entry', $entry_id, $meta_key, $meta_value, $prev_value );
    }

    public static function delete_entry_meta_by_key( $meta_key ) {
        return delete_metadata( 'contactum_entry', null, $meta_key, '', true );
    }
}
