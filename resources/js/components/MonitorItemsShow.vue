<!--個別監控的數值畫面的主畫面-->
<template>
    <div class="border w-100 p-2 my-2 shadow">
        <div :class="[monitor_target<=30 ? 'border-danger' : monitor_target>60? 'border-success': 'border-warning', item_infos.classes[target_name]]"
             class="row border no-gutters m-3 monitor-item rounded-top ">
            <div class="item-info col-12 flex-total-center bg-success rounded-top mb-3"
                 :class="monitor_target<=30 ? 'bg-danger' : monitor_target>60? 'bg-success': 'bg-warning'">
                {{ item_infos.names[target_name]}}
            </div>
            <div class=" col-10 monitor-item-show row float-left">
                <div v-for="(item,index) in monitor_items" v-if="index % 2 === 0" class="col-4">
                    <div class="text-center">{{ item_infos.items[item] }}</div>
                    <div :id="item" class="text-center"></div>
                </div>
            </div>
            <div class="col-2">
                <div class="row no-gutters bg-white rounded my-3 shadow">
                    <span class="weights-style col-4 text-dark border border-success flex-total-center ">權重</span>
                    <span class="items-style col-8 text-dark border border-info flex-total-center">項目</span>

                    <div class="col-12 monitor-item-list row text-dark no-gutters  border border-dark">
                        <div v-for="(item,index) in monitor_items" v-if="index % 2 === 0"
                             class="col-12  flex-total-center border-bottom ">
                            <div class="row item-list-count w-100">
                                <div class="col-4 text-dark text-center  border-right">{{ monitor_items[index+1] }}
                                </div>
                                <div class="col-8 text-dark text-right">{{ item_infos.items[item] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 border border-dark ">
                        <div class="row no-gutters flex-total-center">
                            <div class="col-2 flex-total-center">
                                <i class="fa fa-cog" aria-hidden="true"></i>
                            </div>
                            <div class="col-10 text-dark flex-total-center">
                                綜合指數：
                                <div class="text-small">{{ monitor_target }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {Draw_Info, Make_Circle} from "../Active/Sketchpad";
    import _ from 'lodash';
    import axios from 'axios';

    export default {
        name: "MonitorItemsShow",
        props: {
            monitor_target: Number,
            monitor_items: Array,
            target_name: String,
            url_api: '',
            name: String,
            farmland: Number,
        },
        methods: {
            get_value() {
                let self = this;
                axios.post(this.url_api, {
                    'name': this.name,
                    'farmland': this.farmland,
                    'type': this.target_name,
                }).then(function (res) {
                    self.item_value = res.data;
                }).catch(function (err) {
                    console.log(err);
                }).finally(function () {
                    self.finish_draw();
                })
            },
            finish_draw() {
                let self = this;
                _.forEach(this.item_value, function (id, key) {
                    self.draw_Info[key] = new Draw_Info(self.item_infos.sensor[id['sensor']], id['value'], id['max'], id['min']);
                    Make_Circle(self.draw_Info[key]);
                });
            }
        },
        data: function () {
            return {
                item_infos: {
                    classes: {
                        'water': 'monitor-item-water',
                        'light': 'monitor-item-light',
                        'air': 'monitor-item-air',
                        'weather': 'monitor-item-weather'
                    },
                    names: {
                        'water': '水健康指數',
                        'light': '燈泡健康指數',
                        'air': '空氣健康指數',
                        'weather': '氣候健康指數'
                    },
                    sensor: {
                        "AI1": 'air_cp', "AI2": 'air_ph4',
                        "AI3": 'air_hun', "AI4": 'air_tem',
                        "WA1": 'water_level', "WA2": 'water_ph',
                        "WA3": 'water_soil', "LIG": 'light_lux',
                        "WE1": 'weather_windWay', "WE2": 'weather_windSpeed',
                        "WE3": 'weather_rainAccumulation',
                    },
                    items:
                        {
                            'water_level': '水位',
                            'water_ph': '水PH',
                            'water_soil': '土壤濕度',
                            'light_lux': '亮度',
                            'air_cp': '一氧化碳',
                            'air_ph4': '甲烷',
                            'air_hun': '濕度',
                            'air_tem': '溫度',
                            'weather_windWay': '風向',
                            'weather_windSpeed': '風速',
                            'weather_rainAccumulation': '累積雨量',
                        },
                },
                item_value: {},
                draw_Info: {},

            }
        },
        mounted() {
            this.get_value();
            // setTimeout(, 3600);
        }
    }
</script>

<style scoped>
    .weights-style {
        border-radius: 0.25rem 0 0 0;
    }

    .items-style {
        border-radius: 0 0.25rem 0 0;
    }

    .item-info {
        height: 1.5rem;
    }

    .text-small {
        font-size: 0.8rem;
    }
</style>