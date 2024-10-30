<?php
namespace Contactum;

use Contactum\EntryManager;
use WP_Error;

class Form {

    public $id = 0;
    public $name;
    public $data;
    public $form_fields = [];

    public function __construct( $form = null ) {

        if ( is_numeric( $form ) ) {
            $the_post = get_post( $form );

            if ( $the_post ) {
                $this->id   = $the_post->ID;
                $this->name = $the_post->post_title;
                $this->data = $the_post;
            }
        } elseif ( is_a( $form, 'WP_Post' ) ) {
            $this->id   = $form->ID;
            $this->name = $form->post_title;
            $this->data = $form;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getFields() {

        $form_fields = [];

        $fields = get_children( [
            'post_parent' => $this->id,
            'post_status' => 'publish',
            'post_type'   => 'contactum_input',
            'numberposts' => '-1',
            'orderby'     => 'menu_order',
            'order'       => 'ASC',
        ] );

        foreach ( $fields as $key => $content ) {
            $field = maybe_unserialize( $content->post_content );

            if ( empty( $field['template']  ) ) {
                continue;
            }

            $field['id']   = $content->ID;
            $form_fields[] = $field;
        }

        return $form_fields;
    }

    public function hasField( $field_template ) {
        foreach ( $this->getFields() as $key => $field ) {
            if ( isset( $field['template'] ) && $field['template'] == $field_template ) {
                return true;
            }
        }
    }

    public function getFieldValues() {
        $values = [];
        $fields = $this->getFields();

        if ( !$fields ) {
            return $values;
        }

        foreach ( $fields as $field ) {
            if ( !isset( $field['name'] ) ) {
                continue;
            }

            $value = [
                'label' => isset( $field['label'] ) ? $field['label'] : '',
                'type'  => $field['template'],
            ];

            $values[ $field['name'] ] = array_merge( $field, $value );
        }

        return apply_filters( 'contactum_get_field_values', $values );
    }

    public function getSettings() {
        $settings = get_post_meta( $this->id, 'form_settings', true );
        $default  = contactum_get_default_form_settings();

        return array_merge( $default, $settings );
    }

    public function isSubmissionOpen() {
        $settings = $this->getSettings();

        $needs_login  = ( isset( $settings['require_login'] ) && $settings['require_login'] == 'true' ) ? true : false;
        $has_limit    = ( isset( $settings['limit_entries'] ) && $settings['limit_entries'] == 'true' ) ? true : false;
        $is_scheduled = ( isset( $settings['schedule_form'] ) && $settings['schedule_form'] == 'true' ) ? true : false;

        if ( $this->data->post_status != 'publish' ) {
            return new WP_Error( 'needs-publish', __( 'The form is not published yet.', 'contactum' ) );
        }

        if ( $needs_login && !is_user_logged_in() ) {
            return new WP_Error( 'needs-login', $settings['req_login_message'] );
        }

        if ( $has_limit ) {
            $limit        = (int) $settings['limit_number'];
            $form_entries = $this->num_form_entries();

            if ( $limit <= $form_entries ) {
                return new WP_Error( 'entry-limit', $settings['limit_message'] );
            }
        }

        if ( $is_scheduled ) {
            $start_time   = strtotime( $settings['schedule_start'] );
            $end_time     = strtotime( $settings['schedule_end'] );
            $current_time = current_time( 'timestamp' );

            // too early?
            if ( $current_time < $start_time ) {
                return new WP_Error( 'form-pending', $settings['sc_pending_message'] );
            } elseif ( $current_time > $end_time ) {
                return new WP_Error( 'form-expired', $settings['sc_expired_message'] );
            }
        }

        return apply_filters( 'contactum_is_submission_open', true, $settings, $this );
    }

    public function getNotifications() {
        $notifications = get_post_meta( $this->id, 'notifications', true );
        $defualt       = contactum_get_default_form_notification();

        if( empty( $notifications  ) ) {
            return [];
        }

        $notifications = array_map( function ( $notification ) use ( $defualt ) {
            if ( empty( $notification ) ) {
                $notification = [];
            }

            return array_merge( $defualt, $notification );
        }, $notifications );

        return $notifications;
    }

    /**
     * Get all the integrations
     *
     * @return array
     */
    public function get_integrations() {
        $integrations =  get_post_meta( $this->id, 'integrations', true );
        $default =  contactum()->integrations->get_integration_js_settings();
        $modify = array_merge( $default, $integrations);

        return $modify;
    }

    public function entries() {
        return new EntryManager( $this->id, $this );
    }

    public function num_form_entries() {
        return contactum_count_form_entries( $this->id );
    }

    public function num_all_form_entries() {
        return contactum_count_all_form_entries( $this->id );
    }

    public function prepare_entries( $post_data = [] ) {
        $fields      = contactum()->fields->getFields();
        $form_fields = $this->getFields();
        $entry_fields = [];

        foreach ( $form_fields as $field ) {
            if ( !array_key_exists( $field['template'], $fields ) ) {
                continue;
            }

            $field_class = $fields[ $field['template'] ];
            $entry_fields[ $field['name'] ] = $field_class->prepare_entry( $field, $post_data );
        }

        return $entry_fields;
    }

    public function isPendingForm( $scheduleStart ) {
        $currentTime = current_time( 'timestamp' );
        $startTime   = strtotime( $scheduleStart );

        if ( $currentTime < $startTime ) {
            return true;
        }

        return false;
    }

    public function isExpiredForm( $scheduleEnd ) {
        $currentTime = current_time( 'timestamp' );
        $endTime     = strtotime( $scheduleEnd );

        if ( $currentTime > $endTime ) {
            return true;
        }

        return false;
    }

    public function isOpenForm( $scheduleStart, $scheduleEnd ) {
        $currentTime = current_time( 'timestamp' );
        $startTime   = strtotime( $scheduleStart );
        $endTime     = strtotime( $scheduleEnd );

        if ( $currentTime > $startTime && $currentTime < $endTime ) {
            return true;
        }

        return false;
    }

    public function isFormStatusClosed( $formSettings, $entries ) {

        if ( $formSettings['schedule_form'] === 'true' && $this->isPendingForm( $formSettings['schedule_start'] ) ) {
            return true;
        }

        if ( $formSettings['schedule_form'] === 'true' && $this->isExpiredForm( $formSettings['schedule_end'] ) ) {
            return true;
        }

        if ( $formSettings['limit_entries'] === 'true' && $entries >= $formSettings['limit_number'] ) {
            return true;
        }

        return false;
    }
}
