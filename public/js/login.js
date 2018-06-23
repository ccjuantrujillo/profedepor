(function (){
    var win = null;
    $.fn.openWinSign = function (options){
        $(this).click(function (){
            var _options = {
                url: '',
                callback: '/politica/red/',
                name: 'openWinSign',
                vars: { menubar: 'no', width: 790, height: 360, toolbar: 'no' }
            };

            if(typeof options == 'string')
                options = { url: options };

            options.vars = $.extend(_options.vars, options.vars || {});
            options = $.extend(_options, options || {});

            var winOptions = [], j = 0;
            for(var i in options.vars){
                winOptions[j] = i + '=' + options.vars[i];
                j++;
            }

            winOptions = winOptions.join(',');
            // return alert(winOptions);
            win = window.open(options.url, options.name, winOptions);
        });
    };
})();

$.extend($.modal.defaults, {
	escClose: false
});

function openTerminos(){
    $.get('/politica/red', function (response){
        $(response).modal();
    });
}

$(function (){
    $('#btnGoogle').openWinSign({
        url: '/google/redirect/',
        vars: { height: 400 },
        name: 'Google'
    });
});