<template>
    <div>
        <div v-if="currentPath.query.template == undefined">
            <div v-if="currentPath.query.correctAnswer == undefined">
                <div>
                    <!-- <div class="text-center" @click="uploadModal = true">
                        从文件中批量导入习题
                    </div> -->
                    <router-link :to="{path:this.currentPath.path,query:{applicationType:'习题',questionType:'习题',template:'Add'}}">
                        <div class="category-title template gray-font">
                            <Icon type="ios-list-box-outline" />
                            <span>草稿 {{draftCnt}}</span>
                            <span class="right">
                                <Icon type="ios-arrow-forward" size="22" />
                            </span>
                        </div>
                    </router-link>
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            习题名称
                        </div>
                        <div class="vx-item-right w-50">
                            <Input v-model="addData.title" class="customInput rightToLeft" placeholder="必填"/>
                        </div>
                    </div>
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            批量设置难度
                        </div>
                        <div class="vx-item-right">
                            <i-switch true-color="#13ce66" v-model="difficultlySetting" />
                        </div>
                    </div>
                    <div class="vx-item is-click" v-if="difficultlySetting == true" @click="toggleSelectDifficulty">
                        <Dropdown style="margin-left: 20px" placement="bottom-start" :visible="isVisibleSelectDifficulty" trigger="custom" @on-click="batchType($event)">
                            <a href="javascript:void(0)">
                                <div>{{addData.batchLevel}}<Icon type="ios-arrow-down" /></div>
                            </a>
                            <DropdownMenu slot="list">
                                <DropdownItem name="不限难度">不限难度</DropdownItem>
                                <DropdownItem name="简单">简单</DropdownItem>
                                <DropdownItem name="一般">一般</DropdownItem>
                                <DropdownItem name="困难">困难</DropdownItem>
                            </DropdownMenu>
                        </Dropdown>
                    </div>
                </div>   
                <div v-for="(addDataProp,i) in addData.addDataList" :key="i">
                    <childHomework 
                        :difficultlySetting="difficultlySetting" 
                        :addDataProp="addDataProp"
                        :index="i"
                        @removeQuestionData="removeQuestionData"
                    ></childHomework>
                </div>
                <div class="es-model-operate d-block">
                    <Button class="ml-2 w-sm-100" type="primary" @click="addTitle">添加题目</Button>
                    <!-- <Button class="ml-2 w-sm-100" type="default" @click="viewCategory">查看列表</Button> -->
                    <Button class="ml-2 w-sm-100" type="primary" :disabled="isAdding" :loading="isAdding" @click="submit">直接提交</Button>
                    <Button class="ml-2 w-sm-100" type="default" @click="saveDraft" :disabled="isDrafting" :loading="isDrafting">存为草稿</Button>
                </div>
                <Modal
                    v-model="uploadModal"
                    class="uploadModal"
                    title="导入习题"
                    :styles="{top:'140px',left:'64px'}"
                    >
                        <Upload
                            multiple
                            type="drag"
                            :on-success="handleSuccess"

                            action="/api/fileUpload/other">
                            <div style="padding: 20px 0">
                                <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                <p>将文件拖到此处，或 <span class="text-color">点击上传</span></p>
                            </div>
                        </Upload>
                        <div class="es-item-tooltip px-3">
                            <div>导入说明</div> 
                            <div>1、必须按正确的格式将数据填入模板 <a href="/download/doc/praxisTemplate.doc" class="text-color" download>（ 下载模板 ）</a></div>
                            <div>2、文件格式必须为xls、xlsx、doc。</div>
                        </div>
                </Modal>
            </div>
            <div v-else>
                <div v-if="currentPath.query.correctAnswer == '单选题'">
                    <RadioGroup v-model="routerData.answerData">
                        <div class="vx-item is-click" v-for="count in routerData.questionDataArr.length" :key="count">
                            <div class="vx-item-left w-90">
                                <Radio class="w-100 mb-0" :label="alphabet[count-1]">{{alphabet[count-1]}}</Radio>
                            </div>
                            <div class="vx-item-right">
                                <Icon type="ios-arrow-forward" />
                            </div>
                        </div>
                    </RadioGroup>
                    <div class="es-model-operate">
                        <Button type="primary" @click="selAnswer">提交</Button>
                    </div>
                </div>
                <div v-else-if="currentPath.query.correctAnswer == '多选题'">
                    <CheckboxGroup v-model="routerData.answerDataArr">
                        <div class="vx-item is-click" v-for="count in routerData.questionDataArr.length" :key="count">
                            <div class="vx-item-left w-90">
                                <Checkbox class="w-100" :label="alphabet[count-1]">{{alphabet[count-1]}}</Checkbox>
                            </div>
                            <div class="vx-item-right">
                                <Icon type="ios-arrow-forward" />
                            </div>
                        </div>
                    </CheckboxGroup>
                    <div class="es-model-operate">
                        <Button type="primary" @click="selAnswer">提交</Button>
                    </div>
                </div>
                <div v-else-if="currentPath.query.correctAnswer == '判断题'">
                    <RadioGroup v-model="routerData.answerData">
                        <div class="vx-item is-click">
                            <div class="vx-item-left">
                                <Radio label="A">正确</Radio>
                            </div>
                            <div class="vx-item-right">
                                <Icon type="ios-arrow-forward" />
                            </div>
                        </div>
                        <div class="vx-item is-click">
                            <div class="vx-item-left">
                                <Radio label="B">错误</Radio>
                            </div>
                            <div class="vx-item-right">
                                <Icon type="ios-arrow-forward" />
                            </div>
                        </div>
                    </RadioGroup>
                    <div class="es-model-operate">
                        <Button type="primary" @click="selAnswer">提交</Button>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="currentPath.query.template == 'Add'">
            <div class="apps-template px-1">
                <div   class="row m-0 p-0">
                    <div v-if="templateDataList.length == 0" class="text-center w-100 category-title">
                        没有数据
                    </div>
                    <div v-else class="template-item-cu col-6 col-sm-6 col-md-6 col-lg-4 col-xl-3 p-0 m-0" v-for="(template ,i) in templateDataList" :key="i">
                        <router-link :to="{path:`${currentPath.path}?applicationType=习题&questionType=习题`,query:{myprop:template}}" class="">
                            <div class="template-item-container m-2 position-relative">
                                <img :src="template.imgUrl" alt="" class="picture w-100" v-if="template.imgUrl">
                                <img src="/img/icon/33.jpg" alt="" class="picture w-100" v-else>
                                <p class="text" v-if="template.templateName">{{template.templateName}}</p>
                                <p class="text" v-else>draft({{i+1}})</p>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {VueEditor} from "vue2-editor"
import childHomework from './homework/childHomework'
export default {
    components:{
        VueEditor,
        childHomework
    },
    data(){
        return{
            difficultlySetting:false,
            addData:{
                viewList:[],
                postShow:[],
                addDataList:[],
                title:'',
                batchLevel:'难度',
            },
            templateDataList:[],
            uploadModal:false,
            correctAnswer:null,
            index:0,
            isAdding:false,
            isDrafting:false,
            routerData:null,
            routerIndex:null,
            parentId:null,
            isVisibleSelectDifficulty: false,
            draftCnt:0,
        }
    },
    mounted(){

    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    
    created(){
        axios.get('/api/template',{params:{
            contentType:20
        }}).then(template=>{
            if(template.status == 200){
                this.templateDataList = template.data;
                for( let i =0; i < this.templateDataList.length; i++){
                    this.draftCnt += 1;
                }
            }
        })
        this.addTitle()
    },
    watch:{
        currentPath:{
            handler(val){
                if(val.query.routerData){
                    this.routerData = val.query.routerData
                    this.routerIndex = val.query.index
                    this.parentId = val.query.parentId
                }
                if(val.query.myprop){
                    this.addData = JSON.parse(val.query.myprop.content)
                }
            },
            deep:true
        }
    },
    methods:{
        toggleSelectDifficulty(){
            this.isVisibleSelectDifficulty = !this.isVisibleSelectDifficulty;
        },

        addTemplate(){

        },
        batchType($event){
            this.addData.batchLevel = $event
        },
        addTitle(){
            let element = {
                selLevel:'难度',
                contentData:'',
                questionDataArr:[],
                showAnalysis:false,
                analysisData:'',
                selQuestion:'解答题',
                answerData:'A',
                answerDataArr:['A'],
            };

            this.addData.addDataList.push(element)

        },
        removeQuestionData(item){
            this.addData.addDataList.splice(item.index,1)
        },
        selAnswer(){
            if(this.currentPath.query.parentId == undefined){
                this.addData.addDataList[this.routerIndex] = this.routerData
            }else{
                this.addData.addDataList[this.parentId].questionDataArr[this.routerIndex] = this.routerData
            }
            this.$router.push({path:this.currentPath.path,query:{applicationType:this.currentPath.query.applicationType,questionType:this.currentPath.query.questionType}})
        },
        async submit(){
            // return
            // let element = {}
            for(let i=0;i < this.addData.addDataList.length;i++){
                // debugger
                if(this.addData.addDataList[i].selQuestion == '连线题'){
                    for(let j=0;j<this.addData.addDataList[i].questionDataArr.length;j++){
                        // debugger
                        this.addData.addDataList[i].questionDataArr[j].first.key = j;
                        this.addData.addDataList[i].questionDataArr[j].second.key = j;
                    }
                    for(let k=0;k<this.addData.addDataList[i].questionDataArr.length;k++){
                        // debugger
                        let indexA = Math.floor(Math.random() * this.addData.addDataList[i].questionDataArr.length);
                        let indexB = Math.floor(Math.random() * this.addData.addDataList[i].questionDataArr.length);

                        if( k % 2 ==0){
                            this.swapFirst(this.addData.addDataList[i].questionDataArr,indexA,indexB)
                        }else{
                            this.swapSecond(this.addData.addDataList[i].questionDataArr,indexA,indexB)
                        }
                    }
                }
                if(this.addData.addDataList[i].selQuestion == '文字排序题'){
                    for(let j=0;j<this.addData.addDataList[i].questionDataArr.length;j++){
                        this.addData.addDataList[i].questionDataArr[j].contentData.key=j
                    }
                    for(let j=0;j<this.addData.addDataList[i].questionDataArr.length;j++){
                        let indexA = Math.floor(Math.random() * this.addData.addDataList[i].questionDataArr.length);
                        let indexB = Math.floor(Math.random() * this.addData.addDataList[i].questionDataArr.length);
                        this.swap(this.addData.addDataList[i].questionDataArr,indexA,indexB)
                    }
                }
            }
            if(this.addData.batchLevel == '难度'){
                for(let i=0;i<this.addData.addDataList.length;i++){
                    if(this.addData.addDataList[i].selLevel == '难度'){
                        this.error('请设置题目难度')
                        return;
                    }
                }
            }
            let foamingPosition = ''
            if(this.currentPath.params.schoolName){
                foamingPosition = this.currentPath.params.schoolName
            }
            if(this.currentPath.params.className == undefined){
                this.addData.viewList.push(0)
                this.addData.postShow.push(0)
                this.addData.postShow.push(1)
            }else{
                this.addData.viewList.push(this.currentPath.params.className)
                this.addData.postShow.push(this.currentPath.params.className)
                this.addData.postShow.push(2)
            }
            this.isAdding = true
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:20,foamingPosition:foamingPosition})
            if(res.status == 201){
                this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                this.swr()
            }
            this.isAdding = false
        },
        swapFirst(list,indexA,indexB){
            let tempA = list[indexA].first
            list[indexA].first = list[indexB].first
            list[indexB].first = tempA
        },
        swapSecond(list,indexA,indexB){
            let tempB = list[indexB].second
            list[indexB].second = list[indexA].second
            list[indexA].second = tempB
        },
        swap(list,indexA,indexB){
            let temp = list[indexB].contentData;
            list[indexB].contentData = list[indexA].contentData;
            list[indexA].contentData =temp
        },
        async saveDraft(){
            this.isDrafting = true
            let userId = this.$store.state.user.id

            const res = await this.callApi('post','/api/template',{content:this.addData,userId:userId,contentType:20,templateType:2})
            this.isDrafting = false
            if(res.status == 201){
                this.success('操作成功')
                this.templateDataList.push(res.data)
            }
        },
        viewCategory(){
        },
        handleSuccess(res){
        }, 
        
    }
}
</script>