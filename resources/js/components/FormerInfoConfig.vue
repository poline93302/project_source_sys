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
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <label for="formerInfoName">名稱：</label>
                                <input type="text" id="formerInfoName" :value="formername"/>
                            </div>
                            <!--                            農場列表-->
                            <div class="col-12 my-3">
                                <div v-for="(Name,index) in formName" class="row no-gutters my-2">
                                    <div class="col-2">
                                        <span v-show="index===0">
                                            農場
                                            <i class="fa fa-plus" aria-hidden="true" @click="addForm()"></i>
                                        </span>
                                    </div>
                                    <div class="col-9">
                                        <input type="text" class="w-100" v-model="formName[index]"/>
                                    </div>
                                    <div class="col-1 flex-total-center">
                                        <i class="fa fa-minus text-danger" aria-hidden="true"
                                           @click="deleteForm(index)"></i>
                                    </div>
                                </div>
                            </div>
                            <!--                            農田列表-->
                            <div class="col-12">
                                <div v-for="(Crop,index) in cropName" class="row no-gutters my-2">
                                    <div class="col-2">
                                        <span v-show="index===0">
                                            農田
                                            <i class="fa fa-plus" aria-hidden="true" @click="addCrop()"></i>
                                        </span>
                                    </div>
                                    <div class="col-9">
                                        <div class="row no-gutters">
                                            <div class="col-4">
                                                <select name="" id="" class="w-100 h-100" v-model="Crop[0]">
                                                    <option v-for="form in formName">
                                                        {{ form }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <input type="text" class="w-100" v-model="Crop[1]"/>
                                            </div>
                                            <div class="col-4">
                                                <input type="text" class="w-100" v-model="formaddress[index]"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-1 flex-total-center">
                                        <i class="fa fa-minus text-danger" aria-hidden="true"
                                           @click="deleteCrop(index)"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="resetAny()">關閉</button>
                    <button type="button" class="btn btn-primary">更新</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "FormerInfoConfig",
        props: {
            formername: String,
            formcrop: Array,
            formaddress: Array,
        },
        methods: {
            addForm() {
                this.formName.push('');
            },
            deleteForm(id) {
                this.formName.splice(id, 1);
            },
            addCrop() {
                this.cropName.push([], []);
            },
            deleteCrop(id) {
                this.cropName.splice(id, 1);
            },
            cutFormCrop() {
                let self = this;
                let form = [];
                let formStatus = '';
                _.forEach(this.formcrop, function (value, index) {
                    form[index] = value.split('_');
                    self.cropName.push(form[index]);
                    if (formStatus !== form[index][0]) {
                        self.formName.push(form[index][0]);
                        formStatus = form[index][0];
                    }
                });
            },
            resetAny() {

            }
        },
        data() {
            return {
                //重複者刪除
                formName: [],
                //所有數值進行增加
                cropName: [],
            }
        },
        created() {
            this.cutFormCrop();
        }
    }
</script>

<style scoped>

</style>