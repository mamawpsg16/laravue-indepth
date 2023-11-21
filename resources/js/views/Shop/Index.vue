<template>
    <!-- <Import/> -->
    <Create @addRow="addData"/>
    <Show @updateRow="updateData" :shopId="shop_id"/>
   
    <div class="row">
        <div class="col-10 mx-auto my-2">
            <!-- <label for="isExportAll">Export All</label> &nbsp; -->
            <!-- <input type="checkbox" v-model="isExportAll" id="isExportAll"> -->
            <!-- <br> -->
              <div class="d-flex justify-content-between mb-2">
                <div id="export">
                     <div class="d-flex justify-content-center align-items-center">
                        <select class="form-select" v-model="selectedExportOption" @change="handleExport">
                            <option  value="" disabled selected>Export Options</option>
                            <option  v-for="(option,index) in exportOptions" :value="index" :key="index">{{ option.name }}</option>
                        </select>
                    </div>
                    <!-- <button class="btn me-2" @click="exportCsv" :class="{ 'btn-info': rowData.length > 0, 'btn-secondary': !rowData.length}" :disabled="!rowData.length">Export CSV</button> -->
                </div>
                <div id="import">
                    <button type="button" class="btn btn-primary text-end me-2" data-bs-toggle="modal" data-bs-target="#create-shop-modal">
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
                   domLayout="autoHeight"
                   style="width:100%"
                   :rowHeight="rowHeight"
                   class="ag-theme-alpine container mt-2"
                   animateRows="true"
                   
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
import Create from './Create.vue';
import Show from './Show.vue';
import defaultOptions from '@/composables/gridTableDefaultOptions.js';
import image1 from '../../../assets/images/1.png'
import actionButton from './components/ActionButton.vue';
import Modal from '@/components/Modal/modal.vue';
import { AgGridVue } from "ag-grid-vue3";
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
import { formatDate, formatNumber } from '@/composables/helpers/index.js';
import axios from 'axios';
import { swalSuccess, swalError, Swal } from '@/composables/sweetAlert.js';
const auth_token = `Bearer ${localStorage.getItem('auth-token')}`;
    export default {
        name:'ShopIndex',
        data(){
            return{
                rowHeight:50,
                pageSize:10,
                isExportAll:false,
                pageSizeOptions: [10, 100, 500, 1000],
                paginationNumberFormatter: null,
                columnDefs: [
                    { headerName: "Name", field: "name", unSortIcon:true},
                    { headerName: "Description", field: "description", unSortIcon:true },
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
                product:{
                    name :null,
                    description :null,
                },
                file:null,
                image: image1,
                shop_id:null,
                selectedExportOption:"",
                exportOptions:[
                    {
                        name:'CSV',
                        method:"exportCsv"
                    },
                    // {
                    //     name:'EXCEL',
                    //     method:"exportExcel"
                    // },
                ],
            }
        },
        components: {
            AgGridVue,
            actionButton,
            Modal,
            Create,
            Show
        },
        computed:{
            globalSearchValue(){
                return this.globalSearchFilter;
            }
        },
        async created(){
            this.paginationNumberFormatter = (params) => {
                return '[' + params.value.toLocaleString() + ']';
            };
            await this.getShops();

            
        },
        methods:{
            async getShops(){
                await axios.get('/api/shops',{
                    headers:{
                        'Authorization': auth_token
                    }
                }).then(response =>{
                    if(response.data?.status == 200){
                        console.log(response,'response')
                       this.rowData = response.data.shops.map(shop => ({
                                                            ...shop,
                                                            created_at: formatDate(undefined,shop.created_at,'timestamp'),
                                                            status: shop.active === 1 ? 'Active' : 'Inactive'
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
                this.gridApi = params.api;
                this.gridColumnApi = params.columnApi;
                this.gridApi.paginationSetPageSize(Number(this.pageSize));
                this.gridApi.sizeColumnsToFit(params)
            },
            onFirstDataRendered(params) {
                params.api.sizeColumnsToFit();
            },
            visitShop(data){
                console.log(data,'Visit');
            },
            viewShop(data){
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('edit-shop-modal'));

                this.shop_id = data.id;
                // Show the modal
                modal.show();

            },
            deleteShop(id){
                axios.delete(`/api/shops/${id}`,
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
                            text: 'Shop deleted',
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
                console.log(data);
                Swal.fire({
                    title: "Are you sure?",
                    text:"Delete Shop",
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
            exportCsv() {
                this.gridApi.exportDataAsCsv({columnKeys:['name','description']});
            },
            exportExcel(){
                const worksheet = XLSX.utils.json_to_sheet(rows);
            },
            handleExport(){
                if(this.selectedExportOption != null ){
                    console.log('PASOK')
                    const selectedMethod = this.exportOptions[this.selectedExportOption].method;
                    console.log(selectedMethod);
                    this[selectedMethod]();
                    this.selectedExportOption = ""
                }
            },
            addData(payload){
                const updateData = this.rowData = [
                    payload,
                    ...this.rowData,
                ];
                
                 this.rowData = updateData.map(shop => ({
                                                            ...shop,
                                                            created_at: formatDate(undefined,shop.created_at,'timestamp'),
                                                            status: shop.active === 1 ? 'Active' : 'Inactive'
                                                        }));
            },
            updateData(shop){
                  try {
                    console.log(shop);
                    const index = this.rowData.findIndex(item => item.id === shop.id);

                    // Create a copy of the array and update the specific element
                    const updatedRowData = [...this.rowData];

                    updatedRowData[index] = { 
                                ...shop,
                                created_at: formatDate(undefined,shop.created_at,'timestamp'),
                                status: shop.active === 1 ? 'Active' : 'Inactive'
                    }

                    // Update the original array
                    this.rowData = updatedRowData;
                } catch (error) {
                    console.error('Error updating data:', error);
                }
            },
            uploadImage(e){
                // e.preventDefault()
                const file = event.target.files[0];
                this.file = file;
                if (file) {
                    // Use FileReader to read the file as a data URL
                    const reader = new FileReader();
                    reader.onload = () => {
                        this.image = reader.result; // Set the imageUrl to the data URL
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.image = this.image1; // Reset imageUrl if no file is selected
                }
            },
            saveProduct(){
                const formData = new FormData();
                formData.append('image',this.file);
                formData.append('product',this.product);
                axios.post('/api/product',formData,{
                    headers:{
                        auth_token
                    }
                })
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
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