<template>
    <section class="user_create">
        <div class="container user_create__container">
            <form @submit.prevent="create" class="form-main">
                <div class="form__blocks">
                    <div class="form__block">
                        <label for="employee">Сотрудник</label>
                        <Select
                            v-model="form.employee"
                            filter
                            :options="employees"
                            optionLabel="fio"
                            id="employee"
                            placeholder="Выберите значение"
                            class="select-main"
                            @change="getPhone"
                        />
                        <p v-if="errors.employee" class="error">
                            <small>{{ errors.employee[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="role">Роль</label>
                        <Select
                            v-model="form.role"
                            :options="roles"
                            optionLabel="title"
                            id="role"
                            placeholder="Выберите значение"
                            class="select-main"
                        />
                        <p v-if="errors.role" class="error">
                            <small>{{ errors.role[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="phone">Телефон</label>
                        <InputMask
                            v-model="form.phone"
                            id="phone"
                            mask="+9 (999) 999 99-99"
                            placeholder="+9 (999) 999 99-99"
                            unstyled
                        ></InputMask>
                        <p v-if="errors.phone_id" class="error">
                            <small>{{ errors.phone_id[0] }}</small>
                        </p>
                    </div>
                </div>
                <div class="form__blocks">
                    <div class="form__block">
                        <label for="password">Пароль</label>
                        <input
                            v-model="form.password"
                            class="input-main"
                            type="password"
                            name="password"
                            placeholder="password"
                        />
                        <p v-if="errors.password" class="error">
                            <small>{{ errors.password[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="password_confirmation"
                            >Подтверждение пароля</label
                        >
                        <input
                            v-model="form.password_confirmation"
                            class="input-main"
                            type="password"
                            name="password_confirmation"
                            placeholder="password"
                        />
                        <p v-if="errors.password_confirmation" class="error">
                            <small>{{ errors.password_confirmation[0] }}</small>
                        </p>
                    </div>
                </div>
                <button class="btn-main">Зарегистрировать сотрудника</button>
            </form>
        </div>
    </section>
</template>

<script setup>
import Select from "primevue/select";
import InputMask from "primevue/inputmask";
import { handleUnauthorized } from "@/scripts/handleUnauthorized.js";

import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { ref, onMounted } from "vue";

const form = ref({
    employee: "",
    role: "",
    employee_id: "",
    role_id: "",
    phone: "",
    password: "",
    password_confirmation: "",
});

const store = useStore();
const router = useRouter();

const employees = ref([]);
const roles = ref([]);
const errors = ref([]);

const createData = () => {
    axios
        .get("/api/users/createData", {
            headers: { Authorization: `Bearer ${store.state.token}` },
        })
        .then((res) => {
            employees.value = res.data.data.employees;
            roles.value = res.data.data.roles;
        })
        .catch((error) => {
            if (error.response.status === 401) {
                handleUnauthorized(error).then(() => {
                    createData();
                });
            }
        });
};

const getPhone = () => {
    axios
        .get(`/api/phones/${form.value.employee.id}`, {
            headers: { Authorization: `Bearer ${store.state.token}` },
        })
        .then((res) => {
            form.value.phone = res.data.phone.phone;
        })
        .catch((error) => {
            if (error.response.status === 401) {
                handleUnauthorized(error).then(() => {
                    getPhone();
                });
            }
        });
};

const create = () => {
    form.value.phone = form.value.phone.replace(/\D/g, "");
    form.value.employee_id = form.value.employee.id;
    form.value.role_id = form.value.role.id;
    errors.value = {};

    axios
        .post("/api/users", form.value, {
            headers: { Authorization: `Bearer ${store.state.token}` },
        })
        .then((res) => {
            if (res.status === 201) {
                router.push("/");
            }
        })
        .catch((error) => {
            if (error.response.status === 422) {
                errors.value = error.response.data.errors;
            } else if (error.response.status === 401) {
                handleUnauthorized(error).then(() => {
                    create();
                });
            }
        });
};

createData();
</script>

<style scoped>
.user_create {
    margin-top: 7rem;
}
</style>
