<template>
    <Import @addRow="addData"/>
    <Create @addRow="addData"/>
    <Show :productId="product_id" @updateRow="updateData"/>
    <div class="row" >
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
                        <!-- <button class="btn btn-secondary me-2" @click="exportCsv" :class="{ 'btn-info': data.length > 0, 'btn-secondary': !data.length}" :disabled="!data.length" >Export Csv</button>
                        <button class="btn btn-secondary me-2" @click="exportExcel" :class="{ 'btn-info': data.length > 0, 'btn-secondary': !data.length}" :disabled="!data.length" >Export Excel</button> -->
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
            <Dataset :data="data" :columns="columns">
                <template #body="{ data, index }">
                    <tr>
                        <td>{{ data.name }}</td>
                        <td>{{ data.price }}</td>
                        <td>{{ data.quantity }}</td>
                        <td>{{ data.created_at }}</td>
                        <td>{{ data.status }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary me-2" @click="viewProduct(data.id)">View</button>
                            <button @click="deleteConfirmation(data.id)" class="btn btn-sm btn-danger me-2">Delete</button>
                        </td>
                    </tr>
                </template>
            </Dataset>
        </div>
    </div>
</template>

<script>
import LoadingSpinner from '@/components/Loaders/Spinner.vue';
import Modal from '@/components/Modal/modal.vue';
import Import from './components/Import.vue';
import { exportExcel, exportCsv} from '@/composables/export/index.js';
import axios from 'axios';
import Create from './Create.vue';
import Show from './Show.vue';
import { formatDate } from '@/composables/helpers/index.js';
import { swalSuccess, swalError, Swal } from '@/composables/sweetAlert.js';
import Dataset from '@/components/Dataset/Index.vue'
const auth_token = `Bearer ${localStorage.getItem("auth-token")}`;
    export default {
        name:'ProductIndex',
        data(){
            return{
                isExportAll:false,
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
                columns:[
                    {
                        name: 'Name',
                        field: 'name',
                        sort: ''
                    },
                    {
                        name: 'Price Name',
                        field: 'price',
                        sort: ''
                    },
                    {
                        name: 'Quantity',
                        field: 'quantity',
                        sort: ''
                    },
                    {
                        name: 'Created At',
                        field: 'created_at',
                        sort: ''
                    },
                    {
                        name: 'Status',
                        field: 'status',
                        sort: ''
                    },
                    {
                        name: 'Action',
                        field: 'action',
                        sort: ''
                    },
                ],
                data: [],
                product_id:null,
            }
        },
        components: {
            Import,
            Modal,
            Create,
            Show,
            Dataset
        },
        computed:{
       
        },
        async created(){
            await this.getProducts();
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
                        this.data = response.data?.products.map(product => ({
                                                            ...product,
                                                            created_at: formatDate(undefined,product.created_at,'timestamp'),
                                                            status: product.active === 1 ? 'Active' : 'Inactive'
                                                        }));
                    }
                }).catch(response=>{

                })
            },

            exportCsv() {
                const data = this.data.map(({ id, ...rest}) => rest);
                exportCsv(data)
            },

            exportExcel() {
                const data = this.data.map(({ id, ...rest}) => rest);
                exportExcel(data);
            },

            handleExport(){
                if(this.selectedExportOption != null){
                    const selectedMethod = this.exportOptions[this.selectedExportOption].method;
                     this[selectedMethod]();
                     this.selectedExportOption = ""
                }
            },

            addData(payload){
                if(payload == 'import'){
                    this.getProducts();
                }else{
                    console.log(payload,'PAYLOAD')
                    console.log([
                        payload,
                        ...this.data,
                    ],'SHEESH')
                    const updateData = this.data = [
                        payload,
                        ...this.data,
                    ];
                    
                     this.data = updateData.map(product => ({
                                                                ...product,
                                                                created_at: formatDate(undefined,product.created_at,'timestamp'),
                                                                status: product.active === 1 ? 'Active' : 'Inactive'
                                                            }));
                }
          
            },

            viewProduct(product_id){
                const id = document.getElementById('show-product-modal');
                const modal = bootstrap.Modal.getOrCreateInstance(id);
                this.product_id = product_id;
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
                        this.data = this.data.filter(item => item.id != id);  
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

            deleteConfirmation(id){
                Swal.fire({
                    title: "Are you sure?",
                    text:"Delete Product",
                    showCancelButton: true,
                    confirmButtonText: "Confirm",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.deleteShop(id)
                    } else if (result.isDenied) {
                        Swal.fire("Changes are not saved", "", "info");
                    }
                });
            },

            updateData(payload){
                const index = this.data.findIndex(data => data.id === payload.id);
                // Create a copy of the array and update the specific element
                const updateddata = [...this.data];
                
                updateddata[index] = {
                        ...payload,
                        created_at: formatDate(undefined,payload.created_at,'timestamp'),
                        status: payload.active === 1 ? 'Active' : 'Inactive'
                    };

                this.data = updateddata;
            }

        },
    }
</script>

<style scoped>

</style>