import * as XLSX from "xlsx";

export default function (data = [], fileName = "abc") {
    const worksheet = XLSX.utils.json_to_sheet(data);

    const workbook = XLSX.utils.book_new();

    XLSX.utils.book_append_sheet(workbook, worksheet);

    XLSX.utils.sheet_add_aoa(worksheet, [["Make", "Model", "Price"]], {
        origin: "A1",
    });

    XLSX.writeFile(workbook, `${fileName}.xlsx`, { compression: true });
}
