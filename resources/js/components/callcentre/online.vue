<template>
<div>
    <span class="pulse"></span>
</div>
</template>

<script>
export default {

}
</script>

<style scoped>
.pulse {
    margin-left: 50%;
    display: block;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #2bb956;
    cursor: pointer;
    box-shadow: 0 0 0 rgb(43 185 86 / 67%);
    animation: pulse 2s infinite;
}

@-webkit-keyframes pulse {
    0% {
        -webkit-box-shadow: 0 0 0 rgb(43 185 86 / 67%);
    }

    70% {
        -webkit-box-shadow: 0 0 0 10px rgba(204, 169, 44, 0);
    }

    100% {
        -webkit-box-shadow: 0 0 0 0 rgba(204, 169, 44, 0);
    }
}

@keyframes pulse {
    0% {
        -moz-box-shadow: 0 0 0 rgb(43 185 86 / 67%);
        box-shadow: 0 0 0 rgb(43 185 86 / 67%);
    }

    70% {
        -moz-box-shadow: 0 0 0 10px rgba(204, 169, 44, 0);
        box-shadow: 0 0 0 10px rgba(204, 169, 44, 0);
    }

    100% {
        -moz-box-shadow: 0 0 0 0 rgba(204, 169, 44, 0);
        box-shadow: 0 0 0 0 rgba(204, 169, 44, 0);
    }
}
</style>
