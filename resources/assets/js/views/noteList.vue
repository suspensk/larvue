<template>
    <div class="container">
        <h2 class="text-center">Bootstrap 4 User Rating Form / Comment Form</h2>
            <div class="card" v-for="note,index in notes" :id="note.id">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="https://image.ibb.co/jw55Ex/def_face.jpg" class="img img-rounded img-fluid"/>
                            <p class="text-secondary text-center">{{note.user.name}}</p>
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
                                <a class="float-right btn text-white btn-danger"> <i class="fa fa-heart"></i> Like</a>
                            </p>
                            <div class="clearfix"></div>
                            <p>
                                <span v-for="tag,tagIndex in note.tags" class="transit-1" :id="tag.id"><a href="#">#{{tag.name}}</a>&nbsp;&nbsp;</span>
                            </p>


                        </div>
                    </div>
                </div>
            </div>
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
  //  import draggable from 'vuedraggable'
    export default {
//        components: {
//            draggable
//        },
        data(){
            return {
                notes : [],
                isLoggedIn : null,
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

//                let name = "New task"
//                let category_id = this.categories[id].id
//                let order = this.categories[id].tasks.length

                axios.get('api/notes/' + note_id, {}).then(response => {
                   // this.categories[id].tasks.push(response.data.data)
                    note.text=response.data.text;
                    note.limited=false;
                    console.log(response.data);
                })
            },
//            loadTasks() {
//                this.categories.map(category => {
//                    axios.get(`api/category/${category.id}/tasks`).then(response => {
//                        category.tasks = response.data
//                    })
//                })
//            },
            init() {
                this.notes = [];
                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                axios.get('api/notes').then(response => {
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
        },
        mounted() {
            this.init();

        },
//        beforeRouteEnter (to, from, next) {
//            if ( ! localStorage.getItem('jwt')) {
//                return next('login')
//            }
//
//            next()
//        }
    }
</script>
