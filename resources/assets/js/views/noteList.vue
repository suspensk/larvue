<template>
    <div class="container">
        <div class="row justify-content-center">
            <h1>My notes</h1>
            <ul class="note-list">
                <li v-for="note,index in notes" class="transit-1" :id="note.id">
                    <span v-for="tag,tagIndex in note.tags" class="transit-1" :id="tag.id"><a href="#">#{{tag.name}}</a>&nbsp;&nbsp;</span>

                    <div class="small-card">
                        <span v-for="image in note.images">
                            <img :src="'/uploads/' + image.name" height="100">
                        </span>

                        <span v-model="note.text">{{ note.text}}</span><a v-if="note.limited === true" @click="showText($event,note)" href="#" class="btn btn-info btn-sm">Read More</a>

                    </div>

                </li>
            </ul>


        </div>
    </div>
</template>

<style scoped>
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
    }
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
            //    editingTask : null
            }
        },
        methods : {
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

        },
        mounted() {
        //    this.$zoom('/uploads/dXNlcjJ0aW1lMTU0NDY0NDc2Nw==.PNG');
            let token = localStorage.getItem('jwt')

            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

            axios.get('api/notes').then(response => {
                response.data.forEach((data) => {
                    this.notes.push(
                            data
                            /*{
                        id : data.id,
                        name : data.name,
                        text: data.text,
                        created: data.created_at
                      //  tasks : []
                    }*/)
                })
             //   this.loadTasks()
            })
        },
        beforeRouteEnter (to, from, next) {
            if ( ! localStorage.getItem('jwt')) {
                return next('login')
            }

            next()
        }
    }
</script>
