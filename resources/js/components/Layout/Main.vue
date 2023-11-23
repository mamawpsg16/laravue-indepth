<template>
    <main v-if="store.layout.main" class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <!-- Your Vue router views will be rendered here -->
            <!-- <router-view></router-view> -->
            <slot></slot>
    </main>
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