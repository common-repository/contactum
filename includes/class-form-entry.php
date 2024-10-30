<?php
namespace Contactum;

class Entry {

    private $id        = 0;
    public $form_id    = 0;
    public $user_id    = 0;
    public $ip_address = '127.0.0.1';
    public $device     = '';
    public $referer    = '';
    public $created    = '0000-00-00 00:00:00';

    private $form;
    public $fields     = [];
    public $raw_fields = [];

    public function __construct( $entry_id, $form ) {
        $this->id   = $entry_id;
        $this->form = $form;

        $this->populateEntryData();
    }

    public function populateEntryData() {
        global $wpdb;
        // return if we populated the already, ensures single db call
        if ( $this->form_id ) {
            return;
        }

        $grid_css_added = false;
        $grid_css       = '<style>.contactumTable {display: table; width: 100%; } .contactumTableRow {display: table-row; } .contactumTableRow:nth-child(even) {background-color: #f5f5f5; } .contactumTableHeading {background-color: #eee; display: table-header-group; font-weight: bold; } .contactumTableCell, .contactumTableHead {border: none; display: table-cell; padding: 3px 10px; } .contactumTableFoot {background-color: #eee; display: table-footer-group; font-weight: bold; } .contactumTableBody {display: table-row-group; }</style>';

        $values = [];

        $query = "SELECT * FROM {$wpdb->contactum_entries} as entry
                LEFT JOIN {$wpdb->contactum_entrymeta} AS meta ON entry.id = meta.contactum_entry_id
                WHERE entry.id = {$this->id}";

        $results = $wpdb->get_results( $query );

        if ( $results ) {
            $first_row = reset( $results );

            $this->form_id    = (int) $first_row->form_id;
            $this->user_id    = (int) $first_row->user_id;
            $this->ip_address = long2ip( $first_row->user_ip );
            $this->device     = $first_row->user_device;
            $this->referer    = $first_row->referer;
            $this->created    = $first_row->created_at;

            $this->fields     = $this->form->getFieldValues();
            $this->raw_fields = $this->fields;

            foreach ( $results as $result ) {
                if ( array_key_exists( $result->meta_key, $this->fields ) ) {
                    $field = $this->fields[ $result->meta_key ];
                    $value = $result->meta_value;
                    $this->raw_fields[ $result->meta_key ]['value'] = $value;

                    if ( $field['type'] == 'textarea_field' ) {
                        $value = contactum_format_text( $value );
                    } elseif ( $field['type'] == 'name_field' ) {
                        $value = implode( ' ', explode(CONTACTUM_SEPARATOR, $value ) );
                    } elseif ( in_array( $field['type'], [ 'dropdown_field', 'radio_field' ] ) ) {
                        if ( isset( $field['options'] ) && $field['options'] ) {
                            if ( isset( $field['options'][ $value ] ) ) {
                                $value = $field['options'][ $value ];
                            }
                        }
                    } elseif ( in_array( $field['type'], [ 'multiple_select', 'checkbox_field' ] ) ) {
                        $value      = explode( CONTACTUM_SEPARATOR, $value );
                        $temp_value = $value;

                        if ( is_array( $value ) && $value ) {
                            $new_array = [];

                            foreach ( $value as $option_key ) {
                                if ( is_array( $field['options'] ) && array_key_exists( $option_key, $field['options'] ) ) {
                                    $new_array[] = $field['options'][ $option_key ];
                                } else {
                                    $new_array[] = $option_key;
                                }
                            }

                            $value = $new_array;
                        }
                    }  elseif ( in_array( $field['type'], [ 'image_field', 'file_field' ] ) ) {
                        $file_field = '';
                        $value      = maybe_unserialize( $value );

                        if ( is_array( $value ) && $value ) {
                            foreach ( $value as $attachment_id ) {
                                if ( $field['type'] == 'image_field' ) {
                                    $thumb = wp_get_attachment_image( $attachment_id, 'thumbnail' );
                                } else {
                                    $thumb = get_post_field( 'post_title', $attachment_id );
                                }

                                $full_size = wp_get_attachment_url( $attachment_id );

                                $file_field .= sprintf( '<a href="%s" target="_blank">%s</a> ', $full_size, $thumb );
                            }
                        }

                        $value = $file_field;
                    }

                    $this->fields[ $result->meta_key ]['value'] = apply_filters( 'contactum_entry_meta_field', $value, $field );
                }
            }
        }
    }

    public function getFields() {
        return $this->fields;
    }

    public function getRawFields() {
        return $this->raw_fields;
    }

    public function getMetadata() {
        return [
            'id'         => $this->id,
            'form_id'    => $this->form_id,
            'form_title' => $this->form->getName(),
            'user'       => $this->user_id ? get_user_by( 'id', $this->user_id )->display_name : false,
            'ip_address' => $this->ip_address,
            'device'     => $this->device,
            'referer'    => $this->referer,
            'created'    => date_i18n( 'F j, Y g:i a', strtotime( $this->created ) ),
        ];
    }
}