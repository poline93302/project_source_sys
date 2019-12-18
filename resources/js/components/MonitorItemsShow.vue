<!--個別監控的數值畫面的主畫面-->
<template>
    <div class="border w-100 my-2 shadow bg-white rounded">
        <div :class="[statuesColor(monitor_target,'border'), item_infos.classes[target_name]]"
             class="row border m-3 monitor-item rounded-top ">
            <div class="item-info col-12 flex-total-center bg-success rounded-top mb-2"
                 :class="statuesColor(monitor_target,'bg')">
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
        </div>
        <weights-modal :name="name" :farmland="farmland" :farm_id="farm_id"
                       :type="target_name" :title="item_infos.names[target_name]"
                       :items="monitor_items" :itemsThreshold="item_value"
                       :ch_name="item_infos.items" :sensor_name="item_infos.sensor">
        </weights-modal>
    </div>
</template>

<script>
    import {
        Draw_Info,
        Make_Circle,
        LightChange,
        WindpointerChar,
        DoardChardot,
        WaterLevelChar
    } from "../Active/Sketchpad";
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
                    self.draw_Info[key]['draw_type'] = self.item_infos.items_draws[key];

                    switch (self.draw_Info[key]['draw_type']) {
                        case 'circle': {
                            Make_Circle(self.draw_Info[key]);
                            break;
                        }
                        case 'ph': {
                            DoardChardot(self.draw_Info[key]);
                            break;
                        }
                        case 'revers-circle': {
                            Make_Circle(self.draw_Info[key]);
                            break;
                        }
                        case 'light-img': {
                            LightChange(self.draw_Info[key]);
                            break;
                        }
                        case 'water-level-img': {
                            WaterLevelChar(self.draw_Info[key]);
                            break;
                        }
                        case 'pointer': {
                            WindpointerChar(self.draw_Info[key]);
                            break;
                        }
                    }
                });
            },
            statuesColor(target, type) {
                let colorGYR = ['danger','warning','success'];
                let rightColor = this.target_reverse ? colorGYR :colorGYR.reverse();

                let score = Math.floor(target / 30) ;

                return type === 'border' ?
                    'border-'+rightColor[score] :
                    'bg-'+rightColor[score];
            },
            apartStatues() {
                switch (this.target_name) {
                    case 'air':
                    case 'weather':
                        this.target_reverse = false;
                        break;
                    case 'environment':
                    case 'water':
                    case 'light':
                        this.target_reverse = true;
                        break;

                    //        target_reverse
                }
                console.log(this.target_name);
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
                    items_draws:
                        {
                            'water_level': 'water-level-img',
                            'water_ph': 'ph',
                            'water_soil': 'circle',
                            'light_lux': 'light-img',
                            'air_cp': 'revers-circle',
                            'air_ph4': 'revers-circle',
                            'air_hun': 'circle',
                            'air_tem': 'circle',
                            'weather_windWay': 'pointer',
                            'weather_windSpeed': 'revers-circle',
                            'weather_rainAccumulation': 'revers-circle',
                        },
                },
                target_reverse: true,
                item_value: {},
                draw_Info: {},
            }
        },
        mounted() {
            this.get_value();
            this.apartStatues();
            // setInterval(this.get_value,36000);
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