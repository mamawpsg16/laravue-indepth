<template>
     <Modal class="modal-lg" targetModal="create-shop-modal" modaltitle="Create" :backdrop="true" :escKey="false">
        <template #body>
            <form-wizard @on-complete="store" finishButtonText="Save" :validateOnBack="true" ref="formWizard">
                <!-- <LoadingSpinner v-if="isWizardLoading"/> -->
                <TabContent title="Main details" icon="fa fa-user"  :before-change="validateMainDetails">
                    <div class="d-flex flex-column mb-2">
                        <div class="col-6 mx-auto text-center mb-2">
                            <img :src="image" class="rounded img-fluid img-thumbnail" style="width:300px;" alt=""> <br><code>*</code>
                        </div>
                        <div class="col-4 mx-auto">
                            <input class="form-control" :class="{ 'is-invalid': v$.file.$dirty && (v$.file.required.$invalid) }" type="file" id="formFile" @change="uploadImage" accept="image/png, image/jpeg">
                            <div  v-if="v$.file.$dirty" :class="{ 'invalid-feedback':(v$.file.required.$invalid )}">
                                <p v-if="v$.file.required.$invalid">
                                    Shop Image is required
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="exampleInputEmail1" class="form-label">Shop Name <code>*</code></label>
                            <input required type="name" @click="clearFormErrors" :class="{ 'is-invalid': v$.name.$dirty && (v$.name.required.$invalid) }" class="form-control" v-model="name" autocomplete="name" >
                            <div  v-if="v$.name.$dirty" :class="{ 'invalid-feedback':(v$.name.required.$invalid )}">
                                <p v-if="v$.name.required.$invalid">
                                    Shop Name is required
                                </p>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="exampleInputPassword1" class="form-label">Shop Description</label>
                            <textarea  class="form-control" rows="5" name="exampleInputPassword" v-model="description" required></textarea>
                        </div>
                    </div>
                </TabContent>
                <TabContent title="Location" icon="fa fa-location-dot" :before-change="validateLocationInformation">
                    <div class="row">
                        <div class="col-12">
                            <label for="exampleInputEmail1" class="form-label">Address <code>*</code></label>
                            <textarea  class="form-control" rows="3"  :class="{ 'is-invalid': v$.address.$dirty && (v$.address.required.$invalid || v$.address.maxLength.$invalid ) }" name="exampleInputPassword"  v-model="address" required></textarea> 
                            <div  v-if="v$.address.$dirty" :class="{ 'invalid-feedback':(v$.address.required.$invalid || v$.address.maxLength.$invalid  )}">
                                <p v-if="v$.address.required.$invalid">
                                    Address is required
                                </p>
                                <p v-if="v$.address.maxLength.$invalid">
                                    Address must have a max length of 150 character
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="exampleInputPassword1" class="form-label">City <code>*</code></label>
                            <input type="text" v-model="city" class="form-control" :class="{ 'is-invalid': v$.city.$dirty && (v$.city.required.$invalid) }"  id="formcity">
                            <div  v-if="v$.city.$dirty" :class="{ 'invalid-feedback':(v$.city.required.$invalid )}">
                                <p v-if="v$.city.required.$invalid">
                                    City is required
                                </p>
                            </div>
                            
                        </div>
                        <div class="col-6">
                            <label for="exampleInputPassword1" class="form-label">State</label>
                            <input type="text" class="form-control" v-model="state" autocomplete="state" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="exampleInputPassword1" class="form-label">Country <code>*</code></label>
                            <input type="text" v-model="country" class="form-control" :class="{ 'is-invalid': v$.country.$dirty && (v$.country.required.$invalid) }" id="formcountry">
                            <div  v-if="v$.country.$dirty" :class="{ 'invalid-feedback':(v$.country.required.$invalid )}">
                                <p v-if="v$.country.required.$invalid">
                                    Country is required
                                </p>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="exampleInputPassword1" class="form-label">Zip Code <code>*</code></label>
                            <input type="text" v-model="zip_code" class="form-control" :class="{ 'is-invalid': v$.zip_code.$dirty && (v$.zip_code.required.$invalid || v$.zip_code.minLength.$invalid || v$.zip_code.maxLength.$invalid  ) }"  id="formzip_code">
                            <div  v-if="v$.zip_code.$dirty" :class="{ 'invalid-feedback':(v$.zip_code.required.$invalid || v$.zip_code.minLength.$invalid || v$.zip_code.maxLength.$invalid )}">
                                <p v-if="v$.zip_code.required.$invalid">
                                    Zip Code is required
                                </p>
                                <p v-if="v$.zip_code.minLength.$invalid">
                                    Zip Code must have a min length of 4 character
                                </p>
                                <p v-if="v$.zip_code.maxLength.$invalid">
                                    Zip Code must have a max length of 5 character
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-6">
                            <label for="exampleInputPassword1" class="form-label">Latitude</label>
                            <input type="text" class="form-control" v-model="latitude" autocomplete="country" required>
                        </div>
                        <div class="col-6">
                            <label for="exampleInputPassword1" class="form-label">Longitude</label>
                            <input type="text" class="form-control" v-model="longitude" autocomplete="zip_code" required>
                        </div>
                    </div> -->
                </TabContent>
                <TabContent title="Contact Information" icon="fa fa-phone" :before-change="validateContactInformation" >
                    <div class="row mt-2">
                        <div class="row-12 text-end">
                            <button @click="addContact" type="button" class="btn btn-sm btn-primary">Add</button>
                        </div>
                    </div>
                    <div v-for="(contact, index) in contact_information" :key="index" class="row align-items-center">
                        <div class="col-6">
                          <label :for="'email' + index" class="form-label">Email ({{ index + 1}}) <code>*</code></label>
                          <input
                            :id="'email' + index"
                            type="text"
                            class="form-control"
                            :class="{ 'is-invalid': v$.contact_information[index].email.$dirty && (v$.contact_information[index].email.required.$invalid || v$.contact_information[index].email.email.$invalid) }"
                            v-model="contact.email"
                            autocomplete="email"
                            required
                          />
                            <div  v-if="v$.contact_information[index].email.$dirty" :class="{ 'invalid-feedback':(v$.contact_information[index].email.required.$invalid || v$.contact_information[index].email.email.$invalid)}">
                                <span v-if="v$.contact_information[index].email.required.$invalid">
                                    Email is required
                                </span>
                                <p v-if="v$.contact_information[index].email.email.$invalid">
                                    Email must be a valid email
                                </p>
                            </div>
                        </div>
                        <div class="col-5">
                          <label :for="'phone' + index" class="form-label">Phone ({{ index + 1}}) <code>*</code></label>
                          <input
                            :id="'phone' + index"
                            type="text"
                            class="form-control"
                            :class="{ 'is-invalid': v$.contact_information[index].phone.$dirty && (v$.contact_information[index].phone.required.$invalid) }"
                            v-model="contact.phone"
                            autocomplete="phone"
                            required
                          />
                            <div  v-if="v$.contact_information[index].phone.$dirty" :class="{ 'invalid-feedback':(v$.contact_information[index].phone.required.$invalid)}">
                                <span v-if="v$.contact_information[index].phone.required.$invalid">
                                    Phone is required
                                </span>
                            </div>
                        </div>
                        <div class="col-1">
                          <br>
                          <button @click="deleteContact(index)" v-if="index != 0" type="button" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-minus"></i>
                          </button>
                        </div>
                    </div>
                </TabContent>
                <TabContent title="Others" icon="fa fa-layer-group">
                    <div class="row">
                        <div class="col-6">
                            <label for="exampleInputPassword1" class="form-label">Facebook</label>
                            <input type="text" v-model="other_details.facebook" class="form-control"  id="formcity">
                        </div>
                        <div class="col-6">
                            <label for="exampleInputPassword1" class="form-label">Twitter</label>
                            <input type="text" class="form-control" v-model="other_details.twitter" autocomplete="state" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="exampleInputPassword1" class="form-label">Instagram</label>
                            <input type="text" v-model="other_details.instagram" class="form-control"  id="formcity">
                        </div>
                        <div class="col-6">
                            <label for="exampleInputPassword1" class="form-label">Tiktok</label>
                            <input type="text" class="form-control" v-model="other_details.tiktok" autocomplete="state" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="exampleInputPassword1" class="form-label">Shipping Policy</label>
                            <textarea  class="form-control" rows="5" name="exampleInputPassword" v-model="other_details.shipping_policy" required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="exampleInputPassword1" class="form-label">Return Policy</label>
                            <textarea  class="form-control" rows="5" name="exampleInputPassword" v-model="other_details.returns_policy" required></textarea>
                        </div>
                    </div>
                </TabContent>
            </form-wizard>

        </template>
        <template #footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="closeModal">Close</button>
            <!-- <button type="button" class="btn btn-primary" @click.prevent="store">Save</button> -->
        </template>
    </Modal>
</template>

<script>

import Modal from '@/components/Modal/modal.vue';
import LoadingSpinner from '@/components/Loaders/Spinner.vue';
import {FormWizard, TabContent} from 'vue3-form-wizard'
import { swalSuccess, swalError  } from '@/composables/sweetAlert.js';
import { useVuelidate } from '@vuelidate/core'
import { required, email, minLength, maxLength  } from '@vuelidate/validators'
import axios from 'axios';
const auth_token = `Bearer ${localStorage.getItem('auth-token')}`;

    export default {
        name:'Shop Create',
        setup () {
            return { v$: useVuelidate({ $autoDirty: true }) }
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
                },
            }
        },
        validations () {
            return {
                name:{ required },
                file:{ required },
                address:{required,  maxLength: maxLength(150)},
                country:{required},
                city:{required},
                zip_code:{required, minLength: minLength(4), maxLength: maxLength(5)},
                contact_information:this.contact_information.map((contact) => ({
                    email: { required, email },
                    phone: { required },
                })),
            }
        },
        components: {
            Modal,
            FormWizard,
            TabContent,
            LoadingSpinner
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

            addContact() {
                this.contact_information.push({
                    email: null,
                    phone: null,
                });
            },

            deleteContact(index) {
                this.contact_information.splice(index, 1);
            },
            
            validateMainDetails: function(){
                this.v$.name.$touch();
                this.v$.file.$touch();
                const isValid = (this.v$.name.$errors.length || this.v$.file.$errors.length) ? false : true;
                return isValid;
            },

            validateLocationInformation: function(){
                this.v$.address.$touch();
                this.v$.country.$touch();
                this.v$.city.$touch();
                this.v$.zip_code.$touch();
  
                const isValid = (this.v$.address.$errors.length || this.v$.country.$errors.length || this.v$.city.$errors.length || this.v$.zip_code.$errors.length) ? false : true;
                return isValid;
            },

            validateContactInformation: function(){
                // console.log(this.v$.contact_information,'CONTACT', this.v$.contact_information.length, Object.values(this.v$.contact_information), Object.entries(this.v$.contact_information));
                for (const contact of Object.values(this.v$.contact_information)) {
                    if(Object.hasOwn(contact, 'email')){
                        contact.email.$touch();
                        contact.phone.$touch();
                    }
                }
                const isValid = (this.v$.contact_information.$silentErrors.length) ? false : true;
                return isValid;
            },

            async store(){
                 if(!await this.v$.$validate()){
                    return;
                }
                
                const formData = new FormData();
                formData.append('image',this.file);
                formData.append('name',this.name);
                formData.append('description',this.description);
                formData.append('latitude',this.latitude);
                formData.append('longitude',this.longitude);
                formData.append('address',this.address);
                formData.append('city',this.city);
                formData.append('state',this.state);
                formData.append('country',this.country);
                formData.append('zip_code',this.zip_code);
                formData.append('description',this.description);
                formData.append('description',this.description);
                formData.append('contact_details', JSON.stringify(this.contact_information))
                formData.append('other_details', JSON.stringify(this.other_details))
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
       
                        this.$refs.formWizard.reset()
                        
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
                this.latitude = null;
                this.longitude = null;
                this.address = null;
                this.city = null;
                this.state = null;
                this.country = null;
                this.zip_code = null;

                console.log(this.$data,'this.$data');

                Object.keys(this.other_details).forEach(key => {
                    this.$data.other_details[key] = null;
                })

                this.contact_information = [{
                    email: null,
                    phone: null  
                }]
                this.$refs.formWizard.reset()
                this.v$.$reset()
            },

            closeModal(){
                this.resetForm();
            }
        },
        
    }
</script>

<style scoped>
</style>