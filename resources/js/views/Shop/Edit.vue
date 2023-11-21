<template>
    <form>
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

<script>

import Modal from '@/components/Modal/modal.vue';
import { swalSuccess, swalError, Swal  } from '@/composables/sweetAlert.js';
import axios from 'axios';
const auth_token = `Bearer ${localStorage.getItem('auth-token')}`;

    export default {
        name:'Shop Edit',
        props:{
            shop:{
                type:[Array,Object],
                default:[]
            },
            updateData:{
                type:[Boolean]
            }
        },
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
            updateShop(){
                const formData = new FormData();
                formData.append('image',this.file);
                formData.append('name',this.name);
                formData.append('description',this.description);
                axios.post(`/api/updateShop/${this.shop.id}`,formData, { headers:{ 'Authorization': auth_token}})
                .then((response) => {
                    const { shop, status, message } = response.data
                    if(status == 200){
                        this.$emit('updated',shop);
                
                        swalSuccess({ 
                            icon: 'success',
                            text: 'Updated!',
                            title:message,
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
            updateConfirmation(data){
                Swal.fire({
                    title: "Are you sure?",
                    text:"Update Shop",
                    showCancelButton: true,
                    confirmButtonText: "Confirm",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.updateShop()
                    } else if (result.isDenied) {
                        Swal.fire("Changes are not saved", "", "info");
                    }
                });
            },
            resetForm(){
                this.name = null;
                this.description = null;
            },
            closeModal(){
                // this.resetForm();
                const modal =  bootstrap.Modal.getOrCreateInstance(document.getElementById('edit-shop-modal'));

                // Hide the modal
                modal.hide();
            }
        },
        watch: {
            shop: {
                handler(data) {
                    console.log('WTF');
                    this.name = data.name
                    this.description = data.description
                    this.image = data.shop_image
                    // this will be run immediately on component creation.
                },
                // force eager callback execution
                immediate: true
            },
            updateData:{
                handler(data){
                    if(data){
                        this.updateConfirmation();
                    }
                }
            }
        },
    }
</script>

<style scoped>
</style>