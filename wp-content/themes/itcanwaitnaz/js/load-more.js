jQuery(document).ready(function($){

	//$('.post-listing').append( '<span class="load-more"></span>' );
	var button = $('.load-more');
	//already showing pg 1, init with pg 2
	var page = 2;
	var loading = false;
	var scrollHandling = {
	    allow: true,
	    reallow: function() {
	        scrollHandling.allow = true;
	    },
	    delay: 200 //(milliseconds) adjust to the highest acceptable value
	};

	$(window).scroll(function(){
		if( ! loading && scrollHandling.allow ) {
			scrollHandling.allow = false;
			setTimeout(scrollHandling.reallow, scrollHandling.delay);
			var offset = $(button).offset().top - $(window).scrollTop();
			if( 2000 > offset ) {
				loading = true;
				var data = {
					action: 'be_ajax_load_more',
					page: page,
					query: beloadmore.query,
					scrollload: beloadmore.scrollload
				};
				$.post(beloadmore.url, data, function(res) {
					if( res.success) {
						//if(data.scrollload == 1){
							$('.post-listing').append( res.data );
							$('.post-listing').append( button );
							page = page + 1;
							loading = false;
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

			}
		}
	});

});