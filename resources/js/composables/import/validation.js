export function checkEmptyColumns(except= [], data){
    console.log(data,'DATA');
    console.log(except.includes(''))
    let empty_column = [];
    for (const key in data) {
        console.log('checkEmptyColumns key', key)
        for (const property in data[key]) {
            if(except.length > 0){
                for (const exclude of except) {
                    console.log('checkEmptyColumns property', key, property);
                    if (property != exclude) {
                        if(data[property] === '' || data[property] == null || data[property] == undefined){
                            empty_column.push(data[property]) 
                            break;
                        }
                    }
                }
            }else{
                if(data[key][property] === '' || data[key][property] == null || data[key][property] == undefined){
                    empty_column.push(property) 
                    break;
                }
            }
        }
    }
    return empty_column;
}