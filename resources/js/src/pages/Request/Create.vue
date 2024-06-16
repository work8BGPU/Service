<template>
    <section class="request_create">
        <div class="container request_create__container">
            <form @submit.prevent="create" class="form-main">
                <div class="form__blocks form__blocks-row">
                    <div class="form__block">
                        <label for="passenger">Пассажир</label>
                        <Select
                            v-model="form.passenger"
                            filter
                            :options="passengers"
                            optionLabel="fio_short"
                            id="passenger"
                            placeholder="Выберите значение"
                            class="select-main"
                            @change="getCategory"
                        />
                        <p v-if="errors.passenger" class="error">
                            <small>{{ errors.passenger[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="status">Статус</label>
                        <Select
                            v-model="form.status"
                            filter
                            :options="statuses"
                            optionLabel="title"
                            id="status"
                            placeholder="Выберите значение"
                            class="select-main"
                        />
                        <p v-if="errors.status" class="error">
                            <small>{{ errors.status[0] }}</small>
                        </p>
                    </div>
                </div>
                <div class="form__blocks form__blocks-row">
                    <div class="form__block">
                        <label for="station_departure">Место встречи</label>
                        <Select
                            v-model="form.station_departure"
                            filter
                            :options="stations"
                            optionLabel="title"
                            id="station_departure"
                            placeholder="Выберите значение"
                            class="select-main"
                            @change="findBestRoutes"
                        />
                        <p v-if="errors.station_departure" class="error">
                            <small>{{ errors.station_departure[0] }}</small>
                        </p>
                        <p v-if="errors.metroRouter" class="error">
                            <small>{{ errors.metroRouter[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="station_arrival">Место прибытия</label>
                        <Select
                            v-model="form.station_arrival"
                            filter
                            :options="stations"
                            optionLabel="title"
                            id="station_arrival"
                            placeholder="Выберите значение"
                            class="select-main"
                            @change="findBestRoutes"
                        />
                        <p v-if="errors.station_arrival" class="error">
                            <small>{{ errors.station_arrival[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="date_meet">Дата и время встречи</label>
                        <DatePicker
                            v-model="form.date_meet"
                            id="date_meet"
                            showTime
                            hourFormat="24"
                            placeholder="mm/dd/yyyy hh:mm"
                        />
                        <p v-if="errors.date_meet" class="error">
                            <small>{{ errors.date_meet[0] }}</small>
                         </p>
                    </div>
                </div>
                <div class="form__blocks">
                    <div class="form__block">
                        <label for="description_departure"
                            >Описание места встречи</label
                        >
                        <textarea
                            class="input-main"
                            v-model="form.description_departure"
                            name="description_departure"
                            placeholder="Описание"
                            rows="4"
                        ></textarea>
                        <p v-if="errors.description_departure" class="error">
                            <small>{{ errors.description_departure[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="description_arrival"
                            >Описание места прибытия</label
                        >
                        <textarea
                            class="input-main"
                            v-model="form.description_arrival"
                            name="description_arrival"
                            placeholder="Описание"
                            rows="4"
                        ></textarea>
                        <p v-if="errors.description_arrival" class="error">
                            <small>{{ errors.description_arrival[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="method">Метод приема</label>
                        <Select
                            v-model="form.method"
                            filter
                            :options="methods"
                            optionLabel="title"
                            id="method"
                            placeholder="Выберите значение"
                            class="select-main"
                        />
                        <p v-if="errors.method" class="error">
                            <small>{{ errors.method[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="category">Категория</label>
                        <Select
                            v-model="form.category"
                            filter
                            :options="categories"
                            optionLabel="short_title"
                            id="category"
                            placeholder="Выберите значение"
                            class="select-main"
                        />
                        <p v-if="errors.category" class="error">
                            <small>{{ errors.category[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block">
                        <label for="information"
                            >Дополнительная информация</label
                        >
                        <textarea
                            class="input-main"
                            v-model="form.information"
                            name="information"
                            placeholder="Информация"
                            rows="4"
                        ></textarea>
                        <p v-if="errors.information" class="error">
                            <small>{{ errors.information[0] }}</small>
                        </p>
                    </div>
                    <div class="form__block" v-for="(employee, index) in form.employees"
                            :key="index">
                        <label for="employee">Сотрудник</label>
                        <Select
                            v-model="employee.employee"
                            filter
                            :options="employees"
                            optionLabel="fio_short"
                            id="employee"
                            placeholder="Выберите значение"
                            class="select-main"
                        />
                    </div>
                    <p v-if="errors.employees" class="error">
                        <small>{{ errors.employees[0] }}</small>
                    </p>
                </div>
                <div class="form__blocks">
                    <div v-if="form.metroRouter" class="form__block form__block__path">
                        <div class="path__main">
                            <h4>{{ form.metroRouter.total_time }} мин</h4>
                            <p>пересадок: {{ form.metroRouter.transfers.length }}</p>
                        </div>
                        <div class="path__stations">
                            <h5>{{ form.metroRouter.departure_station.title }} ({{ form.metroRouter.departure_station.name_line }})</h5>
                            <div class="path__stations-all">
                                <p v-for="(station, id) in form.metroRouter.stations" :key="id">
                                    {{ station.title }} ({{ station.name_line }})
                                </p>
                            </div>
                            <h5>{{ form.metroRouter.arrival_station.title }} ({{ form.metroRouter.arrival_station.name_line }})</h5>
                        </div>
                    </div>                    
                    <div class="form__block">
                        <h5>Пассажиры и сотрудники</h5>
                        <div class="form__block__people">
                            <h5>{{ form.number_result }}</h5>
                            <div class="counter__block">
                                <div class="counter">
                                    <p>Пассажиров</p>
                                    <div class="count">
                                        <button
                                            type="button"
                                            @click="passengerMinus"
                                            class="btn-count btn-minus"
                                        >
                                            <svg
                                                width="24"
                                                height="24"
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
                                        <h4>{{ form.number_passenger }}</h4>
                                        <button
                                            type="button"
                                            @click="passengerPlus"
                                            class="btn-count btn-plus"
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
                                </div>
                                <div class="counter">
                                    <p>Сотрудников мужчин</p>
                                    <div class="count">
                                        <button
                                            type="button"
                                            @click="maleMinus"
                                            class="btn-count btn-minus"
                                        >
                                            <svg
                                                width="24"
                                                height="24"
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
                                        <h4>{{ form.insp_m }}</h4>
                                        <button
                                            type="button"
                                            @click="malePlus"
                                            class="btn-count btn-plus"
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
                                </div>
                                <div class="counter">
                                    <p>Сотрудников женщин</p>
                                    <div class="count">
                                        <button
                                            type="button"
                                            @click="femaleMinus"
                                            class="btn-count btn-minus"
                                        >
                                            <svg
                                                width="24"
                                                height="24"
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
                                        <h4>{{ form.insp_f }}</h4>
                                        <button
                                            type="button"
                                            @click="femalePlus"
                                            class="btn-count btn-plus"
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form__block">
                        <h5>Багаж</h5>
                        <div class="form__block__luggage">
                            <div class="form__block">
                                <label for="luggage_type">Тип багажа</label>
                                <Select
                                    v-model="form.luggage_type"
                                    editable
                                    :options="luggage_types"
                                    optionLabel="title"
                                    id="luggage_type"
                                    placeholder="Выберите или введите значение"
                                    class="select-main"
                                />
                                <p v-if="errors.luggage_type" class="error">
                                    <small>{{ errors.luggage_type[0] }}</small>
                                </p>
                            </div>
                            <div class="form__block">
                                <label for="luggage_weight">Вес (в кг)</label>
                                <input
                                    v-model="form.luggage_weight"
                                    name="luggage_weight"
                                    type="text"
                                    placeholder="2 "
                                    class="input-main"
                                ></input>
                                <p v-if="errors.luggage_weight" class="error">
                                    <small>{{ errors.luggage_weight[0] }}</small>
                                </p>
                            </div>
                            <div class="form__block checkbox">
                                <input
                                v-model="form.need_help"
                                name="need_help"
                                type="checkbox"
                                value="1"
                                ></input>
                                <label for="need_help">Нужна помощь</label>
                            </div>
                            <p v-if="errors.need_help" class="error">
                                <small>{{ errors.need_help[0] }}</small>
                            </p>
                        </div>
                    </div>
                </div>
                <button class="btn-main">Создать заявку</button>
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
import { ref, computed } from "vue";

const form = ref({
    passenger: {},
    status: {},
    station_departure: null,
    station_arrival: null,
    date_meet: "",
    description_departure: "",
    description_arrival: "",
    method: {},
    category: {},
    information: "",
    employees: [],
    insp_m: 0,
    insp_f: 0,
    number_passenger: 0,
    luggage_type: '',
    luggage_weight: '',
    need_help: false,

    number_result: "",

    metroRouter: null
});

form.value.number_result = computed(
    () =>
        `${form.value.number_passenger} пассажир, ${
            Number(form.value.insp_m) + Number(form.value.insp_f)
        } сотрудник`
);

const addEmployeeField = () => {
    form.value.employees.push({ employee: "" });
};

const removeEmployeeField = (index) => {
    form.value.employees.splice(index, 1);
};

const store = useStore();
const router = useRouter();

const passengers = ref([]);
const stations = ref([]);
const methods = ref([]);
const categories = ref([]);
const employees = ref([]);
const luggage_types = ref([]);
const statuses = ref([]);

const errors = ref([]);

const createData = () => {
    axios
        .get("/api/requests/createData", {
            headers: { Authorization: `Bearer ${store.state.token}` },
        })
        .then((res) => {
            passengers.value = res.data.data.passengers;
            stations.value = res.data.data.stations;
            methods.value = res.data.data.methods;
            categories.value = res.data.data.categories;
            employees.value = res.data.data.employees;
            luggage_types.value = res.data.data.luggage_types;
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
        .post("/api/requests", form.value, {
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

const getCategory = () => {
    if (!form.value.number_passenger) form.value.number_passenger = 1;

    axios
        .get(`/api/categories/${form.value.passenger.category_id}`, {
            headers: { Authorization: `Bearer ${store.state.token}` },
        })
        .then((res) => {
            const category = res.data.category;
            form.value.category = categories.value.find(cat => cat.id === category.id);
        })
        .catch((error) => {
            if (error.response.status === 401) {
                handleUnauthorized(error).then(() => {
                    getCategory();
                });
            }
        });
}

const findBestRoutes = () => {
    if (form.value.station_departure && form.value.station_arrival) {
        axios.post("/api/metro/stations/findBestRoutes", {
            station_departure: form.value.station_departure,
            station_arrival: form.value.station_arrival
        }, {
            headers: {Authorization: `Bearer ${store.state.token}`}
        }).then(res => {
            form.value.metroRouter = res.data.data[0];
        }).catch(error => {
            if (error.response.status === 401) {
                handleUnauthorized(error).then(() => {
                    findBestRoutes();
                });
            }
        })
    }
}

const passengerPlus = () => {
    if (form.value.number_passenger < 10) form.value.number_passenger++;
};

const malePlus = () => {
    if (form.value.insp_m < 20) {
        form.value.insp_m++;
        addEmployeeField();
    } 
};
const femalePlus = () => {
    if (form.value.insp_f < 20) {
        form.value.insp_f++;
        addEmployeeField();
    }
};

const passengerMinus = () => {
    if (form.value.number_passenger > 0) form.value.number_passenger--;
};
const maleMinus = () => {
    if (form.value.insp_m > 0) {
        form.value.insp_m--;
        removeEmployeeField();
    }
};
const femaleMinus = () => {
    if (form.value.insp_f > 0) {
        form.value.insp_f--;
        removeEmployeeField();
    }
};

createData();
</script>

<style scoped>
.request_create {
    margin-top: 7rem;
}

.form__blocks-row {
    flex-direction: row;
    width: 100%;
    align-items: center;
}

h5 {
    font-weight: normal;
    color: #000;
}

.form__block > h5 {
    margin-bottom: 1rem;
}

.form__block__people, .form__block__luggage, .form__block__path {
    width: 100%;
    background-color: var(--color-main);
    padding: 2.5rem 3rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-main);
}

.counter__block {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 5rem;
    margin-top: 6rem;
}

.counter {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.counter:not(:last-of-type)::after {
    position: absolute;
    content: "";
    width: 100%;
    height: 0.1rem;
    background-color: #b5bdd1;
    left: 0;
    bottom: -2.5rem;
}

.count {
    display: flex;
    align-items: center;
    gap: 2.5rem;
}

.btn-count svg {
    width: 3rem;
    height: 3rem;
}

.count .btn-count path {
    fill: var(--color-btn);
}

.btn-count path {
    transition: var(--transition);
}

.btn-count:hover path {
    fill: var(--color-btn-hover);
}

.btn-count:hover path {
    fill: var(--color-btn-active);
}

.count h4 {
    text-align: center;
    width: 3.5rem;
}

.form__block__luggage {
    background-color: #f5f5f5;
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.path__main {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 0.2rem solid var(--color-btn);
    border-radius: var(--border-radius);
    padding: 2rem 1.5rem;
    margin-bottom: 2.5rem;
}

.path__stations {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.path__stations-all {
    display: flex;
    flex-direction: column;
    gap: 0.7rem;
}

.path__stations h5 {
    font-weight: bold;
}

</style>
