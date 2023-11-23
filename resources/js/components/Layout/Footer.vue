<template>
    <footer v-if="store.layout.footer" class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted">Your footer content goes here.</span>
        </div>
    </footer>
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