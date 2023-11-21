<template>
    <div>
        <!-- <button @click="visitShop" class="btn btn-sm btn-primary me-2">Visit</button> -->
        <button @click="viewShop" class="btn btn-sm btn-primary me-2">View</button>
        <button @click="deleteShop" class="btn btn-sm btn-danger me-2">Delete</button>
    </div>
</template>

<script>
    export default {
        methods: {
            visitShop() {
                this.params.context.componentParent.visitShop(this.params.data)
            },
            viewShop() {
                this.params.context.componentParent.viewShop(this.params.data)
            },
            deleteShop() {
                this.params.context.componentParent.deleteConfirmation(this.params.data)
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>