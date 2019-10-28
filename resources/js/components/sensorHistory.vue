<template>
    <div class="modal fade" :id="sensor_name+'_modal'" tabindex="-1"
         role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="container">
                    <div class="row">
                        <div class="col-12 flex-total-center my-2"> {{ name }}</div>
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-info btn-sm" @click="changeDay(1)">一天
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" @click="changeDay(3)">三天
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" @click="changeDay(7)">七天
                            </button>
                        </div>
                        <div class="col-12 flex-total-center" :id="'painting-history-'+sensor_name">
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
            changeDay(day){
                this.day_count = day;
            },
            finishPainting() {
                this.painting_info = new Draw_Info_History('painting-history-' + this.sensor_name,
                    this.total_value, this.total_time, this.catch_value.max, this.catch_value.min);
                Make_HistoryChart(this.painting_info, this.day_count);
            }
        },
        data() {
            return {
                //抓取value()
                catch_value: {},
                total_value: [],
                total_time: [],
                day_count: 7,
                painting_info: {},
            }
        },
        mounted() {
            this.getValue();
        },
        watch:{
          day_count:{
              handler(){
                  Make_HistoryChart(this.painting_info, this.day_count);
              }
          }
        }
    }
</script>

<style scoped>

</style>