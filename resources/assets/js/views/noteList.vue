<template>
    <div class="container">
        <newNote @reload-list="init()"></newNote>
        <modal v-if="showModal" @close="showModal = false">
            <span slot="body">
                are you sure you want to delete?
            </span>
            <span slot="footer">
                         <button class="modal-default-button" @click="deleteNote()">
                          YES
                         </button>
                <button class="modal-default-button" @click="showModal = false">
                          NO
                         </button>
                    </span>
            <h3 slot="header">Confirmation</h3>
        </modal>
        <h2 class="text-center">Notes</h2>
            <div class="card" v-for="note,index in notes" :id="note.id">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="https://image.ibb.co/jw55Ex/def_face.jpg" class="img img-rounded img-fluid"/>
                            <p class="text-secondary text-center">{{note.user.name}}</p>
                            <p class="text-secondary text-center" v-if="note.privacy == 0">public</p>
                            <p class="text-secondary text-center" v-else-if="note.privacy == 2">private</p>
                            <p class="text-secondary text-center">{{note.created_at}}</p>
                           <!-- <p class="text-secondary text-center">15 Minutes Ago</p>-->
                        </div>
                        <div class="col-md-10">
                           <!-- <p>
                                <a class="float-left" href="https://maniruzzaman-akash.blogspot.com/p/contact.html"><strong>Maniruzzaman Akash</strong></a>
                                <span class="float-right"><i class="text-warning fa fa-star"></i></span>
                                <span class="float-right"><i class="text-warning fa fa-star"></i></span>
                                <span class="float-right"><i class="text-warning fa fa-star"></i></span>
                                <span class="float-right"><i class="text-warning fa fa-star"></i></span>

                            </p>-->
                            <div class="clearfix"></div>
                            <p>
                                <span v-for="image in note.images">
                                <img @click="zoomImage('/uploads/' + image.name)" :src="'/uploads/540-' + image.name" >
                                </span>
                            </p>
                            <div class="clearfix"></div>
                            <p>
                                <span v-model="note.text">{{ note.text}}</span>
                                <a v-if="note.limited === true" @click="showText($event,note)" href="#" class="">Read More</a>
                            </p>
                            <p>
                                <a class="float-right btn btn-outline-primary ml-2"> <i class="fa fa-reply"></i> Reply</a>
                                <a class="float-right btn text-white btn-danger ml-2"> <i class="fa fa-heart"></i> Like</a>
                                <a @click="$event.preventDefault(); showModal = true; curNote=note" href="#" class="float-right btn text-white btn-danger"> <i class="fa fa-trash"></i> Delete</a>

                            </p>
                            <div class="clearfix"></div>
                            <p>
                                <span v-for="tag,tagIndex in note.tags" class="transit-1" :id="tag.id"><a href="#">#{{tag.name}}</a>&nbsp;&nbsp;</span>
                            </p>


                        </div>
                    </div>
                </div>
            </div>
        <notifications group="foo" />
    </div>

</template>

<style scoped>

  /*  .card-inner{
        margin-left: 4rem;
    }
    .card {
        border:0;
        border-radius: 0.5rem;
    }
    .transit-1 {
        transition: all 1s;
    }
    .small-card {
        padding: 1rem;
        background: #f5f8fa;
        margin-bottom: 5px;
        border-radius: .25rem;
    }
    .card-body-dark{
        background-color: #ccc;
    }
    textarea {
        overflow: visible;
        outline: 1px dashed black;
        border: 0;
        padding: 6px 0 2px 8px;
        width: 100%;
        height: 100%;
        resize: none;
    }*/
</style>

<script>
    import Modal from './ModalWindow';
    import NewNote from './NewNote';
  //  import draggable from 'vuedraggable'
    export default {
//        components: {
//            draggable
//        },
        data(){
            return {
                notes : [],
                isLoggedIn : null,
                showModal: false,
                curNote: null,
                tags:[]
            //    editingTask : null
            }
        },
        methods : {
            zoomImage(file){
                    this.$zoom(file);
            },
            showText(e, note) {
                let id = note.id;
                  e.preventDefault();
                  let note_id = id;

                axios.get('api/notes/' + note_id, {}).then(response => {
                   // this.categories[id].tasks.push(response.data.data)
                    note.text=response.data.text;
                    note.limited=false;
                    console.log(response.data);
                })
            },
            deleteNote() {
                this.showModal = false;
                let note_id = this.curNote.id;
                let that = this;

                axios.delete('api/notes/' + note_id, {}).then(response => {
                    that.$notify({
                        group: 'foo',
                        title: 'Success',
                        type: 'success',
                        text: 'deleted successfully'
                    });
                    console.log(response.data);
                    this.init();
                }).catch(error => {
                    that.$notify({
                        group: 'foo',
                        title: 'Error',
                        type: 'error',
                        text: error.response.data.message
                    });
                    //  console.error(error.response.data.error);
                });
            },
            init() {
                this.notes = [];
                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                let q = {};
                let tags = this.tags.map(a => a.id);
                q.tags = tags;
                axios.get('api/notes',{
                        params: {
                            q
                        }}).then(response => {
                    response.data.forEach((data) => {
                        this.notes.push(
                                data
                                )
                    })
                })
            }
        },
        created() {
            this.$radio.$on('logout', () => {
                this.init()
            });
            this.$radio.$on('tag-search', (tags) => {
                this.tags = tags;
                this.init();
            });
        },
        mounted() {
            this.init();

        },
        components: {
            Modal, NewNote
        }
//        beforeRouteEnter (to, from, next) {
//            if ( ! localStorage.getItem('jwt')) {
//                return next('login')
//            }
//
//            next()
//        }
    }
</script>
