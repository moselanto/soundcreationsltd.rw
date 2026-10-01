/**
 * Drag-and-drop ordering for the Brands (sc_brand) list table.
 * Saves the new order to each brand's menu_order via admin-ajax.
 */
( function ( $ ) {
	$( function () {
		if ( typeof scBrandOrder === 'undefined' ) {
			return;
		}
		var $list = $( '#the-list' );
		if ( $list.length === 0 ) {
			return;
		}
		$list.sortable( {
			items: 'tr',
			axis: 'y',
			cursor: 'move',
			opacity: 0.7,
			helper: function ( event, tr ) {
				var $originals = tr.children();
				var $helper = tr.clone();
				$helper.children().each( function ( index ) {
					$( this ).width( $originals.eq( index ).width() );
				} );
				return $helper;
			},
			update: function () {
				var ids = [];
				$list.find( 'tr' ).each( function () {
					var id = this.id || '';
					if ( id.indexOf( 'post-' ) === 0 ) {
						ids.push( id.replace( 'post-', '' ) );
					}
				} );
				$list.css( 'opacity', 0.5 );
				$.post( scBrandOrder.ajaxUrl, {
					action: 'sc_reorder_brands',
					_nonce: scBrandOrder.nonce,
					ids: ids
				} ).always( function () {
					$list.css( 'opacity', 1 );
				} );
			}
		} );
	} );
}( jQuery ) );
