<template>
     <Modal class="modal-lg" targetModal="create-shop-modal" modaltitle="Create - Shop Details" :backdrop="true" :escKey="false">
        <template #body>
            <form>
                <div class="row">
                    <div class="col-6">
                        <label for="exampleInputEmail1" class="form-label">Shop Name</label>
                        <input type="text" class="form-control" v-model="name" autocomplete="name" required>
                    </div>
                    <div class="col-6">
                        <label for="exampleInputPassword1" class="form-label">Shop Description</label>
                        <textarea  class="form-control" rows="5" name="exampleInputPassword" v-model="description" required></textarea>
                    </div>
                </div>
            </form>

        </template>
        <template #footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="closeModal">Close</button>
            <button type="button" class="btn btn-primary" @click.prevent="store">Save</button>
        </template>
    </Modal>
</template>

<script>

import Modal from '@/components/Modal/modal.vue';
import { swalSuccess, swalError  } from '@/composables/sweetAlert.js';
import axios from 'axios';
const auth_token = `Bearer ${localStorage.getItem('auth-token')}`;

    export default {
        name:'Shop Create',
        data(){
            return{
                name: null,
                description: null,
            }
        },
        components: {
            Modal
        },
        methods:{
            store(){
                const data = {name:this.name, description: this.description};
                axios.post('/api/shops',data, { headers:{ 'Authorization': auth_token}})
                .then((response) => {
                    if(response.data?.status == 200){
                        this.$emit('addRow',response.data?.shop);
                        this.name = null;
                        this.description = null;

                        swalSuccess({ 
                            icon: 'success',
                            text: 'You can now setup your shop!',
                            title: 'Shop successfully created!',
                            showConfirmButton: false,
                        })
                        
                    }
                })
                .catch(function (error) {
                    if(error.response?.data?.errors){
                        swalError({
                            icon: 'error',
                            title: error.response?.data.errors,
                            text: "Something went wrong!",
                            showConfirmButton: false,
                        })
                    }
                });
            },
            resetForm(){
                this.name = null;
                this.description = null;
            },
            closeModal(){
                this.resetForm();
            }
        },
        
    }
</script>

<style scoped>
</style>