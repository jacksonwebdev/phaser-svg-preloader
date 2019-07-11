// IIFE - Immediately Invoked Function Expression
// namespaced (globally)
(function(cbwPhaserPublic, $, undefined ) {

	var phaser = cbwPhaserPublic;
	var trigger = '.cbw-phaser-selector';

	// The $ is now locally scoped 
	$(function() {

		if ( $( trigger ).length ) {
			phaser.loadImages( trigger );
		}
		
	});

	phaser.loadImages = function( trigger ) {

		 $( trigger ).each(function(){
		 	if (this.complete) {
                $(this).addClass('cbw-phaser-complete');
            } else {
                $(this).load(function() {
                    $(this).addClass('cbw-phaser-complete');
                });
            }
		 });

	}

	


}( window.cbwPhaserPublic = window.cbwPhaserPublic || {}, jQuery ) );


