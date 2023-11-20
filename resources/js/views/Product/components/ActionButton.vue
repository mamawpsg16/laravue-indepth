<template>
    <div>
        <!-- <button @click="visitProduct" class="btn btn-sm btn-primary me-2">Visit</button> -->
        <button class="btn btn-sm btn-primary me-2" @click="showProduct">View</button>
        <button @click="deleteProduct" class="btn btn-sm btn-danger me-2">Delete</button>
    </div>
</template>

<script>
    export default {
        methods: {
            showProduct() {
                this.params.context.componentParent.showProduct(this.params.data)
            },
            // editProduct() {
            //     this.params.context.componentParent.viewProduct(this.params.data)
            // },
            deleteProduct() {
                this.params.context.componentParent.deleteConfirmation(this.params.data)
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>