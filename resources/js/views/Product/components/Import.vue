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
                          <button type="button" class="btn btn-sm  form-control" :class="{'btn-danger' : rowData.length >0, 'btn-secondary' : !rowData.length}" :disabled="!rowData.length" @click.prevent="cancelUpload">Cancel</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between" @submitEvent="getDetails">
                            <div id="pageSizeContainer">
                                <label for="">Page Size: </label>
                                <select v-model="pageSize" @change="onPageSizeChanged"  class="form-control ">
                                    <option :value="size" v-for="(size, index) in pageSizeOptions" :key="index">{{ size }}</option>
                                </select>
                            </div>
                            <div id="quickFilterContainer">
                                <label for="">Quick Filter: </label>
                                <input type="text" v-model="globalSearchFilter" class="form-control" placeholder="Filter..." @input="onGlobalSearch">
                            </div>
                        </div>
                    </div>
                    <ag-grid-vue
                       style="width: 100%; height:519px;"
                       class="ag-theme-alpine container mt-2"
                       :columnDefs="columnDefs"
                       :rowHeight="rowHeight"
                       :rowData="rowData"
                       :defaultColDef="defaultColDef"
                       :context="context" 
                       :paginationNumberFormatter="paginationNumberFormatter"
                       @grid-ready="onGridReady"
                       :pagination="true"
                   >
                   </ag-grid-vue>
                </div>
            </form>
        </template>
        <template #footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="cancelUpload">Close</button>
            <button type="button" class="btn" @click.prevent="uploadDetails" :class="{'btn-primary' : rowData.length >0, 'btn-secondary' : !rowData.length}" :disabled="!rowData.length">Upload</button>
        </template>
    </Modal>
</template>

<script>
import defaultOptions from '@/composables/gridTableDefaultOptions.js';
import importData  from '@/composables/import/index.js';
import actionButton from '@/components/AgGridTable/action.vue';
import Modal from '@/components/Modal/modal.vue';
import { AgGridVue } from "ag-grid-vue3";
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
import { mapKeys } from '@/composables/import/mapKeys.js';
import { checkEmptyColumns } from '@/composables/import/validation.js';
import { swalSuccess,swalError } from '@/composables/sweetAlert.js';
import axios from 'axios';
const auth_token = `Bearer ${localStorage.getItem("auth-token")}`;
    export default {
        name:'ImportProduct',
        data(){
            return{
                rowHeight:50,
                pageSize:100,
                isExportAll:false,
                pageSizeOptions: [10, 100, 500, 1000],
                paginationNumberFormatter: null,
                table_fields:['name','price','quantity','description'],
                columnDefs: [
                    { headerName: "Name", field: "name", unSortIcon:true },
                    { headerName: "Price", field: "price", unSortIcon:true },
                    { headerName: "Quantity", field: "quantity", unSortIcon:true },
                    { headerName: "Description", field: "description", unSortIcon:true },
                    // {
                    //     headerName: "Action",
                    //     cellRenderer: "actionButton",
                    // }
                    
                ],
                rowData: [],
                defaultColDef: {
                  ...defaultOptions
                },
                gridApi:null,
                gridColumnApi:null,
                globalSearchFilter:null,
            }
        },
        components: {
            AgGridVue,
            actionButton,
            Modal
        },
        computed:{
            globalSearchValue(){
                return this.globalSearchFilter;
            }
        },
        async created(){
            this.paginationNumberFormatter = (params) => {
                return '[' + params.value.toLocaleString() + ']';
            };

            
        },
        methods:{
            onPageSizeChanged() {
                this.gridApi.paginationSetPageSize(Number(this.pageSize));
            },
            onGlobalSearch() {
                this.gridApi.setQuickFilter(this.globalSearchValue);
            },
            onGridReady(params) {
                this.gridApi = params.api;
                this.gridColumnApi = params.columnApi;
                this.gridApi.paginationSetPageSize(Number(this.pageSize));
                this.gridApi.sizeColumnsToFit()
            },
            resetForm() {
                this.rowData = [];
                this.pageSize = 10;
                this.globalSearchFilter = null;
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
                        this.rowData = mappedData;
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
                if(!this.rowData.length){
                    return;
                }
                // Convert data to CSV format
                const csvContent = this.rowData.map(row =>{
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
        beforeMount() {
            this.context = {
                componentParent: this
            }
        },
}
</script>

<style lang="scss" scoped>

</style>