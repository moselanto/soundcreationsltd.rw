( function ( $ ) {
	function preview( $prev, att ) {
		var url = ( att && att.url ) ? att.url : '';
		var isImg = att && att.type === 'image';
		if ( isImg && att.sizes && att.sizes.medium ) { url = att.sizes.medium.url; }
		if ( isImg && url.length > 0 ) {
			$prev.html( '<img src="' + url + '" alt="" style="max-width:190px;height:auto;border-radius:8px;margin-top:8px;display:block;border:1px solid #dcdcde;">' );
		} else if ( url.length > 0 ) {
			$prev.html( '<code style="display:inline-block;margin-top:8px;word-break:break-all;">' + url + '</code>' );
		} else {
			$prev.empty();
		}
	}
	function openPicker( $wrap ) {
		var $url  = $wrap.find( '.sc-media-url' );
		var $prev = $wrap.find( '.sc-media-prev' );
		var mtype = $wrap.attr( 'data-sc-media-type' ) || '';
		var args  = { title: 'Select or upload', multiple: false, button: { text: 'Use this file' } };
		if ( mtype.length > 0 ) { args.library = { type: mtype }; }
		var frame = wp.media( args );
		frame.on( 'select', function () {
			var att = frame.state().get( 'selection' ).first().toJSON();
			var url = att.url;
			if ( att.type === 'image' && att.sizes && att.sizes.large ) { url = att.sizes.large.url; }
			$url.val( url ).trigger( 'change' );
			preview( $prev, att );
		} );
		frame.open();
	}
	$( document ).on( 'click', '.sc-media-pick', function ( e ) {
		e.preventDefault();
		openPicker( $( this ).closest( '.sc-media-field' ) );
	} );
	$( document ).on( 'click', '.sc-media-clear', function ( e ) {
		e.preventDefault();
		var $wrap = $( this ).closest( '.sc-media-field' );
		$wrap.find( '.sc-media-url' ).val( '' ).trigger( 'change' );
		$wrap.find( '.sc-media-prev' ).empty();
	} );
} )( jQuery );
