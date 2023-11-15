<template>
    <div class="row">
        <div class="col-10 mx-auto my-2">
            <div class="d-flex justify-content-between">
                <div id="pageSizeContainer">
                    Page Size: 
                    <select v-model="pageSize" @change="onPageSizeChanged">
                        <option :value="size" v-for="size in pageSizeOptions">{{ size }}</option>
                    </select>
                </div>
                <div id="quickFilterContainer">
                    <span>Quick Filter: </span>
                    <input type="text" id="filter-text-box" v-model="globalSearchFilter" placeholder="Filter..." @input="onGlobalSearch">
                </div>
            </div>
            <ag-grid-vue
                style="width: 100%; height: 500px;"
                class="ag-theme-alpine container mt-2"
                :columnDefs="columnDefs"
                :rowData="rowData"
                :defaultColDef="defaultColDef"
                @grid-ready="onGridReady"
                :pagination="true"
            >
            </ag-grid-vue>
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import defaultOptions from '@/composables/gridTableDefaultOptions.js';
import { AgGridVue } from "ag-grid-vue3";
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
    export default {
        data(){
            return{
                pageSize:10,
                pageSizeOptions: [10, 100, 500, 1000],
                columnDefs: [
                    { headerName: "Make", field: "make", unSortIcon:true },
                    { headerName: "Model", field: "model", unSortIcon:true },
                    { headerName: "Price", field: "price", unSortIcon:true },
                    
                ],
                rowData: [
                    { id:1, make: "Toyota", model: "Celica", price: 35000 },
                    { id:2, make: "Ford", model: "Mondeo", price: 32000 },
                    { id:1, make: "Porsche", model: "Boxter", price: 72000 },
                    { id:1, make: "Toyota", model: "Celica", price: 35000 },
                    { id:1, make: "Ford", model: "Mondeo", price: 32000 },
                    { id:1,  make: "Porsche", model: "Boxter", price: 72000 },
                    { id:1, make: "Porsche", model: "Boxter", price: 72000 },
                    { id:1, make: "Toyota", model: "Celica", price: 35000 },
                    { id:1, make: "Ford", model: "Mondeo", price: 32000 },
                    { id:1,make: "Porsche", model: "Boxter", price: 72000 },
                    { id:1,make: "Porsche", model: "Boxter", price: 72000 },
                    { id:1,make: "Toyota", model: "Celica", price: 35000 },
                    { id:1,make: "Ford", model: "Mondeo", price: 32000 },
                    { id:1,make: "Porsche", model: "Boxter", price: 72000 },
                    { id:1,make: "Porsche", model: "Boxter", price: 72000 },
                    { id:1,make: "Toyota", model: "Celica", price: 35000 },
                    { id:1,make: "Ford", model: "Mondeo", price: 32000 },
                    { id:1,make: "Porsche", model: "Boxter", price: 72000 },
                    { id:1,make: "Porsche", model: "Boxter", price: 72000 },
                    { id:1,make: "Toyota", model: "Celica", price: 35000 },
                    { id:1, make: "Ford", model: "Mondeo", price: 32000 },
                    { id:1,make: "Porsche", model: "Boxter", price: 72000 },
                ],
                defaultColDef: {
                  ...defaultOptions
                },
                gridApi:null,
                globalSearchFilter:null
            }
        },
        components: {
            AgGridVue,
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
                this.gridApi.paginationSetPageSize(Number(this.pageSize));
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>