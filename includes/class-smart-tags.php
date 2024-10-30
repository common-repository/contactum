<?php
namespace Contactum;

class SmartTags {

    public function __construct() {

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
