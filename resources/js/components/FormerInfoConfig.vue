<template>
    <div class="modal fade" id="FormerInfoModal" tabindex="-1" role="dialog" aria-labelledby="FormerInfoModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">農夫農場資訊</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" :action="route" id="updateFormerInfo">
                    <input type="hidden" name="_token" :value="csrf">
                    <input type="hidden" name="temporary" v-model="repeat">
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-6">
                                    <label for="formerInfoName">名稱：</label>
                                    <input type="text" id="formerInfoName" v-model="formerName"
                                           name="updateFormerName"
                                           @change="searchUpdate('updateFormerName',formername,'Info')"/>
                                </div>
                                <div class="col-6">
                                    <label for="formerInfoEmail">信箱：</label>
                                    <input type="text" id="formerInfoEmail" v-model="formerEmail"
                                           name="updateFormerEmail"
                                           @change="searchUpdate('updateFormerEmail',formeremail,'Info')"/>
                                </div>
                                <!--                            農場列表-->
                                <div class="col-12 my-3">
                                    <div v-for="(thisFormName,index) in sortOutData[0]" class="row no-gutters my-2">
                                        <div class="col-2">
                                        <span v-show="index===0">
                                            農場
                                            <i class="fa fa-plus" aria-hidden="true"
                                               @click="addForm('update-FormName-'+sortOutData[0].length,'update-FormEmail-'+sortOutData[0].length)"></i>
                                        </span>
                                        </div>
                                        <div class="col-9 row no-gutters">
                                            <input type="text" class="col-6 w-100" v-model="thisFormName[0]"
                                                   placeholder="請輸入農場名稱" :name='"update-FormName-"+index'
                                                   :id='"update-FormName-"+index'
                                                   @change="searchUpdate('update-FormName-'+index,formName[0][index],'Form')"/>
                                            <input type="text" class="col-6 w-100" v-model="thisFormName[1]"
                                                   placeholder="請輸入農場地址" :name='"update-FormAddress-"+index'
                                                   @change="searchUpdate('update-FormAddress-'+index,thisFormName[0],'Form')"/>
                                        </div>
                                        <div class="col-1 flex-total-center">
                                            <i class="fa fa-minus text-danger" aria-hidden="true"
                                               @click="deleteForm(index)"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--                            農田列表-->
                                <div class="col-12">
                                    <div v-for="(Crop,index) in sortOutData[1]" class="row no-gutters my-2">
                                        <div class="col-2">
                                        <span v-show="index===0">
                                            農田
                                            <i class="fa fa-plus" aria-hidden="true" @click="addCrop()"></i>
                                        </span>
                                        </div>
                                        <div class="col-9">
                                            <div class="row no-gutters">
                                                <div class="col-6">
                                                    <select :name="'selectFormData'+index"
                                                            :id="'select-FormData-'+index"
                                                            class="w-100 h-100"
                                                            @change="reFormCrop(index,Crop.split('|')[2])"
                                                            v-model="(Crop.split('|'))[0]">
                                                        <option v-for="form in sortOutData[0]">
                                                            {{ form[0] }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" :id="'select-CropData-'+index" class="w-100"
                                                           @change="reFormCrop(index,Crop.split('|')[2])"
                                                           v-model="(Crop.split('|'))[1]"
                                                           placeholder="請輸入農作物" :name="'selectCropData'+index"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1 flex-total-center">
                                            <i class="fa fa-minus text-danger" aria-hidden="true"
                                               @click="deleteCrop(Crop.split('|')[2],index)"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="resetAny()">關閉
                        </button>
                        <button type="button" class="btn btn-primary" @click="lostCheck()">更新</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "FormerInfoConfig",
        props: {
            formername: String,
            formeremail: String,
            formcrop: Array,
            route: String,
        },
        methods: {
            addForm() {
                this.sortOutData[0].push(['', '']);
            },
            //id 為 DOM ID
            deleteForm(id) {
                let deleteValue = document.getElementById('update-FormName-' + id);
                if (window.confirm('確定刪除嗎？若確認的話會將相關農地“刪除”')) {
                    this.sortOutData[0].splice(id, 1);
                    //
                    this.stepToInter.push('delete_Form_' + deleteValue.value + "_");
                }
            },
            addCrop() {
                this.sortOutData[1].push('');
            },
            deleteCrop(id, fontId) {
                this.stepToInter.push('delete_Crop_' + id + "_");
                this.sortOutData[1].splice(fontId, 1);
            },
            reFormCrop(id, sqlId) {
                let formName = document.getElementById('select-FormData-' + id);
                let cropName = document.getElementById('select-CropData-' + id);

                this.$set(this.sortOutData[1], id, formName.value + '|' + cropName.value + '|' + sqlId);

                this.stepToInter.push('Crop_Form_' + id + '_' + sqlId);
            },
            cutFormCrop() {
                let self = this;
                let formCrop = [];
                let formStatus = '';
                _.forEach(this.formcrop, function (value, index) {
                    //value[0]農作物 value[1]農場地址
                    formCrop[index] = value[0].split('_');
                    self.cropName.push(formCrop[index]);
                    if (formStatus !== formCrop[index][0]) {
                        self.formName[0].push(formCrop[index][0]);
                        self.formName[1].push(value[1]);
                        formStatus = formCrop[index][0];
                    }
                });
                this.createFlash();
            },
            resetAny() {
                this.sortOutData = [[], []];
                this.createFlash();
            },
            createFlash() {
                let self = this;
                this.formerName = this.formername;
                this.formerEmail = this.formeremail;

                for (let i = 0; i < this.formName[0].length; i++) {
                    self.sortOutData[0].push([this.formName[0][i], this.formName[1][i]])
                }
                for (let i = 0; i < this.cropName.length; i++) {
                    self.sortOutData[1].push(this.cropName[i][0] + '|' + this.cropName[i][1] + "|" + this.formcrop[i][2]);
                }
            },
            //查詢更改部分 id 為更改數值的DOM_ID original原本要被更改的數值
            searchUpdate(id, original, way) {
                this.stepToInter.push(way + "_" + original + "_" + id);
            },

            lostCheck() {
                let updateFormerInfo = document.getElementById('updateFormerInfo');
                this.repeat = Array.from(new Set(this.stepToInter));
                updateFormerInfo.elements.temporary.value = this.repeat
                // console.log();
                // if (confirm('確定送出')) {
                updateFormerInfo.submit();
                // }
            }
        },
        data() {
            return {
                //重複者刪除 [0]名稱[1]地址
                formName: [[], []],
                //所有數值進行增加
                cropName: [],
                //記下前端步驟
                stepToInter: [],
                //最後結果送出
                repeat: [],
                //進行運算[0][0]formName [0][1]Address [1]cropName
                sortOutData: [[], [],],
                //農夫名稱
                formerName: '',
                formerEmail: '',
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        },
        created() {
            this.cutFormCrop();
        }
    }
</script>

<style scoped>

</style>