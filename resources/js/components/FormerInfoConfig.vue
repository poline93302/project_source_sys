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
                    <input type="hidden" name="temporaryForm" v-model="FarmNumbering">
                    <input type="hidden" name="temporaryCrop" v-model="CropNumbering">
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-6">
                                    <label for="formerInfoName">名稱：</label>
                                    <input type="text" id="formerInfoName" v-model="FarmerName"
                                           name="updateFormerName" class="w-98"
                                    />
                                </div>
                                <div class="col-6">
                                    <label for="formerInfoEmail">信箱：</label>
                                    <input type="text" id="formerInfoEmail" v-model="FarmerEmail"
                                           name="updateFormerEmail" class="w-98"/>
                                </div>
                                <div class="btn-group mt-3 col-8">
                                    <div class="btn border" @click="switchConnect = false"
                                         :class="'switch-type-'+!switchConnect">農場資訊
                                    </div>
                                    <div class="btn border" @click="switchConnect = true "
                                         :class="'switch-type-'+switchConnect">農田資訊
                                    </div>
                                </div>
                                <!--                            農場列表-->
                                <div class="col-12 mt-3 border rounded border-info" v-show="!switchConnect">
                                    <div class="flex-total-center row">
                                        <div class="col-12 row flex-total-center text-center bg-primary">
                                            <div class="col  text-light">農場名稱</div>
                                            <div class="col  text-light">農場地址</div>
                                            <div class="col-auto">
                                                <i class="fa fa-plus-circle text-light tool-remind" aria-hidden="true"
                                                   @click="addItems('farm')">
                                                </i>
                                            </div>
                                        </div>
                                        <div v-for="(item,index) in Farms" class="col-12 row  border border-bottom-0">
                                            <input type="text" class="col border-0 text-center" placeholder="請輸入農場名稱"
                                                   :name='"update-FormName-"+index' :id='"update-FormName-"+index'
                                                   v-model="item['farm']"
                                            />
                                            <input type="text" class="col border-0 text-center" placeholder="請輸入農場地址"
                                                   :name='"update-FormAddress-"+index' :id='"update-FormAddress-"+index'
                                                   v-model="item['address']"
                                            />
                                            <div class="col-auto flex-total-center">
                                                <i class="fa fa-minus text-danger" aria-hidden="true"
                                                   @click="deleteItems('farm',index)"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!--                            農田列表-->
                                <div class="col-12 mt-3 border rounded border-info" v-show="switchConnect">
                                    <div class="flex-total-center row">
                                        <div class="col-12 row flex-total-center text-center bg-primary">
                                            <div class="col  text-light">農場</div>
                                            <div class="col  text-light">農田</div>
                                            <div class="col-auto">
                                                <i class="fa fa-plus-circle text-light tool-remind" aria-hidden="true"
                                                   @click="addItems('crop')"></i>
                                            </div>
                                        </div>
                                        <div v-for="(Crop,index) in Crops" class="col-12 row border border-bottom-0">
                                            <input type="hidden" v-model="Crop['farm']" :name="'selectFormData'+index"/>
                                            <div v-if="Crop['create']" class="col text-center">
                                                {{Crop['farm']}}
                                            </div>
                                            <select :name="'selectFormData'+index"
                                                    :id="'select-FormData-'+index"
                                                    v-model="Crop['farm']"
                                                    class="col text-center"
                                                    v-else
                                            >
                                                <option v-show="farm['farm']!==''" v-for="farm in Farms">
                                                    {{ farm['farm'] }}
                                                </option>
                                            </select>

                                            <input type="text" v-model="Crop['crop']"
                                                   class="col border-0 text-center"
                                                   :id="'select-CropData-'+index" :name="'selectCropData'+index"
                                                   placeholder="請輸入農作物"
                                            />
                                            <div class="col-auto flex-total-center">
                                                <i class="fa fa-minus text-danger" aria-hidden="true"
                                                   @click="deleteItems('crop',index)"></i>
                                            </div>
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
                if (grad === 'farm') {
                    let farmLen = this.Farms.length;
                    this.editFarmControl = false;
                    if ((farmLen === 0) || ((this.Farms[farmLen - 1]['farm'] !== ''))) {
                        this.originalFarmID++;
                        this.Farms.push({'id': this.originalFarmID, 'address': '', 'farm': ''});
                        this.FarmsKey.push({'id': this.originalFarmID, 'address': '', 'farm': ''});
                    }
                    this.editFarmControl = true;
                } else {
                    let cropLen = this.Crops.length;
                    this.editCropControl = false;
                    if ((cropLen === 0) || (this.Crops[cropLen - 1]['farm'] !== '')) {
                        this.originalCropID++;
                        this.Crops.push({
                            'id': this.originalCropID,
                            'crop': '',
                            'farm': '',
                            'status': '444',
                            'create': false
                        });
                        this.CropsKey.push({
                            'id': this.originalCropID,
                            'crop': '',
                            'farm': '',
                            'status': '444',
                            'create': false
                        });
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
                        //刪除步驟
                        if (this.stepsFarm.indexOf(this.Farms[index]['id']) !== -1)
                            this.stepsFarm.splice(this.stepsFarm.indexOf(this.Farms[index]['id']), 1);
                        //對要進行刪除的index 存
                        _.forEach(this.Crops, function (item, cropIndex) {
                            if (item['farm'] === self.Farms[index]['farm']) {
                                if (statueCropId >= item['id'])
                                    self.stepsDelete.push('Crop_' + item['id']);
                                deleteCount.push(cropIndex);
                            }
                        });
                        console.log(deleteCount);
                        deleteCount.sort();
                        deleteCount.reverse();
                        //對Crop 進行刪除
                        _.forEach(deleteCount, function (number) {
                            self.Crops.splice(number, 1);
                            self.CropsKey.splice(number, 1);
                        });

                        this.Farms.splice(index, 1);
                        this.FarmsKey.splice(index, 1);
                    }
                } else {
                    console.log(statueCropId);
                    console.log(self.Crops[index]['id']);
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

                this.originalFarmID = this.farms.length !== 0 ? this.farms[this.farms.length - 1]['id'] : 0;
                this.originalCropID = this.crops.length !== 0 ? this.crops[this.crops.length - 1]['id'] : 0;

                this.Farms = _.cloneDeep(self.farms);
                this.FarmsKey = _.cloneDeep(self.farms);
                this.Crops = _.cloneDeep(self.crops);
                this.CropsKey = _.cloneDeep(self.crops);
                //     self.crops.sort(function (a, b) {
                //         return a.farm > b.farm ? 1 : -1
                //     }));
                // this.CropsKey = _.cloneDeep(
                //     self.crops.sort(function (a, b) {
                //         return a.farm > b.farm ? 1 : -1
                //     }));

                this.stepsFarm = [];
                this.stepsCrop = [];
                this.stepsDelete = [];
            },

            //送出確認
            lostCheck() {
                let formUpdate = document.getElementById('updateFormerInfo');
                //針對所有crops form 查看是否有不合法的地方 在
                formUpdate.submit();
                console.log('submit');
            },
        },
        watch: {
            Crops: {
                deep: true,
                handler:
                    function (values) {
                        let self = this;
                        let stepsCache = [];
                        if (this.editCropControl) {
                            _.forEach(values, function (items, index) {
                                if (JSON.stringify(items) !== JSON.stringify(self.CropsKey[index])) {
                                    console.log(JSON.stringify(items), JSON.stringify(self.CropsKey[index]));
                                    stepsCache.push(items['id']);
                                }
                            });
                            self.stepsCrop = Array.from(new Set(stepsCache));
                        }
                    }

                ,
            }
            ,
            //當forms進行更改時
            Farms: {
                deep: true,
                immediate:
                    true,
                handler:

                    function (value) {
                        let self = this;
                        if (this.editFarmControl) {
                            //原因 forms 為 所有農場集合體 所以透過 forEach 抓出
                            _.forEach(value, function (item, index) {
                                if (JSON.stringify(item) !== JSON.stringify(self.FarmsKey[index])) {//當 數值改變 確認是否改變時
                                    //推上更改ID
                                    if (self.stepsFarm.indexOf(item['id']) !== -1) self.stepsFarm.splice(self.stepsFarm.indexOf(item['id']), 1);
                                    self.stepsFarm.push(item['id']);
                                    //將相關的 crops 進行更改名稱
                                    _.forEach(self.Crops, function (cropItem) {
                                        if (cropItem['farm'] === self.FarmsKey[index]['farm']) {
                                            cropItem['farm'] = item['farm'];
                                        }
                                    });
                                    //更新最高數值
                                    self.FarmsKey[index]['farm'] = item['farm'];
                                    self.FarmsKey[index]['address'] = item['address'];
                                }
                            });
                        }
                    }

                ,
            }
        }
        ,
        computed: {
            FarmNumbering() {
                let self = this;
                //為stepsFarm stepsCrop  加上 ＤＯＭ的index
                let keyIndex = 0;
                this.stepsFarmStatus = this.stepsFarm.map(function (item) {
                    _.forEach(self.Farms, function (itemKey, indexKey) {
                        if (itemKey['id'] === item) {
                            keyIndex = indexKey;
                        }
                    });
                    return item + '_' + keyIndex;

                });
                return this.stepsFarmStatus;
            }
            ,
            CropNumbering() {
                let self = this;
                //為stepsFarm stepsCrop  加上 ＤＯＭ的index
                let keyIndex = 0;
                this.stepsCropStatus = this.stepsCrop.map(function (item) {
                    _.forEach(self.Crops, function (itemKey, indexKey) {
                        if (itemKey['id'] === item) {
                            keyIndex = indexKey;
                        }
                    });
                    return item + '_' + keyIndex;
                });
                return this.stepsCropStatus;
            }
        }
        ,

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
                stepsFarmStatus: [],
                stepsCrop: [],
                stepsCropStatus: [],
                stepsDelete: [],
                //
                editFarmControl: true,
                editCropControl: true,
                //
                originalFarmID: 0,
                originalCropID: 0,
                formCheckRun: false,

                switchConnect: false,

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
    .switch-type-true {
        background-color: #1f6fb2;
        color: white;
    }

    .switch-type-false {
        background-color: white;
    }
</style>