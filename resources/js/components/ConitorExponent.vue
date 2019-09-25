<template>
    <div class="container sensor-part border border-light rounded my-5 shadow">
        <div class="row my-3 no-gutters text-center">
            <div class="col-12 mb-3 form-header">
                <div class="row ">
                    <div class="col-3 form-title text-left"> {{ form_crop }}</div>
                    <div class="col-6"></div>
                    <div class="col-3 place-tools text-right">
                        <a data-toggle="collapse" :href='"#replyCollapse"+config_number' role="button"
                           aria-expanded="false" aria-controls="collapseExample">
                            <i class="fa fa-question" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div class="col-12">
                        <div class="collapse" :id='"replyCollapse"+config_number'>
                            <div class="card card-body collapse-item">
                                <span class="">權重對應表 </span>
                                <div>{{ innerHTML = textWeights() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row no-gutters">
                    <div v-for="(hex_value,item,key) in hex_values[1]"
                         class="col-3 mt-3 h-100 d-flex justify-content-center  align-items-center">
                        <div class="monitor-items" :id="count_off(key)">
                            <div>{{ item_id[key] }}</div>
                        </div>
                    </div>
                    <div class="col-12 h-75">&nbsp;</div>
                    <div class="col-12  text-right">
                        <a :href="url_path">
                            <div class="go-monitor"></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {Draw_Info, Make_Hex, Make_Circle} from "../Active/Sketchpad";
    import _ from 'lodash';
    import axios from 'axios';

    export default {
        name: "conitor-exponent",
        props: {
            form_crop: String,
            config_number: 0,
            url_api_target: '',
            url_path: '',
            name: '',
        },
        methods: {
            count_off(id) {
                console.log(this.monitor_id[id]);
                return this.monitor_id[id] + '-' + this.config_number;
            },
            getValue() {
                // hex_value
                let info = [];
                let self = this;
                info = this.form_crop.split('_');
                console.log();
                axios.post(this.url_api_target, {
                    'name': this.name,
                    'farmland': this.config_number,
                }).then(function (res) {
                    //weights =>[0] 權重 [1]大權重
                    self.hex_values = [res.data.weights, res.data.target];
                }).catch(function (err) {
                    console.log('ERROR' + err);
                }).finally(function () {
                    for (let i = 0; i < 4; i++) {
                        self.hex_draw[i] = new Draw_Info(self.monitor_id[i] + '-' + self.config_number, self.hex_values[1][self.en_item_id[i]], 100, 0);
                        Make_Circle(self.hex_draw[i]);
                    }
                });

            },
            textWeights() {
                let pCollapse = '';
                if (this.hex_values[0]) {
                    pCollapse =
                        `
                        ${this.item_id[0]} :  水量 : ${this.hex_values[0].water_level} 水酸鹼值 : ${this.hex_values[0].water_ph} 土壤濕度 : ${this.hex_values[0].water_soil}
                        ${this.item_id[1]}  :  亮度 : ${this.hex_values[0].water_level}
                        ${this.item_id[2]} : 一氧化碳 : ${this.hex_values[0].air_cp} 溫度 : ${this.hex_values[0].air_hun} 甲烷 : ${this.hex_values[0].air_ph4} 濕度 : ${this.hex_values[0].air_tem}
                        ${this.item_id[3]} : 累積雨量 : ${this.hex_values[0].weather_rainAccumulation} 風速 : ${this.hex_values[0].weather_windSpeed} 風向 : ${this.hex_values[0].weather_windWay}
                        `;
                    return pCollapse;
                }
            }
        },
        data() {
            return {
                hex_values: [],
                //六角形ID
                monitor_id: ['monitor-water', 'monitor-light', 'monitor-air', 'monitor-weather'],
                item_id: ['水健康指數', '光健康指數', '空氣健康指數', '氣候健康指數'],
                en_item_id: ['water', 'light', 'air', 'weather'],
                //hex_draw 得到數值
                hex_draw: {},
                register: true,
            }
        },
        mounted() {
            setTimeout(this.getValue(), 3600);
        },
    }
</script>

<style scoped>
    .go-monitor {
        position: absolute;
        top: 0;
        right: 0;
        border-color: transparent #e5c72f transparent transparent;
        border-style: solid solid solid solid;
        border-width: 1.5rem 1.5rem 0 0;
        height: 0;
        width: 0;
    }
</style>