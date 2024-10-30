<div class="wrap">
    <?php
        $EntriesListTable = new \Contactum\Entries_List_Table();
        $EntriesListTable->prepare_items();
        $entry_ids = contactum_count_all_form_entries( $EntriesListTable->form_id );
        //if (  $entry_ids <= 0  ) { ?>

        <?php // } else {
            $EntriesListTable->views();
        ?>
    <form method="post">
        <input type="hidden" name="page" value="contactum-entries" />
        <?php
            if ( ! empty( $_REQUEST['form_id'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification ?>
                <input type="hidden" name="form_id" value="<?php echo absint( $_REQUEST['form_id'] ); // phpcs:ignore WordPress.Security.NonceVerification ?>" />
        <?php
            endif;
            $EntriesListTable->display();
        ?>
    </form>
<?php // } ?>
</div>

<style>
    .contactum-form-blankstate {
        text-align: center;
        padding: 5em 0;
    }
</style>

<!--    
    <div class="contactum-form-blankstate">
        <h2> <?php // echo esc_html__('Whoops, it appears you do not have any form entries yet.', 'contactum' ); ?> </h2>
        <form method="get">
            <input type="hidden" name="page" value="contactum-entries" />
            <?php 
            // if ( !empty ( $EntriesListTable->forms ) ) {
                //  $EntriesListTable->forms_dropdown();
                //  submit_button( __( 'Filter', 'contactum' ), '', '', false, array( 'id' => 'post-query-submit' ) );
            // } 
            ?>
        </form>
    </div> 
-->