<template>
    <section class="main__section">
        <div class="container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Станция отправления</th>
                        <th>Станция прибытия</th>
                        <th>Время начала</th>
                        <th>Время конца</th>
                        <th>Категория</th>
                        <th>Статус</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(request, id) in requests" :key="id">
                        <td>{{ request.id }}</td>
                        <td>{{ request.passenger_fio_short }}</td>
                        <td>{{ request.station_departure }}</td>
                        <td>{{ request.station_arrival }}</td>
                        <td>{{ request.time_start }}</td>
                        <td>{{ request.time_end }}</td>
                        <td>{{ request.category }}</td>
                        <td>
                            <Select
                                v-model="request.status"
                                filter
                                :options="statuses"
                                optionLabel="title"
                                placeholder="Выберите значение"
                                class="select-main-main"
                                @change="updateStatus(request)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
            <Paginator
            v-if="meta.last_page !== 1"
                :totalPages="meta.last_page"
                :currentPage="currentPage"
                @page-changed="handlePageChange"
            />
        </div>
    </section>
</template>

<script setup>
import { useStore } from "vuex";
import { ref } from "vue";
import axios from "axios";
import Select from "primevue/select";
import Paginator from "@/components/Paginator.vue";
import { handleUnauthorized } from "@/scripts/handleUnauthorized.js";

const currentPage = ref(1);

const requests = ref(null);
const statuses = ref(null);
const meta = ref(null);

const store = useStore();

const getStatuses = () => {
    axios
        .get("/api/statuses", {
            headers: { Authorization: `Bearer ${store.state.token}` },
        })
        .then((response) => {
            statuses.value = response.data.data;
        })
        .catch((error) => {
            if (error.response.status === 401) {
                handleUnauthorized(error).then(() => {
                    getStatuses();
                });
            }
        });
};

const getRequests = (page = 1) => {
    axios
        .get(`/api/requests?page=${page}`, {
            headers: { Authorization: `Bearer ${store.state.token}` },
        })
        .then((response) => {
            requests.value = response.data.data;
            meta.value = response.data.meta;
        })
        .catch((error) => {
            if (error.response.status === 401) {
                handleUnauthorized(error).then(() => {
                    getRequests(page);
                });
            }
        });
};

const updateStatus = (request) => {
    axios
        .post(
            `/api/requests/${request.id}/updateStatus`,
            {
                status_id: request.status.id,
            },
            { headers: { Authorization: `Bearer ${store.state.token}` } }
        )
        .then((response) => {})
        .catch((error) => {
            if (error.response.status === 401) {
                handleUnauthorized(error).then(() => {
                    updateStatus(request);
                });
            }
        });
};

function handlePageChange(page) {
    currentPage.value = page;
    getRequests(page);
}

getStatuses();
getRequests(currentPage.value);
</script>

<style>
.main__section {
    margin-top: 7rem;
}

.select-main-main {
    width: 100%;
}

.select-main-main .p-select-dropdown {
    display: none;
}

.table {
    border-collapse: collapse;
    width: 100%;
    border: none;
    margin-bottom: 2rem;
}
.table thead th {
    text-align: left;
    border: none;
    padding: 1.5rem 1.5rem;
    background: rgba(20, 81, 238, 0.5);
    font-size: var(--font-size-small);
    color: rgba(35, 35, 35, 0.8);
    text-align: center;
}
.table__menu {
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
}
.table__menu img:hover {
    opacity: 0.7;
}

.table__menu img {
    max-width: 25px;
}
.table thead tr th:first-child {
    border-radius: 1rem 0 0 1rem;
}
.table thead tr th:last-child {
    border-radius: 0 1rem 1rem 0;
}
.table tbody td {
    text-align: left;
    border: none;
    padding: 1rem 1.5rem;
    font-size: var(--font-size-small);
    color: rgba(35, 35, 35, 0.8);
    text-align: center;
}
.table tbody tr:hover {
    background: #d9d9d9;
}
.table tbody tr td:first-child {
    border-radius: 1rem 0 0 1rem;
}
.table tbody tr td:last-child {
    border-radius: 0 1rem 1rem 0;
}
</style>
