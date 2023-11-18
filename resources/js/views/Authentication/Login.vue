<template>
    <form @submit.prevent="login">
        <div class="row d-flex flex-row">
            <div class="col-4">
                <label for="exampleInputEmail1" class="form-label">Email</label>
                <input type="email" class="form-control" v-model="email" autocomplete="email" required>
            </div>
            <div class="col-4">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" v-model="password" autocomplete="new-password" required>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 mx-auto">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </div>
    </form>
</template>

<script>

import Modal from '@/components/Modal/modal.vue';
import { swalSuccess, swalError  } from '@/composables/sweetAlert.js';
import axios from 'axios';
    export default {
        name:'Login',
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
            async login(){
                await axios.get("/sanctum/csrf-cookie");
                axios.post('/login',{email:this.email, password: this.password})
                .then((response) => {
                    if(response.data?.status == 200){
                        this.name = null;
                        this.email = null;

                        swalSuccess({ 
                            icon: 'success',
                            text: 'You can now login',
                            title: 'User logined successfully!',
                            showConfirmButton: false,
                        })
                        localStorage.setItem('auth-token', response.data.authentication_token);
                        
                        window.location.href = '/dashboard'
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