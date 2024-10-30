<?php
    defined( 'ABSPATH' ) || exit;
    $entries_forms  = contactum_entries_forms();
    $contactumforms = contactum()->forms->all();
    $forms          = $contactumforms['forms'];
?>

<div class="contactum-export-form postboxes metabox-holder">
    <?php if ( ! empty( $forms ) ) { ?>
    <div class="export-form postbox">
        <h3 class="handle"><?php esc_html_e( 'Export Contactum Forms with Settings', 'contactum' ); ?></h3>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" >
            <p>
                <input class="export_type" type="radio" name="export_type" value="all" id="contactum-all_export" checked>
                <label for="contactum-all_export"><?php esc_html_e( 'All', 'contactum' ); ?></label>
            </p>

            <p>
                <input class="export_type" type="radio" name="export_type" value="selected" id="contactum-selected_export">
                <label for="contactum-selected_export"><?php esc_html_e( 'Select individual', 'contactum' ); ?></label>
            </p>

            <?php
                    echo '<select multiple class="formlist" id="contactum-form-export" style="min-width: 350px;" name="form_id[]" data-placeholder="' . esc_attr__( 'Select form', 'contactum' ) . '"><option value="">' . esc_html__( 'Select a form', 'contactum' ) . '</option>';
                    foreach ( $forms as $id => $form ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride
                        echo '<option value="' . esc_attr( $id ) . '">' . esc_html( $form->name ) . '</option>';
                    }
                    echo '</select>';
            ?>
            <div class="publishing-action">
                <?php wp_nonce_field( 'contactum-export-forms' ); ?>
                <input type="hidden" name="action" value="contactum_export_forms"/>
                <button type="submit" class="button button-primary contactum-btn contactum-btn-primary contactum-export-form-action" name="contactum-export-form"><?php esc_html_e( 'Export', 'contactum' ); ?></button>
            </div>
        </form>
    </div>
        <?php
            } else {
                echo '<p>' . esc_html__( 'You need to create a form before you can use form export.', 'contactum' ) . '</p>';
            }
        ?>
    <div class="export-entries postbox">
        <h3 class="handle"><?php esc_html_e( 'Export Contactum Forms Entries', 'contactum' ); ?></h3>
        <?php if( !empty ( $entries_forms ) ) { ?>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <select name="form_id">
                <option value=""> <?php esc_html_e( '&mdash; Select Form &mdash;', 'contactum' ); ?> </option>
                <?php foreach ( $entries_forms as $id => $form ) : ?>
                    <option value="<?php echo esc_attr( $id ); ?>"> <?php echo esc_html( $form->name ); ?> </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="action" value="contactum_export_form_entries"/>
            <button type="submit" class="button button-primary contactum-btn contactum-btn-primary contactum-export-form-entries-action" name="contactum-export-form"><?php esc_html_e( 'Export Entries', 'contactum' ); ?></button>
        </form>
        <?php } else { ?>
            <p> <?php esc_html_e( 'There is no form entries found', 'contactum' ); ?> </p>
        <?php }?>
    </div>
</div>


<style type="text/css">
    .contactum-export-form {
        display: flex;
    }

    .postbox {
        margin-right: 30px;
        flex: 1;
        padding: 10px;
    }

    .export-form, .export-entries {
        box-shadow: 2px 1px 10px 2px #d9d9da;
        margin-top: 10px;
    }

    .export-entries select {
        display: block;
    }

    .contactum-btn {
        margin-top: 15px !important;
    }
</style>


<script>
    (function($){
        $('.formlist').hide();
        $('input.export_type').on('change',function(){
            $(this).closest('form').find('.formlist').slideUp(200);

            if( $(this).attr('value') == 'selected' ) {
                $(this).closest('form').find('.formlist').slideDown(200);
            }
        });

    })(jQuery);

</script>
