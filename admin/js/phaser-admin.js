// javascript related to admin options functionality

// IIFE - Immediately Invoked Function Expression
// namespaced (globally)
(function(cbwPhaserAdmin, $, undefined ) {

	var phaser = cbwPhaserAdmin;
	var regenerate_name = 'phaser_attachment_id';
	var trigger = '.cbw-phaser-selector';

	// The $ is now locally scoped 
	$(function() {

		phaser.regenerateHandler();
		phaser.adminFadeIn();

	});

	phaser.adminFadeIn = function() {
		$( trigger ).each(function(){
			$(this).parent().addClass('phaser-move');
		 	if (this.complete) {
                $(this).addClass('cbw-phaser-complete');
            } else {
                $(this).load(function() {
                    $(this).addClass('cbw-phaser-complete');
                });
            }
		 });
	}

	phaser.regenerateHandler = function( trigger ) {

		 $('body').on('submit', '#phaser-regenerate', function(event){

		 	var attachment_ID = $('input[name="' + regenerate_name + '"]').val();


		 	if ($.trim(attachment_ID).length > 0) {
		 		console.log(attachment_ID);
			 	$.ajax({ 
			         data: {
			         	action: 'render_svg_ajax', 
			         	attachment_ID:  attachment_ID
			         },
			         type: 'POST',
			         url: phaser_admin.ajaxurl,
			         success: function(data) {
			              console.log(data); 
			        },
			        error: function(XMLHttpRequest, textStatus, errorThrown) { 
				        alert("Status: " + textStatus); alert("Error: " + errorThrown); 
				    }  
			    });
			}
			else {
				alert('You must enter an ID first');
			}

			return false;
		 });

	}

	


}( window.cbwPhaserAdmin = window.cbwPhaserAdmin || {}, jQuery ) );


