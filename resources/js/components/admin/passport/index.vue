<template>
<v-app>
    <v-btn small elevation="3" color="primary" @click="store">Post</v-btn>
    <v-btn small elevation="3" color="primary" @click="getData">Get</v-btn>
</v-app>
</template>

<script>
export default {
    methods: {
        store() {
            const data = {
                name: "Jane Doe",
                redirect: "http://127.0.0.1:8000/callback",
            };

            axios
                .post("/oauth/clients", data)
                .then((response) => {
                    console.log(response.data);
                })
                .catch((response) => {
                    console.log(response);
                    // List errors on response...
                });
        },
        getData() {
            const data = {
                name: "Jane Doe",
                redirect: "http://127.0.0.1:8000/callback",
            };

            axios
                .post("/oauth/clients", data)
                .then((response) => {
                    console.log(response.data);
                })
                .catch((response) => {
                    console.log(response);
                    // List errors on response...
                });
        },
    },
};
</script>

<style>
</style>
