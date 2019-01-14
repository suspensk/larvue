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
    methods : {
        setTag (obj) {
            this.tags.push(obj);
            //console.log(obj);
            this.$radio.$emit('tag-search',this.tags);
            this.$refs.autocomplete.setValue('');
        },
        removeTag(id, e){
            e.preventDefault();
            this.tags = this.tags.filter(function( obj ) {
                return obj.id !== id;
            });
            this.$radio.$emit('tag-search',this.tags);
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
