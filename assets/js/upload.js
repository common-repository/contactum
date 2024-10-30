jQuery(document).ready(function($) {

    window.Uploader = function (browse_button, container, max, type, allowed_type, max_file_size) {

        this.removed_files = [];
        this.container = container;
        this.browse_button = browse_button;
        this.max = max || 1;
        this.count = $('#' + container).find('.attachment-list > li').length; //count how many items are there
        this.perFileCount = 0; //file count on each upload
        this.UploadedFiles = 0; //file count on each upload

        //if no element found on the page, bail out
        if( !$('#'+browse_button).length ) {
            return;
        }

        // enable drag option for ordering
        $( "ul.attachment-list" ).sortable({
            placeholder: "highlight"
        });

        $( "ul.attachment-list" ).disableSelection();

        this.uploader = new plupload.Uploader({
            runtimes: 'html5,html4',
            browse_button: browse_button,
            container: container,
            multipart: true,
            multipart_params: {
                action: 'upload_file',
                'nonce' : frontend.nonce,
                form_id: $( '#' + browse_button ).data('form_id')
            },
            max_file_count : 2,
            multiple_queues: false,
            multi_selection: ( ( browse_button == 'avatar-pickfiles' || browse_button == 'featured_image-pickfiles' ) ? false : true ),
            urlstream_upload: true,
            file_data_name: 'file',
            max_file_size: max_file_size + 'kb',
            url: frontend.plupload.url + '&type=' + type,
            flash_swf_url: frontend.flash_swf_url,
            filters: [{
                title: 'Allowed Files',
                extensions: allowed_type
            }]
        });

        // this.uploader.bind('Init', $.proxy(this, 'init'));
        this.uploader.bind('FilesAdded', $.proxy(this, 'added'));
        this.uploader.bind('QueueChanged', $.proxy(this, 'upload'));
        this.uploader.bind('UploadProgress', $.proxy(this, 'progress'));
        this.uploader.bind('Error', $.proxy(this, 'error'));
        this.uploader.bind('FileUploaded', $.proxy(this, 'uploaded'));

        this.uploader.init();

        $('#' + container).on('click', 'a.attachment-delete', $.proxy(this.removeAttachment, this));

        return this.uploader;
    }

    Uploader.prototype = {

        init: function (up, params) {
            this.showHide();
            $('#' + this.container).prepend('<div class="file-warning"></div>');
        },

        showHide: function () {
            if ( this.count >= this.max) {
                if ( this.count > this.max ) {
                    $('#' + this.container + ' .file-warning').html( frontend.warning );
                } else {
                    $('#' + this.container + ' .file-warning').html( frontend.warning );
                }
                $('#' + this.container).find('.file-selector').hide();
                return;
            };
            $('#' + this.container + ' .file-warning').html( '' );
            $('#' + this.container).find('.file-selector').show();
        },
        error: function(up, error) {
            $('#' + this.container).find('#' + error.file.id).remove();

            let msg = '';

            switch (error.code) {
                case -600:
                    msg = frontend.plupload.size_error;
                    break;

                case -601:
                    msg = frontend.plupload.type_error;
                    break;
                default:
                    msg = 'Error #' + error.code + ': ' + error.message;
                    break;
            }

            alert(msg);

            this.count -= 1;
            this.showHide();
            this.uploader.refresh();
        },

        added: function(up, files) {
            let $container = $('#' + this.container).find('.attachment-upload-filelist');
            $.each(files, function(i, file) {
                $(".submit-button").attr("disabled", "disabled");
                $container.append(`<div class="upload-item" id="${file.id}"><div class="progress progress-striped active"><div class="bar"></div></div><div class="filename original">
                    ${file.name}(${plupload.formatSize(file.size)})<b></b></div></div>`);
            });

            up.refresh(); // Reposition Flash/Silverlight
            up.start();
        },

        upload: function(uploader) {
            this.count = uploader.files.length - this.removed_files.length;
            this.showHide();
        },

        progress: function (up, file) {
            var item = $('#' + file.id);
            $('.bar', item).css({ width: file.percent + '%' });
            $('.percent', item).html( file.percent + '%' );
        },

        uploaded: function(up, file, response) {
            $('#' + file.id + " b").html("100%");
            $('#' + file.id).remove();

            if(response.response !== 'error') {
                this.perFileCount++;
                this.UploadedFiles++;
                var $container = $('#' + this.container).find('.attachment-list');
                $container.append(response.response);

                if ( this.perFileCount > this.max ) {
                    var attach_id = $('.image-wrap:last a.attachment-delete',$container).data('attach_id');
                    this.removeExtraAttachment(attach_id);
                    $('.image-wrap',$container).last().remove();
                    this.perFileCount--;
                }

            } else {
                alert(response.error);
                this.count -= 1;
                this.showHide();
            }

            let uploaded        = this.UploadedFiles,
                FileProgress    = up.files.length,
                imageCount      = $('ul.attachment-list > li').length;

            if ( imageCount >= this.max ) {
                $('#' + this.container).find('.file-selector').hide();
            }

            if ( FileProgress === uploaded ) {
                if ( typeof grecaptcha !== 'undefined' && !grecaptcha.getResponse().length ) {
                    return;
                }
                $(".submit-button").removeAttr("disabled");
            }
        },

        removeAttachment: function(e) {
            e.preventDefault();

            var self = this,
            el = $(e.currentTarget);

            // swal({
            //     text: frontend.confirmMsg,
            //     type: 'warning',
            //     showCancelButton: true,
            //     confirmButtonColor: '#d54e21',
            //     confirmButtonText: frontend.delete_it,
            //     cancelButtonText: frontend.cancel_it,
            //     confirmButtonClass: 'btn btn-success',
            //     cancelButtonClass: 'btn btn-danger',
            // }).then(function () {
                let data = {
                    'attach_id' : el.data('attach_id'),
                    'nonce' : frontend.nonce,
                    'action' : 'file_del'
                };
                self.removed_files.push(data);
                jQuery('#del_attach').val(el.data('attach_id'));

                jQuery.post(frontend.ajaxurl, data, function() {
                    self.perFileCount--;
                    el.parent().parent().remove();

                    self.count -= 1;
                    self.showHide();
                    self.uploader.refresh();
                });
            // });
        },

        removeExtraAttachment : function( attach_id ) {
            var self = this;

            var data = {
                'attach_id' : attach_id,
                'nonce' : frontend.nonce,
                'action' : 'file_del'
            };

            this.removed_files.push(data);

            jQuery.post(frontend.ajaxurl, data, function() {
                self.count -= 1;
                self.showHide();
                self.uploader.refresh();
            });

        }
    };
});
