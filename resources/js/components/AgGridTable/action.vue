<template>
    <div>
        <button @click="onClick" class="btn btn-sm btn-primary ">Click me</button>
    </div>
</template>

<script>
    export default {
        methods: {
            onClick() {
                console.log(this.params)
                this.params.context.componentParent.getRowData(this.params.data)
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>