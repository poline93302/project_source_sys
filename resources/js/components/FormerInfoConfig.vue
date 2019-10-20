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
                <form method="get" :action="route" id="updateFormerInfo">
                    <input type="hidden" name="_token" v-model="csrf">
                    <input type="hidden" name="temporaryDelete" v-model="stepsDelete">
                    <input type="hidden" name="temporaryForm" v-model="stepsFarm">
                    <input type="hidden" name="temporaryCrop" v-model="stepsCrop">
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-6">
                                    <label for="formerInfoName">名稱：</label>
                                    <input type="text" id="formerInfoName" v-model="FarmerName"
                                           name="updateFormerName"
                                    />
                                </div>
                                <div class="col-6">
                                    <label for="formerInfoEmail">信箱：</label>
                                    <input type="text" id="formerInfoEmail" v-model="FarmerEmail"
                                           name="updateFormerEmail"/>
                                </div>
                                <!--                            農場列表-->
                                <div class="col-12 my-3">
                                    <div v-for="(item,index) in Farms" class="row no-gutters my-2">
                                        <div class="col-2">
                                            <span v-show="index===0">
                                                農場
                                                <i class="fa fa-plus" aria-hidden="true" @click="addItems('farm')"></i>
                                            </span>
                                        </div>
                                        <div class="col-9 row no-gutters">
                                            <input type="text" class="col-6 w-100" placeholder="請輸入農場名稱"
                                                   :name='"update-FormName-"+index' :id='"update-FormName-"+index'
                                                   v-model="item['farm']"
                                            />
                                            <input type="text" class="col-6 w-100" placeholder="請輸入農場地址"
                                                   :name='"update-FormAddress-"+index' :id='"update-FormAddress-"+index'
                                                   v-model="item['address']"
                                            />
                                        </div>
                                        <div class="col-1 flex-total-center">
                                            <i class="fa fa-minus text-danger" aria-hidden="true"
                                               @click="deleteItems('farm',index)"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--                            農田列表-->
                                <div class="col-12">
                                    <div v-for="(Crop,index) in Crops" class="row no-gutters my-2">
                                        <div class="col-2">
                                                <span v-show="index===0">
                                                    農田
                                                    <i class="fa fa-plus" aria-hidden="true"
                                                       @click="addItems('crop')"></i>
                                                </span>
                                        </div>
                                        <div class="col-9">
                                            <div class="row no-gutters">
                                                <div class="col-6">
                                                    <select :name="'selectFormData'+index"
                                                            :id="'select-FormData-'+index"
                                                            class="w-100 h-100"
                                                            v-model="Crop['farm']"
                                                    >
                                                        <option v-show="farm['farm']!==''" v-for="farm in Farms">
                                                            {{ farm['farm'] }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" v-model="Crop['crop']"
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
            farms: Array,
            crops: Array,
            route: String,
        },
        methods: {
            //新增農場/農田 ps.一次新增一層
            addItems(grad) {
                let farmLen = this.Farms.length;
                let cropLen = this.Crops.length;

                if (grad === 'farm') {
                    this.editFarmControl = false;
                    if (this.Farms[farmLen - 1]['farm'] !== '') {
                        this.originalFarmCount++;
                        this.Farms.push({'id': this.originalFarmCount, 'address': '', 'farm': ''});
                        this.FarmsKey.push({'id': this.originalFarmCount, 'address': '', 'farm': ''});
                    }
                    this.editFarmControl = true;
                } else {
                    this.editCropControl = false;
                    if (this.Crops[cropLen - 1]['farm'] !== '') {
                        this.originalCropCount++;
                        this.Crops.push({'id': this.originalCropCount, 'crop': '', 'farm': ''});
                        this.CropsKey.push({'id': this.originalCropCount, 'crop': '', 'farm': ''});
                    }
                    this.editCropControl = true;
                }
            },
            //刪除農場/農田
            deleteItems(grad, index) {
                let self = this;
                let deleteCount = [];
                // 查看Farm資料庫中的id
                let statueFarmId = self.farms[self.farms.length - 1]['id'];
                // 查看Crop資料庫中的id
                let statueCropId = self.crops[self.crops.length - 1]['id'];

                if (grad === 'farm') {
                    if (confirm('若刪除農場，相關的農田也會一並刪除，確認是否刪除？')) {
                        //新增不進行 紀錄
                        if (statueFarmId >= self.Farms[index]['id']) {
                            this.stepsDelete.push('Farm_' + this.Farms[index]['id']);
                        }
                        //對要進行刪除的index 存
                        _.forEach(this.Crops, function (item, cropIndex) {
                            if (item['farm'] === self.Farms[index]['farm']) {
                                deleteCount.push(cropIndex);
                            }
                        });
                        deleteCount.sort();
                        deleteCount.reverse();
                        //對Crop 進行刪除
                        _.forEach(deleteCount, function (number) {
                            if (statueCropId >= self.Crops[index]['id'])
                                self.stepsDelete.push('Crop_' + self.Crops[index]['id']);
                            self.Crops.splice(number, 1);
                        });

                        this.CropsKey = _.cloneDeep(self.Crops);

                        this.Farms.splice(index, 1);
                        this.FarmsKey.splice(index, 1);
                    }
                } else {
                    if (statueCropId >= self.Crops[index]['id'])
                        this.stepsDelete.push('Crop_' + this.Crops[index]['id']);
                    this.Crops.splice(index, 1);
                    this.CropsKey.splice(index, 1);
                }
            },
            //重設所有數字
            resetValue() {
                let self = this;

                this.FarmerName = this.formername;
                this.FarmerEmail = this.formeremail;

                this.Farms = _.cloneDeep(self.farms);
                this.Crops = _.cloneDeep(self.crops);
                this.FarmsKey = _.cloneDeep(self.farms);
                this.CropsKey = _.cloneDeep(self.crops);

                this.originalFarmCount = this.farms[this.farms.length - 1]['id'];
                this.originalCropCount = this.crops[this.crops.length - 1]['id'];

                this.stepsFarm = [];
                this.stepsCrop = [];
                this.stepsDelete = [];
            },

            //送出確認
            lostCheck() {
                let formUpdate = document.getElementById('updateFormerInfo');
                let self = this;
                //針對所有crops form 查看是否有不合法的地方 在
                //為stepsFarm stepsCrop  加上 ＤＯＭ的index
                let keyIndex = 0;
                //添加 DOM index
                if (!this.formCheckRun) {

                    this.stepsFarm = this.stepsFarm.map(function (item) {
                        _.forEach(self.Farms, function (itemKey, indexKey) {
                            if (itemKey['id'] === item) {
                                keyIndex = indexKey;
                            }
                        });
                        return item + '_' + keyIndex;

                    });
                    this.stepsCrop = this.stepsCrop.map(function (item) {
                        _.forEach(self.Crops, function (itemKey, indexKey) {
                            if (itemKey['id'] === item) {
                                keyIndex = indexKey;
                            }
                        });
                        return item + '_' + keyIndex;
                    });
                    confirm('請再點選一次以確認更改') ? this.formCheckRun = true : this.formCheckRun = false;

                } else {
                    formUpdate.submit();
                }

                console.log('submit');
            },
        },
        watch: {
            Crops: {
                deep: true,
                handler: function (values) {
                    let self = this;
                    let stepsCache = [];
                    if (this.editCropControl) {
                        _.forEach(values, function (items, index) {
                            if (JSON.stringify(items) !== JSON.stringify(self.CropsKey[index])) {
                                stepsCache.push(items['id']);
                            }
                        });
                        self.stepsCrop = Array.from(new Set(stepsCache));
                    }
                },
            },
            //當forms進行更改時
            Farms: {
                deep: true,
                immediate: true,
                handler: function (value) {
                    let self = this;
                    let stepsCache = [];
                    if (this.editFarmControl) {
                        //原因 forms 為 所有農場集合體 所以透過 forEach 抓出
                        _.forEach(value, function (item, index) {
                            if (JSON.stringify(item) !== JSON.stringify(self.FarmsKey[index])) {//當 數值改變 確認是否改變時
                                //推上更改ID
                                stepsCache.push(item['id']);
                                //將相關的 crops 進行更改名稱
                                _.forEach(self.Crops, function (cropItem) {
                                    if (cropItem['farm'] === self.FarmsKey[index]['farm']) {
                                        cropItem['farm'] = item['farm'];
                                    }
                                });
                                //更新最高數值
                                self.FarmsKey[index]['farm'] = item['farm'];
                            }
                        });
                        self.stepsFarm = Array.from(new Set(stepsCache));
                    }
                },
            }
        },

        data() {
            return {
                Farms: [],
                FarmsKey: [],
                //
                Crops: [],
                CropsKey: [],
                //農夫名稱
                FarmerName: '',
                FarmerEmail: '',
                //
                stepsFarm: [],
                stepsCrop: [],
                stepsDelete: [],
                //
                editFarmControl: true,
                editCropControl: true,
                //
                originalFarmCount: 0,
                originalCropCount: 0,
                formCheckRun: false,


                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        }
        ,
        created() {
            this.resetValue();
        }
    }
</script>

<style scoped>

</style>