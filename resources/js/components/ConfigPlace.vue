<template>
    <div class="row my-3 monitor-configs">
        <div class="col-12">
            <div v-for="(Config_Info,index) in Config_Infos"
                 class="config-place border my-3 rounded"
                 :class="Config_Info.switch? 'shadow':''">
                <div class="row">
                    <div class="col-12 my-3">
                        <div class="row mx-2 rounded-top bg-info value-height">
                            <div class="col-4 flex-total-left">
                                <div v-if="!Config_Info.sensor" class="flex-total-left">
                                    <!--                                  當新增時執行  -->
                                    <label for="select_sensor_name"></label>
                                    <select id="select_sensor_name" class="sensor_choice" @change="sensorGet(index)">
                                        <option value=null class="">請選擇</option>
                                        <option v-for="item in items" :value="item" class="">{{ch_name[item]}}</option>
                                    </select>
                                </div>
                                <div v-else class="flex-total-left text-light">
                                    {{ change_chi(Config_Info.sensor) }}
                                </div>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-4 justify-content-end d-flex align-items-center">
                                <i class="fa fa-trash" aria-hidden="true" @click="deleteConfig(index)"></i>
                                <label class="mx-2">
                                    <input type="checkbox" class="check_box d-none" v-model="Config_Info.switch"
                                           name="switch_config" id="switch_control" @change="updateSwitch(index)">
                                    <span class="switch_box">
                                        <span class="switch_btn"></span>
                                    </span>
                                </label>
                                <a data-toggle="collapse" :href='"#collapseUseful"+index'
                                   aria-expanded="false" aria-controls="collapseUseful">
                                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="mx-2 son-partflex-total-center border rounded-bottom collapse"
                             :class="Config_Info.control ? '':'show'"
                             :id='"collapseUseful"+index' :key='"config-part"+index'>
                            <div class="row flex-total-center mt-3">
                                <div v-if="Config_Info.sensor" class="col-12 mb-3 row flex-total-center">
                                    <div class="col-6">
                                        <!--                                    下列透過JQ進行li印出-->
                                        <prog-items :config_value=Config_Info.value :config_index="index"
                                                    :config_sensor=Config_Info.sensor :config_critical=name_critical
                                                    class="value-config value-height"
                                                    @update_value="set_value">
                                        </prog-items>
                                    </div>
                                    <div class="col-2">
                                        <span class="text-secondary text-small">
                                            該範圍  {{ name_critical[Config_Info.sensor].min }} ~ {{ name_critical[Config_Info.sensor].max }}
                                        </span>
                                    </div>
                                    <div class="col-1 flex-total-center">
                                        {{ Config_Info.value }}
                                    </div>
                                    <div class="col-3 flex-total-right">
                                        <button class="btn-create flex-total-center rounded text-white"
                                                @click="updateCreateConfig(index)">
                                            {{ Config_Info.control? '修改':'新增' }}
                                        </button>
                                    </div>
                                </div>
                                <div v-else class="col-12 flex-total-center my-3 text-secondary">請選擇感測器</div>
                            </div>
                        </div>
                    </div>
                    <!--                    中斷-->
                </div>
            </div>
        </div>
        <div class="col-12 d-flex justify-content-between px-3">
            <a :href="back_url">
                <i class="fa fa-reply" aria-hidden="true"></i>
            </a>
            <i class="fa fa-plus monitor-item-name mb-3" aria-hidden="true" @click="add_Configs()"></i>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "ConfigPlace",
        props: {
            back_url: '',
            config_infos: Array,
            name_critical: {},
            api_url: Array,
            former_name: '',
        },
        data() {
            return {
                value_items: [
                    'rounded-left', 'rounded-0', 'rounded-0', 'rounded-0', 'rounded-0',
                    'rounded-0', 'rounded-0', 'rounded-0', 'rounded-0', 'rounded-right'
                ],
                Config_Infos: _.cloneDeep(this.config_infos),
                ch_name: {
                    "WA1": '水位感測器',
                    "WA2": '水PH感測器',
                    "AI1": '溫度感測器',
                    "AI3": '甲烷感測器',
                    "LIG": '光線感測器',
                    "WA3": '土壤濕度感測器',
                    "AI2": '相對濕度感測器',
                    "AI4": '一氧化碳感測器',
                },
                items: ['WA1', 'WA2', 'WA3', 'AI1', 'AI2', 'AI3', 'AI4', 'LIG'],
            }
        },
        methods: {
            //新增Array
            add_Configs() {
                this.Config_Infos.push({
                    id: this.Config_Infos.length,
                    sensor: null,
                    switch: false,
                    farmland: 0,
                    former: '',
                    value: 0,
                    control: false,
                });
            },
            //中英文轉換
            change_chi(name) {
                //先進行切割 後放入物件 撈出
                return this.ch_name[name];
            },
            //用以emit進行更改數值
            set_value(number, index) {
                //設定數值
                this.$set(this.Config_Infos[index], 'value', number);
            },
            //針對新增的Config進行 設定value
            sensorGet(id) {
                let value = document.getElementById('select_sensor_name');
                this.Config_Infos[id].sensor = value.value;
            },
            deleteConfig(index) {
                let self = this;
                if (confirm('確定刪除')) {
                    axios.post(self.api_url[1], {
                        'former': self.former_name,
                        'farmland': self.Config_Infos[index].farmland,
                        'sensor': self.Config_Infos[index].sensor,
                    }).then(function (res) {
                        console.log(res.data);
                    }).catch(function (err) {
                        console.log(err);
                    }).finally(function () {
                        self.$delete(self.Config_Infos, index);
                    });
                }
                this.Config_Infos[index].control ? this.items.push(this.Config_Infos[index].sensor) : "";
                this.items.sort();
            },
            updateCreateConfig(index) {
                let api = this.Config_Infos[index].control ? this.api_url[2] : this.api_url[0];
                let self = this;
                axios.post(api, {
                    'former': this.former_name,
                    'farmland': this.Config_Infos[index].farmland,
                    'sensor': this.Config_Infos[index].sensor,
                    'switch': this.Config_Infos[index].switch,
                    'value': this.Config_Infos[index].value
                }).then(function (res) {
                    console.log(res.data);
                }).catch(function (err) {
                    console.log(err);
                }).finally(function () {
                    alert('新增/更新成功');
                });

                this.items.splice(this.items.indexOf(this.Config_Infos[index].sensor), 1);
                this.$set(this.Config_Infos[index], 'control', true);
            },
            updateSwitch(index) {
                let self = this;
                axios.post(this.api_url[3], {
                    'former': self.former_name,
                    'farmland': this.Config_Infos[index].farmland,
                    'sensor': this.Config_Infos[index].sensor,
                    'switch': this.Config_Infos[index].switch,
                }).then(function (res) {
                    console.log(res.data);
                }).catch(function (err) {
                    console.log(err);
                })
            },
            searchSensor() {
                let number;
                let self = this;
                _.forEach(this.Config_Infos, function (item) {
                    number = self.items.indexOf(item.sensor);
                    self.items.splice(number, 1);
                })
            }
        },
        mounted() {
            this.searchSensor();
        }

    }
</script>

<style scoped>

    .close_box {
        display: none;
    }


    label {
        margin: 0;
        height: 1rem;
    }

    ul {
        list-style-type: none;
        margin: 0;
    }

    #get-li-part ul, li {
        float: left;
    }

    #get-li-part li {
        margin-right: 0.25rem;
    }

    .scroll-range > span {
        font-size: 0.5rem !important;
    }

    .btn-cancel {
        width: 4rem;
        background-color: #1f6fb2;
    }

    .btn-create {
        width: 4rem;
        background-color: #113049;
    }

    .switch_box {
        display: inline-block;
        width: 2rem;
        height: 1.3rem;
        border-radius: 100px;
        background-color: gainsboro;
    }

    .switch_box .switch_btn {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background-color: white;
    }

    .check_box {
        position: absolute;
        opacity: 0;
    }

    .check_box:checked + .switch_box .switch_btn {
        margin-left: 1rem;
    }

    .check_box:checked + .switch_box {
        background-color: #5cd08d;
    }

    .sensor_choice {
        width: 10rem;
        color: #000;
    }

    .value-height {
        height: 2.5rem;
    }

    .text-small {
        font-size: 0.5rem;
    }

    .symbol-w-h:before {
        height: 1rem;
        width: 1rem;
    }
</style>