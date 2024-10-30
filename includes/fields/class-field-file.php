<?php
namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;

class Field_File extends  Contactum_Field {

	public function __construct() {
        $this->name       = __( 'File', 'contactum' );
        $this->input_type = 'file_field';
        $this->icon       = 'user';
    }

    public function render( $field_settings, $form_id ) {
        $allowed_ext = '';
        $extensions  = contactum_allowed_extensions();
        $unique_id   = sprintf( '%s-%d', $field_settings['name'], $form_id );
        
        // print_r($field_settings['extension']);

        if ( is_array( $field_settings['extension'] ) ) {
            foreach ($field_settings['extension'] as $ext) {
                $allowed_ext .= $extensions[$ext]['ext'] . ',';
            }
        } else {
            $allowed_ext = '*';
        }
        ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <?php $this->print_label( $field_settings ); ?>
            <div class="contactum-fields">
                <div id="<?php echo $unique_id; ?>-upload-container">
                    <div class="attachment-upload-filelist" data-type="file" data-required="<?php echo $field_settings['required']; ?>">
                        <!-- <a id="<?php echo $unique_id; ?>-pickfiles" data-form_id="<?php echo $form_id; ?>" class="button  file-selector <?php echo $field_settings['name'].'_'.$form_id; ?>" href="#"><?php _e( 'Select File(s)', '' ); ?></a> -->
                        <a id="<?php echo $unique_id; ?>-pickfiles" data-form_id="<?php echo $form_id; ?>" class="button btn-image file-selector <?php echo $field_settings['name'].'_'.$form_id; ?>" href="#"><?php _e( 'Select File(s)', '' ); ?></a>
                        <ul class="attachment-list thumbnails"></ul>
                    </div>
                </div><!-- .container -->

                <?php $this->help_text( $field_settings ); ?>

            </div> <!-- .fields -->
        </li>
        <?php
        $count       = $field_settings['count'];
        $name        = $field_settings['name'];
        $max_size    = $field_settings['max_size'];

        $script = ";(function($) {
            $(document).ready( function() {
                var uploader = new Uploader(
                    '{$unique_id}-pickfiles',
                    '{$unique_id}-upload-container',
                    {$count},
                    '{$name}',
                    '{$allowed_ext}',
                    {$max_size}
                );
            });
        })(jQuery);";

        wp_add_inline_script( 'contactum-upload', $script );
    }

    public function get_options_settings() {
        $default_options      = $this->get_default_option_settings( true, array('dynamic','width') );

        $settings = array(
            array(
                'name'          => 'max_size',
                'title'         => __( 'Max. file size', 'contactum' ),
                'type'          => 'text',
                'variation'     => 'number',
                'section'       => 'advanced',
                'priority'      => 20,
                'help_text'     => __( 'Enter maximum upload size limit in KB', 'contactum' ),
            ),

            array(
                'name'          => 'count',
                'title'         => __( 'Max. files', 'contactum' ),
                'type'          => 'text',
                'variation'     => 'number',
                'section'       => 'advanced',
                'priority'      => 21,
                'help_text'     => __( 'Number of images can be uploaded', 'contactum' ),
            ),


            array(
                'name'          => 'extension',
                'title'         => __( 'Allowed Files', 'contactum' ),
                'title_class'   => 'label-hr',
                'type'          => 'checkbox',
                'options'       => array(
                    'images'    => __( 'Images (jpg, jpeg, gif, png, bmp)', 'contactum' ),
                    'audio'     => __( 'Audio (mp3, wav, ogg, wma, mka, m4a, ra, mid, midi)', 'contactum' ),
                    'video'     => __( 'Videos (avi, divx, flv, mov, ogv, mkv, mp4, m4v, divx, mpg, mpeg, mpe)', '' )
                ),
                'section'       => 'advanced',
                'priority'      => 22,
                'help_text'     => '',
            )
        );

        return array_merge( $default_options, $settings );
    }

    /**
     * Get the field props
     *
     * @return array
     */
    public function get_field_props() {
        $defaults = $this->default_attributes();
        $props    = array(
            'max_size'      => '1024',
            'count'         => '1',
            'extension'     => array( 'images', 'audio', 'video', 'pdf', 'office', 'zip', 'exe', 'csv' ),
        );

        return array_merge( $defaults, $props );
    }

    public function prepare_entry( $field, $post_data = [] ) {
        return isset( $post_data['contactum_files'][$field['name']] ) ? $post_data['contactum_files'][$field['name']] : [];
    }
}