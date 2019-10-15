<template>
    <div class="modal fade" id="FormerInfoModal" tabindex="-1" role="dialog" aria-labelledby="FormerInfoModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">農夫農場資訊</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="resetValue()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" :action="route" id="updateFormerInfo">
                    <input type="hidden" name="_token" v-model="csrf">
                    <input type="hidden" name="temporary" v-model="steps.toString">
                    <input type="hidden" name="temporary" v-model="stepsCrop.toString">
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-6">
                                    <label for="formerInfoName">名稱：</label>
                                    <input type="text" id="formerInfoName" v-model="formerName"
                                           name="updateFormerName"
                                    />
                                </div>
                                <div class="col-6">
                                    <label for="formerInfoEmail">信箱：</label>
                                    <input type="text" id="formerInfoEmail" v-model="formerEmail"
                                           name="updateFormerEmail"/>
                                </div>
                                <!--                            農場列表-->
                                <div class="col-12 my-3">
                                    <div v-for="(item,index) in forms" class="row no-gutters my-2">
                                        <div class="col-2">
                                            <span v-show="index===0">
                                                農場
                                                <i class="fa fa-plus" aria-hidden="true" @click="addItems('form')"></i>
                                            </span>
                                        </div>
                                        <div class="col-9 row no-gutters">
                                            <input type="text" class="col-6 w-100" placeholder="請輸入農場名稱"
                                                   :name='"update-FormName-"+index' :id='"update-FormName-"+index'
                                                   v-model="item[0]"
                                            />
                                            <input type="text" class="col-6 w-100" placeholder="請輸入農場地址"
                                                   :name='"update-FormAddress-"+index' :id='"update-FormAddress-"+index'
                                                   v-model="item[1]"
                                            />
                                        </div>
                                        <div class="col-1 flex-total-center">
                                            <i class="fa fa-minus text-danger" aria-hidden="true"
                                               @click="deleteItems('form',index)"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--                            農田列表-->
                                <div class="col-12">
                                    <div v-for="(Crop,index) in crops" class="row no-gutters my-2">
                                        <div class="col-2">
                                            <span v-show="index===0">
                                                農田
                                                <i class="fa fa-plus" aria-hidden="true" @click="addItems('crop')"></i>
                                            </span>
                                        </div>
                                        <div class="col-9">
                                            <div class="row no-gutters">
                                                <div class="col-6">
                                                    <select :name="'selectFormData'+index"
                                                            :id="'select-FormData-'+index"
                                                            class="w-100 h-100"
                                                            v-model="Crop[0]"
                                                    >
                                                        <option v-show="form[0]!==''" v-for="form in forms">
                                                            {{ form[0] }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" v-model="Crop[1]"
                                                           :id="'select-CropData-'+index" :name="'selectCropData'+index"
                                                           class="w-100" placeholder="請輸入農作物"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1 flex-total-center">
                                            <i class="fa fa-minus text-danger" aria-hidden="true"
                                               @click="deleteItems('crop',index)"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="resetValue()">關閉
                        </button>
                        <button type="button" class="btn btn-primary" @click="lostCheck()">更新</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';

    export default {
        name: "FormerInfoConfig",
        props: {
            formername: String,
            formeremail: String,
            formcrop: Array,
            route: String,
        },
        methods: {
            //新增農場/農田
            addItems(grad) {
                let formlen = this.forms.length;
                let croplen = this.crops.length;

                if (grad === 'form') {
                    //一次新增一層
                    if (this.forms[formlen - 1][0] !== '') {
                        this.forms.push(['', '', formlen]);
                        this.formsCopyKey.push(['', '', formlen]);
                        this.stepsCount++;
                    }
                } else {
                    if (this.crops[croplen - 1][0] !== '') {
                        this.crops.push(['', '', 'New']);
                    }
                }
            },
            //刪除農場/農田 N
            deleteItems(grad, index) {
                let self = this;
                if (grad === 'form') {
                    //將相關的crops 刪除
                    _.forEach(self.crops, function (item, indexCrops) {
                        if (self.forms[index][0] === item[0]) {
                            console.log(index);
                            console.log('form', item[0]);
                            console.log('crops', self.forms[index][0]);
                            self.crops.splice(indexCrops, 1, '');
                        }
                    });

                    //將農場刪除
                    // this.forms.splice(index, 1);
                    //將農場標記同時刪除
                    //steps相關刪除
                    // this.steps.splice(this.steps.indexOf(this.formsKey[index]), 1);
                    // this.formsCopyKey.splice(index, 1);
                    // this.formsKey.splice(index, 1);
                    // this.stepsCount--;
                } else {
                    this.crops.splice(index, 1);
                }
            },
            //重設所有數字
            resetValue() {
                let self = this;

                let formsStatue = [];
                let addressStatue = [];

                let formsItem = [];
                let addressItem = [];

                this.formerName = this.formername;
                this.formerEmail = this.formeremail;

                this.forms = [];
                this.crops = [];

                this.steps = [];
                this.stepsStatus = [];
                this.finishSteps = [];

                this.formsKey = [];
                this.formsCopyKey = [];

                this.cropsKey = [];

                _.forEach(this.formcrop, function (item) {
                    formsStatue.push(item[0]);
                    addressStatue.push(item[2]);
                    self.crops.push([item[0], item[1], item[3]]);
                    self.cropsKey.push([item[0], item[1], item[3]]);
                });

                formsItem = Array.from(new Set(formsStatue));
                addressItem = Array.from(new Set(addressStatue));

                for (let i = 0; i < formsItem.length; i++) {
                    this.forms.push([formsItem[i], addressItem[i], i]);
                    this.formsKey.push(formsItem[i]);
                    this.formsCopyKey.push([formsItem[i], addressItem[i], i]);
                }

                this.stepsCount = this.formsKey.length - 1;
            },
            //送出確認
            lostCheck() {
                //針對所有crops form 查看是否有不合法的地方
                console.log('submit');
            },
        },
        watch: {
            crops: {
                deep: true,
                handler: function (values) {
                    let self = this;
                    let stepRepeat = [];
                    _.forEach(values, function (items, index) {
                        if (JSON.stringify(items) !== JSON.stringify(self.cropsKey[index])) {
                            stepRepeat.push(items[2] + '_' + index);
                        }
                    });
                    this.stepsCrop = Array.from(new Set(stepRepeat));
                }
            },
            forms: {
                deep: true,
                handler: function (value) {
                    let self = this;
                    let checkStatus = [];
                    {
                        //原因 forms 為 所有農場集合體 所以透過 forEach 抓出
                        _.forEach(value, function (item, index) {
                            //item[] => 0 農場名稱 1 地址 2順序
                            if (item[0] === '') { //判斷item是否為新加入的 y=>將formKey加入create_id
                                self.formsKey.push('create');
                            } else if (JSON.stringify(item) !== JSON.stringify(self.formsCopyKey[index])) {//當 數值改變 確認是否改變時
                                //記錄更改的狀態名稱
                                self.stepsStatus.push(self.formsKey[item[2]] + '_' + item[2]);
                                //將相關的 crops 進行更改名稱
                                _.forEach(self.crops, function (cropItem) {
                                    if (cropItem[0] === self.formsCopyKey[index][0]) {
                                        cropItem[0] = item[0];
                                    }
                                });
                                //更新最高數值
                                self.formsCopyKey[index][0] = item[0];
                            }
                        });
                        _.forEach(Array.from(new Set(self.stepsStatus)), function (item) {
                            if (item) {
                                checkStatus.push(item);
                                self.steps.push(item);
                            }
                        });
                        this.steps = Array.from(new Set(checkStatus));
                    }
                    this.steps.sort();
                }
            }
        },
        data() {
            return {
                forms: [],
                formsKey: [],
                formsCopyKey: [],

                crops: [],
                cropsKey: [],

                //農夫名稱
                formerName: '',
                formerEmail: '',

                steps: [],
                stepsCrop: [],

                stepsCount: 0,
                stepsStatus: [],

                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        },
        created() {
            this.resetValue();
        }
    }
</script>

<style scoped>

</style>