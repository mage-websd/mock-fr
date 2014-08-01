// prevent conflict with prototype.js
var $j = jQuery.noConflict();

$j(document).ready(function() {
    $j("#main-img").elevateZoom({
        gallery:'gallery',
        cursor: 'pointer',
        galleryActiveClass: 'active',
        imageCrossfade: true,
        loadingIcon: 'http://www.elevateweb.co.uk/spinner.gif',
        zoomWindowOffetx: 50,
        zoomWindowOffety: -50,
        tint: true,
        tintColour:'#000',
        tintOpacity: 0.5
    });


});



