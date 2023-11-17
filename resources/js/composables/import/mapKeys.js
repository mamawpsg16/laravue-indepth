export function mapKeys(keys, data=[]){
    const mapData = data.map((item, index) => {
        const mappedData = {};
        console.log(item);
        item.forEach((details, index) => {
            console.log(details,'details')
            mappedData[keys[index]] = details;
        });
        return mappedData
    });
    return mapData;
}