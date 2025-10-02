jQuery(document).ready(function($) {
    console.log('Category header image script loaded');
    
    var mediaUploader;

    // Check if button exists
    if ($('.category-header-image-upload').length) {
        console.log('Upload button found');
    } else {
        console.log('Upload button not found');
    }

    // Handle upload button click
    $(document).on('click', '.category-header-image-upload', function(e) {
        e.preventDefault();
        console.log('Upload button clicked');
        
        var button = $(this);
        var targetInput = button.data('target');
        var previewContainer = button.siblings('.category-header-image-preview');
        var removeButton = button.siblings('.category-header-image-remove');

        console.log('Target input:', targetInput);

        // If the media frame already exists, reopen it
        if (mediaUploader) {
            console.log('Reopening existing media uploader');
            mediaUploader.open();
            return;
        }

        // Check if wp.media exists
        if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
            console.error('WordPress media library not loaded');
            alert('Media library is not available. Please refresh the page and try again.');
            return;
        }

        console.log('Creating new media uploader');

        // Create the media frame
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Select Header Image',
            button: {
                text: 'Use this image'
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });

        // When an image is selected, run a callback
        mediaUploader.on('select', function() {
            console.log('Image selected');
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            console.log('Attachment:', attachment);
            
            // Set the attachment ID in the hidden input
            $('#' + targetInput).val(attachment.id);
            
            // Display the image preview
            previewContainer.html('<img src="' + attachment.url + '" style="max-width: 300px; height: auto;" />');
            
            // Show the remove button
            removeButton.show();
        });

        // Open the media frame
        console.log('Opening media uploader');
        mediaUploader.open();
    });

    // Handle remove button click
    $(document).on('click', '.category-header-image-remove', function(e) {
        e.preventDefault();
        console.log('Remove button clicked');
        
        var button = $(this);
        var uploadButton = button.siblings('.category-header-image-upload');
        var targetInput = uploadButton.data('target');
        var previewContainer = button.siblings('.category-header-image-preview');

        // Clear the input value
        $('#' + targetInput).val('');
        
        // Clear the preview
        previewContainer.html('');
        
        // Hide the remove button
        button.hide();
    });
});