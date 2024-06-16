<template>
    <section class="workday_create">
        <div class="container passenger_create__container">
            <form @submit.prevent="create" class="form-main">
                <div class="form__blocks">
                    <div class="form__block">
                        <label for="employee">Сотрудник</label>
                        <Select
                            v-model="form.employee"
                            filter
                            :options="employees"
                            optionLabel="fio_short"
                            id="employee"
                            placeholder="Выберите значение"
                            class="select-main"
                        />
                        <p v-if="errors.employee" class="error">
                            <small>{{ errors.employee[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="time_work">Время работы</label>
                        <Select
                            v-model="form.time_work"
                            editable
                            :options="time_works"
                            optionLabel="time"
                            id="time_work"
                            placeholder="Выберите или укажите значение"
                            class="select-main"
                        />
                        <p v-if="errors.time_work" class="error">
                            <small>{{ errors.time_work[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="new_time_work_date"
                            >Дата измененного времени работы</label
                        >
                        <DatePicker
                            v-model="form.new_time_work_date"
                            id="new_time_work_date"
                            hourFormat="24"
                            placeholder="mm/dd/yyyy"
                        />
                        <p v-if="errors.new_time_work_date" class="error">
                            <small>{{ errors.new_time_work_date[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="new_time_work"
                            >Измененное время работы</label
                        >
                        <Select
                            v-model="form.new_time_work"
                            editable
                            :options="time_works"
                            optionLabel="time"
                            id="new_time_work"
                            placeholder="Выберите или укажите значение"
                            class="select-main"
                        />
                        <p v-if="errors.new_time_work" class="error">
                            <small>{{ errors.new_time_work[0] }}</small>
                        </p>
                    </div>
                </div>
                <div class="form__blocks">
                    <div class="form__block">
                        <label for="employment_date">Дата устройства</label>
                        <DatePicker
                            v-model="form.employment_date"
                            id="employment_date"
                            hourFormat="24"
                            placeholder="mm/dd/yyyy"
                        />
                        <p v-if="errors.employment_date" class="error">
                            <small>{{ errors.employment_date[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="extra_shift"
                            >Дата дополнительной смены</label
                        >
                        <DatePicker
                            v-model="form.extra_shift"
                            id="extra_shift"
                            hourFormat="24"
                            placeholder="mm/dd/yyyy"
                        />
                        <p v-if="errors.extra_shift" class="error">
                            <small>{{ errors.extra_shift[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="intern">Стажер</label>
                        <Select
                            v-model="form.intern"
                            :options="internValues"
                            optionLabel="title"
                            id="intern"
                            placeholder="Выберите значение"
                            class="select-main"
                        />
                        <p v-if="errors.intern" class="error">
                            <small>{{ errors.intern[0] }}</small>
                        </p>
                    </div>
                </div>
                <div class="form__blocks workdays__jobless">
                    <div class="form__block">
                        <div class="workay__jobless">
                            <h3>Безработные дни</h3>
                            <button
                                type="button"
                                class="btn-jobless btn-add_jobless"
                                @click="addJoblessField"
                            >
                                <svg
                                    width="40"
                                    height="40"
                                    viewBox="0 0 40 40"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M9.58317 20C9.58317 19.0795 10.3294 18.3333 11.2498 18.3333H28.7498C29.6703 18.3333 30.4165 19.0795 30.4165 20C30.4165 20.9204 29.6703 21.6666 28.7498 21.6666L11.2498 21.6666C10.3294 21.6666 9.58317 20.9204 9.58317 20Z"
                                        fill="#1451EE"
                                    />
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M19.9998 9.58329C20.9203 9.58329 21.6665 10.3295 21.6665 11.25V28.75C21.6665 29.6704 20.9203 30.4166 19.9998 30.4166C19.0794 30.4166 18.3332 29.6704 18.3332 28.75V11.25C18.3332 10.3295 19.0794 9.58329 19.9998 9.58329Z"
                                        fill="#1451EE"
                                    />
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M19.9998 4.99996C11.7156 4.99996 4.99984 11.7157 4.99984 20C4.99984 28.2842 11.7156 35 19.9998 35C28.2841 35 34.9998 28.2842 34.9998 20C34.9998 11.7157 28.2841 4.99996 19.9998 4.99996ZM1.6665 20C1.6665 9.87474 9.87462 1.66663 19.9998 1.66663C30.1251 1.66663 38.3332 9.87474 38.3332 20C38.3332 30.1252 30.1251 38.3333 19.9998 38.3333C9.87462 38.3333 1.6665 30.1252 1.6665 20Z"
                                        fill="#1451EE"
                                    />
                                </svg>
                            </button>
                        </div>
                        <div
                            class="workdays__jobless"
                            v-for="(jobless_day, index) in form.jobless_days"
                            :key="index"
                        >
                            <div class="form__block">
                                <label for="status">Статус</label>
                                <Select
                                    v-model="jobless_day.status"
                                    :options="statuses"
                                    editable
                                    optionLabel="title"
                                    id="status"
                                    placeholder="Выберите или укажите значение"
                                    class="select-main"
                                />
                                <p v-if="errors.status" class="error">
                                    <small>{{ errors.status[0] }}</small>
                                </p>
                            </div>
                            <div class="form__block">
                                <label for="start"
                                    >Дата начала</label
                                >
                                <DatePicker
                                    v-model="jobless_day.start"
                                    id="start"
                                    hourFormat="24"
                                    placeholder="mm/dd/yyyy"
                                />
                                <p v-if="errors.start" class="error">
                                    <small>{{ errors.start[0] }}</small>
                                </p>
                            </div>
                            <div class="form__block">
                                <label for="end"
                                    >Дата конца</label
                                >
                                <DatePicker
                                    v-model="jobless_day.end"
                                    id="end"
                                    hourFormat="24"
                                    placeholder="mm/dd/yyyy"
                                />
                                <p v-if="errors.end" class="error">
                                    <small>{{ errors.end[0] }}</small>
                                </p>
                            </div>
                            <button
                                class="btn-jobless btn-remove-jobless"
                                type="button"
                                @click="removeJoblessField(index)"
                            >
                                <svg
                                    width="40"
                                    height="40"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M5.75 12C5.75 11.4477 6.19772 11 6.75 11L17.25 11C17.8023 11 18.25 11.4477 18.25 12C18.25 12.5523 17.8023 13 17.25 13L6.75 13C6.19772 13 5.75 12.5523 5.75 12Z"
                                        fill="white"
                                    />
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3ZM1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23C5.92487 23 1 18.0751 1 12Z"
                                        fill="white"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p v-if="errors.phones" class="error">
                        <small>{{ errors.phones[0] }}</small>
                    </p>
                </div>
                <button class="btn-main">Зарегистрировать пассажира</button>
            </form>
        </div>
    </section>
</template>

<script setup>
import Select from "primevue/select";
import DatePicker from "primevue/datepicker";
import { handleUnauthorized } from "@/scripts/handleUnauthorized.js";

import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { ref } from "vue";

const form = ref({
    employee: "",
    time_work: "",
    extra_shift: "",
    employment_date: "",
    new_time_work_date: "",
    new_time_work: "",
    intern: 0,
    jobless_days: [],
});

const addJoblessField = () => {
    form.value.jobless_days.push({ status: "", start: "", end: "" });
};

const removeJoblessField = (index) => {
    form.value.jobless_days.splice(index, 1);
};

const store = useStore();
const router = useRouter();

const employees = ref([])
const time_works = ref([])
const statuses = ref([])
const errors = ref([]);

const internValues = ref([
    { title: "Да", id: 1 },
    { title: "Нет", id: 0 },
]);

const createData = () => {
    axios
        .get("/api/workdays/createData", {
            headers: { Authorization: `Bearer ${store.state.token}` },
        })
        .then((res) => {
            employees.value = res.data.data.employees;
            time_works.value = res.data.data.time_works;
            statuses.value = res.data.data.statuses;
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
    errors.value = {};

    axios
        .post("/api/workdays", form.value, {
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
.workday_create {
    margin-top: 7rem;
}

.workdays__jobless {
    width: 100%;
}

.workdays__jobless > .form__block {
    gap: 4rem;
}

.workay__jobless {
    display: flex;
    align-items: center;
    gap: 2.5rem;
}

.workdays__jobless {
    display: flex;
    align-items: center;
    gap: 5rem;
}

.workdays__jobless > .form__block {
    width: 100%;
}

.btn-jobless svg {
    width: 4rem;
}

.btn-add_jobless path {
    fill: var(--color-btn);
    transition: var(--transition);
}

.btn-add_jobless:hover path {
    fill: var(--color-btn-hover);
    transition: var(--transition);
}

.btn-add_jobless:active path {
    fill: var(--color-btn-active);
    transition: var(--transition);
}

.btn-remove-jobless path {
    fill: red;
    transition: var(--transition);
}

.btn-remove-jobless:hover path {
    fill: rgb(157, 2, 2);
}

.btn-remove-jobless:active path {
    fill: rgb(110, 0, 0);
}
</style>
