<template>
    <div class="modal fade" :id="target_name+'_modal'" tabindex="-1"
         role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                {{ name }}
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "sensorHistory",
        props: {
            target_name: String,
            name: String,
        },
        methods: {
            getValue() {
                // console.log(location.href);
                axios.post('/api/item/history', function () {

                }).then(function (res) {
                    console.log(res.data);
                }).catch(function (err) {
                    console.log(err);
                })
            }
        },
        data() {
            return {
                //抓取value()
                catch_value: [],
            }
        },
        mounted() {
            this.getValue();
        }
    }
</script>

<style scoped>

</style>