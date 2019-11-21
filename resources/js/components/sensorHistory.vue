<template>
    <div class="modal fade" :id="sensor_name+'_modal'" tabindex="-1"
         role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="container">
                    <div class="row">
                        <div class="col-12 flex-total-center my-2"> {{ name }}</div>

                        <div class="col-10 flex-total-center" :id="'painting-history-'+sensor_name">
                        </div>
                        <div class="col-2 flex-column d-flex btn-history">
                            <button type="button" class="btn btn-outline-info btn-sm" @click="changeDay(1)"
                                    id="choiceDay1">一天
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" @click="changeDay(3)"
                                    id="choiceDay3">三天
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" @click="changeDay(7)"
                                    id="choiceDay7">七天
                            </button>
                        </div>
                        <div class="col-12 flex-total-center">
                            發揮地區
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import _ from 'lodash';
    import {Draw_Info_History, Make_HistoryChart} from '../Active/Sketchpad';

    export default {
        name: "sensorHistory",
        props: {
            sensor_name: String,
            farm_id: Number,
            farmland: Number,
            farmer: String,
            name: String,
            target_type: String,
        },
        methods: {
            async getValue() {
                let self = this;
                // console.log(location.href);
                await axios.post('/api/item/history', {
                    'sensor': this.sensor_name,
                    'farmer': this.farmer,
                    'farm': this.farm_id,
                    'farmland': this.farmland,
                    'type': this.target_type,
                }).then(function (res) {
                    self.catch_value = {
                        'max': res.data.max,
                        'min': res.data.min,
                    };
                    _.forEach(res.data.res, function (item) {
                        self.total_value.push(item.value);
                        self.total_time.push(item.time);
                    });
                }).catch(function (err) {
                    console.log(err);
                }).finally(function () {
                    self.finishPainting();
                })
            },
            changeDay(day) {
                this.day_count = day;
                this.activeBtn(day);
            },
            finishPainting() {
                this.painting_info = new Draw_Info_History('painting-history-' + this.sensor_name,
                    this.total_value, this.total_time, this.catch_value.max, this.catch_value.min);
                Make_HistoryChart(this.painting_info, this.day_count);
            },
            activeBtn(node) {
                let choice = document.getElementById('choiceDay' + node);
                this.delActiveBtn();
                choice.classList.add('active', 'text-white');
            },
            delActiveBtn() {
                let number = [1, 3, 7];

                number.forEach(function (item) {
                    document.getElementById('choiceDay' + item).classList.remove('active', 'text-white');
                });
            }
        },
        data() {
            return {
                //抓取value()
                catch_value: {},
                total_value: [],
                total_time: [],
                day_count: 1,
                painting_info: {},
            }
        },
        mounted() {
            this.getValue();
            this.activeBtn(this.day_count);
        },
        watch: {
            day_count: {
                handler() {
                    Make_HistoryChart(this.painting_info, this.day_count);
                }
            }
        }
    }
</script>

<style scoped>
    .flex-column>button{
        margin-bottom: 1rem;
    }
</style>