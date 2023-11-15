<template>
    <!-- <button :class="btnClass" @click="btnSubmit()"> -->
    <button :class="btnClass" @click="$emit('emitValue','abc')">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        props:{
            btnClass:{
                type: String,
                required: false,
                default: 'btn'
            },
        },
        methods:{
            btnSubmit(){
                this.$emit('submitEvent','sheesh')
            }
        }
        
    }
</script>

<style lang="scss" scoped>

</style>