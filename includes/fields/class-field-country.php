<?php
namespace Contactum\Fields;

use Contactum\Fields\Contactum_Field;

class Field_Country extends  Contactum_Field {

	public function __construct() {
        $this->name       = __( 'Country', 'contactum' );
        $this->input_type = 'country_field';
        $this->icon       = 'globe';
    }

    public function render( $field_settings, $form_id ) {
        $country_list                     = $field_settings['country_list'];
        $country_select_show_list         = ( isset( $country_list['country_select_show_list'] ) && is_array( $country_list['country_select_show_list'] ) ) ? $country_list['country_select_show_list'] : [];
        $country_select_hide_list         = ( isset(  $country_list['country_select_hide_list'] ) && is_array( $country_list['country_select_show_list'] ) ) ?
        $country_list['country_select_hide_list'] : [];
        $list_visibility_option = $country_list['country_list_visibility_opt_name'];
        $value                            = isset( $country_list['name'] ) ? $country_list['name'] : '';
    ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <?php $this->print_label( $field_settings ); ?>
            <div class="contactum-fields">
                <select
                    name="<?php echo $field_settings['name']; ?>"
                    data-required="<?php echo $field_settings['required'] ?>"
                    data-type="select"
                    class="contactum-el-form-control <?php echo $field_settings['name'].'_'.$form_id; ?>"
                    id="<?php echo esc_attr($field_settings['name']) . '_' . esc_attr($form_id); ?>"
                >
                <?php //foreach ($countries as $country ) {
                    // printf('<option value="%s"> %s </option>',$country['code'], $country['name']);
                //} ?>
                </select>
                <?php $this->help_text( $field_settings ); ?>
            </div>

            <?php
                $field_name        = $field_settings['name'];
                $countries         = contactum_get_countries();
                $banned_countries  = $country_select_hide_list;
                $allowed_countries =  $country_select_show_list;
                $sel_country       = !empty( $value ) ? $value : '' ;
                $option_string     = '<option value="-1">Select Country</option>';

                if ( $list_visibility_option == 'hide' ) {
                    foreach ( $countries as $country ) {
                        if ( in_array( $country['code'], $banned_countries ) ) {
                            continue;
                        }
                        $selected = ( $sel_country == $country['code'] ) ? 'selected':'';
                        $option_string .= "<option value=\"{$country['code']}\" { $selected } > {$country['name']}  </option>";
                    }
                } else if ( $list_visibility_option == 'show' ) {
                    foreach ( $countries as $country ) {
                        if ( in_array( $country['code'], $allowed_countries ) || empty( $allowed_countries ) ) {
                            $selected = ( $sel_country == $country['code'] ) ? 'selected':'';
                            $option_string .= "<option value=\"{$country['code']}\" { $selected } > {$country['name']}  </option>";
                        }
                    }
                } else {
                    foreach ( $countries as $country ) {
                        $selected = ( $sel_country == $country['code'] ) ? 'selected':'';
                        $option_string .= "<option value=\"{$country['code']}\" { $selected } > {$country['name']}  </option>";
                    }
                }

                $id = esc_attr($field_settings['name']) . '_' . esc_attr($form_id);

                $countries_obj = json_encode( $countries );

                $script = "
                jQuery(document).ready(function($){
                    var field_name =`{$field_settings['name']}`;
                    $('select[name=\"'+ field_name +'\"]').html(`{$option_string}`);
                });

                ";
               wp_add_inline_script( 'contactum-frontend', $script );
            ?>
        </li>
    <?php }

    public function get_options_settings() {
        $default_options = $this->get_default_option_settings( true, [ 'width' ] );

        $settings = array(
            array(
                'name'          => 'country_list',
                'title'         => '',
                'type'          => 'country_list',
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
            'input_type'        => 'country_list',
            'country_list'  => array(
            'name'                             => '',
                'country_list_visibility_opt_name' => 'all', // all, hide, show
                'country_select_show_list'         => array(),
                'country_select_hide_list'         => array()
            ),
            'is_meta'           => 'yes',
            'id'                => 0,
            'is_new'            => true,
        );

        return array_merge( $defaults, $props );
    }
}