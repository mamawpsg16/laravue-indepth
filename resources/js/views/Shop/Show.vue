<template>
     <Modal  class="modal-lg" targetModal="edit-shop-modal" :modaltitle="`${name} - Shop Details`" :backdrop="true" :escKey="false">
        <template #body>
            <Edit :shop="shop" v-if="edit" @updated="changeState" :updateData="update"/>
            <form v-else>
                <div class="d-flex flex-column mb-2">
                    <div class="col-6 mx-auto text-center mb-2">
                        <img :src="image" class="rounded  img-fluid img-thumbnail" style="width:300px;" alt="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="exampleInputEmail1" class="form-label">Shop Name</label>
                        <p type="text" >{{ name }}</p>
                    </div>
                    <div class="col-6">
                        <label for="exampleInputPassword1" class="form-label">Shop Description</label>
                        <p type="text" >{{ description }}</p>
                    </div>
                </div>
            </form>
        </template>
        <template #footer>
            <div id="view" v-if="edit">
                <button type="button" class="btn btn-danger me-2" @click="cancelEdit">Cancel</button>
                <button type="button" class="btn btn-primary" @click="updateShop">Update</button>
            </div>
            <div  v-else>
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="editShop">Edit</button>
            </div>
        </template>
    </Modal>
</template>

<script>

import Edit from './Edit.vue';
import Modal from '@/components/Modal/modal.vue';
import { swalSuccess, swalError, Swal  } from '@/composables/sweetAlert.js';
import axios from 'axios';
const auth_token = `Bearer ${localStorage.getItem('auth-token')}`;

    export default {
        name:'View Shop',
        props:{
            shopId:{
                type:[Number],
            }
        },
        data(){
            return{
                name: null,
                description: null,
                edit:false,
                update:false,
                image:false,
                shop:[]
            }
        },
        components: {
            Modal,
            Edit
        },
        methods:{
            getShopDetails(){
                axios.get(`api/shops/${this.shopId}`,{ headers:{ 'Authorization': auth_token}})
                .then(response=>{
                    const { status, shop, message } = response.data;
                    if(status == 200){
                        this.shop = shop;
                        this.name = shop.name;
                        this.description = shop.description;
                        this.image = shop.shop_image;
                    }
                }).catch(error =>{

                })
            },
            editShop(){
                this.edit = true;
            },
            changeState(data){
                this.$emit('updateRow',data);
                this.shop = data;
                this.name = data.name,
                this.description = data.description
                this.image = data.shop_image
                this.update = false;
                this.edit = false;
            },
            updateShop(){
                this.update = true;
            },
            cancelEdit(){
                this.edit = false;
                this.update = false;
            },
        },
        watch: {
            shopId(id) {
                this.getShopDetails();
                
                this.edit = false;
                this.update = false

                deep: true
                immediate: true

            }
        },
    }
</script>

<style scoped>
</style>