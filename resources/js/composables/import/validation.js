export function checkEmptyColumns(except= [], data){
    let empty_column = [];
    for (const key in data) {
        for (const property in data[key]) {
            if(except.length > 0){
                for (const exclude of except) {
                    if (property != exclude) {
                        if(data[property] === '' || data[property] == null || data[property] == undefined){
                            empty_column.push(data[property]) 
                            console.log(empty_column,'1');
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
        if(empty_column.length){
            break;
        }
    }
    return empty_column;
}