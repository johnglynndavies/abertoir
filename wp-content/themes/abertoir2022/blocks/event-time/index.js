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

	var useEntityProp = wp.coreData.useEntityProp;

	var useBlockProps = wp.blockEditor.useBlockProps;

	var months = [
		'Jan',
		'Feb',
		'March',
		'April',
		'May',
		'June',
		'July',
		'Aug',
		'Sept',
		'Oct',
		'Nov',
		'Dec'
	];

	var days = [
		'Sun',
		'Mon',
		'Tue',
		'Wed',
		'Thu',
		'Fri',
		'Sat'
	];

	/**
	 * Every block starts by registering a new block type definition.
	 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/#registering-a-block
	 */
	registerBlockType( 'abertoir2022/event-time', {
		apiVersion: 2,
		/**
		 * This is the display title for your block, which can be translated with `i18n` functions.
		 * The block inserter will show this name.
		 */
		title: __( 'Exhibit event time block', 'abertoir2022' ),

		icon: 'calendar',

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
			className: {
				type: 'string',
				source: "text",
			},
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
			var postType = props.context.postType;
			var entityProp = useEntityProp( 'postType', postType, 'meta' );
			var meta = entityProp[0];
			var blockProps = useBlockProps({className: 'exhibit-header__event-time'});

			if (meta.start_date.length) {
				var startTime = new Date(meta.start_date[0]['start_time']);
				var date = startTime.getDate();
				var monthName = months[startTime.getMonth()];
				var dayName = days[startTime.getDay()];
				var startTimeFormatted = `${dayName} ${date} ${monthName} ${startTime.getHours()}:${startTime.getMinutes()}`;
			}
			else {
				var startTimeFormatted = __('Set event date start and end times in sidebar', 'abertoir2022');
			}

			return el(
				'p',
				blockProps,
				startTimeFormatted,
			);
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
})(window.wp);
