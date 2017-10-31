( function ( mw, $ ) {
	var config = mw.config,
		i,
		dropdowns = config.get( 'wgShowMeDropdownIDs' ) ? config.get( 'wgShowMeDropdownIDs' ) : [],
		lists = config.get( 'wgShowMeUnorderedListIDs' ) ? config.get( 'wgShowMeUnorderedListIDs' ) : [];

	function showMe( id ) {
		var dropdown, options, i;
		dropdown = document.getElementById( id );
		if ( dropdown === null ) {
			return;
		} else {
			options = dropdown.options;
		}
		$( '#mw-content-text .' + options[ 0 ].value ).show();
		for ( i = 1; i < options.length; i++ ) {
			$( '#mw-content-text .' + options[ i ].value ).hide();
		}
		$( '#mw-content-text #' + id ).change( function () {
			for ( i = 0; i < options.length; i++ ) {
				if ( options[ i ].value === this.value ) {
					$( '#mw-content-text .' + this.value ).show();
				} else {
					$( '#mw-content-text .' + options[ i ].value ).hide();
				}
			}
		} );
	}
	for ( i = 0; i < dropdowns.length; i++ ) {
		showMe( dropdowns[ i ] );
	}

	function showMeList( id ) {
		/* hide all elements except first */
		$( '#' + id + ' li:not(:first-child)' ).each( function ( idx, li ) {
			$( '.' + $( li ).attr( 'id' ) ).hide();
		} );
		$( '#' + id + ' > li:first-child' ).addClass( 'active' );

		$( '#' + id ).on( 'click', 'li a', function ( event ) {
			var itemClicked = $( event.target ).parent().attr( 'id' ),
				currentActive = $( '#' + id + ' li.active' ).attr( 'id' );

			$( '#' + currentActive ).removeClass( 'active' );
			$( '#mw-content-text .' + currentActive ).hide();

			$( this ).parent().addClass( 'active' );
			$( '#mw-content-text .' + itemClicked ).show();

			return false;
		} );
	}
	for ( i = 0; i < lists.length; i++ ) {
		showMeList( lists[ i ] );
	}
}( mediaWiki, jQuery ) );
