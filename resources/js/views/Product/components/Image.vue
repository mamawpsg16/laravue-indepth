<template>
    <div>
        <img  style="width:100px; height:30px;" :src="this.params.data.product_image"/>
    </div>
</template>

<script>
    export default {
        data(){
            return{
            }
        },
        methods: {
          
        },
        beforeMount() {
            console.log(this?.params,'BEFORE MOUNT')
        }
    }
</script>

<style lang="scss" scoped>

</style>