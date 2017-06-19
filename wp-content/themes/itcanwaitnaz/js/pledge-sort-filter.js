jQuery(function($){
	$('#sortpledgesform').submit(function(){
		var filter = $('#sortpledgesform');
		var displaypledges = $('#displaypledges');
		$.ajax({
			url:filter.attr('action'),
			data:filter.serialize(), // form data
			type:filter.attr('method'), // POST
			beforeSend:function(xhr){
				displaypledges.empty();
				displaypledges.html('<p>Getting Pledges...</p>');
			},
			success:function(data){
				displaypledges.html(data); // insert data
			}
		});
		return false;
	});
});