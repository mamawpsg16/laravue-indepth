<template>
    <Modal class="modal-lg" targetModal="show-product-modal" :modaltitle="`${name} - Details`">
        <template #body>
            <template v-if="isLoading">
               <LoadingSpinner/>
            </template>
            <template v-else>
                <Edit :product="product" :updateData="update" v-if="edit" @updated="changeState"/>
                <form id="shop" v-else>
                    <div class="d-flex flex-column mb-2">
                        <div class="col-6 mx-auto text-center mb-2">
                            <img :src="image" class="rounded  img-fluid img-thumbnail" style="width:300px;" alt="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                        <label for="exampleInputEmail1" class="form-label">Name</label>
                        <p>{{ name }}</p>
                        </div>
                        <div class="col-6">
                        <label for="exampleInputEmail1" class="form-label">Price</label>
                        <p>{{ price }}</p>
                        </div>
                        <div class="col-6">
                        <label for="exampleInputEmail1" class="form-label">Quantity</label>
                        <p>{{ quantity }}</p>
                        </div>
                        <div class="col-6 d-flex flex-column">
                            <label for="exampleInputPassword1" class="form-label">Description</label>
                            <p>{{ description }}</p>
                        </div>
                    </div>
                </form>
            </template>
            
        </template>
        <template #footer>
            <div id="view" v-if="edit">
                <button type="button" class="btn btn-danger me-2" @click="cancelEdit">Cancel</button>
                <button type="button" class="btn btn-primary" @click="updateProduct">Update</button>
            </div>
            <div  v-else>
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="editProduct">Edit</button>
            </div>
        </template>
    </Modal>
</template>

<script>
import LoadingSpinner from '@/components/Loaders/Spinner.vue'
import Modal from '@/components/Modal/modal.vue';
import Edit from './Edit.vue';
import { swalSuccess, swalError  } from '@/composables/sweetAlert.js';
import axios from 'axios';
const auth_token = `Bearer ${localStorage.getItem("auth-token")}`;
    export default {
        name:'Show Product',
        props:['productId'],
        data(){
            return {
                name :null,
                description :null,
                price :null,
                quantity :null,
                image:null,
                file:null,
                edit:false,
                update:false,
                isLoading:false,
                product:[]
            }
        },
        components:{
            Modal,
            Edit,
            LoadingSpinner
        },
        methods:{
            async getProductDetails(){
                this.isLoading = true;
                console.log(this.isLoading);
                await axios.get(`/api/products/${this.productId}`,{
                        headers:{
                            'Authorization' : auth_token
                        }
                }).then(response=>{
                    const {status, product} = response.data; 
                    if(status== 200){
                        this.product = product;
                        this.name = product.name,
                        this.description = product.description,
                        this.price = product.price,
                        this.quantity = product.quantity,
                        this.image = product.product_image
                        this.isLoading = false;
                    }
                }).catch(error =>{

                })
            },
            editProduct(){
                this.edit = true;
            },
            updateProduct(){
                this.update = true;
            },
            cancelEdit(){
                this.edit = false;
                this.update = false;
            },
            changeState(data){
                this.$emit('updateRow',data);
                this.product = data;
                this.name = data.name,
                this.description = data.description,
                this.price = data.price,
                this.quantity = data.quantity,
                this.image = data.product_image
                this.update = false;
                this.edit = false;
            }
        },
          watch: {
            productId(id) {
                console.log(id,'watcher id')
                this.getProductDetails();
                
                this.edit = false;
                this.update = false

                deep: true
                immediate: true

            }
        },
    }
</script>

<style lang="scss" scoped>

</style>