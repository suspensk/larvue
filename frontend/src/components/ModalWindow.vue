<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" persistent max-width="290">
            <slot name="card"></slot>
        </v-dialog>
    </v-layout>
</template>
<script>
    export default {
        data () {
            return {
                dialog: true
            }
        }
    }
</script>