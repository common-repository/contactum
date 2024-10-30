<?php
namespace Contactum\Widgets;

use WP_Widget;

class Contactum_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'contactumform_widget',
            esc_html__('Contactum Forms Widget', 'contactum'),
            array('description' => esc_html__('Add your form by Contactum Forms', 'contactum'),)
        );
    }

    function widget( $args, $instance ) {
        $title   = apply_filters( 'widget_title', $instance['title'] );
        $form_id = $instance['form_id'];
        echo wp_kses_post($args['before_widget']);

        if ( ! empty( $title ) ) {
              echo wp_kses_post( $args['before_title'] ) . wp_kses_post( $title ) . wp_kses_post( $args['after_title'] );
        }

        echo do_shortcode( sprintf( '[contactum id ="%s" ]', $form_id  ) );

        echo wp_kses_post($args['after_widget']);
    }

    function update( $new_instance, $old_instance ) {
        $instance            = [];
        $instance['title']   = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['form_id'] = ( !empty( $new_instance['form_id'] ) ) ? strip_tags( $new_instance['form_id'] ) : '';

        return $instance;
    }

    function form( $instance ) {
        $title   = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Form', '' );
        $form_id = isset( $instance[ 'form_id' ] ) ? $instance[ 'form_id' ] : 0;

        $all_forms = contactum()->forms->all();
        $options   = sprintf( "<option value='%s'>%s</option>", 0, __( 'Select Form', '' ) );

        foreach ( $all_forms['forms'] as $form ) {
            $options .= sprintf( "<option value='%s' %s >%s</option>", $form->id, selected( $form_id, $form->id, false ), $form->name );
        }

        ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', ''); ?></label>
                <input class="widefat"
                    id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                    name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                    value="<?php echo esc_attr($title); ?>"
                />
            </p>

            <p>
                <label for="<?php esc_attr_e( $this->get_field_id('form_id') ); ?>"> Select a form:
                    <select class='widefat' id="<?php esc_attr_e( $this->get_field_id('form_id') ); ?>"
                    name="<?php esc_attr_e( $this->get_field_name('form_id') ); ?>">
                       <?php  echo $options; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  ?>
                    </select>
                </label>
            </p>
            <?php
    }
}
