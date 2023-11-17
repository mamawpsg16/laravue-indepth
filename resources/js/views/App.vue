<template>
    <div>
        <!-- Navbar -->
       <Navbar :authenticated="isAuthenticated"/>
        <!-- Sidebar -->
        <div class="container-fluid">
            <div class="row">
                <Sidebar/>
                <!-- Main content area -->
                <Main>
                    <router-view></router-view>
                </Main>
                <Footer/>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from '../components/Layout/Navbar.vue';
import Sidebar from '../components/Layout/Sidebar.vue';
import Footer from '../components/Layout/Footer.vue';
import Main from '../components/Layout/Main.vue';
import { isUserAuthenticated  } from '../composables/authentication.js';
    export default {
        data(){
            return{
                isAuthenticated:false
            }
        },
        components:{
            Navbar,
            Sidebar,
            Footer,
            Main
        },
        async created() {
            this.isAuthenticated = await isUserAuthenticated();
        }
    }
</script>

<style lang="scss" scoped>

</style>