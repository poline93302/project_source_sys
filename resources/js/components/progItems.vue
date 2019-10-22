<template>
    <ul class="col-12 h-scroll flex-total-center border rounded" id="get-li-part" v-html="li_str">
    </ul>
</template>

<script>
    let process_max = 25;
    export default {
        name: "progItems",
        props: {
            config_value: Number,
            config_index: Number,
            config_sensor: '',
            config_critical: Object,
        },
        methods: {
            //建立li
            create_li(value) {
                //li隔數
                let process_max = 25;
                //數值的總範圍
                let ran = (this.config_critical[this.config_sensor].max - this.config_critical[this.config_sensor].min);
                //計算一個li數值為多少
                let wegi = ran / process_max;
                //一個為多少
                let smallRan = ((Math.floor(25 / 3)) * wegi);
                //顏色this.config_critical[this.config_sensor].min
                let bg_color = ['bg-danger', 'bg-warning', 'bg-success', 'bg-success', 'bg-light'];
                //計算第幾個li為終點
                let manyPoint = Math.floor((value - this.config_critical[this.config_sensor].min) / wegi);

                //計算li
                for (let i = 0; i < process_max; i++) {
                    this.li_str +=
                        `<li class="border blk ${i <= manyPoint ? bg_color[Math.floor((value - this.config_critical[this.config_sensor].min) / smallRan)] : bg_color[4]} ${i === 0 ? 'rounded-left' : i === process_max - 1 ? 'rounded-right' : 'rounded-0'}" id="blk-${this.config_index}-${i}"></li>`;
                }
                this.add_listener();
            },
            //呼叫 father change
            trigger_set(id) {
                let ran = (this.config_critical[this.config_sensor].max - this.config_critical[this.config_sensor].min);
                let wegi = ran / process_max;
                let changeValue = (wegi * id) + this.config_critical[this.config_sensor].min;
                console.log(changeValue);
                this.$emit('update_value', changeValue, this.config_index);
            },
            //加入聆聽事件
            add_listener() {
                this.$nextTick(() => {
                    //加入 click 事件 我給一百分
                    for (let i = 0; i < process_max; i++) {
                        document.getElementById('blk-' + this.config_index + "-" + i).addEventListener('click', () => {
                            this.trigger_set(i);
                        });
                    }
                });
            }
        },
        watch: {
            //監控數值 改變 il的value
            config_value: function () {
                this.li_str = "";
                this.create_li(this.config_value);
            },
        },
        data() {
            return {
                li_str: '',
            }
        },
        created() {
            this.create_li(this.config_value);
        },
    }
</script>

<style scoped>

</style>