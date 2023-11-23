<template>
    <nav  v-if="store.layout.navbar" class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item" v-if="!authenticated">
                        <router-link to="/register" class="nav-link">Register</router-link>
                    </li>
                    <li class="nav-item" v-if="!authenticated">
                        <router-link to="/login" class="nav-link">Login</router-link>
                    </li>
                    <li class="nav-item me-2">{{ authenticated.data?.email }}</li>
                    <li class="nav-item"><button class="btn btn-secondary btn-sm" @click="logout">Logout</button></li>
                    <!-- Add more navigation items as needed -->
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
import { useLayoutStore } from '@/pinia/useLayoutStore.js';
import axios from 'axios';
import { useRouter,useRoute } from 'vue-router';
const auth_token = `Bearer ${localStorage.getItem("auth-token")}`;
    export default {
        props:['authenticated'],
        data(){
            return{
                store : null,
                router: useRouter()
            }
        },
        created(){
            this.store =  useLayoutStore()
        },
        methods:{
            async logout(){
                await axios.post('/api/logout',{},{
                    headers:{
                        'Authorization': auth_token
                    }
                }).then(response =>{
                    const { status } = response.data;
                    if(status == 200){
                        console.log(status);
                        localStorage.removeItem('auth-token');

                        this.router.push({name:"login"})
                        
                    }

                }).catch(error =>{
                    
                })
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>