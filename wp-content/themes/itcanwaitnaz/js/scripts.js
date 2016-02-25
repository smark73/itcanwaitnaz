jQuery(document).ready(function($){

    jQuery('.pledge-wrap').mouseenter(function(){
        var $viewshare = jQuery(this).children('div.view-share');
        $viewshare.toggleClass('vsShow vsHide');

    });

    jQuery('.pledge-wrap').mouseleave(function(){
        var $viewshare = jQuery(this).children('div.view-share');
        $viewshare.toggleClass('vsShow vsHide');
    });

});