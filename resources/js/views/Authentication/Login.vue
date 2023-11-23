<template>
    <form @submit.prevent="login"  class="row d-flex flex-column justify-content-center align-items-center">
        <div class="mx-auto">
            <div class="col-4">
                
                <label for="exampleInputEmail1" class="form-label">Email</label>
                <input required type="email" @click="clearFormErrors" :class="{ 'is-invalid': v$.email.$dirty && (v$.email.required.$invalid || v$.email.email.$invalid) }" class="form-control" v-model="email" autocomplete="email" >
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
                <input required type="password" @click="clearFormErrors" class="form-control"  :class="{ 'is-invalid': v$.password.$dirty && (v$.password.required.$invalid  || v$.password.minLength.$invalid || this.form_error) }"  v-model="password" autocomplete="new-password" >
                <div  v-if="v$.password.$dirty" :class="{ 'invalid-feedback': (v$.password.required.$invalid || v$.password.minLength.$invalid)}">
                    <p v-if="v$.password.required.$invalid">
                        Password is required
                    </p>
                    <p v-if="v$.password.minLength.$invalid">
                        Password must have a min length of 8 character
                    </p>
                </div>
                <p :class="{ 'invalid-feedback': (this.form_error)}" >{{ form_error }}</p>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 mx-auto">
                <p>Dont have account yet,  <router-link :to="{name:'register'}"> Sign up now!</router-link></p>
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </div>
    </form>
</template>

<script>

import Modal from '@/components/Modal/modal.vue';
import { swalSuccess, swalError  } from '@/composables/sweetAlert.js';
import { useLayoutStore } from '../../pinia/useLayoutStore';
import { useVuelidate } from '@vuelidate/core'
import { required, email, minLength  } from '@vuelidate/validators'
import axios from 'axios';
    export default {
        setup () {
            return { v$: useVuelidate({ $autoDirty: true }) }
        },
        name:'Login',
        data(){
            return{
                email: null,
                password: null,
                password_confirmation: null,
                store : useLayoutStore(),
                form_error:null
            }
        },
        validations () {
            return {
                email: { required, email }, 
                password: { required, minLength: minLength(8) },
            }
        },
        components: {
            Modal
        },
        created(){
            this.store.hideLayout();
        },
        computed:{
          
        },
        methods:{
            clearFormErrors(){
                this.form_error = null;
            },
            async login(){
                if(!await this.v$.$validate()){
                    return;
                }
                await axios.get("/sanctum/csrf-cookie");
                axios.post('/login',{email:this.email, password: this.password})
                .then((response) => {
                    console.log(response,'RESPONSE');
                    const { status, message, authentication_token } = response.data;
                    if(status == 200){
                        swalSuccess({ 
                            icon: 'success',
                            text: 'You can now login',
                            title: 'User logined successfully!',
                            showConfirmButton: false,
                        })
                        localStorage.setItem('auth-token', authentication_token);
                        
                        window.location.href = '/dashboard'
                    }else if(status == 404){
                        this.v$.password.$dirty = true;
                        this.form_error = message;
                    }
                })
                .catch(function (error) {
                    console.log(error,'ERRORS');
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