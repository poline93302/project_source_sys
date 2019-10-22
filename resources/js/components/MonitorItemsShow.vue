<!--個別監控的數值畫面的主畫面-->
<template>
    <div class="border w-100 my-2 shadow">
        <div :class="[monitor_target<=30 ? 'border-danger' : monitor_target>60? 'border-success': 'border-warning', item_infos.classes[target_name]]"
             class="row border m-3 monitor-item rounded-top ">
            <div class="item-info col-12 flex-total-center bg-success rounded-top mb-2"
                 :class="monitor_target<=30 ? 'bg-danger' : monitor_target>60? 'bg-success': 'bg-warning'">
                {{ item_infos.names[target_name]}}
            </div>
            <div class="col-lg-8 col-12 monitor-item-show">
                <div class="row no-gutters mx-3 flex-total-center">
                    <div v-for="(item,index) in monitor_items" class="col-auto flex-total-center flex-column">
                        <div>{{ item_infos.items[index] }}</div>
                        <div :id="index"></div>
                    </div>
                </div>
            </div>
            <div class="col-2 d-lg-none"></div>
            <div class="col-lg-4 col-8 flex-total-center">
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
                            <div class="col-2  flex-total-center">
                                <i class="fa fa-cog" aria-hidden="true" data-toggle="modal"
                                   :data-target="'#weight_Modal_'+target_name"></i>
                            </div>
                            <div class="col-10 text-dark flex-total-center">
                                綜合指數：
                                <div class="text-small">{{ monitor_target }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-2 d-lg-none"></div>
        </div>
        <weights-modal :name="name" :farmland="farmland" :farm_id="farm_id"
                       :type="target_name" :title="item_infos.names[target_name]"
                       :items="monitor_items" :itemsThreshold="item_value"
                       :ch_name="item_infos.items" :sensor_name="item_infos.sensor"></weights-modal>
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
            get_value() {
                let self = this;
                axios.post(this.url_api, {
                    'name': this.name,
                    'farm': this.farm_id,
                    'farmland': this.farmland,
                    'type': this.target_name,
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
                        "CON": 'air_cp', "CHE": 'air_ph4',
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