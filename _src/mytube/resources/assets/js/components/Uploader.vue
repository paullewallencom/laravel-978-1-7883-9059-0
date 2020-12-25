<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Upload
                    </div>
                    <div class="panel-body">
                        <input type="file" name="videos[]" v-if="!uploading" multiple @change="onVideosSelected" ref="input">
                        <div class="progress" v-if="uploading && !overallFinish">
                          <div class="progress-bar" role="progressbar" :style="{ 
                              width: overallProgress + '%' 
                          }">
                          </div>
                        </div>    

                        <div class="alert alert-success" v-if="uploading && overallFinish">
                            Uploads complete. <a href="/videos">Go to your videos</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-for="video in videos" v-bind="video">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="alert alert-danger" v-if="video.failed">
                            Something went wrong. Please try again.
                        </div>
                        
                        <span class="help-block">
                            {{ video.saveStatus }}
                        </span>
                        
                        <div class="alert alert-info" v-if="!video.finished">
                            Your video will be available at <a :href="mytube.url + '/videos/' + video.uid " target="_blank">{{ mytube.url }}/videos/{{ video.uid }}</a>.
                        </div>

                        <div class="alert alert-success" v-if="video.finished">
                            Upload complete. Video is now processing.
                        </div>

                        <div class="progress" v-if="!video.finished">
                          <div class="progress-bar" role="progressbar" :style="{ 
                              width: video.progress + '%' 
                          }">
                          </div>
                        </div>

                        <div class="video-form" v-if="!video.finished && !video.failed">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" v-model="video.name" />
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea cols="30" rows="10" class="form-control" v-model="video.description"></textarea>
                            </div>
                            <div class="form-group">
                                    <label for="visibility">Visibility</label>
                                    <select class="form-control" v-model="video.visibility">
                                        <option value="private">Private</option>
                                        <option value="unlisted">Unlisted</option>
                                        <option value="public">Public</option>
                                    </select>
                                </div>
                            <div class="form-group">
                                <button class="btn btn-primary" @click.prevent="updateMetaData(video)">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
 import eventSystem from '../event.js';

 export default{
    data(){
        return {
             videos: [],
             overallProgress: 0,
             overallFinish: false,
             timekeeper: null,
             uploading: false,
             mytube: null
            }
     },
    mounted(){
        this.mytube = window.mytube;

        eventSystem.$on('init', () => {
            if(!this.timekeeper){
                this.timekeeper = setInterval(() => {
                    if(this.unfinishedUploads().length === 0){
                        this.updateOverallProgress();
                        clearInterval(this.timekeeper);
                        this.timekeeper = null;
                    }
                }, 1000);
            }
        });

        eventSystem.$on('progress', (videoObject, e) => {
            this.updateVideoUploadProgress(videoObject, e);
            this.updateOverallProgress();
        });

        eventSystem.$on('finished', (videoObject, e) => {
            videoObject.uploading = false;
            videoObject.finished = true;
        });

        eventSystem.$on('failed', (videoObject, e) => {
            videoObject.failed = true;
        });
    },
    methods: {
        onVideosSelected(){
            var videos = this.$refs.input.files;
            var video;

            for(var i = 0; i < videos.length; i++){
                video = videos[i];

                this.storeMetaData(video)
                    .then((videoObject) => {
                        this.upload(videoObject);
                    }, (videoObject) => {
                        videoObject.failed = true;
                    });
            }
        },

        storeMetaData(video){
            var videoObject = this.buildVideoObject(video);

            return new Promise((resolve, reject) => {
                axios.post('/videos', {
                    name: videoObject.name,
                    description: videoObject.description,
                    visibility: videoObject.visibility,
                    extension: videoObject.video.name.split('.').pop()
                }).then((response) => {
                    videoObject.id = response.data.data.id;
                    videoObject.uid = response.data.data.uid;

                    resolve(videoObject);
                },() => {
                    reject(videoObject);
                });
            });
        },

         buildVideoObject(video){
            var index = this.videos.push({
                id: null,
                uid: null,
                name: 'Untitled',
                description: null,
                visibility: 'private',
                video: video,
                saveStatus: "",
                progress: 0,
                failed: false,
                loadedBytes: 0,
                totalBytes: 0,
                finished: false,
                uploading: false,
                uploadingComplete: false

            }) - 1;

            return this.videos[index];
        },

        upload(videoObject){
            var form = new FormData();
            
            this.uploading = true;
            videoObject.uploading = true;
            
            form.append('video', videoObject.video);
            form.append('id', videoObject.id);
            form.append('uid', videoObject.uid);

            eventSystem.$emit('init');

            axios.post('/upload', form, {
                onUploadProgress: (e) => {
                    eventSystem.$emit('progress', videoObject, e);
                }
            }).then((response) => {
                eventSystem.$emit('finished', videoObject);
             }, () => {
                 eventSystem.$emit('failed', videoObject);
             });
        },

         updateMetaData(videoObject){
            videoObject.saveStatus = "Saving changes...";

            return axios.put('/videos/' + videoObject.uid, {
                name: videoObject.name,
                description: videoObject.description,
                visibility: videoObject.visibility
            }).then((response) => {
                videoObject.saveStatus = 'Changes saved.';

                setTimeout(() => {
                    videoObject.saveStatus = null;
                }, 3000);
            }, () => {
                videoObject.saveStatus = "Failed to save changes...";
            });
        },

        updateVideoUploadProgress(videoObject, e){
            if(!e.lengthComputable){
                return;
            }

            videoObject.loadedBytes = e.loaded;
            videoObject.totalBytes = e.total;

            videoObject.progress = Math.ceil((e.loaded / e.total) * 100);
        },

        updateOverallProgress(){
            var unfinishedUploads = this.unfinishedUploads();
            var totalProgress = 0;

            unfinishedUploads.forEach((video) => {
                totalProgress += video.progress;
            });

            this.overallProgress = parseInt(totalProgress / unfinishedUploads.length || 0);

            if(this.overallProgress >= 100){
                this.overallFinish = true;
            }

        },

         unfinishedUploads(){
            var videos = [];

            for(var i = 0; i < this.videos.length; i++){
                if(this.videos[i].finished){
                    continue;
                }

                videos.push(this.videos[i]);
            }

            return videos;

        }
    }
}
</script>