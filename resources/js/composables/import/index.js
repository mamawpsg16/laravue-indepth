import * as XLSX from "xlsx";

export default function importData(data = []) {
    const workbook = XLSX.read(data, { type: "binary" });

    // Get the first sheet in the workbook (you might need to adjust this based on your specific case)
    const sheetName = workbook.SheetNames[0];
    const sheet = workbook.Sheets[sheetName];

    // Convert the sheet data to an array of objects (each object represents a row)
    const template_details = XLSX.utils.sheet_to_json(sheet, {
        header: 1,
        rawNumbers: false,
        defval: "",
        blankrows:false
    });

    console.log(template_details);

    return template_details;
}
