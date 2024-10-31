jQuery(function($){

	$.each( prothemeswpjQueryShortcode.javascripts, function(index, value) {
		value = value.replace( new RegExp( '&lt;', 'g' ), '<', value);
		value = value.replace( new RegExp( '&gt;', 'g' ), '>', value);
		eval(value);
	});

});