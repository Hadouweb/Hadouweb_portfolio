/**
 * Created by Nicolas on 17/06/2014.
 */
(function( $ ) {
    $( function() {

        var $container = $('.isotope').isotope({
            itemSelector: '.element-item',
            layoutMode: 'fitRows'
        });

        $('#filters').on( 'click', 'button', function() {
            var filterValue = $( this ).attr('data-filter');
            $container.isotope({ filter: filterValue });
        });

        // Fancybox
        $(".fancybox").fancybox({
            maxWidth	: 800,
            maxHeight	: 600,
            fitToView	: false,
            width		: '70%',
            height		: '70%',
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'elastic',
            closeEffect	: 'elastic'
        });

        $("#filters button").click(function(){
            var filter = $(this).data( "filter" );
            var item = $('.isotope').find('.element-item' + filter);
            $(item).find('.fancybox').attr('rel', filter);
        });


    });
})(jQuery);