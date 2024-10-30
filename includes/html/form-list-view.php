<?php
    $url   = esc_url( add_query_arg( [ 'action'   => 'create_template' ], admin_url( 'admin.php' ) ) );
?>
<div class="wrap">
    <h2>
        <?php esc_html_e( 'Forms', 'contactum' );  ?>
        <a href="<?php echo esc_url( $add_new_page_url ); ?>" id="new-contactum-form" class="page-title-action add-form"><?php esc_html_e( 'Add Form', 'contactum' ); ?></a>
    </h2>
    <?php
        $FormListTable = new \Contactum\Forms_List_Table();
    ?>
    <form method="get">
        <input type="hidden" name="page" value="contactum">
        <?php
            $FormListTable->prepare_items();
            $FormListTable->search_box( __( 'Search Forms', 'contactum' ), 'contactum-form-search' );
            $FormListTable->views();
            $FormListTable->display();
        ?>
    </form>
</div>