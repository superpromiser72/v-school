<template>
    <div>
        <div v-if="currentPath.query.addQuestion == undefined">
            <textarea v-model="smsData.text" class="text-content pl-4 pr-3 pt-2 border-right-0 border-top-0 border-left-0 border-bottom gray-font" style="height:250px" cols="30" rows="10" placeholder="输入内容" ></textarea>
            <div class="image-item row m-0 p-0 px-4" v-if="smsData.imgUrl">
                <div class="image-block">
                    <div class="image-upload-list" v-for="(imgUrl,i) in smsData.imgUrl" :key="i">
                        <img :src="imgUrl" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteFile('image',imgUrl)"></Icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="file-item row col-12 px-4" v-if="smsData.otherUrl.length">
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 shadow-none p-0 pr-3 d-flex mt-2" v-for="(otherUrl,j) in smsData.otherUrl" :key="j">
                    <div class="image-upload-list float-left file-gravatar-icon">
                        <img src="/img/icon/icon_rar@2x.png" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteFile('other',otherUrl)"></Icon>
                        </div>
                    </div>
                    <div class="title pt-2 gray-font bg-light-gray w-100">
                        <div class="text-break">{{otherUrl.fileOriName}}</div>
                        <div class="">{{otherUrl.fileSize}}</div>
                    </div>
                </div>
            </div>
            <div class="file-item row col-12 px-4" v-if="smsData.videoUrl.length">
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 shadow-none p-0 pr-3 d-flex mt-2" v-for="(videoUrl,j) in smsData.videoUrl" :key="j">
                    <div class="image-upload-list float-left file-gravatar-icon">
                        <img src="/img/icon/icon_mp4@2x.png" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteFile('video',videoUrl)"></Icon>
                        </div>
                    </div>
                    <div class="title pt-2 gray-font bg-light-gray w-100">
                        <div class="text-break">{{videoUrl.fileOriName}}</div>
                        <div class="">{{videoUrl.fileSize}}</div>
                    </div>
                </div>
            </div>
            <div class="ke-custom-toolbar pl-0">
                <div class="vx-item">
                    <div class="emoji-area-popup sms-emoji" id="emoji">
                        <Picker v-if="emoStatus" set="emojione" @select="onInput" title="选择你的表情符号..." />
                    </div> 
                    <div class="vx-item-left">
                        <Upload
                            ref="imageUploads"
                            :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                            :on-success="imageSuccess"
                            :on-error="handleError"
                            :format="['jpg','gif','png']"
                            :max-size="10240"
                            :show-upload-list="false"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            action="/api/fileUpload/image">
                                <img src="/img/icon/photo.png" alt="" class="uploadicon">
                        </Upload>
                        <img src="/img/icon/emoji.png" alt="" class="uploadicon" @click="toggleEmo">
                        <Upload
                            ref="otherUploads"
                            :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                            :on-success="otherSuccess"
                            :on-error="handleError"
                            :format="['doc','docx','zip','pdf','xls','xlsx','rp','mp3','rp','ppt','pptx','pptm','apk','rar']"
                            :max-size="524288"
                            :show-upload-list="false"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            action="/api/fileUpload/other">
                                <img src="/img/icon/file.png" alt="" class="uploadicon">
                        </Upload>
                        <Upload
                            ref="videoUploads"
                            :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                            :on-success="videoSuccess"
                            :on-error="handleError"
                            :format="['mp4']"
                            :max-size="524288"
                            :show-upload-list="false"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            action="/api/fileUpload/video">
                                <img src="/img/icon/video.png" alt="" class="uploadicon">
                        </Upload>
                        <router-link :to="`${currentPath.path}?applicationType=短信&questionType=短信&addQuestion=contact`">
                            <img src="/img/icon/at.png" alt="" class="uploadicon">
                        </router-link>
                        <!-- <img src="/img/icon/topic.png" alt="" class="uploadicon"> -->
                    </div>
                </div>
            </div>
            
            <div class="es-model-operate">
                <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
                <!-- <Button type="default" @click="draft" :disabled="isDrafting" :loading="isDrafting">存草稿</Button> -->
            </div>
            <!-- <div class="category-title"></div> -->
        </div>
        <div v-else-if="currentPath.query.addQuestion == 'contact'">
            <contactComponent @selectedUser="selUser"></contactComponent>
        </div>
    </div>
</template>

<script>
import { Picker } from 'emoji-mart-vue'
import contactComponent from './contactComponent'
export default {
    components:{
        Picker,
        contactComponent
    },
    data(){
        return{
            smsData:{
                text:'',
                imgUrl:[],
                otherUrl:[
                ],
                videoUrl:[
                ]
            },
            token:'',
            input: '',
            search: '',
            emoStatus:false,
            isLoading:false,
            isDrafting:false,
        }
    },
    created(){
        this.token = window.Laravel.csrfToken;
    },
    mounted(){
        let emoji = document.getElementById("emoji")
        document.addEventListener("mouseup", function(e) {
            if (!emoji.contains(e.target)) {
                
                this.emoStatus = false
            }
        });
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    methods:{
        selUser(val){
            for(let i=0;i<val.length;i++){
                this.smsData.text += val[i]
            }
        },
        toggleEmo(){
            this.emoStatus = !this.emoStatus;
        },
        onInput(e){
            if(!e){
                return false;
            }
            if(!this.smsData.text){
                this.smsData.text = e.native
            }else{
                this.smsData.text = this.smsData.text + e.native
            }
        },
        newline(){
            this.smsData.text = `${this.smsData.text}\n`
        },
        imageSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.smsData.imgUrl.push(res);
        },
        handleError (res, file) {
            this.$Notice.warning({
                title:'The file format is incorrect',
                desc:`${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
            })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select another file type.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
            });
        },
        otherSuccess (res, file) {
            let url = `/uploads/other/${res.fileName}`;
            this.$set(res,'imgUrl',url)
            this.smsData.otherUrl.push(res);
        },
        videoSuccess(res,file){
            
            let url = `uploads/video/${res.fileName}`
            this.$set(res,'imgUrl',url)
            this.smsData.videoUrl.push(res);
        },
        async deleteFile(type,fileName){
            let filePath = '';
            if(type == 'image'){
                filePath = fileName
            }else {
                filePath = fileName.imgUrl
            }
            const res = await this.callApi('delete','/api/fileUpload/file',{fileName:filePath});
            if(res.status == 200){
                if(type == 'image'){
                    this.smsData.imgUrl.pop(fileName)
                }else if(type == 'other'){
                    this.smsData.otherUrl.pop(fileName)
                }else if(type == 'video'){
                    this.smsData.videoUrl.pop(fileName)
                }
            }else{
                this.swr();
            }
        },
        async submit(){
            if(this.smsData.text == ''){
                return this.error('')
            }
            this.isLoading = true
            this.emoStatus = false;
            let userId = this.$store.state.user.id
            let foamingPosition = ''
            if(this.currentPath.params.schoolName){
                foamingPosition = this.currentPath.params.schoolName
            }
            const res = await this.callApi('post','/api/questionnaire',{data:this.smsData,userId:userId,contentType:3,foamingPosition:foamingPosition})
            if(res.status == 201){
                this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                this.swr()
            }
            this.isLoading = false;
        },
        async draft(){
            this.isDrafting = true
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/template',{content:this.smsData,userId:userId,contentType:3,templateType:2})
            if(res.status == 201){
                this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                this.$router.push(this.$route.path)

            }else{
                this.swr();
            }
            this.isDrafting = false;
        }
    }
}
</script>
<style scoped>
.sms-emoji{
    position: absolute;
    top: 46px;
    left: -8px;
}
.emoji-mart{
    height:330px!important;
}
</style>