<template>
    <section class="employee_create">
        <div class="container employee_create__container">
            <form @submit.prevent="create" class="form-main">
                <div class="form__blocks">
                    <div class="form__block">
                        <label for="lastname">Фамилия</label>
                        <input
                            class="input-main"
                            v-model="form.lastname"
                            type="text"
                            name="lastname"
                            placeholder="Иванов"
                        />
                        <p v-if="errors.lastname" class="error">
                            <small>{{ errors.lastname[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="name">Имя</label>
                        <input
                            class="input-main"
                            v-model="form.name"
                            type="text"
                            name="name"
                            placeholder="Иван"
                        />
                        <p v-if="errors.name" class="error">
                            <small>{{ errors.name[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="patronymic">Отчество</label>
                        <input
                            class="input-main"
                            v-model="form.patronymic"
                            type="text"
                            name="patronymic"
                            placeholder="Иванович"
                        />
                        <p v-if="errors.patronymic" class="error">
                            <small>{{ errors.patronymic[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="personal_phone">Личный телефон</label>
                        <InputMask
                            v-model="form.personal_phone"
                            id="personal_phone"
                            mask="+9 (999) 999 99-99"
                            placeholder="+9 (999) 999 99-99"
                            unstyled
                        ></InputMask>
                        <p v-if="errors.personal_phone" class="error">
                            <small>{{ errors.personal_phone[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="work_phone">Рабочий телефон</label>
                        <InputMask
                            v-model="form.work_phone"
                            id="work_phone"
                            mask="+9 (999) 999 99-99"
                            placeholder="+9 (999) 999 99-99"
                            unstyled
                        ></InputMask>
                        <p v-if="errors.work_phone" class="error">
                            <small>{{ errors.work_phone[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="number">Табельный номер</label>
                        <input
                            class="input-main"
                            v-model="form.number"
                            type="text"
                            name="number"
                            placeholder="12345678"
                        />
                        <p v-if="errors.number" class="error">
                            <small>{{ errors.number[0] }}</small>
                        </p>
                    </div>
                </div>
                <div class="form__blocks">                    
                    <div class="form__block">
                        <label for="area">Участок</label>
                        <Select
                            v-model="form.area"
                            editable
                            :options="areas"
                            optionLabel="title"
                            id="area"
                            placeholder="Выберите или укажите значение"
                            class="select-main"
                        />
                        <p v-if="errors.area" class="error">
                            <small>{{ errors.area[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="position">Должность</label>
                        <Select
                            v-model="form.position"
                            editable
                            :options="positions"
                            optionLabel="title"
                            id="position"
                            placeholder="Выберите или укажите значение"
                            class="select-main"
                        />
                        <p v-if="errors.position" class="error">
                            <small>{{ errors.position[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="sex">Пол</label>
                        <Select
                            v-model="form.sex"
                            :options="sex"
                            optionLabel="title"
                            id="sex"
                            placeholder="Выберите значение"
                            class="select-main"
                        />
                        <p v-if="errors.sex" class="error">
                            <small>{{ errors.sex[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="shift">Смена</label>
                        <Select
                            v-model="form.shift"
                            editable
                            :options="shifts"
                            optionLabel="title"
                            id="shift"
                            placeholder="Выберите или укажите значение"
                            class="select-main"
                        />
                        <p v-if="errors.shift" class="error">
                            <small>{{ errors.shift[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="light_work">Легкая работа</label>
                        <Select
                            v-model="form.light_work"
                            :options="light_values"
                            optionLabel="title"
                            id="light_work"
                            placeholder="Выберите значение"
                            class="select-main"
                        />
                        <p v-if="errors.light_work" class="error">
                            <small>{{ errors.light_work[0] }}</small>
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
    name: "",
    lastname: "",
    patronymic: "",
    personal_phone: "",
    work_phone: "",
    number: "",
    position: "",
    sex: null,
    shift: "",
    light_work: false,
});

const store = useStore();
const router = useRouter();

const positions = ref([]);
const shifts = ref([]);
const areas = ref([]);
const errors = ref([]);

const sex = ref([
    { title: "Женский", id: 0 },
    { title: "Мужской", id: 1 },
]);
const light_values = ref([
    { title: "Да", id: 1 },
    { title: "Нет", id: 0 },
]);

const createData = () => {
    axios
        .get(
            "/api/employees/createData", { headers: { Authorization: `Bearer ${store.state.token}` } }
        )
        .then((res) => {
            positions.value = res.data.data.positions;
            shifts.value = res.data.data.shifts;
            areas.value = res.data.data.areas;
        })
        .catch((error) => {
            if (error.response.status === 401) {
                handleUnauthorized(error).then(() => {
                    createData();
                });
            }
        });
};

const create = () => {
    form.value.personal_phone = form.value.personal_phone.replace(/\D/g, "");
    form.value.work_phone = form.value.work_phone.replace(/\D/g, "");
    errors.value = {};

    axios
        .post("/api/employees", form.value, {
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
.employee_create {
    margin-top: 7rem;
}
</style>
