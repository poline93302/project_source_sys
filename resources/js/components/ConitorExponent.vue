<template>
    <div class="col-12 mb-3 form-header">
        <div class="row ">
            <div class="col-3 form-title text-left"> {{ value_group.form }} __ {{ value_group.crop}}</div>
            <div class="col-6"></div>
            <div class="col-3 place-tools text-right">
                <i class="fa fa-question" aria-hidden="true"></i>
            </div>
        </div>
        <div class="row no-gutters mt-3">
            <div class="col-3 my-5 h-100 d-flex justify-content-center  align-items-center">
                <div class="monitor-items">
                    <div class="hexagon" id="monitor-water">
                        <div class="paint-exponent">
                            {{ value_group.water }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3 my-5 h-100 d-flex justify-content-center  align-items-center">
                <div class="monitor-item">
                    <div class="hexagon" id="monitor-light">
                        <div class="paint-exponent">
                            {{ value_group.light }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3 my-5 h-100 d-flex justify-content-center  align-items-center">
                <div class="monitor-items">
                    <div class="hexagon" id="monitor-air">
                        <div class="paint-exponent">
                            {{ value_group.air }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3 my-5 h-100 d-flex justify-content-center  align-items-center">
                <div class="monitor-items">
                    <div class="hexagon" id="monitor-weather">
                        <div class="paint-exponent">
                            {{ value_group.weather }}
                        </div>
                    </div>
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
</template>

<script>
    let urlPath = 'monitor/';
    export default {
        name: "conitor-exponent",
        props: {
            routeName: {
                type: String,
                default: 'jojogg_crop'
            },
            value_group: Object,
        },
        computed: {
            urlPath() {
                return urlPath + this.routeName;
            }
        }
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