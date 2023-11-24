<template>
    <form-wizard finishButtonText="Save" :validateOnBack="true" ref="formWizard">
        <!-- <LoadingSpinner v-if="isWizardLoading"/> -->
        <tab-content title="Main details" icon="fa fa-user">
            <div class="d-flex flex-column mb-2">
                <div class="col-6 mx-auto text-center mb-2">
                    <img :src="image" class="rounded img-fluid img-thumbnail" style="width:300px;" alt="">
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
        </tab-content>
        <tab-content>
            <div class="row">
                <div class="col-12">
                    <label for="exampleInputEmail1" class="form-label">Address</label>
                    <p type="text" >{{ address }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">City</label>
                    <p type="text" >{{ city }}</p>
                </div>
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">State</label>
                    <p type="text" >{{ state }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">Country</label>
                    <p type="text" >{{ country }}</p>
                </div>
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">Zip Code</label>
                    <p type="text" >{{ zip_code }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">Latitude</label>
                    <p type="text" >{{ latitude }}</p>
                </div>
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">Longitude</label>
                    <p type="text" >{{ longitude }}</p>
                </div>
            </div>
        </tab-content>
        <tab-content>
            <div class="row">
                <div class="col-12">
                    <label for="exampleInputEmail1" class="form-label">Address</label>
                    <p type="text" >{{ address }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">City</label>
                    <p type="text" >{{ city }}</p>
                </div>
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">State</label>
                    <p type="text" >{{ state }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">Country</label>
                    <p type="text" >{{ country }}</p>
                </div>
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">Zip Code</label>
                    <p type="text" >{{ zip_code }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">Latitude</label>
                    <p type="text" >{{ latitude }}</p>
                </div>
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">Longitude</label>
                    <p type="text" >{{ longitude }}</p>
                </div>
            </div>
        </tab-content>
    </form-wizard>
</template>

<script>

import Modal from '@/components/Modal/modal.vue';
import { swalSuccess, swalError, Swal  } from '@/composables/sweetAlert.js';
import axios from 'axios';
import {FormWizard, TabContent} from 'vue3-form-wizard'
const auth_token = `Bearer ${localStorage.getItem('auth-token')}`;

    export default {
        name:'Shop Details',
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
                image:null,
                isWizardLoading:false,
                latitude: null,
                longitude: null,
                address: null,
                city: null,
                state: null,
                country: null,
                zip_code: null,
                contact_information:[
                    {
                        email:null,
                        phone:null
                    }
                ],
                other_details:{
                    facebook:null,
                    twitter:null,
                    instagram:null,
                    tiktok:null,
                    shipping_policy:null,
                    returns_policy:null,
                }
            }
        },
        components: {
            Modal,
            FormWizard,
            TabContent,
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
                    this.latitude = data?.location?.latitude;
                    this.longitude = data?.location?.longitude;
                    this.address = data?.location?.address;
                    this.city = data?.location?.city;
                    this.state = data?.location?.state;
                    this.country = data?.location?.country;
                    this.zip_code = data?.location?.zip_code;
                    if(data){
                        data?.contact_information?.forEach(contact_details => {
                            console.log(this.$data,'this.$data');
                            Object.keys(this.contact_information).forEach(key => console.log(key))
                        });
                    }
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