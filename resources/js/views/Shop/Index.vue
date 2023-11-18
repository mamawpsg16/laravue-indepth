<template>
    <!-- <Import/> -->
    <Create @addRow="addData"/>
    <Edit @updateRow="updateData" :details="editDetails"/>
   
    <div class="row">
        <div class="col-10 mx-auto my-2">
            <!-- <label for="isExportAll">Export All</label> &nbsp; -->
            <!-- <input type="checkbox" v-model="isExportAll" id="isExportAll"> -->
            <!-- <br> -->
            <div class="text-end mb-2 ">
                <!-- <button @click="addData">Add Dummy Data</button> -->
                <div class="space-x-2">
                    <button class="btn me-2" @click="exportCsv" :class="{ 'btn-info': rowData.length > 0, 'btn-secondary': !rowData.length}" :disabled="!rowData.length">Export CSV</button>
                    <!-- <button type="button" class="btn btn-primary text-end me-2" data-bs-toggle="modal" data-bs-target="#import-shop-modal">
                        Import
                    </button> -->
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
import Create from './Create.vue';
import Edit from './Edit.vue';
import defaultOptions from '@/composables/gridTableDefaultOptions.js';
import image1 from '../../../assets/images/1.png'
import actionButton from './components/ActionButton.vue';
import Modal from '@/components/Modal/modal.vue';
import { AgGridVue } from "ag-grid-vue3";
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
import Import from './Create.vue';
import axios from 'axios';
import { swalSuccess, swalError, Swal } from '@/composables/sweetAlert.js';
const auth_token = `Bearer ${localStorage.getItem('auth-token')}`;
    export default {
        name:'ShopIndex',
        data(){
            return{
                
                pageSize:10,
                isExportAll:false,
                pageSizeOptions: [10, 100, 500, 1000],
                paginationNumberFormatter: null,
                columnDefs: [
                    { headerName: "Name", field: "name", unSortIcon:true },
                    { headerName: "Description", field: "description", unSortIcon:true },
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
                editDetails:[]
            }
        },
        components: {
            AgGridVue,
            actionButton,
            Import,
            Modal,
            Create,
            Edit
        },
        computed:{
            globalSearchValue(){
                return this.globalSearchFilter;
            }
        },
        async created(){
            await this.getShops();
            this.paginationNumberFormatter = (params) => {
                return '[' + params.value.toLocaleString() + ']';
            };

            
        },
        methods:{
            async getShops(){
                await axios.get('/api/shops',{
                    headers:{
                        'Authorization': auth_token
                    }
                }).then(response =>{
                    if(response.data?.status == 200){
                        console.log(response.data)
                        this.rowData = response.data.shops;
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
            visitShop(data){
                console.log(data,'Visit');
            },
            editShop(data){
                console.log(data,'Edit');
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('edit-shop-modal'));

                this.editDetails = data;
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
            addData(payload){
                this.rowData = [
                    payload,
                    ...this.rowData,
                ];
            },
            updateData(payload){
                  try {
                    const index = this.rowData.findIndex(item => item.id === payload.id);

                    // Create a copy of the array and update the specific element
                    const updatedRowData = [...this.rowData];
                    updatedRowData[index] = { ...payload };

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