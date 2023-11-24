<template>
    <div>
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
      <!-- this will contain all $errors from every <PersonForm/> component -->
      <p v-for="error of v.$errors" :key="error.$uid">
        {{ error.$message }}
      </p>
    </div>
  </template>
  
  <script>
  import { useVuelidate } from '@vuelidate/core'
  import { required, email, minLength, maxLength  } from '@vuelidate/validators'
  
  export default {
    data () {
      return {
        householdName: '',
        people: []
      }
    },
    validations () {
      return { householdName: { required } }
    },
    setup: () => ({ v: useVuelidate() }),
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