<template>
    <form @submit.prevent="register">
        <div class="row d-flex flex-row">
            <div class="col-4">
                <label for="exampleInputEmail1" class="form-label">Name</label>
                <input type="text" class="form-control" v-model="name" autocomplete="name" required>
            </div>
            <div class="col-4">
                <label for="exampleInputEmail1" class="form-label">Email</label>
                <input type="email" class="form-control" v-model="email" autocomplete="email" required>
            </div>
            <div class="col-4">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" v-model="password" autocomplete="new-password" required>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for="exampleInputPassword1" class="form-label">Confirmation Password</label>
                <input type="password" class="form-control" v-model="password_confirmation" autocomplete="new-password" required>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary" >Register</button>
            </div>
        </div>
    </form>
     
    
</template>

<script>

import Modal from '@/components/Modal/modal.vue';
import { swalSuccess, swalError  } from '@/composables/sweetAlert.js';
import axios from 'axios';
    export default {
        name:'Register',
        data(){
            return{
                name: null,
                email: null,
                password: null,
                password_confirmation: null
            }
        },
        components: {
            Modal
        },
        methods:{
            register(){
                console.log('REGISTER');
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