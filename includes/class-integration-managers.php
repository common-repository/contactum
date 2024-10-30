<?php
namespace Contactum;

class IntegrationManager {

    /**
     * The integration instances
     *
     * @var array
     */
    public $integrations = [];

    public function getIntegration( $integration_type ) {
		$integrations = $this->getIntegrations();

		if ( array_key_exists( $integration_type, $integrations ) ) {
            return $integrations[ $field_type ];
        }

        return false;
	}

    /**
     * Return loaded integrations.
     *
     * @return array
     */
    public function getIntegrations() {
        $integrations = array();

        $this->integrations = apply_filters( 'contactum_integrations', $integrations );;
        
        return $this->integrations;
    }

    public function get_integration_js_settings() {
        $settings = [];
        $integrations = $this->getIntegrations();
        
        if( !empty( $integrations ) ) {
            foreach ( $this->getIntegrations() as $integration_id => $integration ) {
                $settings[ $integration_id ] = $integration->get_js_settings();
            }
        }

        return $settings;
    }
}