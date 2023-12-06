<template>
    <!-- <Import/> -->
    <Create @addRow="addData"/>
    <Show @updateRow="updateData" :shopId="shop_id"/>
   
    <div class="row">
        <div class="col-10 mx-auto my-2">
              <div class="d-flex justify-content-between mb-2">
                <div id="export">
                     <div class="d-flex justify-content-center align-items-center">
                        <select class="form-select" v-model="selectedExportOption" @change="handleExport">
                            <option  value="" disabled selected>Export Options</option>
                            <option  v-for="(option,index) in exportOptions" :value="index" :key="index">{{ option.name }}</option>
                        </select>
                    </div>
                </div>
                <div id="import">
                    <button type="button" class="btn btn-primary text-end me-2" data-bs-toggle="modal" data-bs-target="#create-shop-modal">
                        Create
                    </button>
                </div>
           </div>
            <Dataset :data="data" :columns="columns">
                <template #body="{ data, index }">
                    <tr>
                        <td>{{ data.name }}</td>
                        <td>{{ data.description }}</td>
                        <td>{{ data.quantity }}</td>
                        <td>{{ data.created_at }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary me-2" @click="viewShop(data.id)">View</button>
                            <button @click="deleteConfirmation(data.id)" class="btn btn-sm btn-danger me-2">Delete</button>
                        </td>
                    </tr>
                </template>
            </Dataset>
        </div>
    </div>
</template>

<script>
import Create from './Create.vue';
import Show from './Show.vue';
import image1 from '../../../assets/images/1.png'
import Modal from '@/components/Modal/modal.vue';
import { formatDate, formatNumber } from '@/composables/helpers/index.js';
import axios from 'axios';
import { swalSuccess, swalError, Swal } from '@/composables/sweetAlert.js';
import Dataset from '@/components/Dataset/Index.vue'
import { exportExcel, exportCsv} from '@/composables/export/index.js';
const auth_token = `Bearer ${localStorage.getItem('auth-token')}`;
    export default {
        name:'ShopIndex',
        data(){
            return{
                isExportAll:false,
                data: [],
                columns:[
                    {
                        name: 'Name',
                        field: 'name',
                        sort: ''
                    },
                    {
                        name: 'Description',
                        field: 'description',
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
                        name: 'Action',
                        field: 'action',
                        sort: ''
                    },
                ],
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
                    {
                        name:'EXCEL',
                        method:"exportExcel"
                    },
                ],
            }
        },
        components: {
            Modal,
            Create,
            Show,
            Dataset
        },
        async created(){
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
                       this.data = response.data.shops.map(shop => ({
                                                            ...shop,
                                                            created_at: formatDate(undefined,shop.created_at,'timestamp'),
                                                            status: shop.active === 1 ? 'Active' : 'Inactive'
                                                        }));
                    }

                }).catch(response=>{

                })
            },

            viewShop(id){
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('edit-shop-modal'));

                this.shop_id = id;
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
                        this.data = this.data.filter(item => item.id != id);  
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

            deleteConfirmation(id){
                Swal.fire({
                    title: "Are you sure?",
                    text:"Delete Shop",
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

            exportCsv() {
                const data = this.data.map(({ id, user_id, active, status, ...rest}) => rest);
                exportCsv(data)
            },

            exportExcel() {
                const data = this.data.map(({ id,user_id, active, status, ...rest}) => rest);
                exportExcel(data);
            },

            handleExport(){
                if(this.selectedExportOption != null ){
                    const selectedMethod = this.exportOptions[this.selectedExportOption].method;
                    console.log(selectedMethod);
                    this[selectedMethod]();
                    this.selectedExportOption = ""
                }
            },
            
            addData(payload){
                const updateData = this.data = [
                    payload,
                    ...this.data,
                ];
                
                 this.data = updateData.map(shop => ({
                                                            ...shop,
                                                            created_at: formatDate(undefined,shop.created_at,'timestamp'),
                                                            status: shop.active === 1 ? 'Active' : 'Inactive'
                                                        }));
            },

            updateData(shop){
                  try {
                    console.log(shop);
                    const index = this.data.findIndex(item => item.id === shop.id);

                    // Create a copy of the array and update the specific element
                    const updatedData = [...this.data];

                    updatedData[index] = { 
                                ...shop,
                                created_at: formatDate(undefined,shop.created_at,'timestamp'),
                                status: shop.active === 1 ? 'Active' : 'Inactive'
                    }

                    // Update the original array
                    this.data = updatedData;
                } catch (error) {
                    console.error('Error updating data:', error);
                }
            },

            uploadImage(e){
                // e.preventDefault()
                const file = e.target.files[0];
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
    }
</script>

<style scoped>
</style>