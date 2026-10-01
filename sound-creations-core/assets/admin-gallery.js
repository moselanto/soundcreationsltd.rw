( function ( $ ) {
	function openPicker( $wrap ) {
		var $input = $wrap.find( '.sc-gal-input' );
		var $prev  = $wrap.find( '.sc-gal-prev' );
		var frame  = wp.media( {
			title: 'Select project photos',
			library: { type: 'image' },
			multiple: true,
			button: { text: 'Use these photos' }
		} );
		frame.on( 'open', function () {
			var sel = frame.state().get( 'selection' );
			var val = $input.val() || '';
			var ids = val.length ? val.split( ',' ) : [];
			ids.forEach( function ( id ) {
				id = id.trim();
				if ( id.length ) {
					var att = wp.media.attachment( id );
					att.fetch();
					sel.add( att );
				}
			} );
		} );
		frame.on( 'select', function () {
			var ids  = [];
			var html = '';
			frame.state().get( 'selection' ).each( function ( att ) {
				var j = att.toJSON();
				ids.push( j.id );
				var url = ( j.sizes && j.sizes.thumbnail ) ? j.sizes.thumbnail.url : j.url;
				html += '<span class="sc-gal-item"><img src="' + url + '" style="width:84px;height:84px;object-fit:cover;border-radius:6px;"></span>';
			} );
			$input.val( ids.join( ',' ) );
			$prev.html( html );
		} );
		frame.open();
	}
	$( document ).on( 'click', '.sc-gal-add', function ( e ) {
		e.preventDefault();
		openPicker( $( this ).closest( '.sc-gal' ) );
	} );
	$( document ).on( 'click', '.sc-gal-clear', function ( e ) {
		e.preventDefault();
		var $wrap = $( this ).closest( '.sc-gal' );
		$wrap.find( '.sc-gal-input' ).val( '' );
		$wrap.find( '.sc-gal-prev' ).empty();
	} );
} )( jQuery );
