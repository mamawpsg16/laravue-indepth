<template>
    <!-- <div>
      <input type="text" v-model="householdName">
      <form>
        <div class="row" v-for="person in people">
            <div class="col-4">
                <label for="">Name</label>
                <input type="text" name="" id="" v-model="person.name">
            </div>
            <div class="col-4">
                <label for="">Email</label>
                <input type="email" name="" id="" v-model="person.email">
            </div>
            <div class="col-4">
                <label for="">Name</label>
                <input type="text" name="" id="" v-model="person.address">
            </div>
        </div>
      </form>
      <button @click.prevent="addPerson">Add person</button>
      <p v-for="error of v.$errors" :key="error.$uid">
        {{ error.$message }}
      </p>

      
    </div> -->
    <dataset
        v-slot="{ ds }"
        :ds-data="formattedData"
        :ds-search-in="columnsToFilter"
        :ds-sortby="sortBy(cols)"
      >
      <div class="d-flex justify-content-between align-items-center mb-2" :data-page-count="ds.dsPagecount">
        <div class="mb-2 mb-md-0">
          <dataset-show />
        </div>
        <div class="col-md-4">
          <dataset-search ds-search-placeholder="Search..." />
        </div>
      </div>
      <div class="row">
        <div class="table-responsive col-md-12">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th v-for="(th, index) in cols" :key="th.field" :class="['sort', th.sort]"  @click="onColumnSort($event, index,cols)">
                  {{ th.name }} <i class="fa-solid fa-sort"></i>
                </th>
              </tr>
            </thead>
            <dataset-item tag="tbody">
              <template #default="{ row, rowIndex }">
                <tr>
                  <td>{{ row.firstName }}</td>
                  <td>{{ row.lastName }}</td>
                  <td>{{ row.birthDate }}</td>
                </tr>
              </template>
              <template #noDataFound>
                <div class="col-md-12 pt-2">
                  <p class="text-center">No results found</p>
                </div>
              </template>
            </dataset-item>
          </table>
        </div>
      </div>
      <div class="d-flex flex-md-row flex-column justify-content-between align-items-center">
          <DatasetInfo class="py-3 fs-sm" />
          <DatasetPager class="flex-wrap py-3 fs-sm" />
      </div>
    </dataset>
  </template>
  
  <script>
  import { useVuelidate } from '@vuelidate/core'
  import { required, email, minLength, maxLength  } from '@vuelidate/validators'
  import { Dataset, DatasetItem, DatasetInfo, DatasetPager, DatasetSearch, DatasetShow } from 'vue-dataset'
  import { sortBy, onColumnSort } from '@/composables/dataset/index.js';
  import { formatDate } from '@/composables/helpers/index.js'
  export default {
    setup: () => (
      { 
        v: useVuelidate(),
        sortBy,
        onColumnSort
      }
    ),
    data () {
      return {
        householdName: '',
        people: [],
        cols: [
          {
            name: 'First Name',
            field: 'firstName',
            sort: ''
          },
          {
            name: 'Last Name',
            field: 'lastName',
            sort: ''
          },
          {
            name: 'Birthdate',
            field: 'birthDate',
            sort: ''
          }
        ],
        users:[
            {
              firstName: 'John',
              lastName: 'Doe',
              birthDate: '2004-02-11'
            },
            {
              firstName: 'George',
              lastName: 'Adams',
              birthDate: '2003-07-28'
            },
        ],
      }
    },
    components:{
      Dataset,
      DatasetItem,
      DatasetInfo,
      DatasetPager,
      DatasetSearch,
      DatasetShow
    },
    validations () {
      return { householdName: { required } }
    },
    computed:{
      columnsToFilter(){
        const columns = [];
        for (const key in this.users[0]) {
          if(key != 'birthDate'){
            columns.push(key);
          }
        }
        return columns;
      },

      formattedData(){
        return this.users.map(user => {
          return {
            ...user,
            birthDate: formatDate(undefined,user.birthDate),
          };
        });
      }
    },
    methods: {
      addPerson () {
        this.people.push({
          name: '',
          email: '',
          address: ''
        })
      }
    }
  }
  </script>