(function() {
	
    tinymce.PluginManager.add( 'prothemeswp-jquery-shortcode', function( editor, url ) {

		//When switching from text to visual
		editor.on( 'BeforeSetContent', function( event ) {
			$.each(prothemeswpjQueryShortcode.shortcodes, function (index, shortcode) {
				event.content = wp.shortcode.replace( shortcode, event.content, function( match, left, tag, attrs, slash, content, closing, right ) {
					var openShortcode = '[' + shortcode;
					$.each(match.attrs.named, function(namedAttr, namedValue) {
						openShortcode += ' ' + namedAttr + '="' + namedValue + '"';
					});
					openShortcode += ']';
					if( 'undefined' == typeof match.content ) {
						return openShortcode;
					}
					var matchContent = match.content;
					matchContent = matchContent.replace( new RegExp('&','g'), '&amp;');
					return openShortcode + matchContent + '[/' + shortcode + ']';
				});
			});
		});
	
		//When switching from visual to text
		editor.on( 'PostProcess', function( event ) {
			$.each(prothemeswpjQueryShortcode.shortcodes, function (index, shortcode) {
				event.content = wp.shortcode.replace( shortcode, event.content, function( match, left, tag, attrs, slash, content, closing, right ) {
					var openShortcode = '[' + shortcode;
					$.each(match.attrs.named, function(namedAttr, namedValue) {
						openShortcode += ' ' + namedAttr + '="' + namedValue + '"';
					});
					openShortcode += ']';
					if( 'undefined' == typeof match.content ) {
						return openShortcode;
					}
					var matchContent = match.content;
					matchContent = matchContent.replace( new RegExp('&lt;','g'), '<');
					matchContent = matchContent.replace( new RegExp('&gt;','g'), '>');
					matchContent = matchContent.replace( new RegExp('&#91;','g'), '[');
					matchContent = matchContent.replace( new RegExp('&#93;','g'), ']');
					matchContent = matchContent.replace( new RegExp('&amp;','g'), '&');
					return openShortcode + matchContent + '[/' + shortcode + ']';
				});
			});
		});
	});
	
})();