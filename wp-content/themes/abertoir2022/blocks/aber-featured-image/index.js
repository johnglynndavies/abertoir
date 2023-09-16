( function( wp ) {
	var registerBlockVariation = wp.blocks.registerBlockVariation;

	/**
	 * Post featured image variation with caption.
	 */
	registerBlockVariation( 'core/post-featured-image', {
			name: 'abertoir2022/featured-image',
			title: 'Featured image with caption',
			description: 'Displays the featured image with a caption',
			isActive: ['abertoir2022/featured-image'],
			icon: 'tag',
			attributes: {
				namespace: 'abertoir2022/featured-image',
				className: 'aber-featured-image'
			},
			scope: [ 'inserter' ]
		}
	);

})(window.wp);
