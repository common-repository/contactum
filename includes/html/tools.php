<?php
    $tabs  =  array(
        'import' => __( 'Import', 'contactum' ),
        'export' => __( 'Export', 'contactum' )
    );

    $current_tab = ! empty( $_REQUEST['tab'] ) ? sanitize_title( wp_unslash( $_REQUEST['tab'] ) ) : 'import'; // phpcs:ignore WordPress.Security.NonceVerification
?>
    <div class="wrap">
        <nav class="nav-tab-wrapper contactum-nav-tab-wrapper">
            <?php
            foreach ( $tabs as $slug => $label ) {
                echo '<a href="' . esc_html( admin_url( 'admin.php?page=contactum-tools&tab=' . esc_attr( $slug ) ) ) . '" class="nav-tab ' . ( $current_tab === $slug ? 'nav-tab-active' : '' ) . '">' . esc_html( $label ) . '</a>';
            }
            ?>
        </nav>
        <h1 class="screen-reader-text"><?php echo esc_html( $tabs[ $current_tab ] ); ?></h1>
        <?php
            switch ( $current_tab ) {
                case 'import':
                    require_once CONTACTUM_INCLUDES . '/html/import.php';
                    break;
                case 'export':
                    require_once CONTACTUM_INCLUDES . '/html/export.php';
                    break;
                default:
                    require_once CONTACTUM_INCLUDES . '/html/import.php';
                    break;
            }
        ?>
    </div>
