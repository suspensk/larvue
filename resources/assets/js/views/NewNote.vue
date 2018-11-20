<template>
    <div class="flex-center position-ref full-height">
        <div class="container">
            <div id="components-demo">
                <button-counter></button-counter>
                <button-counter></button-counter>
                <button id="show-modal" @click="showModal = true">add tag</button>
                <modal v-if="showModal" @close="showModal = false">
                    <!--
                      you can use custom content here to overwrite
                      default content
                    -->
                    <span slot="body"><input v-model="newTag" type="text" placeholder="tag"></span>
                    <span slot="footer">
                         <button class="modal-default-button" @click="hideModal">
                          OK
                         </button>
                    </span>
                    <h3 slot="header">Adding new tag</h3>
                </modal>
            </div>
            <div  class="m-b-md">

                <div class="card-body">
                    <form method="POST" action="/notes/add">
                        <!--<div class="form-group row">-->
                            <!--<label for="name" class="col-md-4 col-form-label text-md-right">Tags</label>-->

                            <!--<div class="col-md-6">-->
                                <!--<input id="name" type="text" class="form-control" v-model="name" required autofocus>-->
                            <!--</div>-->
                        <!--</div>-->
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Title</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" v-model="name" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="text" class="col-md-4 col-form-label text-md-right">Text</label>

                            <div class="col-md-6">
                                <textarea rows="8" id="text" type="text" class="form-control" v-model="text" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" @click="handleSubmit">
                                    Add note
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <notifications group="foo" />
        <autocomplete
                url="/api/tags"
                anchor="name"
                label=""
                :on-select="getData"
                :onAjaxLoaded="ajaxLoaded"
        >
        </autocomplete>
    </div>
</template>

<style scoped>
.full-height {
    height: 100vh;
}

.flex-center {
    align-items: center;
    display: flex;
    justify-content: center;
}

.position-ref {
    position: relative;
}

.top-right {
    position: absolute;
    right: 10px;
    top: 18px;
}

.content {
    text-align: center;
}

.title {
    font-size: 60px;
}

.links > a {
    color: #636b6f;
    padding: 0 25px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .1rem;
    text-decoration: none;
    text-transform: uppercase;
}

.m-b-md {
    margin-bottom: 30px;
    color: #000000;
}
</style>


<script>
    import Modal from './ModalWindow';
    import Autocomplete from 'vue2-autocomplete-js';
    require('vue2-autocomplete-js/dist/style/vue2-autocomplete.css')
    export default {
        data(){
            return {
                name : "",
                text : "",
                showModal: false,
                newTag: ""
            }
        },
        methods : {
            handleSubmit(e) {
                e.preventDefault();
                //this.$router.go('/board');
                    axios.post('api/notes', {
                        name: this.name,
                        text: this.text,
                    })
                            .then(response => {
                                this.$router.push('/notes');
                            })
                            .catch(error => {
                                console.error(error);
                            });

            },
            hideModal() {
                let that = this;
                axios.post('api/tags', {
                    name: this.newTag,
                })
                        .then(response => {
//
                             console.log(response.data);
                            that.$notify({
                                group: 'foo',
                                title: 'Success',
                                type: 'success',
                                text: 'added successfully'
                            });

                        //    this.$router.push('/notes');
                        })
                        .catch(error => {
                            console.error(error);
                            that.$notify({
                                group: 'foo',
                                title: 'Error',
                                type: 'error',
                                text: error.response.statusText
                            });
                        });
                this.showModal = false
            },
            getData(obj){
                console.log('data:',obj);
            },
            ajaxLoaded(obj){
                console.log('mydata:',obj);
             //   let response = JSON.parse(obj);
                // The options to pass in the autocomplete props
                this.options = obj;
            }
        },
        components: {
            Modal, Autocomplete
        }

    }
</script>
