<template>
        <form id="product">
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

<script>
import Modal from '@/components/Modal/modal.vue';
import { swalSuccess, swalError, Swal  } from '@/composables/sweetAlert.js';
import axios from 'axios';
const auth_token = `Bearer ${localStorage.getItem("auth-token")}`;
    export default {
        name:'Edit Product',
        props:['product','updateData'],
        data(){
            return {
                id :null,
                name :null,
                description :null,
                price :null,
                quantity :null,
                image:null,
                file:null
            }
        },
        components:{
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
            resetForm(){
                this.name = null;
                this.description = null;
                this.price = null;
                this.quantity = null;
                this.image = null;
                this.file = null;
                document.getElementById("product").reset();
            },
            updateProduct(){
                const formData = new FormData();
                formData.append('image',this.file);
                formData.append('name',this.name);
                formData.append('description',this.description);
                formData.append('price',this.price);
                formData.append('quantity',this.quantity);
                axios.post(`/api/updateProduct/${this.product.id}`,formData,{
                        headers:{
                            'Authorization' : auth_token
                        }
                })
                .then((response) => {
                    const { status, product, message } = response.data;
                    if(status == 200){
                        this.resetForm();
                        this.$emit('updated',product);
                        // this.$emit('addRow',response.data?.product);

                        swalSuccess({ 
                            icon: 'success',
                            text: 'You can now set your product!',
                            title: message,
                            showConfirmButton: false,
                        })
                        
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            updateConfirmation(data){
                Swal.fire({
                    title: "Are you sure?",
                    text:"Update Product",
                    showCancelButton: true,
                    confirmButtonText: "Confirm",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.updateProduct()
                    } else if (result.isDenied) {
                        Swal.fire("Changes are not saved", "", "info");
                    }
                });
            },
        },
        watch: {
            // whenever question changes, this function will run
            product: {
                handler(data) {
                    this.name = data.name;
                    this.description = data.description;
                    this.price = data.price;
                    this.quantity = data.quantity;
                    this.image = data.product_image;
                    // this will be run immediately on component creation.
                },
                // force eager callback execution
                immediate: true
            },
            updateData: {
                handler(data) {
                    if(data){
                        // this.update = data;
                        this.updateConfirmation()
                    }
                },
                // force eager callback execution
                immediate: true
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>