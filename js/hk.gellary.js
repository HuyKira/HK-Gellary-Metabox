jQuery(document).ready(function($){
  	$("#gallery-hk-metabox").sortable({
	  	deactivate: function( event, ui ) {}
	});
	$("#gallery-hk-metabox").disableSelection();
	$('.gallery-hk-metabox').on('click', '.del-img', function(event) {
		event.preventDefault();
		$(this).parent('.kira').remove();
	});
    $('#gallery-hk').click(function(event) {
        event.preventDefault(); // Prevent reload page when click the button
        var file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select Images', // The title of frame
            library: {}, // Library of images, like as post
            button: {text: 'Select'},
            multiple: true // Enable select multiple
        });
        file_frame.on('open', function() {
            var images = $( '#gallery_input' ).val();
            images = images.split( ',' ); // Get all images id and split to an array
            var selection = file_frame.state().get( 'selection' );
            $.each(images, function(index, el) {
                var attachment = wp.media.attachment( el );
                attachment.fetch();
                selection.add( attachment ? [ attachment ] : [] );
            });
        });
        file_frame.on('select', function() {
            var attachment_ids = [];
            attachment = file_frame.state().get( 'selection' ).toJSON();
            imgs_html = '';

            // Each selected image, push the id of image to an array and show image
            $.each( attachment, function(index, item){
                attachment_ids.push( item.id );
                imgs_html += '<li class="kira" data-image-id="'+ item.id +'">';
                imgs_html += '<div class="over">';
                imgs_html += '<img src="'+ item.url +'" />';
                imgs_html += '</div>';
                imgs_html += '<a class="del-img">';
                imgs_html += '<span class="dashicons dashicons-dismiss"></span></a>'; // Button to remove image
                imgs_html += '<input type="hidden" name="hkgellary[]" value="'+ item.id +'" />'; // Button to remove image
                imgs_html += '</li>';
            });

            $( '#gallery_input' ).val( attachment_ids.join( ',' ) ); // List of all images
            $( '#gallery-hk-metabox' ).append( imgs_html ); // Show images
        });
        file_frame.open();
    });
    
});