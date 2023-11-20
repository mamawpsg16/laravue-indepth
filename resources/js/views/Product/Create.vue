<template>
    <Modal class="modal-lg" targetModal="create-product-modal" modaltitle="Create">
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
                      <label for="exampleInputEmail1" class="form-label">Name</label>
                      <input type="email" class="form-control" v-model="name" aria-describedby="emailHelp">
                    </div>
                    <div class="col-6">
                      <label for="exampleInputEmail1" class="form-label">Price</label>
                      <input type="number" class="form-control" v-model="price" >
                    </div>
                    <div class="col-6">
                      <label for="exampleInputEmail1" class="form-label">Quantity</label>
                      <input type="number" class="form-control" v-model="quantity" >
                    </div>
                    <div class="col-6 d-flex flex-column">
                        <label for="exampleInputPassword1" class="form-label">Description</label>
                        <textarea name="" rows="5" v-model="description" class="form-control"></textarea>
                    </div>
                </div>
            </form>
        </template>
        <template #footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click.prevent="saveProduct">Save changes</button>
        </template>
    </Modal>
</template>

<script>
import Modal from '@/components/Modal/modal.vue';
import { swalSuccess, swalError  } from '@/composables/sweetAlert.js';
import axios from 'axios';
const auth_token = `Bearer ${localStorage.getItem("auth-token")}`;
    export default {
        name:'Create Product',
        data(){
            return {
                name :null,
                description :null,
                price :null,
                quantity :null,
                image:null,
                file:null,
            }
        },
        components:{
            Modal
        },
        methods:{
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
            resetForm(){
                this.name = null;
                this.description = null;
                this.price = null;
                this.quantity = null;
                this.image = null;
                this.file = null;
                document.getElementById("shop").reset();
            },
            saveProduct(){
                const formData = new FormData();
                formData.append('image',this.file);
                // formData.append('product',JSON.stringify(this.product));
                formData.append('name',this.name);
                formData.append('description',this.description);
                formData.append('price',this.price);
                formData.append('quantity',this.quantity);
                axios.post('/api/products',formData,{
                        headers:{
                            'Authorization' : auth_token
                        }
                    })
                    .then((response) => {
                        if(response.data?.status == 200){
                            this.resetForm();
                            this.$emit('addRow',response.data?.product);

                            swalSuccess({ 
                                icon: 'success',
                                text: 'You can now set your product!',
                                title: response.data?.message,
                                showConfirmButton: false,
                            })
                            
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            },
        }
</script>

<style lang="scss" scoped>

</style>