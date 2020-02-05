<template>
    <div class="d-flex justify-content-center mt-5" v-if="pageLoading">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="container" v-else>
        <div class="container-inner">
            <div class="top-panel">
                <button type="button" class="btn btn-primary" v-b-modal.modal-add-department>
                    <i class="fa fa-plus"></i> Новый отдел
                </button>

                <b-modal id="modal-add-department" title="Создание нового отдела" size="sm" hide-footer>
                    <form @submit.prevent="addDepartment">
                        <div class="form-group">
                            <label for="label">Название отдела:</label>
                            <b-form-input
                                id="label"
                                v-model.trim="$v.newDepartment.label.$model"
                                :state="validateNewDepartmentState('label')"
                                aria-describedby="input-live-feedback"
                            ></b-form-input>
                            <div class="invalid-feedback" v-if="!$v.newDepartment.label.required">
                                Поле обязательно для заполнения
                            </div>
                            <div class="invalid-feedback" v-if="!$v.newDepartment.label.minLength || !$v.newDepartment.label.maxLength">
                                Введите от 3 до 20 символов
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" v-if="!$v.newDepartment.$error && $v.newDepartment.$dirty" :disabled="actionLoading">
                            <span v-if="actionLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span v-if="!actionLoading">Добавить</span>
                        </button>
                        <button type="button" class="btn btn-secondary" @click="$bvModal.hide('modal-add-department')">
                            Отмена
                        </button>
                    </form>
                </b-modal>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Название отдела</th>
                    <th scope="col">Количество сотрудников отдела</th>
                    <th scope="col">Максимальная зарплата среди сотрудников отдела</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="department in departments">
                    <td>{{ department.label }}</td>
                    <td>{{ department.employees && department.employees.length ? department.employees.length : 0 }}</td>
                    <td>{{ maxSalary(department.employees) }}</td>
                    <td>
                        <button type="button" class="btn btn-link" @click="editModal(department)">
                            <h5><i class="fa fa-pencil text-primary"></i></h5>
                        </button>
                        <button type="button" class="btn btn-link" @click="removeModal(department)">
                            <h5><i class="fa fa-trash-o text-danger"></i></h5>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
            <!--Remove modal-->
            <b-modal id="modal-remove-department" size="sm" title="Удаление отдела" hide-footer>
                <p v-if="removingDepartment.employees && removingDepartment.employees.length">
                    Вы не можете удалить отдел, которому принадлежат сотрудники
                </p>
                <button v-else type="button" class="btn btn-danger" @click="removeDepartment" :disabled="actionLoading">
                    <span v-if="actionLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span v-if="!actionLoading">Удалить</span>
                </button>
            </b-modal>
            <!--Edit modal-->
            <b-modal id="modal-edit-department" title="Редактирование отдела" size="sm" hide-footer>
                <form @submit.prevent="editDepartment">
                    <div class="form-group">
                        <label for="label">Название отдела:</label>
                        <b-form-input
                            id="label"
                            v-model.trim="$v.editingDepartment.label.$model"
                            :state="validateEditDepartmentState('label')"
                            aria-describedby="input-live-feedback"
                        ></b-form-input>
                        <div class="invalid-feedback" v-if="!$v.editingDepartment.label.required">
                            Поле обязательно для заполнения
                        </div>
                        <div class="invalid-feedback" v-if="!$v.editingDepartment.label.minLength || !$v.editingDepartment.label.maxLength">
                            Введите от 3 до 20 символов
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success" v-if="!$v.editingDepartment.$error && $v.editingDepartment.$dirty" :disabled="actionLoading">
                        <span v-if="actionLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span v-if="!actionLoading">Изменить</span>
                    </button>
                    <button type="button" class="btn btn-secondary" @click="$bvModal.hide('modal-edit-department')">
                        Отмена
                    </button>
                </form>
            </b-modal>
        </div>
    </div>
</template>

<script>
    import { required, minLength, maxLength } from 'vuelidate/lib/validators'
    export default {
        name: "DepartmentsComponent",
        data() {
            return {
                pageLoading: false,
                actionLoading: false,
                departments: [],
                newDepartment: {
                    label: ''
                },
                removingDepartment: [],
                editingDepartment: [],
            }
        },
        validations: {
            newDepartment:{
                label: {
                    required,
                    minLength: minLength(3),
                    maxLength: maxLength(20),
                }
            },
            editingDepartment:{
                label: {
                    required,
                    minLength: minLength(3),
                    maxLength: maxLength(20),
                }
            },
        },
        mounted() {
            this.loadDepartments();
        },
        methods: {
            loadDepartments() {
                this.pageLoading = true;
                axios.get('api/departments')
                    .then(response => {
                        this.departments = response.data;
                        this.pageLoading = false;
                    })
                    .catch(error=>{
                        console.log(error)
                    });
            },
            maxSalary(employees) {
                if(employees && employees.length) {
                    return  Math.max.apply(Math, employees.map(function(o) { return o.salary; }));
                }
                else {
                    return 0;
                }
            },
            validateNewDepartmentState(name) {
                const { $dirty, $error } = this.$v.newDepartment[name];
                return $dirty ? !$error : null;
            },
            validateEditDepartmentState(name) {
                const { $dirty, $error } = this.$v.editingDepartment[name];
                return $dirty ? !$error : null;
            },
            addDepartment() {
                if(!this.$v.newDepartment.$error && this.$v.newDepartment.$dirty)
                {
                    this.actionLoading = true;
                    axios.post('api/departments', {
                        department: this.newDepartment
                    })
                        .then(response => {
                            this.departments.push(response.data);
                            this.clearNewDepartment();
                            this.actionLoading = false;
                            this.$bvModal.hide('modal-add-department');
                        })
                        .catch(error=>{
                            console.log(error)
                        });
                }
            },
            clearNewDepartment() {
                this.newDepartment.label = '';
                this.$v.$reset();
            },
            removeModal(item) {
                this.removingDepartment = item;
                this.$bvModal.show('modal-remove-department');
            },
            editModal(item) {
                this.editingDepartment = JSON.parse(JSON.stringify(item));
                this.$bvModal.show('modal-edit-department');
            },
            removeDepartment() {
                this.actionLoading = true;
                axios.delete(`api/departments/${this.removingDepartment.id}`)
                    .then(response => {
                        this.departments.some(item => {
                            if(item.id === this.removingDepartment.id)
                                this.departments.splice(this.departments.indexOf(item),1)
                        });
                        this.actionLoading = false;
                        this.$bvModal.hide('modal-remove-department');
                    })
                    .catch(error=>{
                        console.log(error)
                    });
            },
            editDepartment() {
                if(!this.$v.editingDepartment.$error && this.$v.editingDepartment.$dirty) {
                    this.actionLoading = true;
                    axios.put(`api/departments/${this.editingDepartment.id}`, {
                        department: this.editingDepartment
                    })
                        .then(response => {
                            this.departments.some(item => {
                                if (item.id === this.editingDepartment.id)
                                    this.departments.splice(this.departments.indexOf(item), 1, this.editingDepartment)
                            });
                            this.actionLoading = false;
                            this.$bvModal.hide('modal-edit-department');
                        })
                        .catch(error => {
                            console.log(error)
                        });
                }
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
