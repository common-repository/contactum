<?php
namespace Contactum;
use WP_Query;

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Forms_List_Table extends \WP_List_Table {

    public function __construct() {

        global $status, $page, $page_status;

        parent::__construct( [
            'singular' => 'contactum-form',
            'plural'   => 'contactum-forms',
            'ajax'     => false,
        ] );
    }


	public function get_columns() {
        $columns = [
            'cb'        => '<input type="checkbox" />',
            'name'      => __( 'Form Name', 'contactum' ),
            'shortcode' => __( 'Shortcode', 'contactum' ),
            'author'    => __( 'Author', 'contactum' ),
            'date'      => __( 'Date', 'contactum' ),
            'entries'   => __( 'Entries', 'contactum' ),
        ];

		return $columns;
	}

	public function prepare_items() {
        $columns               = $this->get_columns();
        $hidden                = [];
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = [ $columns, $hidden, $sortable ];
        // $this->process_bulk_action();
        // $per_page     = get_option( 'posts_per_page', 20 );
        $per_page     = $this->get_items_per_page( 'contactum_forms_per_page' );
        $current_page = $this->get_pagenum();
        $offset       = ( $current_page - 1 ) * $per_page;

        $args = [
            'offset'         => $offset,
            'posts_per_page' => $per_page,
        ];

        if ( isset( $_REQUEST['s'] ) && !empty( $_REQUEST['s'] ) ) {
            $args['s'] = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
        }

        if ( isset( $_GET['post_status'] ) && !empty( $_GET['post_status'] ) ) {
            $args['post_status'] = sanitize_text_field( wp_unslash( $_GET['post_status'] ) );
        }

        if ( isset( $_GET['orderby'] ) && !empty( $_GET['orderby'] ) ) {
            $args['orderby'] = sanitize_text_field( wp_unslash( $_GET['orderby'] ) );
        }

        if ( isset( $_GET['order'] ) && !empty( $_GET['order'] ) ) {
            $args['order'] = sanitize_text_field( wp_unslash( $_GET['order'] ) );
        }

        $items  = $this->item_query( $args );

        $this->counts = $items['count'];
        $this->items  = $items['items'];

        $this->set_pagination_args( [
            'total_items' => $items['count'],
            'per_page'    => $per_page,
            'total_pages' => ceil( $this->count / $per_page )
        ] );

	}

    public function item_query( $args ) {
        $defauls = [
            'post_status' => 'any',
            'orderby'     => 'DESC',
            'order'       => 'ID'
        ];

        $args = wp_parse_args( $args, $defauls );

        $args['post_type'] = 'contactum_forms';

        $query = new WP_Query( $args );

        $items = [];

        if ( $query->have_posts() ) {
            $i = 0;

            while ( $query->have_posts() ) {
                $query->the_post();

                $item = $query->posts[ $i ];

                $items[ $i ] = [
                    'ID'          => $item->ID,
                    'name'        => $item->post_title,
                    'post_status' => $item->post_status,
                    'author'      => $item->post_author,
                    'date'        => $item->post_date
                ];

                $i++;
            }
        }

        $count = $query->found_posts;

        wp_reset_postdata();

        return [
            'items' => $items,
            'count' => $count,
        ];
    }

    public function column_cb( $item ) {
        return sprintf( '<input type="checkbox" name="id[]" value="%1$s" />', esc_attr( $item['ID'] ) );
    }

    public function column_name( $item ) {
        $request_data = wp_unslash( $_REQUEST );
        $title = '<strong>' . $item['name'] . '</strong>';
        
        $actions['edit']        = sprintf( '<a href="?page=%s&action=%s&id=%s">Edit</a>',
            esc_attr( $request_data['page'] ),
            'edit', absint( $item['ID'] ));
        
        $actions['export_entries'] = sprintf(
            '<a href="%s" title="%s" target="_blank" rel="noopener noreferrer">%s</a>',
            esc_url( contactum_get_form_export_url( $item['ID'] ) ),
            esc_attr__( 'Export Entries', 'contactum' ),
            esc_html__( 'Export Entries', 'contactum' )
        );
        
        $actions['entries'] = sprintf(
            '<a href="%s" title="%s" rel="noopener noreferrer">%s</a>',
            esc_url( contactum_get_form_entries_url( $item['ID'] ) ),
            esc_attr__( 'Entries', 'contactum' ),
            esc_html__( 'Entries', 'contactum' )
        );
        
        $actions['preview'] = sprintf(
            '<a href="%s" title="%s" target="_blank" rel="noopener noreferrer">%s</a>',
            esc_url( contactum_get_form_preview_url( $item['ID'] ) ),
            esc_attr__( 'View preview', 'contactum' ),
            esc_html__( 'Preview', 'contactum' )
        );

        $actions['duplicate'] = sprintf(
            '<a href="%s" title="%s">%s</a>',
            esc_url(
                wp_nonce_url(
                    add_query_arg(
                        array(
                            'action'  => 'duplicate',
                            'id' => $item['ID'],
                        ),
                        admin_url( 'admin.php?page=contactum' )
                    ),
                    'bulk-contactum-forms'
                )
            ),
            esc_attr__( 'Duplicate this form', 'contactum' ),
            esc_html__( 'Duplicate', 'contactum' )
        );

        $actions['delete'] = sprintf(
            '<a href="%s" title="%s">%s</a>',
            esc_url(
                wp_nonce_url(
                    add_query_arg(
                        array(
                            'action'  => 'delete',
                            'id' => $item['ID'],
                        ),
                        admin_url( 'admin.php?page=contactum' )
                    ),
                    'bulk-contactum-forms'
                )
            ),
            esc_attr__( 'Delete this form', 'contactum' ),
            esc_html__( 'Delete', 'contactum' )
        );

        return $title . $this->row_actions( $actions );
    }

    public function column_author( $item ) {
        $user = get_user_by( 'id', $item['author'] );

        if ( ! $user ) {
            return '<span class="na">&ndash;</span>';
        }

        $user_name = ! empty( $user->data->display_name ) ? $user->data->display_name : $user->data->user_login;

        if ( current_user_can( 'edit_user' ) ) {
            return '<a href="' . esc_url(
                add_query_arg(
                    array(
                        'user_id' => $user->ID,
                    ),
                    admin_url( 'user-edit.php' )
                )
            ) . '">' . esc_html( $user_name ) . '</a>';
        }

        return esc_html( $user_name );
    }

    public function column_date( $item ) {
        $t_time = mysql2date(
            __( 'Y/m/d g:i:s A', 'contactum' ),
            $item['date'],
            true
        );
        $m_time = $item['date'];
        $time   = mysql2date( 'G', $item['date']) - get_option( 'gmt_offset' ) * 3600;

        $time_diff = time() - $time;

        if ( $time_diff > 0 && $time_diff < 24 * 60 * 60 ) {
            $h_time = sprintf(
                /* translators: %s: Time */
                __( '%s ago', '' ),
                human_time_diff( $time )
            );
        } else {
            $h_time = mysql2date( __( 'Y/m/d', '' ), $m_time );
        }

        return '<abbr title="' . $t_time . '">' . $h_time . '</abbr>';
    }

    public function column_entries( $item ) {
        // return '<a href="' . esc_url(
        //     add_query_arg(
        //         array(
        //             'form_id' => $item['ID'],
        //         ),
        //         admin_url( 'admin.php?page=contactum-entries' )
        //     )
        // ) . '">' . contactum_count_form_entries( $item['ID'] ) . '</a>';

        return '<a href="' . esc_url(
            add_query_arg(
                array(
                    'form_id' => $item['ID'],
                ),
                admin_url( 'admin.php?page=contactum-entries' )
            )
        ) . '">' . contactum_count_all_form_entries( $item['ID'] ) . '</a>';  
    }

	public function  column_default( $item, $column_name ) {
		switch ( $column_name) {
			case 'ID':
            case 'name':
            case 'author':
			   return $item[$column_name];
            case 'shortcode':
               return '<code>[contactum id="' . $item['ID'] . '"]</code>';
			default:
				return $item;
		}
	}

    public function process_bulk_action() {
        $request_data =  wp_unslash( $_REQUEST );
        $action = $this->current_action();
        $ids = isset( $_REQUEST['id'] ) ? wp_parse_id_list( wp_unslash( $_REQUEST['id'] ) ) : array();
        echo $action;
        exit;
        if ( $action ) {
            $remove_query_args = ['_wp_http_referer', '_wpnonce', 'action', 'id', 'post', 'action2' ];
            $add_query_args    = [];
            switch ( $action ) {
                case 'delete':
                    foreach ( $ids as $id ) {
                        wp_delete_post( $id  );
                    }
                    $add_query_args['deleted'] = count( $ids );
                    break;
                case 'duplicate':
                    if ( !empty( $_GET['id'] ) ) {
                        $id = intval( $_GET['id'] );
                        $add_query_args['duplicated'] = contactum()->forms->duplicate( $id );
                    }
                    break;
            }

            if ( ( isset( $request_data['action'] ) && $request_data['action'] == 'bulk-delete' ) || ( isset( $request_data['action2'] ) && $request_data['action2'] == 'bulk-delete' ) ) {

                $ds = esc_sql( $request_data['id'] );

                foreach ( $ids as $id ) {
                        wp_delete_post( $id  );
                }
            }

            $request_uri = isset( $_SERVER['REQUEST_URI'] )  ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
            $redirect    = remove_query_arg( $remove_query_args, $request_uri );
            $redirect    = add_query_arg( $add_query_args, $redirect );
        }
    }

    public function get_sortable_columns() {
        $sortable_columns = array(
            'name'   => array( 'name', true ),
            'author' => array( 'author', false ),
            'date'   => array( 'date', false ),
        );

        return $sortable_columns;
    }

    public function get_bulk_actions() {
        $actions['bulk-delete']      = __( 'Delete Permanently', 'contactum' );

        return $actions;
    }

    public function current_action() {

        if ( isset( $_GET['contactum_form_search'] ) ) {
            return 'contactum_form_search';
        }

        return parent::current_action();
    }

    public function search_box( $text, $input_id ) {
        $requestdata = wp_unslash( $_REQUEST );

        if ( empty( $_REQUEST['s'] ) && ! $this->has_items() ) {
            return;
        }

        $input_id = $input_id . '-search-input';

        if ( ! empty( $requestdata['orderby'] ) ) {
            echo '<input type="hidden" name="orderby" value="' . esc_attr( $requestdata['orderby'] ) . '" />';
        }
        if ( ! empty( $requestdata['order'] ) ) {
            echo '<input type="hidden" name="order" value="' . esc_attr( $requestdata['order'] ) . '" />';
        }
        if ( ! empty( $requestdata['post_mime_type'] ) ) {
            echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $requestdata['post_mime_type'] ) . '" />';
        }
        if ( ! empty( $requestdata['detached'] ) ) {
            echo '<input type="hidden" name="detached" value="' . esc_attr( $requestdata['detached'] ) . '" />';
        }
        ?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html ( $text ); ?>:</label>
            <input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
                <?php submit_button( $text, 'button', 'contactum_form_search', false, array( 'id' => 'search-submit' ) ); ?>
        </p>
        <?php
    }
}