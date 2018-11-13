<template>
    <div class="flex-center position-ref full-height">
        <script type="text/x-template" id="modal-template">
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-container">

                            <div class="modal-header">
                                <slot name="header">
                                    default header
                                </slot>
                            </div>

                            <div class="modal-body">
                                <slot name="body">
                                    default body
                                </slot>
                            </div>

                            <div class="modal-footer">
                                <slot name="footer">
                                    default footer
                                    <button class="modal-default-button" @click="$emit('close')">
                                        OK
                                    </button>
                                </slot>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </script>
        <div class="container">
            <div id="components-demo">
                <button-counter></button-counter>
                <button-counter></button-counter>
                <button id="show-modal" @click="showModal = true">Show Modal</button>
                <modal v-if="showModal" @close="showModal = false">
                    <!--
                      you can use custom content here to overwrite
                      default content
                    -->
                    <h3 slot="header">custom header</h3>
                </modal>
            </div>
            <div  class="m-b-md">

                <div class="card-body">
                    <form method="POST" action="/notes/add">
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
    export default {
        data(){
            return {
                name : "",
                text : "",
                showModal: false
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
//                                localStorage.setItem('user',response.data.success.name)
//                                localStorage.setItem('jwt',response.data.success.token)
//
//                                if (localStorage.getItem('jwt') != null){
//                                    this.$router.go('/board')
//                                }
                              //  console.log(response.data);

                                this.$router.push('/notes');
                            })
                            .catch(error => {
                                console.error(error);
                            });

            }
        },

    }
</script>
