<template>
    <div class="flex-center position-ref full-height">
        <div class="container">
            <div id="components-demo">
                <picture-input
                        ref="pictureInput"
                        :removable="true"
                        :hideChangeButton="true"
                        :toggleAspectRatio="false"
                        :changeOnClick="true"
                        @change="onChangeImage"
                        @remove="onRemoveImage"
                        width="400"
                        height="300"
                        margin="16"
                        accept="image/jpeg,image/png"
                        size="15"
                        buttonClass="btn"
                        :customStrings="{
        upload: '<h1>Bummer!</h1>',
        drag: 'Drag a 😺 GIF or GTFO'
      }">
                </picture-input>
               <!-- <button-counter></button-counter>
                <button-counter></button-counter>-->

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
                        <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Tags</label>
                        <div class="col-md-6">
                            <span v-for="tag in tags">
                                <a href="#" @click="removeTag(tag.id, $event)"> x </a><span>{{ tag.name }}</span>&nbsp;&nbsp;
                            </span>
                            <autocomplete
                                    ref="autocomplete"
                                    className="tags"
                                    url="/api/tags"
                                    anchor="name"
                                    label=""
                                    :onSelect="setTag"
                                    :onAjaxLoaded="ajaxLoaded"
                                    placeholder="placeholder"
                                    :customHeaders="myHeaders"
                            >
                            </autocomplete>
                            <button id="show-modal" class="btn btn-primary"  @click="showModal = true">add tag</button>
                        </div>
                        </div>
                        <div class="form-group row">
                            <label for="text" class="col-md-4 col-form-label text-md-right">Text</label>

                            <div class="col-md-6">
                                <textarea rows="8" id="text" type="text" class="form-control" v-model="text" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="privacy" class="col-md-4 col-form-label text-md-right">Privacy settings:</label>

                            <div class="col-md-6">
                            <select name="privacy" class="form-control" id="privacy" v-model="privacy">
                                <option value="0">public</option>
                                <option value="1">protected</option>
                                <option value="2">private</option>
                            </select>
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
    import PictureInput from 'vue-picture-input';
    require('vue2-autocomplete-js/dist/style/vue2-autocomplete.css')
    export default {
        data(){
            return {
              //  name : "",
                text : "",
                privacy: 0,
                showModal: false,
                newTag: "",
                tags: [],
                image: ""
            }
        },
        computed: {
          myHeaders() {
              return {
                  Authorization: 'Bearer ' + localStorage.getItem('jwt')
              };
          }
        },
        methods : {
            onRemoveImage (image) {
                this.image = "";
            },
            onChangeImage (image) {
             //   console.log('New picture selected!')
                if (image) {
                    this.image = image;
                //    console.log('Picture loaded.')



                } else {
                    console.log('FileReader API not supported: use the <form>, Luke!')
                }
            },
            handleSubmit(e) {
                e.preventDefault();
                let that = this;
                //this.$router.go('/board');
                let tags = this.tags.map(a => a.id);
                var fd = new FormData();
                if(this.image!= "") {
                    let picture = this.$refs.pictureInput.file;
                    fd.append('image', picture);
                }
                    fd.append('text', this.text);
                    fd.append('privacy', this.privacy);
                    fd.append('tags', tags);

                    axios.post('api/notes', fd, {headers: { 'content-type': 'multipart/form-data' }})
                            .then(response => {
                                that.$notify({
                                    group: 'foo',
                                    title: 'Success',
                                    type: 'success',
                                    text: 'added successfully'
                                });
                                  this.$router.push('/notes');
                            })
                            .catch(error => {
                                that.$notify({
                                    group: 'foo',
                                    title: 'Error',
                                    type: 'error',
                                    text: error.response.data.message
                                });
                              //  console.error(error.response.data.error);
                            });


//                    axios.post('api/notes', {
//                      //  name: this.name,
//                        text: this.text,
//                        tags: tags
//                    })
//                        .then(response => {
//                        //    this.$router.push('/notes');
//                        })
//                        .catch(error => {
//                            console.error(error);
//                        });

            },
            hideModal() {
                let that = this;
                axios.post('api/tags', {
                    name: this.newTag,
                })
                        .then(response => {
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
                            that.$notify({
                                group: 'foo',
                                title: 'Error',
                                type: 'error',
                                text: error.response.data.message
                            });
                            //  console.error(error.response.data.error);
                        });
                this.showModal = false
            },
            setTag (obj) {
                this.tags.push(obj);
             //   console.log(this.$root)
             //   this.$nextTick(function () {
                //    $('.tags-input').val('');
               //     this.$refs.autocomplete.setValue ('-')
                //  })
                this.$refs.autocomplete.setValue('');
            },
            removeTag(id, e){
                e.preventDefault();
                this.tags = this.tags.filter(function( obj ) {
                    return obj.id !== id;
                });
            },
            ajaxLoaded(obj){
                console.log('mydata:',obj);
             //   let response = JSON.parse(obj);
                // The options to pass in the autocomplete props
                this.options = obj;
            }
        },
        components: {
            Modal, Autocomplete, PictureInput
        }

    }
</script>
<style>
    .tags-list ul li a {background-color: #ffc107  }
    .tags-list ul {z-index: 1;}
</style>