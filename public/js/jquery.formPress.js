$.fn.formPress = function (e){
    var self = $(this);
    self.keypress(function (e){
        var keyCode = e.keyCode || e.which;
        if(keyCode == 13)
            self.submit();
    });

    return self;
};