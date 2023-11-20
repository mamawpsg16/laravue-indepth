<template>
    <Modal class="modal-lg" targetModal="show-product-modal" :modaltitle="`${this.product?.name} - Details`">
        <template #body>
            <form id="shop" v-if="!edit">
                <div class="d-flex flex-column mb-2">
                    <div class="col-6 mx-auto text-center mb-2">
                        <img :src="this.product?.image" class="rounded  img-fluid img-thumbnail" style="width:300px;" alt="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                      <label for="exampleInputEmail1" class="form-label">Name</label>
                      <p>{{ this.product?.name }}</p>
                    </div>
                    <div class="col-6">
                      <label for="exampleInputEmail1" class="form-label">Price</label>
                      <p>{{ this.product?.price }}</p>
                    </div>
                    <div class="col-6">
                      <label for="exampleInputEmail1" class="form-label">Quantity</label>
                       <p>{{ this.product?.quantity }}</p>
                    </div>
                    <div class="col-6 d-flex flex-column">
                        <label for="exampleInputPassword1" class="form-label">Description</label>
                        <p>{{ this.product?.description }}</p>
                    </div>
                </div>
            </form>
            <Edit :details="product" :updateData="update" v-else @updated="changeState"/>
        </template>
        <template #footer>
            <div id="view" v-if="!edit">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="editProduct">Edit</button>
            </div>
            <div  v-else>
                <button type="button" class="btn btn-danger me-2" @click="cancelEdit">Cancel</button>
                <button type="button" class="btn btn-primary" @click="updateProduct">Update</button>
            </div>
        </template>
    </Modal>
</template>

<script>
import Modal from '@/components/Modal/modal.vue';
import Edit from './Edit.vue';
import { swalSuccess, swalError  } from '@/composables/sweetAlert.js';
import axios from 'axios';
const auth_token = `Bearer ${localStorage.getItem("auth-token")}`;
    export default {
        name:'Show Product',
        props:['details'],
        data(){
            return {
                // name :null,
                // description :null,
                // price :null,
                // quantity :null,
                // image:null,
                file:null,
                edit:false,
                update:false,
                product:[],
            }
        },
        components:{
            Modal,
            Edit
        },
        methods:{
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
                this.product = {
                    id: data?.id,
                    name : data?.name,
                    description : data?.description,
                    price : data?.price,
                    quantity : data?.quantity,
                    image : data?.product_image
                }
                this.update = false;
                this.edit = false;
            }
        },
          watch: {
            // whenever question changes, this function will run
            details(data) {
                this.product = {
                    id: data?.id,
                    name : data?.name,
                    description : data?.description,
                    price : data?.price,
                    quantity : data?.quantity,
                    image : data?.product_image
                }
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