<?php
$form_id   = isset( $_GET['form_id'] ) ? absint( $_GET['form_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification
$entry_id  = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification
$form     = contactum()->forms->get( $form_id );
$entry    = $form->entries()->get( $entry_id );
$metadata = $entry->getMetadata();
$fields   = $entry->getFields();
?>
<div class="wrap contactum-forms">
    <h1 class="wp-heading-inline"> <?php esc_html_e( 'Entry Details', 'contactum' ); ?> </h1>

    <div class="contactum-forms-entry">
        <div id="poststuff">
            <!-- <div id="post-body" class="metabox-holder columns-2"> -->
            <div id="post-body" class="metabox-holder contactum_entry">
                <!-- Entry Fields metabox -->
                <div class="contactum_entry_fields" id="post-body-content  " style="position: relative;">
                    <!-- <div id="contactum-forms-entry-fields" class="postbox"> -->
                    <div id="contactum-forms-entry-fields">
                        <div class="inside">
                            <table class="wp-list-table widefat fixed posts contactum_card">
                                <tbody>
                                    <?php
                                        foreach ($fields as $field ) { ?>
                                            <tr class="contactum_table_row">
                                                <td> <?php esc_attr_e( $field['label'] ); ?> </td>
                                                <td> <?php
                                                    if( isset( $field['value'] ) ) {
                                                        echo is_array( $field['value'] ) ? implode(CONTACTUM_SEPARATOR, $field['value'] ) : $field['value']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                    } ?> </td>
                                            </tr>
                                        <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="postbox-container-1" class="postbox-container contactum_entry_details contactum_card">
                    <!-- <div id="contactum-forms-entry-details" class="postbox"> -->
                    <div id="contactum-forms-entry-details">
                        <h2 class="handle"> <span><?php esc_html_e( 'Submission Info', 'contactum' ); ?></span> </h2>
                        <div class="inside">
                            <ul class="contactum-forms-entry-details-meta">
                                <li class="contactum-forms-entry-id">
                                    <span class="dashicons dashicons-admin-network"></span>
                                    <?php esc_html_e( 'Entry ID:', 'contactum' ); ?> <strong> <?php esc_attr_e( $metadata['id'] ); ?> </strong>
                                </li>

                                <li class="contactum-forms-entry-date">
                                    <span class="dashicons dashicons-calendar"></span>
                                    <?php esc_html_e( 'Submitted:', 'contactum' ); ?> <strong> <?php esc_attr_e( $metadata['created'] ); ?> </strong>
                                </li>

                                <li class="contactum-forms-entry-user">
                                    <span class="dashicons dashicons-admin-users"></span>
                                    <?php esc_html_e( 'User:', 'contactum' ); ?> <strong> <a href=""> <?php esc_attr_e( $metadata['user'] ); ?> </a> </strong>
                                </li>

                                <li class="contactum-forms-entry-ip">
                                    <span class="dashicons dashicons-location"></span>
                                    <?php esc_html_e( 'User IP:', 'contactum' ); ?> <strong> <?php esc_attr_e( $metadata['ip_address'] ); ?> </strong>
                                </li>

                                <li class="contactum-forms-entry-referer">
                                    <span class="dashicons dashicons-admin-links"></span>
                                    <?php esc_html_e( 'Referer Link:', 'contactum' ); ?> <strong><a href="<?php  esc_attr_e( $metadata['referer'] ); ?>" target="_blank"> View</a> </strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>

table {
    border: none  !important;
}

.contactum_entry {
    display: flex;
    justify-content: space-between;
    gap: 30px;
}

.contactum_card {
    background-color: #fff;
    border: 1px solid transparent;
    border-radius: 8px;
    box-shadow: 0 2px 3px 0 hsla(0,0%,51%,.1);
    padding: 24px;
    box-sizing: border-box;
}

.contactum_entry_fields {
    flex: 2
}

.contactum_entry_details {
    flex: 1;
}

.contactum-forms-entry-details-meta li {
    border-bottom: 1px solid #ececec;
    margin-bottom: 12px;
    padding-bottom: 12px;
}

tr.contactum_table_row td {
  border-bottom: 1px solid #ececec;
  padding: 12px 0;

}
</style>