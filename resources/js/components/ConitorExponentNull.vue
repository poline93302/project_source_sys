<template>
    <div class="text-center container sensor-part border border-light rounded mt-5 shadow py-5  bg-white">
        <div class="col-12"> {{form_crop}}</div>
        <div class="col-12">請通知客服已開通資訊</div>
    </div>
</template>

<script>
    export default {
        name: "ConitorExponentNull",
        props: {
            form_crop: String,
        }
    }
</script>

<style scoped>

</style>