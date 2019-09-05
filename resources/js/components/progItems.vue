<template>
    <ul class="col-12 h-scroll flex-total-center" id="get-li-part" v-html="li_str">
    </ul>
</template>

<script>
    let process_max = 25;
    export default {
        name: "progItems",
        props: {
            config_value: '',
            config_index: 0,
        },
        methods: {
            //建立li
            create_li(value) {
                let small_range = Math.floor(process_max / 3);
                let bg_color = ['bg-light', 'bg-danger', 'bg-warning', 'bg-success'];
                //判斷index
                for (let i = 0; i < process_max; i++) {
                    this.li_str +=
                        `<li class="blk ${i <= value ? bg_color[Math.ceil(value / small_range)] : bg_color[0]}  ${i === 0 ? 'rounded-left' : i === process_max - 1 ? 'rounded-right' : 'rounded-0'}" id="blk-${this.config_index}-${i}"></li>`;
                }
                this.add_listener();
            },
            //呼叫 father change
            trigger_set(value) {
                this.$emit('update_value', value, this.config_index);
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