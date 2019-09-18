<template>
    <div class="container sensor-part border border-light rounded mb-3">
        <div class="row my-3 no-gutters text-center">
            <div class="col-12 mb-3 form-header">
                <div class="row ">
                    <div class="col-3 form-title text-left"> {{ value_group.form }} __ {{ value_group.crop}}</div>
                    <div class="col-6"></div>
                    <div class="col-3 place-tools text-right">
                        <i class="fa fa-question" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="row no-gutters">
                    <div v-for="(hex_value,index) in hex_values"
                         class="col-3 mt-3 h-100 d-flex justify-content-center  align-items-center">
                        <div class="monitor-items">
                            <!--                            class="hexagon"-->
                            <div :id=count_off(index)></div>
                        </div>
                    </div>
                    <div class="col-12 h-75">&nbsp;</div>
                    <div class="col-12  text-right">
                        <a :href="urlPath">
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

    export default {
        name: "conitor-exponent",
        props: {
            value_group: Object,
            config_number: 0,
        },
        methods: {
            count_off(id) {
                return this.monitor_id[id] + '-' + this.config_number;
            }
        },
        computed: {
            urlPath() {
                return this.url_path + this.value_group.form + '_' + this.value_group.crop;
            },
        },
        data() {
            return {
                hex_values: [this.value_group.water, this.value_group.light,
                    this.value_group.air, this.value_group.weather],
                url_path: 'monitor/',
                //六角形ID
                monitor_id: ['monitor-water', 'monitor-light', 'monitor-air', 'monitor-weather'],
                hex_draw: [],
            }
        },
        mounted() {
            for (let i = 0; i < 4; i++) {
                this.hex_draw[i] = new Draw_Info(this.monitor_id[i] + '-' + this.config_number, this.hex_values[i], 100, 0);
                Make_Circle(this.hex_draw[i])
            }
        },
    }
</script>

<style scoped>
    .go-monitor {
        position: absolute;
        top: 0;
        right: 0;
        border-color: transparent #00FF00 transparent transparent;
        border-style: solid solid solid solid;
        border-width: 1.5rem 1.5rem 0 0;
        height: 0;
        width: 0;
        background-color: var(--main-color);
    }
</style>