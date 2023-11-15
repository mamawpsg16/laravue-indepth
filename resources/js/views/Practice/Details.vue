<template>
    <div>
        Home
        <form action="">
            <div class="form-group">
                <!-- <Label v-slot="slotProps"> -->
                <Label>
                    <template #label1="label1Props">First Name</template>
                    <!-- <template #label2>Second Name</template> -->
                    <!-- {{ slotProps.text }}  {{ slotProps.count }} -->
                    <!-- <template #label1="label1Props"> {{label1Props.text}}  {{ label1Props.count}} First Name</template> -->
                </Label>
                <!-- <input type="text" ref="input"> -->
                <Input v-model="firstName" ref="input"></Input>
            </div>
            <Button btnClass="btn btn-md btn-primary text-left" type="button" @emit-value="submit">Submit</Button>
            <Button btnClass="btn btn-md btn-primary text-left" type="button"  @click="increment">
                {{ count }}
            </Button>
            {{ randomNumber }}
            <div class="row">
                <div class="col-md-8  mx-auto">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Age</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <tr v-for="people in studentsForDisplay" :key="people.firstName">
                                <td>{{ people.firstName }}</td>
                                <td>{{ people.lastName }}</td>
                                <td>{{ people.age }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Team</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <tr v-for="(team, index) in teams" :key="index">
                                <td>{{ team }} {{ index }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <span v-for="n in 10">{{ n }}</span>
                    <!-- <p class="text-end" :class="{'active' : isActive}, {'bold' : isBold}">Count: {{ studentsCount }}</p> -->
                    <p class="text-end" :class="classObject" :style="{ 'font-size': fontSize + 'px' }">Count: {{ studentsCount }}</p>

                    <ul>
                        <!-- <li v-for="(team, index) in sf_teams"  :key="index"> <span v-if="team.isActive">-></span>{{ team }}</li> -->
                        <ListItem v-for="(team, index) in sf_teams" :team="team.name" :id="team.id" :isActive="team.id == activeTeamId" :key="index"></ListItem>
                    </ul>
                    <Button class="btn-primary" @click="chooseTeam" type="button">Choose a Team</Button>
                    Mouse position is at: {{ x }}, {{ y }}
                </div>
            </div>
        </form> 
    </div>
</template>

<script>
// import Button from '../components/Form/Button.vue';
import { computed } from 'vue'
import { useMouse } from '@/components/mouseTracker.vue';
import Label from '@/components/Form/label.vue';
import ListItem from '@/components/listItem.vue'
import Button from '@/components/Form/button.vue';
import Input from '@/components/Form/input.vue';
    export default {
        provide() {
            // use function syntax so that we can access `this`
            return {
                firstName: computed(() => this.firstName)
            }
        },
        data(){
            return{
                firstName: null,
                randomNumber:0,
                count:0,
                students:[
                    {firstName:'Kevin', lastName:'Mensah', age:20, birthDate: '2022-01-16'},
                    {firstName:'Rojenson', lastName:'Lugo', age:21, birthDate: '2022-02-22'},
                    {firstName:'John Paul', lastName:'Alindog', age:22, birthDate: '2022-03-15'},
                    {firstName:'Rustian', lastName:'Taculog', age:23, birthDate: '2022-04-31'},
                    {firstName:'Ricardo', lastName:'Gubat', age:24, birthDate: '2022-05-07'},
                    {firstName:'Nino', lastName:'Escalera', age:25, birthDate: '2022-06-08'},
                ],
                teams:[
                    'SKT1',
                    'Team Liquid',
                    'DRX'
                ],
                sf_teams:[
                    {id:0, name:'SakalBibeh'},
                    {id:1, name:'TC7 Warriors'},
                    {id:2, name:'Sundalo'},
                    {id:3, name:'Team Mabalazik'}
                ],
                isActive: true,
                isBold: true,
                hasError:true,
                fontSize:30,
                activeTeamId:0
            }
        },
        setup(){
            const { x, y } = useMouse()

            return { x, y };
        },
        components:{
            Button,
            Label,
            Input,
            ListItem
        },
        computed:{
            studentsForDisplay() {
                return this.students.map(student => {
                    return {
                        firstName: student.firstName,
                        lastName: student.lastName,
                        age: student.age  
                    }
                })
            },
            studentsCount(){
                return this.students.length;
            },
            classObject() {
                return {
                    active: !this.hasError && this.isActive,
                    bold: this.isBold
                }
            }
        },
        methods:{
            submit(payload){
                console.log('EMIT SUCCESS',payload)
            },
            btnSubmit(payload){
                console.log(payload);
            },
            async increment(e) {
                console.log(e.target.tagName)
                this.count++
                this.$nextTick(() => {
                    // Code here will run after the DOM has been updated
                    // console.log(this.$el.textContent,'this.$el.textContent')
                    console.log('Updated message:', 'Update after dom been updated');
                });
            },
            chooseTeam() {
            // Find the first inactive team and make it active
                this.activeTeamId = ( this.activeTeamId + 1 ) % this.sf_teams.length
            },
        },
        // beforeCreate(){
        //     console.log('beforeCreate',this.count)
        // },
        // created(){
        //     console.log('created',this.count)
        // },
        mounted() {
            // console.log(this.$refs.input.focus())
            console.log(this.$route)
            console.log(this.$router)
            console.log('mounted',this.count)
        },
        // beforeUpdate() {
        //     console.log('beforeUpdate',this.count)
        // },
        // updated() {
        //     console.log('updated',this.count)
        // },
        // beforeUnmount() {
        //     console.log('beforeUnmount',this.count)
        // },
        // unmounted() {
        //     console.log('unmounted',this.count)
        // },
        watch: {
            count(newValue, oldValue){
                console.log(newValue, oldValue)
               this.randomNumber = Math.trunc(Math.random() * 5) + 1;
            }
            // count:{
            //     handler(newValue) {
            //         this.randomNumber = Math.trunc(Math.random() * 5) + 1;
            //     },
               
            // }
        }
    }
</script>

<style  scoped>
.active{
    color:green;
}
.bold{
    font-weight: bold;
}
ul{
    list-style-type: none;
}
</style>