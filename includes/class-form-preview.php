<?php
namespace Contactum;

class Form_Preview {

    private $form_id;
    private $is_preview = true;

    public function __construct() {

        if( !isset($_GET['contactum_form_preview']) && empty( $_GET['contactum_form_preview'] ) ) {
            return ;
        }

        $this->form_id = isset( $_GET['contactum_form_preview'] ) ? intval( $_GET['contactum_form_preview'] ) : 0;

        add_action( 'pre_get_posts', [ $this, 'pre_get_posts' ] );
        add_filter( 'template_include', [ $this, 'template_include' ] );

        add_filter( 'the_title', [ $this, 'the_title' ] );
        add_filter( 'the_content', [ $this, 'the_content' ] );
        add_filter( 'get_the_excerpt', [ $this, 'the_content' ] );
        add_filter( 'post_thumbnail_html', '__return_empty_string' );
    }

    public function pre_get_posts( $query ) {
        if ( $query->is_main_query() ) {
            $query->set( 'posts_per_page', 1 );
        }

        return $query;
    }

    public function the_title( $title ) {
        return $title;
    }

    public function the_content( $content ) {
        if ( $this->is_preview ) {
            if ( !is_user_logged_in() ) {
                return __( 'You must be logged in to preview this form.', 'contactum' );
            }
        }

        return do_shortcode( sprintf( '[contactum id="%d"]', $this->form_id ) );
    }

    public function template_include() {
        return locate_template( [ 'page.php', 'single.php', 'index.php' ] );
    }
}
