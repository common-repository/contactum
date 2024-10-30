<?php
namespace Contactum\Templates;

abstract class Contactum_Form_Template {

	public $title;
	public $description;
    public $form_fields;
    public $form_settings;

    public $category = 'default';

	public function __construct() {

	}

	public function get_title() {
		return $this->title;
	}

	public function get_description() {
		return $this->description;
	}

    public function get_form_fields() {
        return $this->form_fields;
    }

    public function get_form_settings() {
        return contactum_get_default_form_settings();
    }

    public function get_form_notifications() {
        return [ contactum_get_default_form_notification() ];
    }

    public function get_register_fields() {
        return contactum()->fields->getFields();
    }

    public function is_enabled() {
        return $this->enabled;
    }
}