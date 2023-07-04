(function(wp) {
	/**
	 * Registers a new block provided a unique name and an object defining its behavior.
	 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/#registering-a-block
	 */
	var registerBlockType = wp.blocks.registerBlockType;
	/**
	 * Returns a new element of given type. Element is an abstraction layer atop React.
	 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/packages/packages-element/
	 */
	var el = wp.element.createElement;

	var RichText = wp.blockEditor.RichText;
	/**
	 * Retrieves the translation of text.
	 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/packages/packages-i18n/
	 */
	var __ = wp.i18n.__;

	/**
	 * Every block starts by registering a new block type definition.
	 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/#registering-a-block
	 */
	registerBlockType( 'abertoir2022/exhibit-meta', {
		apiVersion: 3,
		title: __( 'Exhibit meta block', 'abertoir2022' ),
		icon: 'calendar',
		category: 'abertoir',

		/**
		 * Optional block extended support features.
		 */
		supports: {
			// Removes support for an HTML mode.
			html: false,
			align: false,
			color: {
				//text: false,
				background: false,
				link: false,
			},
		},

		attributes: {
			director: {
				type: 'string',
				source: 'text',
				selector: 'dd.director'
			},
			country: {
				type: 'string',
				source: 'text',
				selector: 'dd.country'
			},
			language: {
				type: 'string',
				source: 'text',
				selector: 'dd.language'
			},
			runningtime: {
				type: 'string',
				source: 'text',
				selector: 'dd.runningtime'
			},
			year: {
				type: 'string',
				source: 'text',
				selector: 'dd.year'
			}
		},

		/**example: {
        attributes: {
            director: 'Hello World',
        },
    },*/

		/**
		 * The edit function describes the structure of your block in the context of the editor.
		 * This represents what the editor will render when the block is used.
		 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-edit-save/#edit
		 *
		 * @param {Object} [props] Properties passed from the editor.
		 * @return {Element}       Element to render.
		 */
		edit: function( props ) {
			var blockProps = wp.blockEditor.useBlockProps({className: 'exhibit-meta'});
			var director = props.attributes.director;
			var country = props.attributes.country;
			var language = props.attributes.language;
			var runningtime = props.attributes.runningtime;
			var year = props.attributes.year;

			function onChangeContent( newContent ) {
				props.setAttributes( { [this]: newContent } );
			}

			return el('dl', blockProps, [
				el('dt', {}, __('Director')),
				el(RichText,
					Object.assign({}, {
						tagName: 'dd',
						onChange: onChangeContent.bind('director'),
						value: director,
						allowedFormats: [],//empty options
						placeholder: __('eg. Barry Convex...'),
					})),
				el('dt', {}, __('Country')),
				el(RichText,
					Object.assign({}, {
						tagName: 'dd',
						onChange: onChangeContent.bind('country'),
						value: country,
						allowedFormats: [],//empty options
						placeholder: __('eg. Wales...'),
					})),
				el('dt', {}, __('Language')),
				el(RichText,
					Object.assign({}, {
						tagName: 'dd',
						onChange: onChangeContent.bind('language'),
						value: language,
						allowedFormats: [],//empty options
						placeholder: __('eg. Welsh...'),
					})),
				el('dt', {}, __('Running time')),
				el(RichText,
					Object.assign({}, {
						tagName: 'dd',
						onChange: onChangeContent.bind('runningtime'),
						value: runningtime,
						allowedFormats: [],//empty options
						placeholder: __('eg. 90 min...'),
					})),
				el('dt', {}, __('Year')),
				el(RichText,
					Object.assign({}, {
						tagName: 'dd',
						onChange: onChangeContent.bind('year'),
						value: year,
						allowedFormats: [],//empty options
						placeholder: __('eg. 1986...'),
					})
				)
			]);
		},

		/**
		 * The save function defines the way in which the different attributes should be combined
		 * into the final markup, which is then serialized by Gutenberg into `post_content`.
		 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-edit-save/#save
		 *
		 * @return {Element}       Element to render.
		 */
		save: function( props ) {
			var blockProps = wp.blockEditor.useBlockProps.save({className: 'exhibit-meta'});
			var director = props.attributes.director;
			var country = props.attributes.country;
			var language = props.attributes.language;
			var runningtime = props.attributes.runningtime;
			var year = props.attributes.year;

			return el(
				'dl', blockProps, [
					el('dt', {}, __('Director')),
					el(RichText.Content, {
						tagName: 'dd',
						className: 'director',
						value: director
					}),
					el('dt', {}, __('Country')),
					el(RichText.Content, {
						tagName: 'dd',
						className: 'country',
						value: country
					}),
					el('dt', {}, __('Language')),
					el(RichText.Content, {
						tagName: 'dd',
						className: 'language',
						value: language
					}),
					el('dt', {}, __('Running time')),
					el(RichText.Content, {
						tagName: 'dd',
						className: 'runningtime',
						value: runningtime
					}),
					el('dt', {}, __('Year')),
					el(RichText.Content, {
						tagName: 'dd',
						className: 'year',
						value: year
					})
				]
			);
		}

	});
})(window.wp);
