<?php
namespace Contactum;

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Entries_List_Table extends \WP_List_Table {

    public $form_id;
    public $form_data;
    public $forms;
    public $form;

    function __construct() {
        global $status, $page;
        
        $contact_forms = contactum()->forms->getForms( $filters = true );
        array_map(
            function ( $form ) {
                $form->entries  = $form->num_all_form_entries();
                // $form->entries  = $form->num_form_entries();
                $form->settings = $form->getSettings();
            }, $contact_forms['forms']
        );
        
        $contact_forms = $this->filter_contact_forms( $contact_forms );
        // echo "<pre>";
        // print_r($contact_forms);
        // $this->forms   = contactum_entries_forms();
        $this->forms   = $contact_forms['forms'];
        $this->form_id = ! empty( $_REQUEST['form_id'] ) ? absint( $_REQUEST['form_id'] ) : key( $this->forms );
        $this->form    = contactum()->forms->get( $this->form_id );

        parent::__construct(
            array(
                'singular'  => 'contactum-entry',
                'plural'    => 'contactum-entry',
                'ajax'      => true
            )
        );
    }

    /**
     * Filter
     *
     * @return void
     */
    public function filter_contact_forms( &$contact_forms ) {
        foreach ( $contact_forms['forms'] as $key => &$form ) {
            if ( isset( $form->entries ) && !$form->entries ) {
                unset( $contact_forms['forms'][ $key ] );
            }
        }

        $contact_forms['meta']['total'] = count( $contact_forms['forms'] );

        return $contact_forms;
    }

    protected function get_views() {
        $status_links  = array();
        $class         = 'current';
        $total_entries = contactum_count_form_entries( $this->form_id, 'publish');
        /* translators: %s: count */
        $status_links['all'] = "<a href='admin.php?page=contactum-entries&amp;form_id=$this->form_id'$class>" . sprintf( _nx( 'All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', $total_entries, 'entries', 'contactum' ), number_format_i18n( $total_entries ) ) . '</a>';

        $statuses  = [ 'trash'     => __( 'Trash', 'contactum' ), ];
        foreach ($statuses as $key => $value) {
            $total_entries = contactum_count_form_entries( $this->form_id, $key );
            $status_links[$key] = "<a href='admin.php?page=contactum-entries&amp;form_id=$this->form_id&amp;status=$key'$class>". sprintf( _nx( "{$value} <span class='count'>(%s)</span>", "{$value} <span class='count'>(%s)</span>", $total_entries, 'entries', 'contactum' ), number_format_i18n( $total_entries ) ) . '</a>';
        }

        return $status_links;
    }

    public function no_items() {
        esc_html_e('You do not have any form entries yet', 'contactum' );
    }

    public function get_columns() {
        $columns            = array( 'cb' => '<input type="checkbox" />' );
        $cols               = contactum_get_entry_columns( $this->form_id );
        $columns            = array_merge( $columns, $cols );
        $columns['created'] = __( "Date Created", 'contactum' );
        $columns['actions'] = __( "Action", 'contactum' );

        return $columns;
    }

    public function prepare_items() {
        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $per_page     = 10;
        $current_page = $this->get_pagenum();

        // Query args.
        $args = array(
            'status'  => 'publish',
            'limit'   => $per_page,
            'offset'  => $per_page * ( $current_page - 1 ),
        );

        if ( ! empty( $_REQUEST['status'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
            $args['status'] = sanitize_key( wp_unslash( $_REQUEST['status'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
        }

        if( !empty( $this->forms ) ){
            $entries = contactum_get_form_entries( $this->form_id, $args );
            $columns = contactum_get_entry_columns( $this->form_id );

            array_map(
                function ( $entry ) use ( $columns ) {
                    $entry_id = $entry->id;
                    $entry->fields = [];

                    foreach ( $columns as $meta_key => $label ) {
                        if ( empty( $meta_key ) ) {
                            continue;
                        }
                        $value                      = EntryMeta::get_entry_meta( $entry_id, $meta_key, true );
                        $entry->fields[ $meta_key ] = str_replace( ' | ', ' ', $value );
                        $entry->fields[ $meta_key ] = $value;
                    }
                }, $entries
            );

            $this->items = $entries;

            $this->set_pagination_args(
                array(
                    'total_items' => count( $entries ),
                    'per_page'    => $per_page,
                    'total_pages' => ceil( count( $entries ) / $per_page ),
                )
            );

        } else {
            $this->entries = [];
        }

        $this->process_bulk_action();
    }

    public function  column_default( $item, $column_name ) {
        switch ( $column_name) {
            case 'created':
                return $item->created_at;
            default:
                return $item->fields[$column_name];
                break;
        }
    }

    public function column_cb( $item ) {
        return sprintf( '<input type="checkbox" name="id[]" value="%d" />', $item->id );
    }

    public function column_actions( $item ) {
        if ( isset( $_GET['status'] ) && 'trash' === $_GET['status'] ) {
            $actions = array(
                    'restore' => '<a class="submitdelete" aria-label="' . esc_attr__( 'Restore form entry', 'contactum' ) . '" href="' . esc_url(
                        wp_nonce_url(
                            add_query_arg(
                                array(
                                    'action'  => 'restore',
                                    'id'      => $item->id,
                                    'form_id' => $this->form_id,
                                ),
                                admin_url( 'admin.php?page=contactum-entries' )
                            ),
                            'bulk-contactum-entry'
                        )
                    ) . '">' . esc_html__( 'Restore', 'contactum' ) . '</a>',
                    'delete' => '<a class="submitdelete" aria-label="' . esc_attr__( 'Delete form entry', 'contactum' ) . '" href="' . esc_url(
                        wp_nonce_url(
                            add_query_arg(
                                array(
                                    'action'  => 'delete',
                                    'id'      => $item->id,
                                    'form_id' => $this->form_id,
                                ),
                                admin_url( 'admin.php?page=contactum-entries' )
                            ),
                            'bulk-contactum-entry'
                        )
                    ) . '">' . esc_html__( 'Delete Permanently', 'contactum' ) . '</a>',
            );
        } else{
            $actions = array(
                'view'  => '<a href="' . esc_url(
                    admin_url( 'admin.php?page=contactum-entries&amp;form_id=' . $item->form_id .'&amp;action=view&amp;id=' . $item->id ) ) . '">' . esc_html__( 'View', 'contactum' ) . '</a>',
                    /* translators: %s: entry name */
                    'trash' => '<a class="submitdelete" aria-label="' . esc_attr__( 'Trash form entry', 'contactum' ) . '" href="' . esc_url(
                        wp_nonce_url(
                            add_query_arg(
                                array(
                                    'action'  => 'trash',
                                    'id'      => $item->id,
                                    'form_id' => $this->form_id,
                                ),
                                admin_url( 'admin.php?page=contactum-entries' )
                            ),
                            'bulk-contactum-entry'
                        )
                    ) . '">' . esc_html__( 'Trash', 'contactum' ) . '</a>',
            );
        }
        return implode( ' <span class="sep">|</span> ', $actions );
    }

    public function process_bulk_action() {
        $action            = $this->current_action();
        $entry_ids         = isset( $_REQUEST['id'] ) ? wp_parse_id_list( wp_unslash( $_REQUEST['id'] ) ) : array();
        $count             = 0;
        $remove_query_args = ['_wp_http_referer', '_wpnonce', 'action', 'id', 'post', 'action2','form_id' ];

        if ( $action ) {
            switch ( $action) {
                case 'restore':
                    foreach ( $entry_ids as $entry_id ) {
                        EntryManager::change_entry_status( $entry_id, 'publish' );
                        $count++;
                    }
                    break;
                case 'delete':
                    foreach ( $entry_ids as $entry_id ) {
                        EntryManager::delete_entry( $entry_id );
                        $count++;
                    }
                    break;
                case 'trash':
                    foreach ( $entry_ids as $entry_id ) {
                        EntryManager::change_entry_status($entry_id, 'trash');
                        $count++;
                    }
                    break;
            }

            $request_uri = isset( $_SERVER['REQUEST_URI'] )  ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
            $redirect    = remove_query_arg( $remove_query_args, $request_uri );

            // wp_redirect(esc_url( $redirect ) );
            // exit();
        }
    }

    public function get_sortable_columns() {
        $sortable_columns = array();

        return $sortable_columns;
    }

    public function get_bulk_actions() {

        if ( isset( $_GET['status'] ) && 'trash' === $_GET['status'] ) {
            $actions['restore'] = __( 'Restore', 'contactum' );
            $actions['delete']  = __( 'Delete Permanently', 'contactum' );
        } else {
            $actions['trash']   = __( 'Move to Trash', 'contactum' );
        }

        return $actions;
    }

    protected function extra_tablenav( $which ) {
        if( $which == 'top' ) {
        $this->forms_dropdown();
        submit_button( __( 'Filter', 'contactum' ), '', 'filter_action', false, array( 'id' => 'post-query-submit' ) );
      //  submit_button( __( 'Export CSV', 'contactum' ), '', 'export_action', false, array( 'id' => 'export-csv-submit' ) );
    ?>
        <div class="alignleft actions">
            <a href="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>?action=contactum_export_form_entries&amp;form_id=<?php echo esc_attr(
                $this->form_id ); ?>" class="button" style="margin-top: 0px;">
                <span class="dashicons dashicons-download" style="margin-top: 4px;"> </span> Export Entries
            </a>
        </div>
   <?php } }

    public function forms_dropdown() {
        $form_id = isset( $_REQUEST['form_id'] ) ? absint( $_REQUEST['form_id'] ) : $this->form_id;
    ?>
        <label for="filter-by-form" class="screen-reader-text"><?php esc_html_e( 'Filter by form', 'contactum' ); ?></label>
        <select name="form_id" id="filter-by-form">
            <?php foreach ( $this->forms as $id => $form ) : ?>
                <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $form_id, $id ); ?>> <?php echo esc_html( $form->name ); ?> </option>
            <?php endforeach; ?>
        </select>
    <?php }

 public function current_action() {

        if ( isset( $_GET['contactum_entries_search'] ) ) {
            return 'contactum_entries_search';
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
            <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"> <?php esc_html_e ( $text, 'contactum' ); ?>:</label>
            <input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
                <?php submit_button( $text, 'button', 'contactum_entries_search', false, array( 'id' => 'search-submit' ) ); ?>
        </p>
        <?php
    }
}
