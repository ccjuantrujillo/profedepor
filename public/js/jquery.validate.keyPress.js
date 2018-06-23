(function ($){
    $.extend($.fn, {
        formPress: function (e){
            var self = $(this);
            self.keypress(function (e){
                var keyCode = e.keyCode || e.which;
                if(keyCode == 13)
                    self.submit();
            });

            return self;
        },

        number: function (){
            var self = $(this);

            self.bind('keypress', function (e){
                var charCode = e.keyCode || e.which;
                if (charCode <= 13)
                    return true;

                var keyChar = String.fromCharCode(charCode);
                var re = /^\d+$/
                return re.test(keyChar);
            });
        }
    });
})(jQuery);