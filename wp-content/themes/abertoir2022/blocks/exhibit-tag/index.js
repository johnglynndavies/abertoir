( function( wp ) {
	/**
	 * Registers a new block provided a unique name and an object defining its behavior.
	 */
	var registerBlockType = wp.blocks.registerBlockType;
	/**
	 * Returns a new element of given type. Element is an abstraction layer atop React.
	 */
	var el = wp.element.createElement;
	/**
	 * Retrieves the translation of text.
	 */
	var __ = wp.i18n.__;

	var useSelect = wp.data.useSelect;

	var useEntityProp = wp.coreData.useEntityProp;

	var useBlockProps = wp.blockEditor.useBlockProps;

	var htmlDecode = function(input) {
		var doc = new DOMParser().parseFromString(input, "text/html");
		return doc.documentElement.textContent;
	}

	/**
	 * Every block starts by registering a new block type definition.
	 */
	registerBlockType( 'abertoir2022/exhibit-tag', {
		apiVersion: 2,
		/**
		 * This is the display title for your block, which can be translated with `i18n` functions.
		 * The block inserter will show this name.
		 */
		title: __( 'Exhibit tag block', 'abertoir2022' ),

		icon: 'tag',

		/**
		 * Blocks are grouped into categories to help users browse and discover them.
		 * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
		 */
		category: 'abertoir',

		// get block context
		usesContext: ['postId', 'postType'],

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
			content: {
				type: 'string',
				source: 'text',
			},
			style: {},
		},

		/**
		 * The edit function describes the structure of your block in the context of the editor.
		 * This represents what the editor will render when the block is used.
		 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-edit-save/#edit
		 *
		 * @param {Object} [props] Properties passed from the editor.
		 * @return {Element}       Element to render.
		 */
		edit: function( props ) {
			var blockProps = useBlockProps({className: 'exhibit-tags__tag'});
			var termnames = [];
			var term_ids = wp.data.select("core/editor").getCurrentPostAttribute("exhibit_tags");

			for (let i = 0; i < term_ids.length; i++) {
				// Get the category term with term id = 25
				const term = useSelect( ( select ) =>
					select('core').getEntityRecord( 'taxonomy', 'exhibit_tags', term_ids[i] )
				);
				if (term != undefined) {
					termnames.push(htmlDecode(term.name));
				}
			}

			if (termnames.length) {
				var output = [];
				//var parBlockProps = useBlockProps({className: 'exhibit-tags'});

				for (let i = 0; i < termnames.length; i++) {
					var tag = el(
						'li',
						blockProps,
						termnames[i],
					);
					output.push(tag);
				}

				return el('ul', {class: 'exhibit-tags'}, output);
			}
			else {
				return null;
			}
			
		}

		/**
		 * The save function defines the way in which the different attributes should be combined
		 * into the final markup, which is then serialized by Gutenberg into `post_content`.
		 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-edit-save/#save
		 *
		 * @return {Element}       Element to render.
		 */
		/*save: function() {
			return el(
				'p',
				{},
				__( 'Hello from the saved content!', 'abertoir2022' )
			);
		}*/
	} );
} )(
	window.wp
);
