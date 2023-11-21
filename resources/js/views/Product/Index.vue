<template>
    <Import @addRow="addData"/>
    <Create @addRow="addData"/>
    <Show :productId="product_id" @updateRow="updateData"/>
    <div class="row">
        <div class="col-10 mx-auto my-2">
            <!-- <label for="isExportAll">Export All</label> &nbsp; -->
            <!-- <input type="checkbox" v-model="isExportAll" id="isExportAll"> -->
            <!-- <br> -->
                <!-- <button @click="addData">Add Dummy Data</button> -->
            <div class="row">
                <div class="d-flex justify-content-between mb-2">
                    <div id="export">
                        <div class="d-flex justify-content-center align-items-center">
                            <select class="form-select" v-model="selectedExportOption" @change="handleExport">
                                <option  value="" disabled selected>Export Options</option>
                                <option  v-for="(option,index) in exportOptions" :value="index" :key="index">{{ option.name }}</option>
                            </select>
                        </div>
                        <!-- <button class="btn btn-secondary me-2" @click="exportCsv" :class="{ 'btn-info': rowData.length > 0, 'btn-secondary': !rowData.length}" :disabled="!rowData.length" >Export Csv</button>
                        <button class="btn btn-secondary me-2" @click="exportExcel" :class="{ 'btn-info': rowData.length > 0, 'btn-secondary': !rowData.length}" :disabled="!rowData.length" >Export Excel</button> -->
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
                   class="ag-theme-alpine mt-2"
                   :columnDefs="columnDefs"
                   :rowData="rowData"
                   :defaultColDef="defaultColDef"
                   :context="context" 
                   :paginationNumberFormatter="paginationNumberFormatter"
                   @grid-ready="onGridReady"
                   :rowHeight="rowHeight"
                   :pagination="true"
               >
               </ag-grid-vue>
           </div>
        </div>
    </div>
</template>

<script>
import defaultOptions from '@/composables/gridTableDefaultOptions.js';
import actionButton from './components/ActionButton.vue';
import Modal from '@/components/Modal/modal.vue';
import { AgGridVue } from "ag-grid-vue3";
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
import Import from './components/Import.vue';
import Export from '@/composables/export/index.js';
import axios from 'axios';
import Create from './Create.vue';
import Show from './Show.vue';
import { formatDate } from '@/composables/helpers/index.js';
import { swalSuccess, swalError, Swal } from '@/composables/sweetAlert.js';
const auth_token = `Bearer ${localStorage.getItem("auth-token")}`;
    export default {
        name:'ProductIndex',
        data(){
            return{
                rowHeight:50,
                pageSize:10,
                isExportAll:false,
                pageSizeOptions: [10, 100, 500, 1000],
                paginationNumberFormatter: null,
                selectedExportOption:"",
                exportOptions:[
                    {
                        name:'CSV',
                        method:"exportCsv"
                    },
                    {
                        name:'EXCEL',
                        method:"exportExcel"
                    },
                ],
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
                product_id:null
            }
        },
        components: {
            AgGridVue,
            actionButton,
            Import,
            Modal,
            Create,
            Show
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
                        console.log(response.data?.products,'response.data?.products')
                        this.rowData = response.data?.products.map(product => ({
                                                            ...product,
                                                            created_at: formatDate(undefined,product.created_at,'timestamp'),
                                                            status: product.active === 1 ? 'Active' : 'Inactive'
                                                        }));
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
                this.gridApi.sizeColumnsToFit()
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
            handleExport(){
                if(this.selectedExportOption != null){
                    const selectedMethod = this.exportOptions[this.selectedExportOption].method;
                    console.log(selectedMethod,'selectedMethod')
                     this[selectedMethod]();
                }
            },
            addData(payload){
                if(payload == 'import'){
                    this.getProducts();
                }else{
                    console.log(payload,'PAYLOAD')
                    console.log([
                        payload,
                        ...this.rowData,
                    ],'SHEESH')
                    const updateData = this.rowData = [
                        payload,
                        ...this.rowData,
                    ];
                    
                     this.rowData = updateData.map(product => ({
                                                                ...product,
                                                                created_at: formatDate(undefined,product.created_at,'timestamp'),
                                                                status: product.active === 1 ? 'Active' : 'Inactive'
                                                            }));
                }
          
            },
            viewProduct(params){
                const id = document.getElementById('show-product-modal');
                const modal = bootstrap.Modal.getOrCreateInstance(id);
                this.product_id = params.id;
                modal.show();
            },
            deleteShop(id){
                axios.delete(`/api/products/${id}`,
                { 
                    headers:{
                        'Authorization': auth_token
                    }
                })
                .then(response =>{
                    if(response.data?.status == 200){
                        this.rowData = this.rowData.filter(item => item.id != id);  
                        swalSuccess({ 
                            icon: 'success',
                            text: 'Product deleted',
                            title: response.data?.message,
                            showConfirmButton: false,
                        })
                    }
                }).catch(error =>{
                    if(error.response?.data?.errors){
                        swalError({
                            icon: 'error',
                            title: error.response?.data.message,
                            text: "Something went wrong!",
                            showConfirmButton: false,
                        })
                    }
                })
            },
            deleteConfirmation(data){
                Swal.fire({
                    title: "Are you sure?",
                    text:"Delete Product",
                    showCancelButton: true,
                    confirmButtonText: "Confirm",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.deleteShop(data.id)
                    } else if (result.isDenied) {
                        Swal.fire("Changes are not saved", "", "info");
                    }
                });
            },
            updateData(payload){
                const index = this.rowData.findIndex(data => data.id === payload.id);
                // Create a copy of the array and update the specific element
                const updatedRowData = [...this.rowData];
                
                updatedRowData[index] = {
                        ...payload,
                        created_at: formatDate(undefined,payload.created_at,'timestamp'),
                        status: payload.active === 1 ? 'Active' : 'Inactive'
                    };

                // Update the original array
                this.rowData = updatedRowData;
            }

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