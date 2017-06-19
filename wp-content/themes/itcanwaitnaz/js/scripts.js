jQuery(function($){

    jQuery(document).on("mouseenter", ".pledge-wrap", function(){
        var $viewshare = jQuery(this).children('div.view-share');
        $viewshare.toggleClass('vsShow vsHide');

    });

    jQuery(document).on("mouseleave", ".pledge-wrap", function(){
        var $viewshare = jQuery(this).children('div.view-share');
        $viewshare.toggleClass('vsShow vsHide');
    });

});