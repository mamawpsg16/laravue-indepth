<template>
    <div class="row">
        <div class="col-10 mx-auto my-2">
            <!-- <label for="isExportAll">Export All</label> &nbsp; -->
            <!-- <input type="checkbox" v-model="isExportAll" id="isExportAll"> -->
            <!-- <br> -->
            <div class="text-end">
                <!-- <button @click="addData">Add Dummy Data</button> -->
                <button class="mb-2 btn btn-sm btn-secondary" @click="exportCsv">Export CSV</button>
           </div>
           <div class="row">
               <div class="d-flex justify-content-between" @submitEvent="getDetails">
                   <div id="pageSizeContainer">
                       Page Size: 
                       <select v-model="pageSize" @change="onPageSizeChanged">
                           <option :value="size" v-for="size in pageSizeOptions">{{ size }}</option>
                       </select>
                   </div>
                   <div id="quickFilterContainer">
                       <span>Quick Filter: </span>
                       <input type="text" v-model="globalSearchFilter" placeholder="Filter..." @input="onGlobalSearch">
                   </div>
               </div>
               <ag-grid-vue
                   style="width: 100%; height:519px;"
                   class="ag-theme-alpine container mt-2"
                   :columnDefs="columnDefs"
                   :rowData="rowData"
                   :defaultColDef="defaultColDef"
                   :context="context" 
                   @grid-ready="onGridReady"
                   :pagination="true"
               >
               </ag-grid-vue>
               <router-view></router-view>
           </div>
        </div>
    </div>
</template>

<script>
import defaultOptions from '@/composables/gridTableDefaultOptions.js';
import actionButton from '@/components/AgGridTable/action.vue';
import { AgGridVue } from "ag-grid-vue3";
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
    export default {
        data(){
            return{
                pageSize:10,
                isExportAll:false,
                pageSizeOptions: [10, 100, 500, 1000],
                columnDefs: [
                    { headerName: "Make", field: "make", unSortIcon:true },
                    { headerName: "Model", field: "model", unSortIcon:true },
                    { headerName: "Price", field: "price", unSortIcon:true },
                    {
                        headerName: "Action",
                        cellRenderer: "actionButton",
                    }
                    
                ],
                rowData: [
                    { id:1, make: "Toyota", model: "Celica", price: 1000, },
                    { id:2, make: "Ford", model: "Mondeo", price: 2000 },
                    { id:3, make: "Porsche", model: "Porsche A", price: 2100 },
                    { id:4, make: "Buggati", model: "Buggati A", price: 3500 },
                    { id:5, make: "Vios", model: "Vios A", price: 6000 },
                    { id:6,  make: "BMW", model: "BMW A", price: 500 },
                    { id:7, make: "GTR", model: "GTR A", price: 777 },
                    { id:8, make: "Honda", model: "Honda A", price: 666 },
                    { id:9, make: "Grandia", model: "Grandia A", price: 7000 },
                    { id:10,make: "Fortuner", model: "Fortuner B", price: 5432 },
                    { id:11,make: "Hi-Ace", model: "Ace B", price: 8900 },
                    { id:12,make: "Shiza", model: "Shiza A", price: 5000 },
                ],
                defaultColDef: {
                  ...defaultOptions
                },
                gridApi:null,
                gridColumnApi:null,
                globalSearchFilter:null
            }
        },
        components: {
            AgGridVue,
            actionButton
        },
        computed:{
            globalSearchValue(){
                return this.globalSearchFilter;
            }
        },
        methods:{
            onPageSizeChanged() {
                this.gridApi.paginationSetPageSize(Number(this.pageSize));
            },
            onGlobalSearch() {
                this.gridApi.setQuickFilter(this.globalSearchValue);
            },
            onGridReady(params) {
                console.log(params,'PARAMS')
                this.gridApi = params.api;
                this.gridColumnApi = params.columnApi;
                this.gridApi.paginationSetPageSize(Number(this.pageSize));
            },
            getRowData(data){
                console.log(data,'fuckkkk');
            },
            exportCsv() {
                this.gridApi.exportDataAsCsv({columnKeys:['make','model','price']});
            },
            addData(){
                this.rowData = [
                    ...this.rowData,
                    { id: 12, make: "Dummy", model: "Dummy A", price: 6666 }
                ];
            }
        },
        beforeMount() {
            this.context = {
                componentParent: this
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>