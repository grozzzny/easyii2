var translit = function (text){
    // Символ, на который будут заменяться все спецсимволы
    var space = '-';
    // Берем значение из нужного поля и переводим в нижний регистр
    text = text.toLowerCase();

    // Массив для транслитерации
    var transl = {
        'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh',
        'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
        'о': 'o', 'п': 'p', 'р': 'r','с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h',
        'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'sh','ъ': space, 'ы': 'y', 'ь': space, 'э': 'e', 'ю': 'yu', 'я': 'ya',
        ' ': space,
        'q' : 'q',
        'w' : 'w',
        'e' : 'e',
        'r' : 'r',
        't' : 't',
        'y' : 'y',
        'u' : 'u',
        'i' : 'i',
        'o' : 'o',
        'p' : 'p',
        'a' : 'a',
        's' : 's',
        'd' : 'd',
        'f' : 'f',
        'g' : 'g',
        'h' : 'h',
        'j' : 'j',
        'k' : 'k',
        'l' : 'l',
        'z' : 'z',
        'x' : 'x',
        'c' : 'c',
        'v' : 'v',
        'b' : 'b',
        'n' : 'n',
        'm' : 'm'
    };

    var result = '';
    var curent_sim = '';

    for(i=0; i < text.length; i++) {
        // Если символ найден в массиве то меняем его
        if(transl[text[i]] != undefined) {
            if(curent_sim != transl[text[i]] || curent_sim != space){
                result += transl[text[i]];
                curent_sim = transl[text[i]];
            }
        }
        // Если нет, то оставляем так как есть
        else {
            //result += text[i];
            //curent_sim = text[i];
            result += '';
            curent_sim = '';
        }
    }

    result = result.replace(/^-/, '');
    return result.replace(/-$/, '');
};