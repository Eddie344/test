<template>
    <div class="d-flex justify-content-center mt-5" v-if="loading">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="container" v-else>
        <table class="table">
            <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col" v-for="department in departments">{{ department.label }}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="employee in employees">
                <th scope="row">{{ employee.name +' '+ employee.surname }}</th>
                <td v-for="department in departments">
                    <div v-if="isPlus(employee, department)"><i class="fa fa-check text-success"></i></div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "MainComponent",
        data() {
            return {
                loading: false,
                departments: [],
                employees: [],
            }
        },
        mounted() {
            this.loadDepartments();
            this.loadEmployees();
        },
        methods: {
            isPlus(employee, department) {
                let plus = false;
                employee.departments.forEach(function(item){
                    if(item.id === department.id) plus = true
                    console.log(item.id);
                    console.log(department.id);
                });
                return plus;
            },
            loadDepartments() {
                this.loading = true;
                axios.get('api/departments')
                    .then(response => {
                        this.departments = response.data;
                    })
                    .catch(error=>{
                        console.log(error)
                    });
            },
            loadEmployees() {
                axios.get('api/employees')
                    .then(response => {
                        this.employees = response.data;
                        this.loading = false;
                    })
                    .catch(error=>{
                        console.log(error)
                    });
            },
        }
    }
</script>

<style scoped>
    .container {
        margin-top: 30px;
    }

</style>
