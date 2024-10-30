<?php
    defined( 'ABSPATH' ) || exit;
?>
<div class="contactum-forms-import-form">
    <div class="postbox">
        <h3><?php esc_html_e( 'Import Contactum Forms', 'contactum' ); ?></h3>
        <p><?php esc_html_e( 'Select JSON file to import the form.', 'contactum' ); ?></p>
        <div class="contactum-forms-file-upload">
            <input type="file" name="file" id="contactum-forms-import" <?php esc_attr_e( 'files selected', 'contactum' ); ?>" accept=".json" />
        </div>
        <p class="description">
            <i class="dashicons dashicons-info"></i>
            <?php
            /* translators: %s: File format */
            printf( esc_html__( 'Only %s file is allowed.', 'contactum' ), '<strong>JSON</strong>' );
            ?>
        </p>
        <div class="publishing-action">
            <button type="submit" class="button button-primary contactum-forms-btn contactum-forms-btn-primary contactum_forms_import_action" name="contactum-forms-import-form"><?php esc_html_e( 'Import Form', 'contactum' ); ?></button>
            <?php wp_nonce_field( 'contactum-import-forms' ); ?>
        </div>
    </div>
</div>


<!-- <table style="min-width: 500px;">
    <tbody>
        <tr>
            <td><?php _e( 'Contact Form 7', 'contactum' ); ?></td>
            <th><button class="button external_import" data-name="cf7" data-importing="<?php esc_attr_e( 'Importing...', 'contactum' ); ?>" data-original="<?php esc_attr_e( 'Import', 'contactum' ); ?>"><?php _e( 'Import', 'contactum' ); ?></button></th>
        </tr>
        <tr>
            <td><?php _e( 'Ninja Forms', 'contactum' ); ?></td>
            <th><button class="button external_import" data-name="nf" data-importing="<?php esc_attr_e( 'Importing...', 'contactum' ); ?>" data-original="<?php esc_attr_e( 'Import', 'contactum' ); ?>"><?php _e( 'Import', 'contactum' ); ?></button></th>
        </tr>
        <tr>
            <td><?php _e( 'Caldera Forms', 'contactum' ); ?></td>
            <th><button class="button external_import" data-name="caldera-forms" data-importing="<?php esc_attr_e( 'Importing...', 'contactum' ); ?>" data-original="<?php esc_attr_e( 'Import', 'contactum' ); ?>"><?php _e( 'Import', 'contactum' ); ?></button></th>
        </tr>
        <tr>
            <td><?php _e( 'Gravity Forms', 'contactum' ); ?></td>
            <th><button class="button external_import" data-name="gf" data-importing="<?php esc_attr_e( 'Importing...', 'contactum' ); ?>" data-original="<?php esc_attr_e( 'Import', 'contactum' ); ?>"><?php _e( 'Import', 'contactum' ); ?></button></th>
        </tr>
        <tr>
            <td><?php _e( 'WP Forms', 'contactum' ); ?></td>
            <th><button class="button external_import" data-name="wpforms" data-importing="<?php esc_attr_e( 'Importing...', 'contactum' ); ?>" data-original="<?php esc_attr_e( 'Import', 'contactum' ); ?>"><?php _e( 'Import', 'contactum' ); ?></button></th>
        </tr>
    </tbody>
</table> -->

<style type="text/css">
    .postbox {
        padding: 10px;
    }

    .contactum_forms_import_action {
        margin-top: 15px !important;
    }
</style>
