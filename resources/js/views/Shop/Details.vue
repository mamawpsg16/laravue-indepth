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
            <!-- <div class="row">
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">Latitude</label>
                    <p type="text" >{{ latitude }}</p>
                </div>
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">Longitude</label>
                    <p type="text" >{{ longitude }}</p>
                </div>
            </div> -->
        </tab-content>
        <tab-content>
            <div class="row"  v-for="(contact,index) in contact_information" :key="index">
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">Email</label>
                    <p type="text" >{{ contact.email }}</p>
                </div>
                <div class="col-6">
                    <label for="exampleInputEmail1" class="form-label">Phone</label>
                    <p type="text" >{{ contact.phone }}</p>
                </div>
            </div>
        </tab-content>
        <tab-content>
                <div  class="row" >
                    <div v-for="(social, index) in other_details" 
                        :key="index"
                        :class="{'col-6': !['shipping_policy','returns_policy'].includes(index), 
                                'col-12': ['shipping_policy','returns_policy'].includes(index)}">
                        <template v-if="!except.includes(index)">
                            <label for="exampleInputEmail1" class="form-label">{{ transformToPascalCase(index)}}</label>
                            <p type="text" >{{ other_details[index] }}</p>
                        </template>
                    </div>
                </div>
        </tab-content>
        <template v-slot:footer="props">
            <div class="wizard-footer-left">
                <wizard-button  v-if="props.activeTabIndex > 0 && !props.isLastStep" @click.native="props.prevTab()" :style="props.fillButtonStyle">Previous</wizard-button>
                <wizard-button  v-if="props.isLastStep" @click.native="props.prevTab()" :style="props.fillButtonStyle">Previous</wizard-button>
            </div>
            <div class="wizard-footer-right">
               <wizard-button v-if="!props.isLastStep" @click.native="props.nextTab()" class="wizard-footer-right" :style="props.fillButtonStyle">Next</wizard-button>
            </div>
        </template>
    </form-wizard>
</template>

<script>

import Modal from '@/components/Modal/modal.vue';
import { swalSuccess, swalError, Swal  } from '@/composables/sweetAlert.js';
import { useChageWizardState } from '@/pinia/useChageWizardState.js';
import axios from 'axios';

import {FormWizard, WizardButton, TabContent} from 'vue3-form-wizard'
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
                except:['id', 'shop_id', 'created_at', 'updated_at'],
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
                },
                store:useChageWizardState()
            }
        },
        
        components: {
            Modal,
            FormWizard,
            TabContent,
            WizardButton
        },
        created() {
            console.log('CREATED');
            this.$nextTick(() => {
            // Now you can safely access this.$refs
            // if(this.store.wizard_reset){
            //     this.resetForm();
            // }
            });
        },
        mounted(){
            console.log('MOUNTED')
        },
        computed:{

        },
        methods:{
            transformToPascalCase(text){
                let transformedText = "";
                const arrayString = text.split("_");
                for (const word of arrayString) {
                    transformedText += word.at(0).toUpperCase() + word.slice(1) + " ";
                }
                return transformedText;
            },
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
                this.$refs.formWizard.reset()
            },
            closeModal(){
                // this.resetForm();
                const modal =  bootstrap.Modal.getOrCreateInstance(document.getElementById('edit-shop-modal'));

                // Hide the modal
                modal.hide();
            },
            isLastStep() {
                console.log("FS")
                if (this.$refs.formWizard) {
                    console.log('SHET')
                    return this.$refs.formWizard.isLastStep
                }
                return false
            }
        },
        watch: {
            shop: {
                handler(data) {
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
                        this.contact_information = data.contact_information;
                        this.other_details = data.other_details;
                    }
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
            },
        },
    }
</script>

<style scoped>
</style>