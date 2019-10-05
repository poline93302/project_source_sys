<template>
    <div class="modal fade" :id="'weight_Modal_'+type" tabindex="-1" role="dialog"
         :aria-labelledby="'weight_Modal_'+type+'Label'" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="'weight_Modal_'+type+'Label'">{{title}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="">
                    <div class="modal-body row no-gutters text-center ">
                        <div class="col-4  border border-info">感測名稱</div>
                        <div class="col-4  border-top border-bottom border-info">權重</div>
                        <div class="col-4  border  border-info">極限值</div>
                        <div v-for="(item,key) in use_items" class="col-12 row no-gutters text-center">
                            <div class="col-4 border border-top-0 border-right-0 flex-total-center"> {{ch_name[key]}}
                            </div>
                            <input type="text"
                                   class="col-4 border border-top-0 border-right-0 flex-total-center text-center"
                                   v-model="use_items[key]" @change="edit=false"
                                   oninput="value=value.replace(/[^\d]/g,'')">
                            <div class="col-4 border border-top-0 border-right-0 row no-gutters">
                                <div class="col-12 row no-gutters">
                                    <div class="col-4 border-right border-bottom">max</div>
                                    <div class="col-8 border-right border-bottom ">{{use_threshold}}</div>
                                </div>
                                <div class="col-12 row no-gutters">
                                    <div class="col-4 border-right">min</div>
                                    <div class="col-8 border-right">123</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="resetData()">關閉
                        </button>
                        <button type="button" class="btn btn-primary" @click="submitData()">{{edit?'更新':'設定'}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';

    export default {
        name: "WeightsModal",
        props: {
            type: String,
            title: String,
            items: Object,
            itemsThreshold: Object,
            ch_name: Object,
        },
        methods: {
            resetData() {
                this.use_items = _.cloneDeep(this.items);
            },
            submitData() {
                let self = this;
                let upTotal = 0;
                let total = 0;
                let count = 0;
                // 改變後的數值
                let changeNumber = [];
                // 存放sensor之順序
                let sensor_name = [];

                let alert_str = '您輸入的數值錯誤是否願意以[';

                if (!this.edit) {
                    //建立新的陣列
                    _.forEach(this.use_items, function (value, key) {
                        sensor_name.push(key);
                        changeNumber.push(value);
                    });


                    //計算最佳數直
                    do {
                        upTotal = total;
                        total = 0;
                        //     //計算總和
                        _.forEach(changeNumber, function (value) {
                            total += Number(value);
                        });
                        for (let i = 0; i < changeNumber.length; i++) {
                            changeNumber[i] = Math.round((changeNumber[i] / total * 10000) / 100);
                        }
                        //  判斷計算是否總和為無窮 連續兩為無窮數 進行最大值++
                        if (upTotal === total) count++;
                        // 確認最大值
                        if (count > 2) {
                            changeNumber.sort();
                            changeNumber[changeNumber.length - 1] += Math.abs(upTotal - 100);
                            break;
                        }
                    } while (total !== 100);

                    // //設定顯示資訊之的數值
                    for (let i = 0; i < sensor_name.length; i++) {
                        alert_str += self.ch_name[sensor_name[i]] + ' = ' + changeNumber[i] + "%";
                        i !== sensor_name.length - 1 ? alert_str += ', ' : alert_str += ']';
                    }
                    // //設定權重上拉下降後的數值
                    if (confirm(alert_str)) {
                        for (let i = 0; i < changeNumber.length; i++) {
                            this.$set(this.use_items, sensor_name[i], changeNumber[i]);
                        }

                    } else {
                        this.resetData();
                    }
                    this.edit = true;
                } else {
                    console.log('update');
                }


            },
        },
        data() {
            return {
                use_items: {},
                use_threshold: {},
                // 是否編輯過
                edit: true,
            }
        },
        mounted() {
            this.use_items = _.cloneDeep(this.items);
            this.use_threshold = _.cloneDeep(this.itemsThreshold);
        },
    }
</script>

<style scoped>

</style>