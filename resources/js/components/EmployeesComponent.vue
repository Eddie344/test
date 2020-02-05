<template>
    <div class="d-flex justify-content-center mt-5" v-if="pageLoading">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="container" v-else>
        <div class="container-inner">
            <div class="top-panel">
                <button type="button" class="btn btn-primary" v-b-modal.modal-add-employee>
                    <i class="fa fa-plus"></i> Новый сотрудник
                </button>

                <b-modal id="modal-add-employee" title="Добавление сотрудника" size="sm" hide-footer>
                    <form @submit.prevent="addEmployee">
                        <div class="form-group">
                            <label for="new-surname">Фамилия:</label>
                            <b-form-input
                                id="new-surname"
                                v-model.trim="$v.newEmployee.surname.$model"
                                :state="validateNewEmployeeState('surname')"
                                aria-describedby="input-live-feedback"
                            ></b-form-input>
                            <div class="invalid-feedback" v-if="!$v.newEmployee.surname.required">
                                Поле обязательно для заполнения
                            </div>
                            <div class="invalid-feedback" v-if="!$v.newEmployee.surname.minLength || !$v.newEmployee.surname.maxLength">
                                Введите от 3 до 20 символов
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new-name">Имя:</label>
                            <b-form-input
                                id="new-name"
                                v-model.trim="$v.newEmployee.name.$model"
                                :state="validateNewEmployeeState('name')"
                                aria-describedby="input-live-feedback"
                            ></b-form-input>
                            <div class="invalid-feedback" v-if="!$v.newEmployee.name.required">
                                Поле обязательно для заполнения
                            </div>
                            <div class="invalid-feedback" v-if="!$v.newEmployee.name.minLength || !$v.newEmployee.name.maxLength">
                                Введите от 3 до 20 символов
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new-patronymic">Отчество:</label>
                            <b-form-input
                                id="new-patronymic"
                                v-model.trim="$v.newEmployee.patronymic.$model"
                                :state="validateNewEmployeeState('patronymic')"
                                aria-describedby="input-live-feedback"
                            ></b-form-input>
                            <div class="invalid-feedback" v-if="!$v.newEmployee.patronymic.required">
                                Поле обязательно для заполнения
                            </div>
                            <div class="invalid-feedback" v-if="!$v.newEmployee.patronymic.minLength || !$v.newEmployee.patronymic.maxLength">
                                Введите от 3 до 20 символов
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new-gender">Пол</label>
                            <b-form-select
                                id="new-gender"
                                v-model="$v.newEmployee.gender_id.$model"
                                :state="validateNewEmployeeState('gender_id')"
                                :options="genders"
                                value-field="id"
                                text-field="label"
                            >
                                <template v-slot:first>
                                    <b-form-select-option :value="null" disabled>-- Укажите пол --</b-form-select-option>
                                </template>
                            </b-form-select>
                            <div class="invalid-feedback" v-if="!$v.newEmployee.gender_id.required">
                                Поле обязательно для заполнения
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new-salary">Зарплата:</label>
                            <b-form-input
                                id="new-salary"
                                type="number"
                                v-model.trim="$v.newEmployee.salary.$model"
                                :state="validateNewEmployeeState('salary')"
                                aria-describedby="input-live-feedback"
                            ></b-form-input>
                            <div class="invalid-feedback" v-if="!$v.newEmployee.salary.required">
                                Поле обязательно для заполнения
                            </div>
                            <div class="invalid-feedback" v-if="!$v.newEmployee.salary.between">
                                Укажите число от 1 до 100 000
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new-departments">Отделы</label>
                            <b-form-select
                                v-model="$v.newEmployee.departments.$model"
                                :options="departments"
                                multiple
                                :state="validateNewEmployeeState('departments')"
                                value-field="id"
                                text-field="label"
                            ></b-form-select>
                        </div>
                        <button type="submit" class="btn btn-success" v-if="!$v.newEmployee.$error && $v.newEmployee.$dirty" :disabled="actionLoading">
                            <span v-if="actionLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span v-if="!actionLoading">Добавить</span>
                        </button>
                        <button type="button" class="btn btn-secondary" @click="$bvModal.hide('modal-add-employee')">
                            Отмена
                        </button>
                    </form>
                </b-modal>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Фамилия</th>
                    <th scope="col">Имя</th>
                    <th scope="col">Отчество</th>
                    <th scope="col">Пол</th>
                    <th scope="col">Зарплата</th>
                    <th scope="col">Отделы</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="employee in employees">
                    <td>{{ employee.surname }}</td>
                    <td>{{ employee.name }}</td>
                    <td>{{ employee.patronymic }}</td>
                    <td>{{ employee.gender.label }}</td>
                    <td>{{ employee.salary }}</td>
                    <td>
                        <b-dropdown size="lg"  variant="link" toggle-class="text-decoration-none p-0" no-caret>
                            <template v-slot:button-content>
                                <i class="fa fa-chevron-down"></i>
                            </template>
                            <b-dropdown-text v-for="department in employee.departments" :key="department.id">{{ department.label }}</b-dropdown-text>
                        </b-dropdown>
                    </td>
                    <td>
                        <button type="button" class="btn btn-link" @click="editModal(employee)">
                            <h5><i class="fa fa-pencil text-primary"></i></h5>
                        </button>
                        <button type="button" class="btn btn-link" @click="removeModal(employee)">
                            <h5><i class="fa fa-trash-o text-danger"></i></h5>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
            <!--Remove modal-->
            <b-modal id="modal-remove-employee" size="sm" title="Удаление сотрудника" hide-footer>
                <button type="button" class="btn btn-danger" @click="removeEmployee" :disabled="actionLoading">
                    <span v-if="actionLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span v-if="!actionLoading">Удалить</span>
                </button>
            </b-modal>
            <!--Edit modal-->
            <b-modal id="modal-edit-employee" title="Редактирование сотрудника" size="sm" hide-footer>
                <form @submit.prevent="editEmployee">
                    <div class="form-group">
                        <label for="edit-surname">Фамилия:</label>
                        <b-form-input
                            id="edit-surname"
                            v-model.trim="$v.editingEmployee.surname.$model"
                            :state="validateEditEmployeeState('surname')"
                            aria-describedby="input-live-feedback"
                        ></b-form-input>
                        <div class="invalid-feedback" v-if="!$v.editingEmployee.surname.required">
                            Поле обязательно для заполнения
                        </div>
                        <div class="invalid-feedback" v-if="!$v.editingEmployee.surname.minLength || !$v.editingEmployee.surname.maxLength">
                            Введите от 3 до 20 символов
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-name">Имя:</label>
                        <b-form-input
                            id="edit-name"
                            v-model.trim="$v.editingEmployee.name.$model"
                            :state="validateEditEmployeeState('name')"
                            aria-describedby="input-live-feedback"
                        ></b-form-input>
                        <div class="invalid-feedback" v-if="!$v.editingEmployee.name.required">
                            Поле обязательно для заполнения
                        </div>
                        <div class="invalid-feedback" v-if="!$v.editingEmployee.name.minLength || !$v.editingEmployee.name.maxLength">
                            Введите от 3 до 20 символов
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-patronymic">Отчество:</label>
                        <b-form-input
                            id="edit-patronymic"
                            v-model.trim="$v.editingEmployee.patronymic.$model"
                            :state="validateEditEmployeeState('patronymic')"
                            aria-describedby="input-live-feedback"
                        ></b-form-input>
                        <div class="invalid-feedback" v-if="!$v.editingEmployee.patronymic.required">
                            Поле обязательно для заполнения
                        </div>
                        <div class="invalid-feedback" v-if="!$v.editingEmployee.patronymic.minLength || !$v.editingEmployee.patronymic.maxLength">
                            Введите от 3 до 20 символов
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-gender">Пол</label>
                        <b-form-select
                            id="edit-gender"
                            v-model="$v.editingEmployee.gender_id.$model"
                            :state="validateEditEmployeeState('gender_id')"
                            :options="genders"
                            value-field="id"
                            text-field="label"
                        ></b-form-select>
                    </div>
                    <div class="form-group">
                        <label for="edit-salary">Зарплата:</label>
                        <b-form-input
                            id="edit-salary"
                            type="number"
                            v-model.trim="$v.editingEmployee.salary.$model"
                            :state="validateEditEmployeeState('salary')"
                            aria-describedby="input-live-feedback"
                        ></b-form-input>
                        <div class="invalid-feedback" v-if="!$v.editingEmployee.salary.required">
                            Поле обязательно для заполнения
                        </div>
                        <div class="invalid-feedback" v-if="!$v.editingEmployee.salary.between">
                            Укажите число от 1 до 100 000
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-departments">Отделы</label>
                        <b-form-select
                            v-model="$v.editingEmployee.departments.$model"
                            :options="departments"
                            multiple
                            :state="validateEditEmployeeState('departments')"
                            value-field="id"
                            text-field="label"
                        ></b-form-select>
                    </div>
                    <button type="submit" class="btn btn-success" v-if="!$v.editingEmployee.$error && $v.editingEmployee.$anyDirty" :disabled="actionLoading">
                        <span v-if="actionLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span v-if="!actionLoading">Изменить</span>
                    </button>
                    <button type="button" class="btn btn-secondary" @click="$bvModal.hide('modal-edit-employee')">
                        Отмена
                    </button>
                </form>
            </b-modal>
        </div>
    </div>
</template>

<script>
    import { required, minLength, maxLength, between } from 'vuelidate/lib/validators'
    export default {
        name: "EmployeesComponent",
        data() {
            return {
                pageLoading: false,
                actionLoading: false,
                genders: [],
                departments: [],
                employees: [],
                newEmployee: {
                    surname: '',
                    name: '',
                    patronymic: '',
                    gender_id: null,
                    salary: '',
                    departments: [],
                },
                removingEmployee: [],
                editingEmployee: {},
            }
        },
        validations: {
            newEmployee:{
                surname: {
                    required,
                    minLength: minLength(3),
                    maxLength: maxLength(20),
                },
                name: {
                    required,
                    minLength: minLength(3),
                    maxLength: maxLength(20),
                },
                patronymic: {
                    required,
                    minLength: minLength(3),
                    maxLength: maxLength(20),
                },
                gender_id: {
                    required
                },
                salary: {
                    required,
                    between: between(1, 100000)
                },
                departments: {
                    required,
                }
            },
            editingEmployee:{
                surname: {
                    required,
                    minLength: minLength(3),
                    maxLength: maxLength(20),
                },
                name: {
                    required,
                    minLength: minLength(3),
                    maxLength: maxLength(20),
                },
                patronymic: {
                    required,
                    minLength: minLength(3),
                    maxLength: maxLength(20),
                },
                gender_id: {
                    required
                },
                salary: {
                    required,
                    between: between(1, 100000)
                },
                departments: {
                    required,
                }
            },
        },
        mounted(){
            this.loadGenders();
            this.loadDepartments();
            this.loadEmployees();
        },
        methods: {
            loadGenders() {
                axios.get('api/genders')
                    .then(response => {
                        this.genders = response.data;
                    })
                    .catch(error=>{
                        console.log(error)
                    });
            },
            loadDepartments() {
                axios.get('api/departments')
                    .then(response => {
                        this.departments = response.data;
                    })
                    .catch(error=>{
                        console.log(error)
                    });
            },
            loadEmployees() {
                this.pageLoading = true;
                axios.get('api/employees')
                    .then(response => {
                        this.employees = response.data;
                        this.pageLoading = false;
                    })
                    .catch(error=>{
                        console.log(error)
                    });
            },
            validateNewEmployeeState(name) {
                const { $dirty, $error } = this.$v.newEmployee[name];
                return $dirty ? !$error : null;
            },
            validateEditEmployeeState(name) {
                const { $dirty, $error } = this.$v.editingEmployee[name];
                return $dirty ? !$error : null;
            },
            addEmployee() {
                if(!this.$v.newEmployee.$error && this.$v.newEmployee.$dirty)
                {
                    this.actionLoading = true;
                    axios.post('api/employees', {
                        employee: this.newEmployee
                    })
                        .then(response => {
                            this.employees.push(response.data);
                            this.clearNewEmployee();
                            this.actionLoading = false;
                            this.$bvModal.hide('modal-add-employee');
                        })
                        .catch(error=>{
                            console.log(error)
                        });
                }
            },
            clearNewEmployee() {
                this.newEmployee.name = this.newEmployee.surname = this.newEmployee.patronymic = this.newEmployee.salary = '';
                this.newEmployee.gender_id = null;
                this.newEmployee.departments = [];
                this.$v.newEmployee.$reset();
            },
            removeModal(item) {
                this.removingEmployee = item;
                this.$bvModal.show('modal-remove-employee');
            },
            editModal(item) {
                this.editingEmployee = JSON.parse(JSON.stringify(item));
                this.$v.editingEmployee.$reset();
                this.getEditDepartmentsIds();
                this.$bvModal.show('modal-edit-employee');
            },
            removeEmployee() {
                this.actionLoading = true;
                axios.delete(`api/employees/${this.removingEmployee.id}`)
                    .then(response => {
                        this.employees.some(item => {
                            if(item.id === this.removingEmployee.id)
                                this.employees.splice(this.employees.indexOf(item),1)
                        });
                        this.actionLoading = false;
                        this.$bvModal.hide('modal-remove-employee');
                    })
                    .catch(error=>{
                        console.log(error)
                    });
            },
            editEmployee() {
                if(!this.$v.editingEmployee.$error && this.$v.editingEmployee.$anyDirty) {
                    this.actionLoading = true;
                    axios.put(`api/employees/${this.editingEmployee.id}`, {
                        employee: this.editingEmployee
                    })
                        .then(response => {
                            this.employees.some(item => {
                                if (item.id === this.editingEmployee.id)
                                    this.employees.splice(this.employees.indexOf(item), 1, response.data)
                            });
                            this.actionLoading = false;
                            this.$bvModal.hide('modal-edit-employee');
                        })
                        .catch(error => {
                            console.log(error)
                        });
                }
            },
            getEditDepartmentsIds() {
                let departments = this.editingEmployee.departments;
                this.editingEmployee.departments = [];
                this.editingEmployee.departments = departments.map(item => item.id);
            }
        }
    }
</script>

<style scoped>
    .container {
        margin-top: 30px;
    }
    td>button {
        padding: 0;
        margin-right: 10px;
    }
    h5 {
        margin: 0;
    }
</style>
