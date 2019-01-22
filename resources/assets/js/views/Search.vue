<template>
        <form class="form-inline my-2 my-lg-0">
             <span v-for="tag in tags">
                <a href="#" @click="removeTag(tag.id, $event)"> x </a><span>{{ tag.name }}</span>&nbsp;&nbsp;
             </span>
            <autocomplete
                    ref="autocomplete"
                    className="tags form-control"
                    url="/api/tags"
                    anchor="name"
                    label=""
                    :onSelect="setTag"
                    :onAjaxLoaded="ajaxLoaded"
                    placeholder="placeholder"
                    :customHeaders="myHeaders"
            >
            </autocomplete>
          <!--  <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">-->
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</template>


<script>
    import Autocomplete from 'vue2-autocomplete-js';
    require('vue2-autocomplete-js/dist/style/vue2-autocomplete.css')
export default {
    data(){
        return {
            isLoggedIn : null,
            name : null,
            tags: [],
        }
    },
    computed: {
        myHeaders() {
            return {
                Authorization: 'Bearer ' + localStorage.getItem('jwt')
            };
        }
    },
    mounted(){
        this.isLoggedIn = localStorage.getItem('jwt')
        this.name = localStorage.getItem('user')
    },
    created(){
        this.$radio.$on('tag-query', (tags) => {
            this.tags = tags;
           // this.init();
        });
    },
    methods : {
        setTag (obj) {
            this.tags.push(obj);
            this.$refs.autocomplete.setValue('');
            this.$router.push(this.createQuery());
            this.$radio.$emit('tag-search',this.tags);
        },
        removeTag(id, e){
            e.preventDefault();
            this.tags = this.tags.filter(function( obj ) {
                return obj.id !== id;
            });

            this.$router.push(this.createQuery());
            this.$radio.$emit('tag-search',this.tags);
        },
        createQuery(){
            if(this.tags.length == 0){
                return '/notes';
            }
            let tags_names = this.tags.map(a => a.name);
            let tags_string = tags_names.join();
            let query = '/notes?tags=' + tags_string;
            return query;
        },
        ajaxLoaded(obj){
         //   console.log('mydata:',obj);
            //   let response = JSON.parse(obj);
            // The options to pass in the autocomplete props
            this.options = obj;
        }
    },
    components: {
        Autocomplete
    }
}
</script>
