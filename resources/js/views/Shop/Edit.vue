<template>
     <Modal class="modal-xl" targetModal="edit-shop-modal" modaltitle="Shop Details" :backdrop="true" :escKey="false">
        <template #body>
            <form>
                <div class="row">
                    <div class="col-4">
                        <label for="exampleInputEmail1" class="form-label">Shop Name</label>
                        <input type="text" class="form-control" v-model="name" autocomplete="name" required>
                    </div>
                    <div class="col-4">
                        <label for="exampleInputPassword1" class="form-label">Shop Description</label>
                        <input type="text" class="form-control" v-model="description" required>
                    </div>
                </div>
            </form>

        </template>
        <template #footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="closeModal">Close</button>
            <button type="button" class="btn btn-primary" @click.prevent="updateConfirmation">Update</button>
        </template>
    </Modal>
</template>

<script>

import Modal from '@/components/Modal/modal.vue';
import { swalSuccess, swalError, Swal  } from '@/composables/sweetAlert.js';
import axios from 'axios';
const auth_token = `Bearer ${localStorage.getItem('auth-token')}`;

    export default {
        name:'Shop Edit',
        props:{
            details:{
                type:[Array,Object],
                default:[]
            }
        },
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
            update(){
                 const data = {name:this.name, description: this.description};
                axios.put(`/api/shops/${this.details.id}`,data, { headers:{ 'Authorization': auth_token}})
                .then((response) => {
                    if(response.data?.status == 200){
                        this.$emit('updateRow',response.data?.data);
                

                        swalSuccess({ 
                            icon: 'success',
                            text: 'Updated!',
                            title: response.data?.message,
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
                        this.update()
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
                this.resetForm();
                const modal =  bootstrap.Modal.getOrCreateInstance(document.getElementById('edit-shop-modal'));

                // Hide the modal
                modal.hide();
            }
        },
        watch: {
            details(updatedDetails) {
                this.name = updatedDetails.name
                this.description = updatedDetails.description
            }
        },
    }
</script>

<style scoped>
</style>