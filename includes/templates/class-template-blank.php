<?php
namespace Contactum\Templates;

use Contactum\Templates\Contactum_Form_Template;

class Template_Blank extends Contactum_Form_Template {
        public function __construct() {
                parent::__construct();
                $this->enabled     = true;
                $this->title       = __( 'Blank Form', 'contactum' );
                $this->description = __( 'Create a simple Blank form.', 'contactum' );
                $this->image       = CONTACTUM_ASSETS . '/images/form-template/blank.png';
                $this->category    = 'default';
        }

        public function get_form_fields() {
        	return __return_empty_array();
        }
}