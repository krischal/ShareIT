<template>
<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div id="data-container" class="p-3">
                    <section id="data-header" class="mt-3">
                        <h3 class="text-center">Feel Free to share any thing!</h3>
                    </section>
                    <section id="data-errors">
                        <div v-if="createSharedDataForm.errors.length > 0" class="alert alert-warning alert-dismissible fade show" role="alert">
                            <span v-for="(error, index) in createSharedDataForm.errors" :key="index">{{ error }}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div v-if="createSharedDataForm.isCreated" class="alert alert-success alert-dismissible fade show" role="alert">
                            <span>Thanks for sharing your thoughts!</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                    </section>
                    <section id="add-data-form" class="my-3">
                        <form>
                            <div class="d-flex justify-content-between align-items-center">
                                <textarea
                                    v-model="createSharedDataForm.description"
                                    v-on:keyup.enter="addSharedData"
                                    minlength="5"
                                    placeholder="Share your thoughts on anything!"
                                    type="text" class="form-control mr-3"></textarea>
                                <button v-if="createSharedDataForm.isSubmitting" class="btn btn-primary" type="button" disabled>
                                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                    <span class="sr-only">Sharing...</span>
                                </button>
                                <button v-else @click.prevent="addSharedData" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </section>
                    <section id="data-actions"></section>
                    <section id="data-list">
                        <ul class="list-group">
                            <div v-if="shareddatas.isLoading" class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <li
                                v-if="!shareddatas.isLoading && shareddatas.data.length > 0"
                                v-for="data in shareddatas.data" :key="data.uuid"
                                class="list-group-item">
                                <div class="col-12 p-0">
                                    <h5>Guest</h5>
                                </div>
                                <div class="col-12 px-0">
                                    {{ data.description }}
                                    <br>
                                    <button class="btn btn-sm btn-round btn-danger mt-2" href="#" @click.prevent="destroy(data)">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                        <br>
                                </div>
                                <div class="d-flex w-100 justify-content-between">
                                    <small class="text-muted"></small>
                                    <small class="text-muted">{{ data.created_at }}</small>
                                </div>
                            </li>
                            <li v-if="!shareddatas.isLoading && shareddatas.data.length === 0" class="list-group-item list-group-item-action list-group-item-warning">
                                No posts found.
                            </li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
var dataCount =0;
    export default {
        name: 'SharedDataList',
        data() {
            return {
                shareddatas: {
                    isLoading: false,
                    data: []
                },
                createSharedDataForm: {
                    isSubmitting: false,
                    isCreated: false,
                    description: undefined,
                    errors: []
                },
                error: undefined
            }
        },
        mounted() {
            this.loadSharedData();
            let timer = setInterval(this.asyncData, 1500);
        },
        methods: {

            /**
             * Loads shareddatas
             */
            loadSharedData() {
                // update loader to loading
                this.shareddatas.isLoading = true;

                axios.get('/allData')
                .then((response) => {
                    this.shareddatas.data = response.data;
                    dataCount = response.data.length;

                })
                .catch((error) => {
                    console.log(error.message);
                    this.error = 'Unable to load shareddatas. View log for more information';
                })
                .finally(() => {
                    // disable loader
                    this.shareddatas.isLoading = false;
                })
            },

            /**
             * Create a SharedItem.
             * Uses payload in the this.createSharedDataForm param
             */
            addSharedData() {
                // update loader to loading
                this.createSharedDataForm.isSubmitting = true;
                // Use can use this as the payload.
                axios.post('/addData', this.createSharedDataForm)
                .then((response) => {
                    this.createSharedDataForm.errors = [];
                    this.createSharedDataForm.description = undefined;
                    this.createSharedDataForm.isCreated = true;
                    this.loadSharedData();
                })
                .catch((error) => {
                    /**
                     * Check for form validation error. Laravel return http code 422
                     * _ lodash is already loaded by laravel. check resources/js/bootstrap.js
                     */
                    if (error.response && error.response.status === 422) {
                        this.createSharedDataForm.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        console.log(error.message);
                        this.error = 'Unable to add data. View log for more information';
                    }
                })
                .finally(() => {
                    // disable loader
                    this.createSharedDataForm.isSubmitting = false;
                })
            },

            /**
             * Deletes item
             * @param sharedItem
             */

            destroy(sharedItem) {
                // Use can use this as the payload.
                axios.delete(`/delData/${sharedItem.id}`)
                    .then((response) => {
                        this.loadSharedData();
                    })
                    .catch((error) => {
                        console.log(error.message);
                        this.error = 'Post couldnot be deleted!';
                    })
            },

            asyncData(){
                axios.get('/allData')
                .then((response) => {
                    let data = response.data;
                    if (data.length != dataCount){
                        dataCount = data.length;
                        this.loadSharedData();
                    }
                })
                .catch((error) => {
                    console.log(error.message);
                    this.error = 'Unable to async data.';
                })
                .finally(() => {
                    // disable loader
                 this.shareddatas.isLoading = false;
                })
            }
            

        }
    }
</script>
