const timestamp = {
    hour: "2-digit",
    minute: "numeric",
    second: "numeric",
};

const dateDefaultOptions = {
    year: "2-digit",
    month: "2-digit",
    day: "2-digit",
    hour12: true,
};

const numberDefaultOptions = { 
    style:'decimal', 
    minimumFractionDigits:2
}


export const formatDate = function(locale = undefined, date = '', format="timestamp" ) {
    
    date = !date ? new Date() : new Date(date);
    const newOptions = format == 'date' ? dateDefaultOptions : {...dateDefaultOptions, ...timestamp};

    return new Intl.DateTimeFormat(locale,newOptions).format(date)
}
export const formatNumber = function (locale =undefined, amount = '0.00',  options = {}) {
    const style = Object.keys(options).length === 0  ? numberDefaultOptions : {...numberDefaultOptions, ...options};
    return new Intl.NumberFormat(locale,style).format(amount)
}