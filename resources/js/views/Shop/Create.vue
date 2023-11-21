<template>
     <Modal class="modal-lg" targetModal="create-shop-modal" modaltitle="Create - Shop Details" :backdrop="true" :escKey="false">
        <template #body>
            <form id="shop">
                <div class="d-flex flex-column mb-2">
                    <div class="col-6 mx-auto text-center mb-2">
                        <img :src="image" class="rounded  img-fluid img-thumbnail" style="width:300px;" alt="">
                    </div>
                    <div class="col-4 mx-auto">
                        <input class="form-control" type="file" id="formFile" @change="uploadImage" accept="image/png, image/jpeg">
                    </div>
                </div>
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
                file:null,
                image:null
            }
        },
        components: {
            Modal
        },
        methods:{
            uploadImage(e){
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
            store(){
                const formData = new FormData();
                 formData.append('image',this.file);
                 formData.append('name',this.name);
                 formData.append('description',this.description);
                axios.post('/api/shops',formData, { headers:{ 'Authorization': auth_token}})
                .then((response) => {
                    if(response.data?.status == 200){
                        this.$emit('addRow',response.data?.shop);
                        this.resetForm()

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
                this.image = null;
                this.file = null;
                document.getElementById("shop").reset();
            },
            closeModal(){
                this.resetForm();
            }
        },
        
    }
</script>

<style scoped>
</style>