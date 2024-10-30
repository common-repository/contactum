<?php
namespace Contactum;

use Contactum\Form;
use WP_Query;

class FormManager {

    public function all() {
        return $this->getForms( [ 'posts_per_page' => -1 ] );
    }

    public function getForms( $args = [], $filters = false ) {
        $forms_array = [
            'forms' => [],
            'meta'  => [
                'total' => 0,
                'pages' => 0,
            ],
        ];
        $defaults  = [
            'post_type'   => 'contactum_forms',
            'post_status' => [ 'publish', 'draft', 'pending' ],
        ];

        $args  = wp_parse_args( $args, $defaults );

        $query = new WP_Query( $args );
        $forms = $query->get_posts();

        if ( $forms ) {
            foreach ( $forms as $form ) {
                $forms_array['forms'][$form->ID] = new Form( $form );
            }
        }

        $forms_array['meta']['total'] = (int) $query->found_posts;
        $forms_array['meta']['pages'] = (int) $query->max_num_pages;

        wp_reset_postdata();

        return $forms_array;
    }

    public function get( $form ) {
        return new Form( $form );
    }

    public function create( $form_name, $fields = [] ) {
        $author = get_current_user_id();

        $form_data = [
            'post_title'  => $form_name,
            'post_type'   => 'contactum_forms',
            'post_status' => 'publish',
            'post_author' => $author
        ];

        $form_id = wp_insert_post( $form_data );

        if( is_wp_error( $form_id ) ) {
            return $form_id;
        }

        if ( $fields ) {

            foreach ( $fields as $menu_order => $field ) {
                wp_insert_post( [
                    'post_type'    => 'contactum_input',
                    'post_status'  => 'publish',
                    'post_content' => maybe_serialize( $field ),
                    'post_parent'  => $form_id,
                    'menu_order'   => $menu_order,
                ] );
            }

        }

        return $form_id;
    }

    public function delete( $form_id, $force = true  ) {
        global $wpdb;
        wp_delete_post( $form_id, $force );

        $wpdb->delete( $wpdb->posts,
            [
                'post_parent' => $form_id,
                'post_type'   => 'contactum_input',
            ]
        );
    }


    public function save( $data ) {
        $saved_fields  = [];
        $new_fields = [];
        wp_update_post( [ 'ID' => $data['form_id'], 'post_status' => 'publish', 'post_title' => $data['post_title'] ] );

        $existing_fields = get_children( [
            'post_parent' => $data['form_id'],
            'post_status' => 'publish',
            'post_type'   => 'contactum_input',
            'numberposts' => '-1',
            'orderby'     => 'menu_order',
            'order'       => 'ASC',
            'fields'      => 'ids',
        ] );

        if ( !empty( $data['form_fields'] ) ) {
            foreach ( $data['form_fields'] as $order => $field ) {
                if ( !empty( $field['is_new'] ) ) {
                    unset( $field['is_new'] );
                    unset( $field['id'] );

                    $field_id = 0;
                } else {
                    $field_id = $field['id'];
                }

                $field_id = contactum_insert_form_field($data['form_id'], $field, $field_id ,$order );

                $new_fields[] = $field_id;

                $field['id'] = $field_id;

                $saved_fields[] = $field;
            }
        }

        $inputs_to_delete = array_diff( $existing_fields, $new_fields );


        if ( !empty( $inputs_to_delete ) ) {
            foreach ( $inputs_to_delete as $delete_id ) {
                wp_delete_post( $delete_id, true );
            }
        }

        update_post_meta( $data['form_id'], 'notifications', $data['notifications'] );
        update_post_meta( $data['form_id'], 'form_settings', $data['form_settings'] );
        update_post_meta( $data['form_id'], 'integrations', $data['integrations'] );
        update_post_meta( $data['form_id'], 'contactum_version', CONTACTUM_VERSION );

        return $saved_fields;
    }

    public function duplicate( $id ) {
        $form = $this->get( $id);

        if ( empty( $form ) ) {
            return;
        }

        $form_id = $this->create( $form->name, $form->getFields() );

        $data = [
            'form_id'       => absint( $form_id ),
            'post_title'    => sanitize_text_field( $form->name ) . ' (#' . $form_id . ')',
            'form_fields'   => $this->get( $form_id )->getFields(),
            'form_settings' => $form->getSettings(),
            'notifications' => $form->getNotifications(),
            'integrations'  => $form->get_integrations(),
        ];

        $form_fields = $this->save( $data );

        return $form_id;
    }
}
