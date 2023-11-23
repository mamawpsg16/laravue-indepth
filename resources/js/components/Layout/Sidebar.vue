<template>
    <nav v-if="store.layout.sidebar" id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar vh-100">
        <div class="position-sticky">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <router-link to="/dashboard" class="nav-link"><i class="fa-solid fa-chart-line"></i> &nbsp;Dashboard</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/products" class="nav-link"><i class="fa-solid fa-basket-shopping"></i> &nbsp;Products</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/shops" class="nav-link"><i class="fa-solid fa-store"></i> &nbsp;Shops</router-link>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
import { useLayoutStore } from '@/pinia/useLayoutStore.js';
    export default {
        data(){
            return{
                store : null
            }
        },
        created(){
            this.store =  useLayoutStore()
        }
    }
</script>

<style lang="scss" scoped>

</style>