<template>
    <div id="container">
        <button @click="redo">REDO</button>
        <button @click="undo">UNDO</button>
    </div>
    <div id="pointers" @click="point">
        <div class="pointer" v-for="pointer in pointers" :key="pointer" :style="{top: `${pointer?.yAxis}`, left:`${pointer?.xAxis}`}"></div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
const pointers =  ref([]);

const history = ref([]);

const point = (event) =>{
    pointers.value.push({'xAxis':`${calculateXAxis(event.offsetX)}%`, 'yAxis':`${calculateYAxis(event.offsetY)}%`});
    console.log(Object.keys(pointers.value),'KEYS');
    console.log(Object.values(pointers.value),'VALUES');
    console.log(Object.entries(pointers.value),'ENTRIES');
}

const calculateXAxis = (xAxis) =>{
    return (xAxis / window.innerWidth) * 100
}
const calculateYAxis = (yAxis) =>{
    return (yAxis / window.innerHeight) * 100
}


const redo = () =>{
    if(history.value.length >0){
        const point = history.value.pop();
        pointers.value.push(point);
    }
}

const undo = () =>{
    if(pointers.value.length >0){
        const undoPointer = pointers.value?.pop();
        history.value.push(undoPointer);
    }
}
</script>

<style scoped>

#container{
    display: flex;
    justify-content: center;
    overflow: hidden;
}
#pointers{
    position:relative;
    height:100vh !important;
}

.pointer{
    background-color: burlywood;
    border-radius: 50%;
    height:20px;
    width:20px;
    position: absolute;
}

body{
    cursor: pointer !important;
}
</style>