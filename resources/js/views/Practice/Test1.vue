<template>
    <div>
        <h2>TESTS</h2>
        <label for="">Checkbox</label>
        <input type="checkbox" v-model="checkBoxValue" true-value="yes" false-value="no">
        <p>{{ checkBoxValue }}</p>


        <label for="">Radio</label>
        <input type="radio" value="one" v-model="radioInputValue">
        <input type="radio" value="two" v-model="radioInputValue">
        <p>{{ radioInputValue }}</p>


        <select v-model="selectInputValue">
            <option value="SELECT 1">Option 1</option>
            <option value="SELECT 2">Option 2</option>
        </select>
        <p>{{ selectInputValue }}</p>

        <!-- SINGLE DYNAMIC INPUT  -->
        <Input v-model="inputValue" />
        {{ inputValue }}

        <!-- MULTIPLE DYNAMIC INPUTS  -->
        <MultipleInput v-model:title="title" v-model:author="author" v-model:content="content"
            style="border-color:green;" />
        <div>
            <label for="">Title</label>
            {{ title }}
        </div>
        <div>
            <label for="">Author</label>
            {{ author }}
        </div>
        <div>
            <label for="">Content</label>
            {{ content }}
        </div>
        <button @click="activeId = (activeId + 1) % peoples.length" style="margin:10px 0px;">Next</button>
      

        <form @submit.prevent="upload" enctype="multipart/form-data">
            <label for="">Image</label>
            <input type="file"  @change="onFileChange" multiple accept="image/*">

            <label for="">JSON</label>
            <input type="file" id="json">
            <button type="submit">UPLOAD</button>
        </form>
    </div>
</template>

<script setup>
import { ref, provide, onBeforeMount } from 'vue';
import axios from 'axios';
import Input from '@/components/Form/Input.vue';
import MultipleInput from '@/components/MultipleInput.vue';
// import data from '@/views/api/Users.js';
provide('firstName','Sheesh');

const jsonFiles = ref([]);
const jsonImages = ref([]);
const activeId = ref(0);
const peoples = ref([
    {
        id: 0,
        name: 'Rojenson Lugo',
    },
    {
        id: 1,
        name: 'Kevin Mensah',
    },
    {
        id: 2,
        name: 'Ricardo Gubat',
    },
    {
        id: 3,
        name: 'Rustian Taculog',
    },
    {
        id: 4,
        name: 'John Paul Alindog',
    },
    {
        id: 5,
        name: 'Arween Agustin',
    },
])
const users = ref([]);

const title = ref(null);
const author = ref(null);
const content = ref(null);

const inputValue = ref(null);
const selectInputValue = ref(null);
const checkBoxValue = ref('no');
const radioInputValue = ref('no');
const selectedFiles = ref(null)
const onFileChange = function (event) {
    selectedFiles.value = event.target.files;
}

const upload = function () {
    if (selectedFiles.value && selectedFiles.value.length > 0) {
        const formData = new FormData();

        for (let i = 0; i < selectedFiles.value.length; i++) {
            const file = selectedFiles.value[i];

            // Validate file extension (you can modify this according to your allowed file types)
            const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExtension)) {
                console.error('Invalid file type. Allowed types are jpg, jpeg, png, and gif.');
                return;
            }

            // Validate file size (you can modify the maximum allowed size)
            const maxSizeInBytes = 5 * 1024 * 1024; // 5 MB
            if (file.size > maxSizeInBytes) {
                console.error('File size exceeds the limit (5 MB).');
                return;
            }

            formData.append('files[]', file);
        }

        axios
            .post('/api/uploadImage', formData)
            .then((response) => {
                console.log(response.data.message);
                // Handle the response or perform any additional tasks here
            })
            .catch((error) => {
                console.error(error);
                // Handle errors here
            });
    }
};

// const getUsers = async function () {
//     const api_users = await data().then(response => {
//         if (response.status >= 200 && response.status < 300) {
//             users.value = response.data;
//             console.log(users.value, 'USERS');
//         }
//     }).catch(error => {
//         throw error;
//     });

//     console.log('WOW');
// };

// const getApis = function () {
//     const shit = Apis.getComments().then(response => {
//         console.log(response, 'APIS');
//     });
//     console.log('AFTER APIS')
// }

// const getUrl = function () {
//     let url = new URL('https://jsonplaceholder.typicode.com/users');
//     const response = axios.get(url)
//         .then(response => {
//             /** DESTRUCTURE RESPONSE  OBJECT */
//             let data = response.data.map(({ name, phone, username, ...other_details }) => ({
//                 name,
//                 phone,
//                 username,
//                 other_details,
//             }));
//             console.log(data, 'URL');
//             /** DESTRUCTURE RESPONSE  ARRAY */
//             const nested = [3, 4, , 7, [5, 6]];
//             const [i, , j, o, ...others] = nested;
//             console.log(i, j, o, 'NESTED');
//             console.log(others, 'OTHERS');
//         })
//         .catch(error => {
//             console.log(error);
//         });

//     // axios.get(request).then(response=>{
//     //     console.log(response,'URLS');

//     // }).catch(error =>{
//     //     console.log(error,'ERROR');
//     // })
//     // console.log(url.host, url.headers, url.protocol, url.pathname, url.method);
// }

// onBeforeMount(() => {
//     getUsers();
//     getApis();
//     getUrl();
// })
</script>

<style lang="scss" scoped></style>