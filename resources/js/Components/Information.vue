<template>
    <div class="container">
        <div :class="{ 'success-message': true, 'show': showSuccessMessage }">{{ successMessage }}</div>
        <div class="button-container">
            <div class="sort-buttons">
                <primary-button class="sort-button" @click="sortByCompleted">Completion Date</primary-button>
                <primary-button class="sort-button" @click="sortByJoined">Joining Date</primary-button>
                <primary-button class="sort-button" @click="sortByName">By Alphabet</primary-button>
                <primary-button class="ml-40 bg-orange-600 hover:bg-orange-500 focus:bg-orange-700 active:bg-orange-900"
                                @click="showModal=true">Create course
                </primary-button>
            </div>
            <div class="filter-container">
                <div class="filter-buttons">
                    <primary-button class="filter-button" :class="{ active: statusFilter === '' }"
                                    @click="statusFilter = ''">
                        All
                    </primary-button>
                    <primary-button class="filter-button" :class="{ active: statusFilter === '1' }"
                                    @click="statusFilter = '1'">
                        In Progress
                    </primary-button>
                    <primary-button class="filter-button" :class="{ active: statusFilter === '2' }"
                                    @click="statusFilter = '2'">
                        Complete
                    </primary-button>
                </div>
                <div class="filter-inputs">
                    <input v-model="nameFilter" type="text" placeholder="Name/Email"/>
                    <input v-model="titleFilter" type="text" placeholder="Course Title"/>
                </div>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>User name</th>
                <th>Course title</th>
                <th>Status</th>
                <th>Joined course</th>
                <th>Course completion</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="info in visibleInformation" :key="info.id" :id="'row-' + info.id">
                <td>{{ info.name }}</td>
                <td>{{ info.title }}</td>
                <td v-bind:class="{ 'in-progress': info.status === 1, 'complete': info.status === 2 }">
                    {{ info.status === 1 ? 'In Progress' : 'Complete' }}
                    <secondary-button @click="editStatus(info.courseID, info.userID, info.status, info)">Update
                    </secondary-button>
                </td>
                <td>{{ info.joined }}</td>
                <td v-if="info.status === 2">{{ info.completed }}</td>
                <td v-else></td>
                <danger-button @click="deleteCourse(info.courseID, info.userID)">Delete</danger-button>
            </tr>
            </tbody>
        </table>
        <primary-button class="load-more-button" v-if="visibleInformation.length < information.length"
                        @click="showMore">Load
            More
        </primary-button>
    </div>
    <Modal :show="showModal" @close="showModal=false">
        <template #header>
            <h2 class="text-2xl font-bold">Create course</h2>
        </template>
        <template #body>
            <div class="flex flex-col">
                <input class="modal-input" v-model="name" type="text" id="name" placeholder="User Email">
                <input class="modal-input" v-model="title" type="text" id="title" placeholder="Course Title">
                <label for="status">Status</label>
                <select class="modal-input" id="status">
                    <option value="1">In Progress</option>
                    <option value="2">Complete</option>
                </select>
            </div>
        </template>
        <template #footer>
            <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
            <secondary-button @click="showModal=false">Cancel</secondary-button>
            <primary-button @click="createCourse(name, title, status)">Create</primary-button>
        </template>
    </Modal>
</template>

<script lang="ts">
import axios from 'axios'
import DangerButton from "@/Components/DangerButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import {ref} from "vue";

export default {
    components: {Modal, SecondaryButton, PrimaryButton, DangerButton},
    data() {
        return {
            information: [],
            sortType: '',
            sortDirection: 1,
            statusFilter: '',
            itemsToShow: 20,
            nameFilter: '',
            titleFilter: '',
            itemsPerPage: 10,
            currentPage: 1,
            totalItems: 0,
            showModal: ref(false),
            name: '',
            title: '',
            status: 1,
            successMessage: '',
            showSuccessMessage: false,
            errorMessage: ''
        }
    },
    created() {
        axios.get('/api/', {
            params: {
                nameFilter: this.nameFilter,
                titleFilter: this.titleFilter,
                statusFilter: this.statusFilter,
                itemsPerPage: this.itemsPerPage,
                currentPage: this.currentPage
            }
        }).then(response => {
            console.log("response", response)
            this.information = response.data.data;
            this.currentPage = response.data.currentPage;
            this.totalItems = response.data.total;
        }).catch(error => {
            console.log("error", error)
        });
    },
    computed: {
        visibleInformation() {
            let filteredInformation = this.information.filter(info => {
                return (
                    (info.title.toLowerCase().includes(this.titleFilter.toLowerCase()) || this.titleFilter === '') &&
                    (info.name.toLowerCase().includes(this.nameFilter.toLowerCase()) || info.email.toLowerCase().includes(this.nameFilter.toLowerCase()) || this.nameFilter === '') &&
                    (this.statusFilter === '' || info.status.toString() === this.statusFilter)
                );
            });

            if (this.sortType === 'name') {
                filteredInformation.sort((a, b) => {
                    return this.sortDirection * a.name.localeCompare(b.name);
                });
            } else if (this.sortType === 'joined') {
                filteredInformation.sort((a, b) => {
                    return this.sortDirection * (new Date(a.joined) - new Date(b.joined));
                });
            } else if (this.sortType === 'completed') {
                filteredInformation.sort((a, b) => {
                    if (!a.completed) return 1;
                    if (!b.completed) return -1;
                    return this.sortDirection * (new Date(a.completed) - new Date(b.completed));
                });
            }

            return filteredInformation.slice(0, this.itemsToShow);
        }
    },
    methods: {
        sortByCompleted() {
            if (this.sortType === 'completed') {
                this.sortDirection *= -1;
            } else {
                this.sortType = 'completed';
                this.sortDirection = -1;
            }
        },
        sortByJoined() {
            if (this.sortType === 'joined') {
                this.sortDirection *= -1;
            } else {
                this.sortType = 'joined';
                this.sortDirection = 1;
            }
        },
        sortByName() {
            if (this.sortType === 'name') {
                this.sortDirection *= -1;
            } else {
                this.sortType = 'name';
                this.sortDirection = 1;
            }
        },
        showMore() {
            this.itemsToShow += 10;
        },
        deleteCourse(courseId, userId) {
            console.log("courseId", courseId, "userId", userId)
            axios.delete('api/delete/course/' + courseId + '/user/' + userId)
                .then(response => {
                    console.log(response.data.message);
                    const index = this.information.findIndex(info => info.courseID === courseId && info.userID === userId);
                    if (index !== -1) {
                        this.information.splice(index, 1);
                        this.totalItems = this.information.length;
                        this.currentPage = Math.ceil(this.totalItems / this.itemsPerPage);
                    }
                })
                .catch(error => {
                    console.log(error.response.data.message);
                });
        },
        editStatus(courseId, userId, status, info) {
            console.log("courseId", courseId, "userId", userId)
            const newStatus = status === 1 ? 2 : 1;
            axios.put('api/update/course/' + courseId + '/user/' + userId + '/status/' + newStatus)
                .then(response => {
                    console.log(response.data.message);
                    info.status = newStatus;
                    info.completed = response.data.completed;
                    status = newStatus;
                    if (info.status === 2) {
                        info.completed = new Date().toISOString().slice(0, 19).replace('T', ' ');
                    } else {
                        info.completed = null;
                    }
                })
                .catch(error => {
                    console.log(error.response.data.message);
                });
        },
        createCourse(name, title, status) {
            axios.put('api/create/course/' + title + '/user/' + name + '/status/' + status)
                .then(response => {
                    console.log(response.data);
                    this.showModal = false;
                    this.successMessage = 'Course created successfully!';
                    this.showSuccessMessage = true;
                    setTimeout(() => {
                        this.successMessage = '';
                        this.showSuccessMessage = false;
                    }, 3000)
                })
                .catch(error => {
                    console.log(error.response.data);
                    this.showModal = true;
                    this.errorMessage = 'Something went wrong!';
                });
        }
    }
}
</script>

<style scoped>
.button-container {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: flex-start;
    margin-left: 40px;
    margin-top: 16px;
}

.filter-container {
    display: inline-flex;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.filter-inputs {
    display: inline-flex;
    margin-left: 48px;
}

.filter-inputs input {
    margin-left: 8px;
    padding: 2px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.load-more-button {
    margin-left: 16px;
    padding: 8px;
    background-color: #305271;
    color: #fff;
    border: none;
    border-radius: 5px;
    margin-bottom: 16px;
}

.container {
    max-width: 900px;
    margin: 0 auto;
    justify-content: center;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 16px;
}

.table th,
.table td {
    padding: 10px;
    text-align: left;
}

.table th {
    background-color: #f2f2f2;
    font-weight: bold;
}

.table td.in-progress {
    color: #ff9800;
}

.table td.complete {
    color: #4caf50;
}

.modal-input {
    margin: 8px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.success-message {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: #4BB543;
    color: #fff;
    text-align: center;
    font-size: 18px;
    font-weight: 600;
    padding: 20px;
    z-index: 9999;
    opacity: 50%;
    transform: translateY(-100%);
    transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
}

.success-message.show {
    opacity: 1;
    transform: translateY(0%);
}

.error-message {
    color: #f44336;
    margin-bottom: 8px;
}
</style>
