<template>
    <form @submit.prevent="register">
        <div class="row d-flex flex-row">
            <div class="col-4">
                <label for="exampleInputEmail1" class="form-label">Name</label>
                <input required type="name" :class="{ 'is-invalid': v$.name.$dirty && (v$.name.required.$invalid) }" class="form-control" v-model="name" autocomplete="name" >
                <div  v-if="v$.name.$dirty" :class="{ 'invalid-feedback':(v$.name.required.$invalid)}">
                    <p v-if="v$.name.required.$invalid">
                        Name is required
                    </p>                    
                </div>
            </div>
            <div class="col-4">
                <label for="exampleInputEmail1" class="form-label">Email</label>
                <input required type="email" :class="{ 'is-invalid': v$.email.$dirty && (v$.email.required.$invalid || v$.email.email.$invalid) }" class="form-control" v-model="email" autocomplete="email" >
                <div  v-if="v$.email.$dirty" :class="{ 'invalid-feedback':(v$.email.required.$invalid || v$.email.email.$invalid)}">
                    <p v-if="v$.email.required.$invalid">
                        Email is required
                    </p>
                    <p v-if="v$.email.email.$invalid">
                        Email must be a valid email
                    </p>
                    
                </div>
            </div>
            <div class="col-4">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input required type="password" class="form-control"  :class="{ 'is-invalid': v$.password.$dirty && (v$.password.required.$invalid  || v$.password.minLength.$invalid) }"  v-model="password" autocomplete="new-password" >
                <div  v-if="v$.password.$dirty" :class="{ 'invalid-feedback': (v$.password.required.$invalid || v$.password.minLength.$invalid)}">
                    <p v-if="v$.password.required.$invalid">
                        Password is required
                    </p>
                    <p v-if="v$.password.minLength.$invalid">
                        Password must have a min length of 8 character
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for="exampleInputPassword1" class="form-label">Confirmation Password</label>
                <input required type="password" class="form-control"  :class="{ 'is-invalid': v$.password_confirmation.$dirty && (v$.password_confirmation.required.$invalid  || v$.password_confirmation.sameAs.$invalid ) }"  v-model="password_confirmation" autocomplete="new-password_confirmation" >
                <div v-if="v$.password_confirmation.$dirty" :class="{ 'invalid-feedback': (v$.password_confirmation.required.$invalid || v$.password_confirmation.sameAs.$invalid)}">
                    <span v-if="v$.password_confirmation.required.$invalid">
                        Password confirmation is required
                    </span>
                    <br>
                    <span v-if="v$.password_confirmation.sameAs.$invalid">
                        Password confirmation must be same as password
                    </span>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <p>Already have an account? ,  <router-link :to="{name:'login'}"> Login!</router-link></p>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary" >Register</button>
            </div>
        </div>
    </form>
     
    
</template>

<script>

import Modal from '@/components/Modal/modal.vue';
import { useLayoutStore } from '../../pinia/useLayoutStore';
import { swalSuccess, swalError  } from '@/composables/sweetAlert.js';
import { useVuelidate } from '@vuelidate/core'
import { required, email, minLength, sameAs   } from '@vuelidate/validators'
import axios from 'axios';
    export default {
        setup () {
            return { v$: useVuelidate({ $autoDirty: true }) }
        },
        name:'Register',
        data(){
            return{
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
                store : useLayoutStore()
            }
        },
        validations () {
            return {
                name: { required }, 
                email: { required, email }, 
                password: { required, minLength: minLength(8) },
                password_confirmation: {
                    required,
                    sameAs:sameAs(this.password)
                }
            }
        },
        components: {
            Modal
        },
        created(){
            this.store.hideLayout();
        },
        methods:{
            async register(){
                if(!await this.v$.$validate()){
                    return;
                }
                axios.post('/register',{
                    name:this.name,
                    email:this.email,
                    password:this.password,
                    password_confirmation:this.password_confirmation,
                })
                .then((response) => {
                    if(response.data?.status == 200){
                        this.name = null;
                        this.email = null;
                        this.password = null;
                        this.password_confirmation = null;
                        this.v$.$reset()

                        swalSuccess({ 
                            icon: 'success',
                            text: 'You can now login',
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
        },
        
    }
</script>

<style scoped>
</style>