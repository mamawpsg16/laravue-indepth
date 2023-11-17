export function mapKeys(keys, data=[]){
    const mapData = data.map((item, index) => {
        const mappedData = {};
        item.forEach((details, index) => {
            mappedData[keys[index]] = details;
        });
        return mappedData
    });
    return mapData;
}