<template>
    <!-- <Modal class="modal-xl" targetModal="import-product-modal" modaltitle="Import Product Details" :backdrop="true">
        <template #body>
            <form>
                <div class="row">
                    <div class="">
                        <input class="form-control" type="file" id="formFile" @change="uploadImage" accept="image/png, image/jpeg">
                    </div>
                </div>
            </form>
        </template>
        <template #footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click.prevent="saveProduct">Save changes</button>
        </template>
    </Modal> -->
    <Import/>
    <!-- <Modal class="modal-xl" targetModal="product-modal" modaltitle="Create">
        <template #body>
            <form>
                <div class="d-flex flex-column mb-2">
                    <div class="col-6 mx-auto text-center mb-2">
                        <img :src="image" class="rounded  img-fluid img-thumbnail" style="width:450px;" alt="">
                    </div>
                    <div class="col-4 mx-auto">
                        <input class="form-control" type="file" id="formFile" @change="uploadImage" accept="image/png, image/jpeg">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                      <label for="exampleInputEmail1" class="form-label">Name</label>
                      <input type="email" class="form-control" v-model="product.name" aria-describedby="emailHelp">
                    </div>
                    <div class="col-6 d-flex flex-column">
                        <label for="exampleInputPassword1" class="form-label">Description</label>
                        <textarea name="" rows="5" v-model="product.description" class="form-control"></textarea>
                    </div>
                </div>
            </form>
        </template>
        <template #footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click.prevent="saveProduct">Save changes</button>
        </template>
    </Modal> -->
    <div class="row">
  
        <div class="col-10 mx-auto my-2">
            <!-- <label for="isExportAll">Export All</label> &nbsp; -->
            <!-- <input type="checkbox" v-model="isExportAll" id="isExportAll"> -->
            <!-- <br> -->
            <div class="text-end mb-2 ">
                <!-- <button @click="addData">Add Dummy Data</button> -->
                <div class="space-x-2">
                    <button class="btn btn-secondary me-2" @click="exportCsv">Export CSV</button>
                    <button type="button" class="btn btn-primary text-end me-2" data-bs-toggle="modal" data-bs-target="#import-product-modal">
                        Import
                    </button>
                    <button type="button" class="btn btn-primary text-end me-2" data-bs-toggle="modal" data-bs-target="#product-modal">
                        Create
                    </button>
                </div>
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
import Modal from '@/components/Modal/modal.vue';
import { AgGridVue } from "ag-grid-vue3";
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
import Import from './components/Import.vue';
import axios from 'axios';
const authToken = {'Authorization': `Bearer Token`};
    export default {
        data(){
            return{
                
                pageSize:10,
                isExportAll:false,
                pageSizeOptions: [10, 100, 500, 1000],
                paginationNumberFormatter: null,
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
                globalSearchFilter:null,
                product:{
                    name :null,
                    description :null,
                },
                file:null,
                image: image1,
            }
        },
        components: {
            AgGridVue,
            actionButton,
            Import
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
                        authToken
                    }
                }).then(response =>{

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
            addData(){
                this.rowData = [
                    ...this.rowData,
                    { id: 12, make: "Dummy", model: "Dummy A", price: 6666 }
                ];
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
                        authToken
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