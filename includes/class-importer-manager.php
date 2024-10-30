<?php
namespace Contactum;

use Contactum\Importer\Importer_WPForms;
use Contactum\Importer\Importer_Ninja_Forms;
use Contactum\Importer\Importer_GF;
use Contactum\Importer\Importer_CF7;
use Contactum\Importer\Importer_Caldera_Forms;

/**
 * Importer Manager
 *
 * @since 1.1.0
 */
class Importer_Manager {

    public function __construct() {
        $this->get_importers();
    }

    /**
     * Fetch and instantiate all the importers
     *
     * @return array
     */
    public function get_importers() {
        require_once CONTACTUM_INCLUDES . '/importer/class-importer-abstract.php';
        require_once CONTACTUM_INCLUDES . '/importer/class-importer-cf7.php';
        require_once CONTACTUM_INCLUDES . '/importer/class-importer-gf.php';
        require_once CONTACTUM_INCLUDES . '/importer/class-importer-wpforms.php';
        require_once CONTACTUM_INCLUDES . '/importer/class-importer-ninja-forms.php';
        require_once CONTACTUM_INCLUDES . '/importer/class-importer-caldera-forms.php';

        $importers = [
            'cf7'        => new Importer_CF7(),
            'gravity'    => new Importer_GF(),
            'wpforms'    => new Importer_WPForms(),
            'ninjaforms' => new Importer_Ninja_Forms(),
            'caldera'    => new Importer_Caldera_Forms(),
        ];

        return apply_filters( 'contactum_form_importers', $importers );
    }
}
