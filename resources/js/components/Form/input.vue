<template>
    <input type="email" :class="inptClass"  :value="modelValue" @input="emitValue($event.target.value)" v-bind="$attrs">
</template>

<script>
    export default {
        inject: ['firstName'],
        props:{
            inptClass:{
                type: String,
                required: false,
                default: 'form-control'
            }, 
            modelValue: {
                type: String,
            },
        },
        emits: ['update:modelValue'],
        methods:{
            emitValue(newValue){
                this.$emit('update:modelValue',newValue)
            }
            
        },
        mounted(){
            
        },
        beforeUpdate() {
            console.log('beforeUpdate',this.firstName,'INJECT')
        },
        updated() {
            console.log('updated',this.firstName,'INJECT')
        },
    }
</script>

<style lang="scss" scoped>

</style>