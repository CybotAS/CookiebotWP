( function( blocks, element ) {
	let el                = element.createElement,
		registerBlockType = blocks.registerBlockType;

	registerBlockType(
		'cookiebot/cookie-declaration',
		{
			apiVersion: 3,
			title: 'Cookie Declaration',
			keywords: ['cookiebot'],
			icon: 'media-spreadsheet',
			category: 'widgets',
			edit: function(props) {
				return el(
					'i',
					{},
					'Cookiebot Cookie Declaration'
				);
			},
			save: function() {
				return null;
			},
		}
	);
}(
	window.wp.blocks,
	window.wp.element,
) );
