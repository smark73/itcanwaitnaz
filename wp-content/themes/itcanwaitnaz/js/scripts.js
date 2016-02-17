jQuery(document).ready(function($){

    // remove some jquery mobile styliing that interferes
    //$(document).bind('mobileinit',function(){
        //$.mobile.keepNative = "select,input,button,a";
    //});

    //init isotope and assign to var
    var $grid = $('.grid').isotope({
        itemSelector: '.grid-item',
        layoutMode: 'masonry',
        masonry: {
            columnWidth: 300
        }
    });

    //layout grid after each image loads
    //$grid.imagesLoaded().progress( function(){
        //$grid.isotope('layout');
    //});

    // filter items on button click
    $('.posts-hdr').on( 'click', 'a', function() {
      var filterValue = $(this).attr('data-filter');
      // use filter function if class value matches
      $grid.isotope({ filter: filterValue });

    });

});