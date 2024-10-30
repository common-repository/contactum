<?php
namespace Contactum;

use Contactum\SmartTags;
use Contactum\EntryMeta;
use Contactum\Lib\Emogrifier;

class Notification {

    private $merge_tags = [];
    private $args       = [];

    public function __construct( $args = [] ) {
        $defaults = [
            'form_id'  => 0,
            'entry_id' => 0,
            'page_id'  => 0,
        ];

        $this->args = wp_parse_args( $args, $defaults );
    }

    public function sendNotifications() {
        error_log('notifications');
       $notifications = $this->getActiveNotifications();

        if ( !$notifications ) {
            return;
        }

        $this->setMergeTags();

        foreach ($notifications as $notification ) {
            if( $notification['type'] == 'email' ) {
                $this->sendNotification($notification);
            }
        }
    }

    public function sendNotification( $notification ) {
        // $this->setMergeTags();
        // $notification = contactum_get_default_form_notification();
        $headers     = [];

        $to          = $this->replaceTags( $notification['to'] );

        $subject     = $this->replaceTags( $notification['subject'] );
        $subject     = static::replaceNameTag( $subject, $this->args['entry_id'] );

        $message     = $this->replaceTags( $notification['message'] );
        $message     = static::replaceNameTag( $message, $this->args['entry_id'] );
        $message     = $this->replaceAllFields( $message );
        $message     = wpautop( $message );

        $fromName    = $this->replaceTags( $notification['fromName'] );
        $fromName    = static::replaceNameTag( $fromName, $this->args['entry_id'] );
        $fromAddress = $this->replaceTags( $notification['fromAddress'] );
        $replyTo     = $this->replaceTags( $notification['replyTo'] );
        $cc          = $this->replaceTags( $notification['cc'] );
        $bcc         = $this->replaceTags( $notification['bcc'] );


        if ( $fromName && $fromAddress ) {
            
            $headers[] = sprintf( 'From: %s <%s>', $fromName, $fromAddress );

            // $headers['from'] = [
            //     'email' => $fromAddress,
            //     'name'  => $fromName,
            // ];
        }

        if ( $cc ) {
            $headers[] = sprintf( 'CC: %s', $headers['cc'] );
            // $headers['cc'] = $cc;
        }

        if ( $bcc ) {
            $headers[] = sprintf( 'BCC: %s', $bcc );
            // $headers['bcc'] = $bcc;
        }

        if ( $replyTo ) {
            $headers[] = sprintf( 'Reply-To: %s', $replyTo );
            // $headers['replyto'] = $replyTo;
        }

        $headers[] = 'Content-Type: text/html; charset=UTF-8';

        $email_body = $this->getFormatBody( $message );

        wp_mail( $to, $subject, htmlspecialchars_decode( $email_body ), $headers );
    }


    public function getActiveNotifications() {
        $notifications = contactum()->forms->get( $this->args['form_id'] )->getNotifications();

        if ( $notifications ) {
            $notifications = array_filter( $notifications, function ( $notification ) {
                return $notification['active'] == true;
            } );

            return $notifications;
        }

        return false;
    }

    public function setMergeTags() {
        if ( $this->merge_tags ) {
            return $this->merge_tags;
        }

        $tags = $this->getMergeTags();
        $replace_array = [];

        foreach ( $tags as $section => $child ) {
            if ( !$child['tags'] ) {
                continue;
            }

            foreach ( $child['tags'] as $search_key => $label ) {
                $replace_array[ '{' . $search_key . '}' ] = $this->getMergeValue( $search_key );
            }
        }

        $this->merge_tags = $replace_array;
    }



    public function getMergeValue( $tag ) {
        switch ( $tag ) {
            case 'entry_id':
                return $this->args['entry_id'];
                break;

            case 'form_id':
                return $this->args['form_id'];
                break;

            case 'form_name':
                return get_post_field( 'post_title', $this->args['form_id'] );
                break;

            case 'admin_email':
                return get_option( 'admin_email' );
                break;

            case 'date':
                return date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
                break;

            case 'site_name':
                return get_bloginfo( 'name' );
                break;

            case 'site_url':
                return site_url( '/' );
                break;

            case 'page_title':
                return get_post_field( 'post_title', $this->args['page_id'] );
                break;

            case 'ip_address':
                return contactum_get_client_ip();
                break;

            case 'user_id':
                return get_current_user_id();
                break;

            case 'first_name':
                return $this->get_user_prop( 'first_name' );
                break;

            case 'last_name':
                return $this->get_user_prop( 'last_name' );
                break;

            case 'display_name':
                return $this->get_user_prop( 'display_name' );
                break;

            case 'user_email':
                return $this->get_user_prop( 'user_email' );
                break;

            case 'url_page':
                return get_permalink( $this->args['page_id'] );
                break;

            case 'url_referer':
                return isset( $_SERVER['HTTP_REFERER'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '';
                break;

            case 'url_login':
                return wp_login_url();
                break;

            case 'url_logout':
                return wp_logout_url();
                break;

            case 'url_register':
                return wp_registration_url();
                break;

            case 'url_lost_password':
                return wp_lostpassword_url();
                break;
            default:
                return apply_filters( 'contactum_merge_tag_value', '', $tag, $this->args );
                break;
        }
    }


    public function get_user_prop( $property ) {
        $user = wp_get_current_user();

        if ( $user->ID != 0 ) {
            return $user->{$property};
        }

        return '';
    }

    public function getFormatBody( $message ) {
        ob_start();
        include CONTACTUM_INCLUDES . '/emailers/template/header.php';
        $header = ob_get_clean();

        ob_start();
        include CONTACTUM_INCLUDES . '/emailers/template/footer.php';
        $footer = ob_get_clean();

        ob_start();
        include CONTACTUM_INCLUDES . '/emailers/template/styles.php';
        $css = apply_filters( 'contactum_email_styles', ob_get_clean() );

        $content = $header . $message . $footer;

        return $message;
    }

    public function replaceTags( $text = '' ) {
        $merge_keys   = array_keys( $this->merge_tags );
        $merge_values = array_values( $this->merge_tags );
        $text         = str_replace( $merge_keys, $merge_values, $text );
        $text         = static::replaceFieldTags( $text, $this->args['entry_id'] );

        return $text;
    }


    public static function replaceFieldTags( $text, $entry_id ) {
        $pattern = '/{field:(\w*)}/';
        preg_match_all( $pattern, $text, $matches );

        if ( !$matches ) {
            return $text;
        }

        foreach ( $matches[1] as $index => $meta_key ) {
            $meta_value = EntryMeta::get_entry_meta( $entry_id, $meta_key, true );

            if ( is_array( $meta_value ) ) {
                $meta_value = implode(CONTACTUM_SEPARATOR, $meta_value );
            }

            $text = str_replace( $matches[0][$index], $meta_value, $text );
        }

        return $text;
    }


    public static function replaceNameTag( $text, $entry_id ) {
        $pattern = '/{name-(full|first|middle|last):(\w*)}/';

        preg_match_all( $pattern, $text, $matches );

        if ( !$matches[0] ) {
            return $text;
        }

        list( $search, $fields, $meta_key ) = $matches;

        $meta_value = EntryMeta::get_entry_meta( $entry_id, $meta_key[0], true );
        $replace    = explode( ' | ', $meta_value );

        foreach ( $search as $index => $search_key ) {
            if ( 'first' == $fields[ $index ] ) {
                $text = str_replace( $search_key, $replace[0], $text );
            } elseif ( 'middle' == $fields[ $index ] ) {
                $text = str_replace( $search_key, $replace[1], $text );
            } elseif ( 'last' == $fields[ $index ] ) {
                $text = str_replace( $search_key, $replace[2], $text );
            } else {
                $text = str_replace( $search_key, implode( ' ', $replace ), $text );
            }
        }

        return $text;
    }

    public static function replaceFileTags( $text, $entry_id ) {
        $pattern = '/{(?:image|file):(\w*)}/';
        preg_match_all( $pattern, $text, $matches );

        // bail out if nothing found to be replaced
        if ( !$matches ) {
            return $text;
        }

        foreach ( $matches[1] as $index => $meta_key ) {
            $meta_value = EntryMeta::get_entry_meta( $entry_id, $meta_key, true );

            $files = [];

            if ( is_array( $meta_value ) ) {
                foreach ( $meta_value as $key => $attachment_id ) {
                    $file_url = wp_get_attachment_url( $attachment_id );

                    if ( $file_url ) {
                        $files[] = $file_url;
                    }
                }
            } else {
                $file_url = wp_get_attachment_url( $attachment_id );

                if ( $file_url ) {
                    $files[] = $file_url;
                }
            }

            $files = implode( ' ', $files );
            $text  = str_replace( $matches[0][$index], $files, $text );
        }

        return $text;
    }

    public function replaceAllFields( $text = '' ) {
        // check if {all_fields} exists
        if ( false === strpos( $text, '{all_fields}' ) ) {
            return $text;
        }
        $form     = contactum()->forms->get( $this->args['form_id'] );
        $entry    = $form->entries()->get( $this->args['entry_id'] );
        $fields   = $entry->getFields();

        if ( !$fields ) {
            return $text;
        }

        $table = '<table width="600" cellpadding="0" cellspacing="0">';
        $table .= '<tbody>';

        foreach ( $fields as $key => $value ) {
            $field_value = isset( $value[ 'value' ] ) ? $value[ 'value' ] : '';

            if ( !$field_value ) {
                continue; // let's skip empty fields
            }

            $table .= '<tr class="field-label">';
            $table .= '<th><strong>' . $value['label'] . '</strong></th>';
            $table .= '</tr>';
            $table .= '<tr class="field-value">';
            $table .= '<td>';

            if ( in_array( $value['type'], [ 'multiple_select', 'checkbox_field' ] ) ) {
                $field_value = is_array( $field_value ) ? $field_value : [];

                if ( $field_value ) {
                    $table .= '<ul>';

                    foreach ( $field_value as $value_key ) {
                        $table .= '<li>' . $value_key . '</li>';
                    }
                    $table .= '</ul>';
                } else {
                    $table .= '&mdash;';
                }
            } else {
                $table .= $field_value;
            }

            $table .= '</td>';
            $table .= '</tr>';
        }

        $table .= '</tbody>';
        $table .= '</table>';

        $text = str_replace( '{all_fields}', $table, $text );

        return $text;
    }

    public function getMergeTags() {
        $tags = [
            'form' => [
                'title' => __( 'Form', 'contactum' ),
                'tags'  => [
                    'entry_id'  => __( 'Entry ID', 'contactum' ),
                    'form_id'   => __( 'Form ID', 'contactum' ),
                    'form_name' => __( 'Form Name', 'contactum' ),
                ],
            ],
            'system' => [
                'title' => __( 'System', 'contactum' ),
                'tags'  => [
                    'admin_email' => __( 'Site Administrator Email', 'contactum' ),
                    'date'        => __( 'Date', 'contactum' ),
                    'site_name'   => __( 'Site Title', 'contactum' ),
                    'site_url'    => __( 'Site URL', 'contactum' ),
                    'page_title'  => __( 'Embedded Page Title', 'contactum' ),
                ],
            ],
            'user' => [
                'title' => __( 'User', 'contactum' ),
                'tags'  => [
                    'ip_address'   => __( 'IP Address', 'contactum' ),
                    'user_id'      => __( 'User ID', 'contactum' ),
                    'first_name'   => __( 'First Name', 'contactum' ),
                    'last_name'    => __( 'Last Name', 'contactum' ),
                    'display_name' => __( 'Display Name', 'contactum' ),
                    'user_email'   => __( 'Email', 'contactum' ),
                ],
            ],
            'urls' => [
                'title' => __( 'URL\'s', 'contactum' ),
                'tags'  => [
                    'url_page'                         => __( 'Embeded Page URL', 'contactum' ),
                    'url_referer'                      => __( 'Referer URL', 'contactum' ),
                    'url_login'                        => __( 'Login URL', 'contactum' ),
                    'url_logout'                       => __( 'Logout URL', 'contactum' ),
                    'url_register'                     => __( 'Register URL', 'contactum' ),
                    'url_lost_password'                => __( 'Lost Password URL', 'contactum' )
                ],
            ],
        ];

        return  $tags;
    }
}