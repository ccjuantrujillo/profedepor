(function ($){
    var currentPosition = null,
        pag = null,
        container = null,
        slideInner = null;

    function getSlide(position){
        currentPosition = position || currentPosition;

        $.ajax({
            url: '/ranking/listem',
            data: { position: position },
            beforeSend: null,
            success: function (response){
                slideInner.html('<div class="slide" style="display: none">' + response.slide + '</div>');

                $('.slide').fadeIn(800);

                $('#rank_fecha_desc').html('Fecha ' + response.fecha_desc + ': ' + response.primera_fecha);

                pag = response.pag;
                $('#leftControl').css('visibility', (pag.prev && pag.prev > 0) ? 'visible' : 'hidden');
                $('#rightControl').css('visibility', (pag.next && pag.next > 0) ? 'visible' : 'hidden');
            }
        });
    }

    $.fn.slider = function (options){
        var self = $(this);
        self.html('<div id="slidesContainer"></div>');

        $('.control').click(function (e){
            if($(this).attr('id')=='rightControl'){
                currentPosition = pag.next && pag.next > 0 ? pag.next : null;
            } else {
                currentPosition = pag.prev && pag.prev > 0 ? pag.prev : null;
            }

            getSlide(currentPosition);
        });


        container = $('#slidesContainer').html('<div id="slideInner"></div>');
        slideInner = $('#slideInner');

        getSlide();
    }
})(jQuery);

$(function (){
    $('#slideshow').slider();
});