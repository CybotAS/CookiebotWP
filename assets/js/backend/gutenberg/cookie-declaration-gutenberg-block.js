( function( blocks, element ) {
	let el                = element.createElement,
		registerBlockType = blocks.registerBlockType;

	registerBlockType(
		'cookiebot/cookie-declaration',
		{
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
		}
	);
}(
	window.wp.blocks,
	window.wp.element,
) );
