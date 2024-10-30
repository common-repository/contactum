<div id="contactum-form-template-modal">
    <div class="contactum-form-template-modal">
        <span id="modal-label" class="screen-reader-text"><?php esc_html_e( 'Modal window. Press escape to close.', 'contactum'  ); ?></span>
        <a href="#" class="close">× <span class="screen-reader-text"><?php esc_html_e( 'Close modal window', 'contactum'  ); ?></span></a>
        <header class="modal-header">
            <h2> <?php esc_html_e( 'Select a Template', 'contactum' ); ?> </h2>
        </header>

        <div class="content-container modal-footer">
            <div class="content">
                <ul>
                    <?php
                    foreach ( $templates as $key => $template ) {
                        $class    = 'template-active';
                        $title    = $template->title;
                        $image    = $template->image ? $template->image : '';
                        $disabled = '';

                        $url   = esc_url( add_query_arg( [
                            'action'   => $action_name,
                            'template' => $key,
                            '_wpnonce' => wp_create_nonce( 'contactum_create_from_template' ),
                        ], admin_url( 'admin.php' ) ) );

                        if ( !$template->is_enabled() ) {
                            $url      = '#';
                            $class    = 'template-inactive';
                            $title    = __( 'This integration is not installed.', '' );
                            $disabled = 'disabled';
                        }
                    ?>

                        <li class="<?php echo esc_attr( $class ); ?>">
                            <h3><?php echo esc_html( $template->get_title() ); ?></h3>
                            <?php if ( $image ) {
                            printf( '<img src="%s" alt="%s">', esc_attr( $image ), esc_attr( $title ) );
                        } ?>

                            <div class="form-create-overlay">
                                <div class="title"><?php echo esc_html( $title ); ?></div>
                                <div class="description"><?php echo esc_html( $template->get_description() ); ?></div>
                                <br>
                                <a href="<?php echo esc_url( $url ); ?>" class="button button-primary  btn-submit" title="<?php echo esc_attr( $template->get_title() ); ?>" <?php echo esc_attr($disabled ); ?>>
                                    <?php esc_html_e( 'Create Form', '' ); ?>
                                </a>
                            </div>
                        </li>

                    <?php
                    } ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="contactum-form-template-modal-backdrop"></div>
</div>

<style type="text/css">

.contactum-form-template-modal {
    background: #fff;
    position: fixed;
    top: 8%;
    bottom: 5%;
    right: 5%;
    left: 12%;
    display: none;
    box-shadow: 0 1px 20px 5px rgba(0, 0, 0, 0.1);
    z-index: 160000;
}
.contactum-form-template-modal.show {
  display: block;
}
.contactum-form-template-modal * {
  box-sizing: border-box;
}
.contactum-form-template-modal a.close {
  position: absolute;
  top: 0;
  right: 0;
  font: 300 1.71429em "dashicons" !important;
  color: #777;
  content: '\f335';
  display: inline-block;
  padding: 10px 20px;
  z-index: 5;
  text-decoration: none;
  height: 50px;
  cursor: pointer;
  border-left: 1px solid #ddd;
}
.contactum-form-template-modal a.close:hover {
  background: #eee;
  opacity: 0.8;
  text-decoration: none;
}
.contactum-form-template-modal a.close:active {
  background: #eee;
  opacity: 0.4;
}
.contactum-form-template-modal .modal-header {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 50px;
  z-index: 4;
  border-bottom: 1px solid #ddd;
  padding-left: 15px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
}
.contactum-form-template-modal .modal-header h2 {
  line-height: 50px;
  text-align: left;
  margin-top: 0;
  color: #5d6d74;
  font-size: 20px;
  text-shadow: 0 1px 1px #fff;
}
.contactum-form-template-modal .modal-header h2 small {
  font-weight: normal;
  font-size: 13px;
  margin-left: 15px;
}
.contactum-form-template-modal .content-container {
  position: absolute;
  top: 50px;
  right: 0;
  bottom: 50px;
  left: 0;
  overflow: auto;
  padding: 2em 2em;
}
.contactum-form-template-modal .content-container.no-footer {
  bottom: 0;
}
.contactum-form-template-modal .content {
  margin: 0 auto;
  max-width: 900px;
  text-align: left;
}
.contactum-form-template-modal .content ul {
  width: 100%;
  margin: 0;
  padding: 0;
}
.contactum-form-template-modal .content ul li {
  text-align: center;
  min-height: 280px;
  width: 220px;
  border: 0px;
  box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.1);
  position: relative;
  border-radius: 3px;
  margin-bottom: 30px;
  float: left;
  margin-left: 30px;
}
.contactum-form-template-modal .content ul li h3 {
  margin-top: 0;
  margin-bottom: 0;
  border: 0px;
  /* background: #7e3bd0; */
  background: #409EFF;
  padding: 13px;
  font-weight: normal;
  font-size: 13px;
  color: #fff;
  border-radius: 3px 3px 0px 0px;
  text-align: left;
}
.contactum-form-template-modal .content ul li .title {
  font-size: 17px;
  margin: 0 0 10px 0;
  line-height: 23px;
}
.contactum-form-template-modal .content ul li.template-active img,
.contactum-form-template-modal .content ul li.template-inactive img {
  max-width: 100%;
  max-height: 211px;
}
.contactum-form-template-modal .content ul li .form-middle-text {
  margin-top: 70px;
  font-size: 15px;
}
.contactum-form-template-modal .content ul li .form-middle-text span.dashicons {
  font-size: 45px;
  color: #ddd;
  margin: 0 auto;
  width: auto;
  height: auto;
  display: block;
}
.contactum-form-template-modal .content ul li .form-create-overlay {
  position: absolute;
  display: none;
}
.contactum-form-template-modal .content ul li .form-create-overlay a.button.button-primary {
  width: 200px;
  min-height: 30px;
  padding: 0;
  color: #fff;
  /* background: #7e3bd0; */
  background: #409EFF;
  outline: none;
  border: none;
}
.contactum-form-template-modal .content ul li.on-progress:before {
  content: "\f463";
  display: inline-block;
  font: normal 20px/1 'dashicons';
  color: #f56e28;
  -webkit-animation: rotation 2s infinite linear;
  animation: rotation 2s infinite linear;
  position: absolute;
  top: 40%;
  left: 45%;
}
.contactum-form-template-modal .content ul li.on-progress a {
  pointer-events: none;
  opacity: 0.2;
}
.contactum-form-template-modal .content ul li:hover {
  background: #fff;
}
.contactum-form-template-modal .content ul li:hover .form-create-overlay {
  animation: contactumFadeIn .25s;
  padding: 10px;
  text-align: center;
  display: flex;
  flex-direction: column;
  justify-content: center;
  background: rgba(0, 0, 0, 0.7);
  height: 100%;
  width: 100%;
  top: 0px;
  left: 0px;
  color: #fff;
  border-radius: 3px;
}
.contactum-form-template-modal .content ul li a {
  text-decoration: none;
  color: #555;
  padding: 20px;
  display: block;
  min-height: 118px;
}
.contactum-form-template-modal .content ul li .title {
  font-size: 17px;
  margin: 0 0 10px 0;
  line-height: 23px;
}
.contactum-form-template-modal .content ul li .description {
  color: #fff;
}
.contactum-form-template-modal .content ul li:nth-child(3n+1) {
  margin-left: 0;
  clear: both;
}
.contactum-form-template-modal .content ul li.template-inactive .title,
.contactum-form-template-modal .content ul li.template-inactive .description {
  color: #ddd;
}
.contactum-form-template-modal .content ul li.blank-form {
  text-align: center;
}
.contactum-form-template-modal .content ul li.blank-form span {
  display: block;
}
.contactum-form-template-modal .content ul li.blank-form span.dashicons {
  font-size: 45px;
  color: #ddd;
  margin: 0 auto;
  width: auto;
  height: auto;
}
.contactum-form-template-modal footer {
  position: absolute;
  left: 0;
  bottom: 0;
  width: 100%;
  padding: 12px 20px;
  border-top: 1px solid #ddd;
  background: #fff;
  text-align: left;
}
.contactum-form-template-modal-backdrop {
  position: fixed;
  z-index: 159999;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  min-height: 360px;
  background: #000;
  opacity: .7;
  display: none;
}
.contactum-form-template-modal-backdrop.show {
  display: block;
}

.btn-submit {
    background: #409EFF;
    /* background: #7e3bd0; */
    /* width: 200px; */
    padding: 10px 20px;
    background: #7e3bd0;
    color: #fff;
    border: none;
}

.btn-submit:hover {
    background: #7e3bd0;
    color: #fff;
}

/* Smartphones (portrait and landscape) ----------- */
@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
  .contactum-form-template-modal .modal-header h2 small {
    display: none;
  }
  .contactum-form-template-modal .content ul li {
    float: none;
    width: 100%;
    margin-left: 0;
  }
}

@media (max-width: 767px) {
    .contactum-form-template-modal .content ul li {
        margin-left: 0;
    }
    .contactum-form-template-modal .modal-header h2 small {
        display: none;
    }
}
</style>

<script type="text/javascript">
(function($) {
    var popup = {
        init: function() {
            $('.wrap').on('click', 'a.page-title-action.add-form', this.openModal);
            $('.contactum-form-template-modal-backdrop, .contactum-form-template-modal .close').on('click', $.proxy(this.closeModal, this) );

            $('body').on( 'keydown', $.proxy(this.onEscapeKey, this) );
        },

        openModal: function(e) {
            e.preventDefault();

            $('.contactum-form-template-modal').show();
            $('.contactum-form-template-modal-backdrop').show();
        },

        onEscapeKey: function(e) {
            if ( 27 === e.keyCode ) {
                this.closeModal(e);
            }
        },

        closeModal: function(e) {
            if ( typeof e !== 'undefined' ) {
                e.preventDefault();
            }

            $('.contactum-form-template-modal').hide();
            $('.contactum-form-template-modal-backdrop').hide();
        }
    };

    $(function() {
        popup.init();
    });

})(jQuery);
</script>