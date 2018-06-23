function _attrib(obj, reg){
    var reg = reg || new RegExp();
    var content = '';
    for(var i in obj)
        if(reg.test(i))
            content += '[Object].' + i + ' = ' + obj[i] + '\n';

    return alert(content);
}