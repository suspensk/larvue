<template>
    <div class="flex-center position-ref full-height">
        <div class="container">
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
