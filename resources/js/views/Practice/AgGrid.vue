<template>
<div class="row">
     <div class="col-10 mx-auto my-2">
        <div class="row">
            <ag-grid-vue
                style="width: 100%; height:519px;"
                class="ag-theme-alpine"
                :columnDefs="columnDefs"
                :rowData="rowData"
                :rowHeight="rowHeight"
                :defaultColDef="defaultColDef"
                onFirstDataRendered: onFirstDataRendered
                :pagination="true"
            >
            </ag-grid-vue>
        </div>
     </div>
</div>
</template>

<script>
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
import { AgGridVue } from "ag-grid-vue3";

export default {
  name: "App",
  components: {
    AgGridVue,
  },
  setup() {
    return {
        onFirstDataRendered(params) {
            params.api.sizeColumnsToFit();
        },
        rowHeight:70,
        defaultColDef: {
    sortable: true,
    resizable: true,
    // filter: true,
},
      columnDefs: [
        { headerName: "Make", width: '200px', field: "make" },
        { headerName: "Model", initiawidthlWidth: '200', field: "model" },
        { headerName: "Price", width: '200',field: "price" },
        { headerName: "Description", width: '200', field: "description", wrapText : true },
        { headerName: "Buble", field: "price" },
      ],
      rowData: [
        { make: "Toyota", model: "Celica", price: 35000, description:"Hello World Its me again your boy", color:"Black Gray Purple" },
        { make: "Ford", model: "Mondeo", price: 32000, description:"Lil Peep X XXXtentacion X JuiceWlrd", color:"White Blue Yellow" },
        { make: "Porsche", model: "Boxster", price: 72000, description: "Mac X Jcole", color:"Orange Gray Pink"},
      ],
    };
  },
};
</script>