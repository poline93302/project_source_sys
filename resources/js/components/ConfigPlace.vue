<template>
    <div class="row my-3 monitor-configs">
        <div class="col-12  rounded border">
            <div v-for="(Config_Info,index) in Config_Infos" class="config-place border my-3">
                <div class="row">
                    <div class="col-12 my-3">
                        <div class="row mx-3 rounded">
                            <div class="col-3 flex-total-left">
                                <div v-if="!Config_Info.sensor_name" class="flex-total-left">
                                    <!--                                  當新增時執行  -->
                                    <label for="select_sensor_name"></label>
                                    <select id="select_sensor_name" class="sensor_choice">
                                        <option value=null class="">請選擇</option>
                                        <option value="WTL" class="">水位感測器</option>
                                        <option value="SOL" class="">土壤感測器</option>
                                    </select>
                                </div>
                                <div v-else class="flex-total-left">
                                    {{ change_chi(Config_Info.sensor_name) }}
                                </div>
                            </div>
                            <div class="col-5"></div>
                            <div class="col-4 justify-content-end d-flex align-items-center">
                                <a href="#" class="mx-1">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                                <label class="mx-2">
                                    <input type="checkbox" class="check_box d-none" v-model="Config_Info.switch"
                                           name="switch_config" id="switch_control">
                                    <span class="switch_box">
                                        <span class="switch_btn"></span>
                                    </span>
                                </label>

                                <i class="fa fa-sort-desc " aria-hidden="true" @click="open_page(Config_Info.id)"></i>

                            </div>
                        </div>
                    </div>
                    <!--                    中斷-->
                    <div class="son-part col-12 flex-total-center" :id='"config-part"+index'
                         :class="Config_Info.create_config_info? 'close_box':'open_box'">
                        <div class="row flex-total-center w-100">
                            <div class="col-12 mb-3 row flex-total-center">
                                <div class="col-6 row">
                                    <!--                                    下列透過JQ進行li印出-->
                                    <prog-items :config_value=Config_Info.sensor_value :config_index="index"
                                                class="value-config value-height"
                                                @update_value="set_value"></prog-items>
                                </div>
                                <div class="col-6 row value-show value-height">
                                    <div class="col-12 flex-total-center">
                                        {{Config_Info.sensor_value}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row mb-3">
                                <div class="col-6">
                                    <div class="col-12 scroll-range flex-total-center">
                                        <span>該範圍A~B</span>
                                    </div>
                                </div>
                                <div class="col-6 flex-total-right">
                                    <a href="#" class="mx-3">
                                        <div class="btn-cancel flex-total-center rounded"> {{
                                            Config_Info.create_config_info? '取消':'清除' }}
                                        </div>
                                    </a>
                                    <a href="#" class="ml-3">
                                        <div class="btn-create flex-total-center rounded"> {{
                                            Config_Info.create_config_info? '修改':'新增' }}
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <i class="fa fa-plus monitor-item-name mb-3" aria-hidden="true" @click="add_Configs()"></i>
        </div>
    </div>
</template>

<script>
    let ch_name = {
        'WTL': '水位感測器',
        'SOL': '土壤感測器',
    };
    export default {
        name: "ConfigPlace",
        data() {
            return {
                Config_Infos: [
                    {
                        id: 0,
                        sensor_name: 'WTL_001',
                        switch: true,
                        sensor_value: 20,
                        create_config_info: true,
                    }
                ],
                value_items: [
                    'rounded-left', 'rounded-0', 'rounded-0', 'rounded-0', 'rounded-0',
                    'rounded-0', 'rounded-0', 'rounded-0', 'rounded-0', 'rounded-right'
                ]
            }
        },
        methods: {
            add_Configs() {
                this.Config_Infos.push({
                    id: this.Config_Infos.length,
                    sensor_name: null,
                    switch: false,
                    create_config_info: false,
                    sensor_value: 0,
                });
            },
            //打開子畫面
            open_page(index) {
                let part = document.getElementById('config-part' + index);
                let info = this.Config_Infos[index];

                if (info.sensor_name !== 'null')
                    part.style.display === 'flex' ? part.style.display = 'none' : part.style.display = 'flex';
            },
            change_chi(name) {
                //先進行切割 後放入物件 撈出
                return ch_name[name.split('_')[0]];

            },
            set_value(number, index) {
                //設定數值
                this.$set(this.Config_Infos[index], 'sensor_value', number);
            },
            //進去條背景改變
            bg_item_value(index) {
                let item_value = [];
                let item_id = 'blk';
                for (let i = 0; i <= index; i++) {
                    item_value[i] = document.getElementById(item_id + i);
                    // item_value[i].className('blk-bark');
                    console.log(item_value[i]);
                }
            }
        },
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
        height: 1.2rem;
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
        height: 2rem;
    }

    .symbol-w-h:before {
        height: 1rem;
        width: 1rem;
    }
</style>