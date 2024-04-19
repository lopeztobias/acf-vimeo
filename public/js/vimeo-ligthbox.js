jQuery(document).ready(function($) {
    function cerrarLightbox() {
        $('.mco_lightbox_container_vimeo').hide();
        $('body').css('overflow', 'auto');
    }
    $('.mco_lightbox_container_vimeo').on('click', function(e) {
        if (e.target === this) {
            cerrarLightbox();
        }
    });
    $(document).on('keyup', function(e) {
        if (e.key === "Escape") {
            cerrarLightbox();
        }
    });

    $('.mco_lightbox_container_vimeo__close').on('click', function(e) {
        if (e.target === this) {
            cerrarLightbox();
        }
    });

    $('.mco_vimeo_lightbox').on('click', function(e) {
        e.preventDefault();
        
        const ligthboxid = $(this).data('linked');
       
        $('.mco_lightbox_container_vimeo').hide(); 
        $('#' + ligthboxid).show();
        $('body').css('overflow', 'hidden');
    });
    $('.mco_vimeo_lightbox_portada').on('click', function(e) {
        e.preventDefault();
        
        const ligthboxid = $(this).data('linked');
       
        $('.mco_lightbox_container_vimeo').hide(); 
        $('#' + ligthboxid).show();
        $('body').css('overflow', 'hidden');
    });
});
