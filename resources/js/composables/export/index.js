import * as XLSX from "xlsx";
import { formatDate } from '@/composables/helpers/index.js';
export const exportExcel =  (data = [], fileName = "Excel") => {
    const worksheet = XLSX.utils.json_to_sheet(data);

    const workbook = XLSX.utils.book_new();

    XLSX.utils.book_append_sheet(workbook, worksheet);

    XLSX.utils.sheet_add_aoa(worksheet, [["Make", "Model", "Price"]], {
        origin: "A1",
    });

    XLSX.writeFile(workbook, `${fileName}.xlsx`, { compression: true });
}


export const exportCsv = (data, fileName = formatDate(new Date()) , headers = [], separator = ",") =>{
    const worksheet = XLSX.utils.json_to_sheet(data);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.sheet_add_aoa(worksheet, [headers], { origin: "A1" });
    XLSX.utils.book_append_sheet(workbook, worksheet, "Dates");
    XLSX.writeFile(workbook, fileName+".csv", { compression: true, FS:separator });
} 