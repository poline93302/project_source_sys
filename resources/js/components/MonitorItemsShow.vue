<!--個別監控的數值畫面的主畫面-->
<template>
    <div class="border w-100 my-2 shadow">
        <div :class="[monitor_target<=30 ? 'border-danger' : monitor_target>60? 'border-success': 'border-warning', item_infos.classes[target_name]]"
             class="row border m-3 monitor-item rounded-top ">
            <div class="item-info col-12 flex-total-center bg-success rounded-top mb-2"
                 :class="monitor_target<=30 ? 'bg-danger' : monitor_target>60? 'bg-success': 'bg-warning'">
                <div class="justify-content-between">
                    {{ item_infos.names[target_name]}}
                    <i class="fa fa-cog" aria-hidden="true" data-toggle="modal"
                       :data-target="'#weight_Modal_'+target_name"></i>
                </div>
            </div>
            <div class="col-lg-12 col-12 monitor-item-show">
                <div class="row no-gutters mx-3 flex-total-center">
                    <div v-for="(item,index) in monitor_items" class="col-auto flex-total-center flex-column">
                        <div>{{ item_infos.items[index] }}</div>
                        <div data-toggle="modal" :data-target="'#'+index + '_modal'" :id="index"></div>
                        <sensor-history :sensor_name="index" :name="item_infos.items[index]"
                                        :farm_id="farm_id" :farmland="farmland"
                                        :farmer="name" :target_type="target_name"
                        >

                        </sensor-history>
                    </div>
                </div>
            </div>
            <!--            <div class="col-2 d-lg-none"></div>
                        <div class="col-lg-4 col-8 flex-total-center mb-3">
                            <div class="row no-gutters bg-white rounded shadow ">
                                <span class="weights-style col-4 text-dark border border-success flex-total-center ">權重</span>
                                <span class="items-style col-8 text-dark border border-info flex-total-center">項目</span>

                                <div class="col-12 monitor-item-list row text-dark no-gutters  border border-dark">
                                    <div v-for="(item,index) in monitor_items"
                                         class="col-12  flex-total-center border-bottom ">
                                        <div class="row item-list-count w-100">
                                            <div class="col-4 text-dark text-center  border-right flex-total-center">{{ item }}
                                            </div>
                                            <div class="col-8 text-dark text-right flex-total-center">
                                                {{ item_infos.items[index] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 border border-dark rounded-bottom ">
                                    <div class="row no-gutters flex-total-center">
                                        <div class="col-12 text-dark flex-total-center">
                                            綜合指數：
                                            <div class="text-small">{{ monitor_target }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2 d-lg-none"></div>-->
        </div>
        <weights-modal :name="name" :farmland="farmland" :farm_id="farm_id"
                       :type="target_name" :title="item_infos.names[target_name]"
                       :items="monitor_items" :itemsThreshold="item_value"
                       :ch_name="item_infos.items" :sensor_name="item_infos.sensor">
        </weights-modal>
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
            monitor_items: Object,
            farm_id: Number,
            target_name: String,
            url_api: '',
            name: String,
            farmland: Number,
        },
        methods: {
            async get_value() {
                let self = this;
                await axios.post(this.url_api, {
                    'name': this.name,
                    'farm': this.farm_id,
                    'farmland': this.farmland,
                    'type': this.target_name,
                    'gateWay': false,
                }).then(function (res) {
                    _.forEach(res.data, function (item) {
                        self.$set(self.item_value, [self.item_infos.sensor[item.sensor]], {
                            'max': item.max,
                            'min': item.min,
                            'value': item.value
                        });
                    });
                }).catch(function (err) {
                    console.log(err);
                }).finally(function () {
                    self.finish_draw();
                })
            },
            finish_draw() {
                let self = this;
                _.forEach(this.item_value, function (id, key) {
                    self.draw_Info[key] = new Draw_Info(key, id['value'], id['max'], id['min']);
                    Make_Circle(self.draw_Info[key]);
                });
            }
        },
        data: function () {
            return {
                item_infos: {
                    classes: {
                        'environment': 'monitor-item-environment',
                        'water': 'monitor-item-water',
                        'light': 'monitor-item-light',
                        'air': 'monitor-item-air',
                        'weather': 'monitor-item-weather'
                    },
                    names: {
                        'environment': '環境指數',
                        'water': '水指數',
                        'light': '光指數',
                        'air': '場域健康指數',
                        'weather': '氣候指數'
                    },
                    sensor: {
                        //CON CHE 有害氣體
                        "CON": 'air_cp', "CHE": 'air_ph4',
                        //OTE OHY 是環境
                        "OTE": 'air_hun', "OHY": 'air_tem',
                        "WLS": 'water_level', "WPH": 'water_ph',
                        "WSO": 'water_soil', "LFS": 'light_lux',
                        "OWN": 'weather_windWay', "OWS": 'weather_windSpeed',
                        "ORA": 'weather_rainAccumulation',
                    },
                    items:
                        {
                            'water_level': '水位',
                            'water_ph': '水PH',
                            'water_soil': '土壤濕度',
                            'light_lux': '亮度',
                            'air_cp': '一氧化碳',
                            'air_ph4': '甲烷',
                            'air_hun': '相對濕度',
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