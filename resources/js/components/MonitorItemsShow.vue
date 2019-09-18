<template>
    <div class="border w-100 p-2">
        <div v-for="(item_info,index) in item_infos.classes" :class=" item_infos.classes[index]"
             class="row border no-gutters my-3 monitor-item">
            <div class="col-10 monitor-item-show row flex-total-center ">
                <div class="col-12 flex-total-center">{{item_infos.names[index]}}</div>
                <div v-for="item in item_infos.items[index]" class="col" :id="item"></div>
            </div>
            <div class="col-2">
                <div class="row no-gutters bg-white rounded my-3">
                    <span class="weights-style col-4 text-dark border border-success flex-total-center ">權重</span>
                    <span class="items-style col-8 text-dark border border-info flex-total-center">項目</span>

                    <div class="col-12 monitor-item-list row text-dark no-gutters">
                        <div v-for="(item_list,key) in ch_name[index]" class="col-12  flex-total-center">
                            <div class="row item-list-count w-100">
                                <div class="col-4 text-dark text-center"> {{ item_count[index][key] }}</div>
                                <div class="col-8 text-dark text-right"> {{ item_list }}</div>
                            </div>
                        </div>
                        <div class="col-12  justify-content-center align-items-end d-flex ">
                            <div class="row no-gutters">
                                <div class="col-12 text-dark flex-total-center">
                                    綜合指數 ： {{ monitor_value[index] }}
                                </div>
                                <div class="col-12 color-identification flex-total-center w-100"
                                     :class="monitor_value[index]<=30 ? 'bg-danger' : monitor_value[index]>60? 'bg-success': 'bg-warning'"
                                >
                                </div>
                            </div>
                        </div>
                        <!--                辨識條-->
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
            monitor_value: Array,
        },
        methods: {
            get_value() {
                console.log('Time_Out');
                // axios.get()
            },
        },
        data: function () {
            return {
                item_infos: {
                    classes: ['monitor-item-water', 'monitor-item-light', 'monitor-item-air', 'monitor-item-weather'],
                    names: ['水健康指數', '燈泡健康指數', '空氣健康指數', '氣候健康指數'],
                    items: [
                        ['water-level', 'water-ph', 'water-soil'],
                        ['light-lux'],
                        ['air-co', 'air-ch', 'air-tem', 'air-hum'],
                        ['weather-wind-way', 'weather-wind-speed', 'weather-rain-acc']
                    ]
                },
                draw_Info: {},
                //感測器比重
                item_count: [
                    [30, 20, 50],
                    [100],
                    [20, 20, 30, 30],
                    [30, 30, 40]
                ],
                item_value: [
                    [200, 7, 50],
                    [2000],
                    [30, 40, 24, 80],
                    [360, 23, 150]
                ],
                //各項的[MIN,MAX]
                item_critical: [
                    [
                        [0, 500], [0, 14], [0, 100]
                    ],
                    [
                        [100, 3000]
                    ],
                    [
                        [0, 100], [0, 100], [0, 40], [0, 100]
                    ],
                    [
                        [0, 360], [0, 25], [0, 500]
                    ],
                ],
                ch_name: [
                    ['水位', '水PH', '土壤濕度'],
                    ['燈泡亮度'],
                    ['一氧化碳', '甲烷', '溫度', '濕度'],
                    ['風向', '風速', '累積雨量']
                ],

            }
        },
        mounted() {
            let self = this;
            let value_wrap = 0;
            _.forEach(this.item_infos.items, function (id, key) {
                for (let i = 0; i < id.length; i++) {
                    self.draw_Info[value_wrap] = new Draw_Info(id[i], self.item_value[key][i], self.item_critical[key][i][1], self.item_critical[key][i][0]);
                    Make_Circle(self.draw_Info[value_wrap]);
                    value_wrap++;
                }
            });
        },
        created() {
            setTimeout(this.get_value, 5000);
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
</style>