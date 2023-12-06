<template>
    <Modal class="modal-xl" targetModal="import-product-modal" modaltitle="Import Product Details" :backdrop="true" :escKey="false">
        <template #body>
            <form id="import">
                <div class="row d-flex justify-content-between align-items-center mb-4">
                    <label for="formFile">Upload Template</label>
                    <div class="col-9">
                            <input class="form-control" type="file" id="formFile" @change="uploadTemplate" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                    </div>
                    <div class="col-3">
                          <button type="button" class="btn btn-sm  form-control" :class="{'btn-danger' : data.length >0, 'btn-secondary' : !data.length}" :disabled="!data.length" @click.prevent="cancelUpload">Cancel</button>
                    </div>
                </div>
                <Dataset :data="data" :columns="columns" @submitEvent="getDetails">
                    <template #body="{ data, index }">
                        <tr>
                            <td>{{ data.name }}</td>
                            <td>{{ data.price }}</td>
                            <td>{{ data.quantity }}</td>
                            <td>{{ data.description }}</td>
                        </tr>
                    </template>
                </Dataset>
            </form>
        </template>
        <template #footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="cancelUpload">Close</button>
            <button type="button" class="btn" @click.prevent="uploadDetails" :class="{'btn-primary' : data.length >0, 'btn-secondary' : !data.length}" :disabled="!data.length">Upload</button>
        </template>
    </Modal>
</template>

<script>
import importData  from '@/composables/import/index.js';
import Modal from '@/components/Modal/modal.vue';
import { mapKeys } from '@/composables/import/mapKeys.js';
import { checkEmptyColumns } from '@/composables/import/validation.js';
import { swalSuccess,swalError } from '@/composables/sweetAlert.js';
import Dataset from '@/components/Dataset/Index.vue';
import axios from 'axios';
const auth_token = `Bearer ${localStorage.getItem("auth-token")}`;
    export default {
        name:'ImportProduct',
        data(){
            return{
                isExportAll:false,
                table_fields:['name','price','quantity','description'],
                columns:[
                    {
                        name: 'Name',
                        field: 'name',
                        sort: ''
                    },
                    {
                        name: 'Price',
                        field: 'price',
                        sort: ''
                    },
                    {
                        name: 'Quantity',
                        field: 'quantity',
                        sort: ''
                    },
                    {
                        name: 'Description',
                        field: 'description',
                        sort: ''
                    },
                ],
                data: [],
            }
        },
        components: {
            Modal,
            Dataset
        },
        methods:{
            resetForm() {
                this.data = [];
                document.getElementById("import").reset();
            },
            uploadTemplate(event) {
                // Get the selected file from the input
                const file = event.target.files[0];

                // Check if a file is selected
                if (!file) {
                    console.error('No file selected.');
                    return;
                }

                // Create a new FileReader instance
                const reader = new FileReader();

                // Define the onload event handler for when the file is successfully read
                reader.onload = (event) => {
                    // Extract the binary data from the FileReader result
                    const data = event.target.result;
                    console.log(data,'data')
                    try {
                        const template_details = importData(data);
                       
                        console.log(template_details,'data')
                       const mappedData= mapKeys(this.table_fields,template_details.slice(1));

                        console.log(mappedData,'mappedData')
                       const hasEmptyDetails = checkEmptyColumns(undefined, mappedData);
                       console.log(hasEmptyDetails,'hasEmptyDetails')
                        if(hasEmptyDetails.length >0){
                            swalError({
                                icon: 'error',
                                title: `${hasEmptyDetails.join('')} cannot be empty!`,
                                text: "Kindly Check Template!",
                                showConfirmButton: false,
                            })

                            return;
                        }
                        this.data = mappedData;
                    } catch (error) {
                        // Log an error if there's a problem reading the Excel file
                        console.error('Error reading the Excel file:', error);
                    }
                };

                // Define the onerror event handler for any FileReader errors
                reader.onerror = (event) => {
                    // Log an error if there's an issue during the file reading process
                    console.error('File reading error:', event.target.error);
                };

                // Read the contents of the selected file as binary data
                reader.readAsBinaryString(file);
            },
            uploadDetails(){
                if(!this.data.length){
                    return;
                }
                // Convert data to CSV format
                const csvContent = this.data.map(row =>{
                    return Object.values(row).join('\t');
                }).join('\n');
          
                
                // Create a Blob containing the CSV data
                const blob = new Blob([csvContent], { type: 'text/csv' });
                const formData = new FormData();
                formData.append('csv',blob,"insert_data.csv");
                axios.post('/api/importProducts',formData,{
                    headers:{
                        'Authorization': auth_token
                    }
                }).then(response =>{
                    this.resetForm();
                    this.$emit('addRow','import');
                    swalSuccess({ 
                        icon: 'success',
                        text: 'Products Uploaded!',
                        title: response.data?.message,
                        showConfirmButton: false,
                    })
                    
                }).catch(error =>{
                    
                })
            },
            cancelUpload(){
                this.resetForm();
            }
        },
}
</script>

<style lang="scss" scoped>

</style>