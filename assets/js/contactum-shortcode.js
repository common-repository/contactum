jQuery(document).ready(function($) {

    tinymce.create( 'tinymce.plugins.contactum_btn', {
        init: function( editor, url ) {

            editor.addButton( 'contactum_btn', {
                title: 'Contactum Shortcodes',
                classes: '',
                type: 'menubutton',
                menu: ''
            });
        }
    });

    tinymce.PluginManager.add( '', tinymce.plugins.contactum_btn )
});
