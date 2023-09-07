// The "Upload" button
(function($) {

    $(document).ready(function() {
        $(document).on('click', '.upload_image_button', function() {
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var $button = $(this);
            var $galleryItem = $(this).closest('.gallery-item');
            
            wp.media.editor.send.attachment = function(props, attachment) {
                console.log(attachment);
                $galleryItem.find('img').attr('src', attachment.url);
                $galleryItem.find('.gallery-item__item').val(attachment.id);
                $galleryItem.find('.gallery-item__caption').val(attachment.caption);
                wp.media.editor.send.attachment = send_attachment_bkp;
            }
            wp.media.editor.open($button);
            return false;
        });

        // The "Remove" button (remove the value from input type='hidden')
        $(document).on('click', '.remove_image_button', function() {
            var answer = confirm('Are you sure you want to remove this slide?');
            if (answer == true) {
                $(this).closest('.gallery-item').remove();
            }
            return false;
        });
    });


}(jQuery));