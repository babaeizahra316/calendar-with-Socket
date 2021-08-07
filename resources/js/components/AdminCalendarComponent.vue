<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form @submit.prevent>
                    <div class="form-group">
                        <label for="user_name">User Name</label>
                        <input type="text" id="user_name" class="form-control" v-model="newEvent.user_name" disabled>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input
                                        type="date"
                                        id="start_date"
                                        class="form-control"
                                        v-model="newEvent.start_date"
                                        disabled
                                >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" id="end_date" class="form-control" v-model="newEvent.end_date" disabled>
                            </div>
                        </div>
                        <template>
                            <div class="col-md-6 mb-4">
                                <button class="btn btn-sm btn-success" @click="updateEvent">Accept</button>
                                <button class="btn btn-sm btn-danger" @click="deleteEvent">Reject</button>
                            </div>
                        </template>
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <Fullcalendar @eventClick="showEvent" :plugins="calendarPlugins" :events="events"/>
            </div>
        </div>
    </div>
</template>

<script>

    import Fullcalendar from "@fullcalendar/vue";
    import dayGridPlugin from "@fullcalendar/daygrid";
    import interactionPlugin from "@fullcalendar/interaction";
    import axios from "axios";

    export default {
        components: {
            Fullcalendar
        },
        data() {
            return {
                calendarPlugins: [dayGridPlugin, interactionPlugin],
                events: "",
                newEvent: {
                    user_name: "",
                    event_name: "",
                    start_date: "",
                    end_date: "",
                    status: "",
                    backgroundColor: ""
                },
                addingMode: true,
                indexToUpdate: "",
            };
        },

        created() {
            this.getEvents();
            this.listenForActivity();
        },
        methods: {
            listenForActivity() {
                Echo.channel(`model`)
                    .listen('.CalendarCreated', (e) => {
                        console.log(e.model);
                        this.resetForm();
                        this.getEvents();
                    })
                    .listen('.CalendarUpdated', (e) => {
                        console.log(e.model);
                        this.resetForm();
                        this.getEvents();
                    })
                    .listen('.CalendarDeleted', (e) => {
                        console.log(e.model);
                        this.resetForm();
                        this.getEvents();
                    });
            },
            showEvent(arg) {
                this.addingMode = false;
                const { id, user_name, title, start, end, status,  backgroundColor} = this.events.find(
                    event => event.id === +arg.event.id
                );
                this.indexToUpdate = id;
                this.newEvent = {
                    user_name: user_name,
                    event_name: title,
                    start_date: start,
                    end_date: end,
                    status: status,
                    backgroundColor: backgroundColor
                };
            },
            updateEvent() {
                axios
                    .put("/admin/calendar/" + this.indexToUpdate, {
                        ...this.newEvent
                    })
                    .then(resp => {
                        this.resetForm();
                        this.getEvents();
                        this.addingMode = !this.addingMode;
                    })
                    .catch(err =>
                        console.log("Unable to update event!", err.response.data)
                    );
            },
            deleteEvent() {
                axios
                    .delete("/admin/calendar/" + this.indexToUpdate)
                    .then(resp => {
                        this.resetForm();
                        this.getEvents();
                        this.addingMode = !this.addingMode;
                    })
                    .catch(err =>
                        console.log("Unable to delete event!", err.response.data)
                    );
            },
            getEvents() {
                axios
                    .get("/admin/calendar")
                    .then(resp => (this.events = resp.data.data))
                    .catch(err => console.log(err.response.data));
            },
            resetForm() {
                Object.keys(this.newEvent).forEach(key => {
                    return (this.newEvent[key] = "");
                });
            }
        },
        watch: {
            indexToUpdate() {
                return this.indexToUpdate;
            }
        }
    };
</script>

<style lang="css">
    @import "~@fullcalendar/core/main.css";
    @import "~@fullcalendar/daygrid/main.css";

    .fc-title {
        color: #fff;
    }

    .fc-title:hover {
        cursor: pointer;
    }
</style>

