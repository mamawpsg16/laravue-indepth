<template>
    <Import/>
    <Create/>
    <div class="row">
        <div class="col-10 mx-auto my-2">
            <!-- <label for="isExportAll">Export All</label> &nbsp; -->
            <!-- <input type="checkbox" v-model="isExportAll" id="isExportAll"> -->
            <!-- <br> -->
                <!-- <button @click="addData">Add Dummy Data</button> -->
            <div class="d-flex justify-content-between mb-2">
                <div id="export">
                    <button class="btn btn-secondary me-2" @click="exportCsv">Export Csv</button>
                    <button class="btn btn-secondary me-2" @click="exportExcel">Export Excel</button>
                </div>
                <div id="import">
                    <button type="button" class="btn btn-primary text-end me-2" data-bs-toggle="modal" data-bs-target="#import-product-modal">
                        Import
                    </button>
                    <button type="button" class="btn btn-primary text-end me-2" data-bs-toggle="modal" data-bs-target="#create-product-modal">
                        Create
                    </button>
                </div>
           </div>
           <div class="row">
               <div class="d-flex justify-content-between" @submitEvent="getDetails">
                   <div id="pageSizeContainer">
                       Page Size: 
                       <select v-model="pageSize" @change="onPageSizeChanged">
                           <option :value="size" v-for="(size, index) in pageSizeOptions" :key="index">{{ size }}</option>
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
                   :paginationNumberFormatter="paginationNumberFormatter"
                   @grid-ready="onGridReady"
                   :pagination="true"
               >
               </ag-grid-vue>
           </div>
        </div>
    </div>
</template>

<script>
import defaultOptions from '@/composables/gridTableDefaultOptions.js';
import image1 from '../../../assets/images/1.png'
import actionButton from '@/components/AgGridTable/action.vue';
// import image from './components/Image.vue';
import Modal from '@/components/Modal/modal.vue';
import { AgGridVue } from "ag-grid-vue3";
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
import Import from './components/Import.vue';
import Export from '@/composables/export/index.js';
import axios from 'axios';
import Create from './Create.vue';
const auth_token = `Bearer ${localStorage.getItem("auth-token")}`;
    export default {
        name:'ProductIndex',
        data(){
            return{
                pageSize:10,
                isExportAll:false,
                pageSizeOptions: [10, 100, 500, 1000],
                paginationNumberFormatter: null,
                columnDefs: [
                //    {
                //         headerName: "Image",
                //         cellRenderer: "image",
                //     },
                    { headerName: "Name", field: "name", unSortIcon:true},
                    { headerName: "Price", field: "price", unSortIcon:true},
                    { headerName: "Quantity", field: "quantity", unSortIcon:true },
                    { headerName: "Created At", field: "created_at", unSortIcon:true },
                    { headerName: "Status", field: "status", unSortIcon:true },
                    {
                        headerName: "Action",
                        cellRenderer: "actionButton",
                    }
                    
                ],
                rowData: [],
                defaultColDef: {
                  ...defaultOptions
                },
                gridApi:null,
                gridColumnApi:null,
                globalSearchFilter:null,
            }
        },
        components: {
            AgGridVue,
            actionButton,
            Import,
            Modal,
            Create,
            // image
        },
        computed:{
            globalSearchValue(){
                return this.globalSearchFilter;
            }
        },
        async created(){
            await this.getProducts();
            this.paginationNumberFormatter = (params) => {
                return '[' + params.value.toLocaleString() + ']';
            };

            
        },
        methods:{
            async getProducts(){
                await axios.get('/api/products',{
                    headers:{
                        'Authorization': auth_token
                    }
                }).then(response =>{
                    if(response.data?.status == 200){
                        this.rowData = response.data?.products;
                    }
                }).catch(response=>{

                })
            },
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
            exportExcel() {
                const data = this.rowData.map(({ id, ...rest}) => rest);
                Export(data);
            },
            create(){
                // const modalId = document.getElementById('product-modal');
                // const modal = bootstrap.Modal.getOrCreateInstance(modalId)
                // modal.show()
            },
            addData(){
                this.rowData = [
                    ...this.rowData,
                    { id: 12, make: "Dummy", model: "Dummy A", price: 6666 }
                ];
            },
        },
        beforeMount() {
            this.context = {
                componentParent: this
            }
        },
        mounted() {
        },
    }
</script>

<style scoped>
</style>