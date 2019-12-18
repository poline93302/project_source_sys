<template>
    <div class="container sensor-part border border-light rounded mt-5 shadow bg-white">
        <div class="row mt-3 no-gutters text-center">
            <div class="col-12 mb-3 form-header">
                <div class="row ">
                    <div class="col-5 form-title text-left text-title"> {{ form_crop }}</div>
                    <div class="col-4"></div>
                    <div class="col-3 place-tools text-right">
                        <a data-toggle="collapse" :href='"#replyCollapse"+config_number' role="button"
                           aria-expanded="false" aria-controls="collapseExample">
                            <i class="fa fa-list text-center" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div class="col-12">
                        <div class="collapse" :id='"replyCollapse"+config_number'>
                            <div class="card card-body collapse-item">
                                <div v-if="hex_values[0]" class="container">
                                    <div class="row text-center flex-total-center">
                                        <div class="col-12 border border-info border-bottom-0 pt-2">權重對應表</div>
                                        <div class="col-12 text-notice text-secondary border border-info border-top-0 pb-2">
                                            (若需要更改請至農場監控畫面進行更改)
                                        </div>
                                        <div class="col-12 border border-info p-0">
                                            <div class="row no-gutters">
                                                <div class="col-4 border-info border-right">健康名稱</div>
                                                <div class="col-4 border-info border-right">感測名稱</div>
                                                <div class="col-4 border-info border-right">權重</div>
                                            </div>
                                        </div>

                                        <div class="col-12 border border-top-0">
                                            <div v-for="(item,index) in en_item_id"
                                                 class="row text-center p-0 ">
                                                <div class="col-4 border-right flex-total-center border-top py-2">
                                                    {{ item_id[index] }}
                                                </div>
                                                <div class="col-4 border-right ">
                                                    <div v-for="it in sensorOrder[item]" class="border-top py-2">
                                                        {{sensor_ch[it]}}
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div v-for="it in sensorOrder[item]" class="border-top py-2">
                                                        {{hex_values[0][it]}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row no-gutters flex-scrollable overflow-auto p-2 p-lg-0 flex-nowrap">
                    <div v-for="(hex_value,item,key) in hex_values[1]"
                         class="col-3 mt-3 mr-5 mr-lg-0 flex-total-center text-center">
                        <div class="monitor-items" :id="count_off(key)">
                            <div>{{ item_id[key] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-right">
                    <a :href="url_path">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                    </a>
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
                return this.monitor_id[id] + '-' + this.config_number;
            },
            getValue() {
                // hex_value
                let self = this;

                axios.post(this.url_api_target, {
                    'name': this.name,
                    'farm': this.form_crop.split('_')[0],
                    'farmland': this.config_number,
                    'gateWay': false,
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
        },
        data() {
            return {
                hex_values: [],
                //六角形ID
                monitor_id: ['monitor-air', 'monitor-weather', 'monitor-water', 'monitor-light'],
                //感測器順序
                sensorOrder: {
                    'air': ['air_cp', 'air_ph4'],
                    'light': ['light_lux'],
                    'water': ['water_level', 'water_ph'],
                    'weather': ['weather_rainAccumulation', 'weather_windSpeed'],
                },
                sensor_ch: {
                    'water_level': '水位',
                    'water_ph': '水PH',
                    'water_soil': '土壤濕度',
                    'light_lux': '亮度',
                    'air_cp': '一氧化碳',
                    'air_ph4': '甲烷',
                    'air_hun': '濕度',
                    'air_tem': '溫度',
                    'weather_windSpeed': '風速',
                    'weather_rainAccumulation': '累積雨量',
                },
                item_id: ['場域健康指數', '氣候指數', '水指數', '光指數'],
                en_item_id: ['air', 'weather', 'water', 'light'],
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
        bottom: 0;
        right: 0;
        border-color: transparent #59ff00 transparent transparent;
        border-style: solid solid solid solid;
        border-width: 1.5rem 1.5rem 0 0;
        height: 0;
        width: 0;
    }

    .text-notice {
        font-size: 0.5rem !important;
    }

    .text-title {
        font-size: 1.2rem !important;
    }
</style>