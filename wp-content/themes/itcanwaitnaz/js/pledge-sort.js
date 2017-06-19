jQuery(function($){
	$('#sortpledges').submit(function(e){
		var data = {
			action: 'icw_ajax_sort',
			query: icwloadonsort.query,
		};
		$.post(icwloadonsort.url, data, function(res) {
			if( res.success) {
				//if(data.scrollload == 1){
					$('.post-listing').empty();
					$('.post-listing').append( res.data );
					//console.log(res);
					//console.log(res.data);
					//console.log(data.scrollload);
				//}
			} else {
				//console.log(res);
			}
		}).fail(function(xhr, textStatus, e) {
			// console.log(xhr.responseText);
		});
		e.preventDefault();
	});

});