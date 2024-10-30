<?php

namespace Contactum;

use Contactum\Fields\Contactum_Form_Field_Pro;

/**
 * Text Field Class
 */
class Contactum_Form_Field_GMap extends Contactum_Form_Field_Pro {
    public function __construct() {
        $this->name       = __( 'Google Map', 'contactum' );
        $this->input_type = 'google_map';
        $this->icon       = 'map-marker';
    }
}

/**
 * Text Field Class
 */
class Contactum_Form_Field_Hook extends Contactum_Form_Field_Pro {
    public function __construct() {
        $this->name       = __( 'Action Hook', 'contactum' );
        $this->input_type = 'action_hook';
        $this->icon       = 'anchor';
    }
}


/**
 * Rating Field Class
 */
class Contactum_Form_Field_Linear_Scale extends Contactum_Form_Field_Pro {
    public function __construct() {
        $this->name       = __( 'Linear Scale', 'contactum' );
        $this->input_type = 'linear_scale';
        $this->icon       = 'ellipsis-h';
    }
}

/**
 * Checkbox Grids Field Class
 */
class Contactum_Form_Field_Checkbox_Grid extends Contactum_Form_Field_Pro {
    public function __construct() {
        $this->name       = __( 'Checkbox Grid', 'contactum' );
        $this->input_type = 'checkbox_grid';
        $this->icon       = 'th';
    }
}

/**
 * Multiple Choice Grids Field Class
 */
class Contactum_Form_Field_Multiple_Choice_Grid extends Contactum_Form_Field_Pro {
    public function __construct() {
        $this->name       = __( 'Multiple Choice Grid', 'contactum' );
        $this->input_type = 'multiple_choice_grid';
        $this->icon       = 'braille';
    }
}

/**
 * Repeat Field Class
 */
class Contactum_Form_Field_Repeat extends Contactum_Form_Field_Pro {
    public function __construct() {
        $this->name       = __( 'Repeat Field', 'contactum' );
        $this->input_type = 'repeat_field';
        $this->icon       = 'text-width';
    }
}

/**
 * Shortcode Field Class
 */
class Contactum_Form_Field_Shortcode extends Contactum_Form_Field_Pro {
    public function __construct() {
        $this->name       = __( 'Shortcode', 'contactum' );
        $this->input_type = 'shortcode';
        $this->icon       = 'calendar-o';
    }
}

/**
 * Step Field Class
 */
class Contactum_Form_Field_Step extends Contactum_Form_Field_Pro {
    public function __construct() {
        $this->name       = __( 'Step Start', 'contactum' );
        $this->input_type = 'step_start';
        $this->icon       = 'step-forward';
    }
}