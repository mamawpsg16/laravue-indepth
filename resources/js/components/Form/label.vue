<template>
   <label :class="lblClass">
        <!-- <slot text="greetingMessage" :count="1"></slot> -->
        <slot name="label1" text="greetingMessage" :count="1"></slot>
    </label>
    <!-- <label :class="lblClass">
        <slot name="label2"></slot>
    </label> -->
</template>

<script>
    export default {
        props:{
            lblClass:{
                type: String,
                required: false,
                default: 'btn'
            },
        }
        
    }
</script>

<style lang="scss" scoped>

</style>