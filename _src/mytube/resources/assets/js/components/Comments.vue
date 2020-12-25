<template>
    <div class="comments">
        <p>{{ comments.length }} comments</p>

        <div class="video-comment clearfix" v-if="mytube.user.authenticated">
            <textarea placeholder="Say something" class="form-control video-comment-input" v-model="body"></textarea>

            <div class="pull-right">
                <button type="submit" class="btn btn-default" @click.prevent="newComment" >Post</button>
            </div>
        </div>

        <ul class="media-list">
            <li class="media" v-for="comment in comments" v-bind="comment">
                <div class="media-left">
                   <a :href="'/channel/' + comment.channel.slug">
                        <img :src="comment.chanel.avatar" width="40" height="40" class="media-object">
                    </a>
                </div>
                <div class="media-body">
                    <a :href="'/channel/' + comment.channel.slug">{{ comment.channel.name }}</a> {{ comment.created_at_human }}
                    <p>{{ comment.body }}</p>
                    <ul class="list-inline">
                        <li>
                            <a href="#" @click.prevent="toggleReplyForm(comment.id)" v-if="mytube.user.authenticated">{{ showReplyForm === comment.id ? 'Cancel' : 'Reply' }}</a>
                        </li>
                        <li>
                            <a href="#" @click.prevent="removeComment(comment.id)" v-if="mytube.user.id == comment.user_id" >Delete</a>
                        </li>
                    </ul>

                    <div class="video-comment clear" v-if="showReplyForm === comment.id" >
                        <textarea class="form-control reply-body-input" v-model="replyBody"></textarea>
                        <div class="pull-right">
                            <button type="submit" class="btn btn-default" @click.prevent="createReply(comment.id)">Reply</button>
                        </div>
                    </div>

                    <div class="media" v-for="reply in comment.replies" v-bind="reply">
                        <div class="media-left">
                            <a :href="'/channel/' + reply.channel.slug">
                                <img :src="reply.channel.avatar" class="media-object">
                            </a>
                        </div>
                        <div class="media-body">
                            <a :href="'/channel/' + reply.channel.slug">{{ reply.channel.name }}</a> {{ reply.created_at_human }}
                            <p>{{ reply.body }}</p>
                            <ul class="list-inline" >
                                <li>
                                    <a href="#" v-if="mytube.user.id == reply.user_id" @click.prevent="removeComment(reply.id)" >Delete</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                comments: [],
                body: null,
                replyBody: null,
                showReplyForm: null,
                mytube: null
            }
        },

        props: {
            videoUid: null
        },

        mounted(){
            this.mytube = window.mytube;
            this.getComments();
        },

        methods: {
            getcomments(){
                axios.get('/videos/' + this.videoUid + '/comments')
                     .then((response) => {
                        this.comments = response.data.comments;
                     });
            },

            newComment(){
                axios.post('/videos/' + this.videoUid + '/comments', {
                    body: this.body
                })
                .then((response) => {
                    this.comments.unshift(response.data.comment);
                    this.body = null;
                })
            },

            removeComment(commentId){
                if(!confirm('Are you sure you want to delete this comment?')){
                    return;
                }

                this.comments.map((comment, index) => {

                    if(comment.id === commentId){
                        this.comments.splice(index, 1);
                        return;
                    }

                    comment.replies.map((reply, replyIndex) => {
                        if(reply.id === commentId){
                            this.comments[index].replies.splice(replyIndex, 1);
                            return;
                        } 
                    });


                });

                axios.delete('/videos/' + this.videoUid + '/comments/' + commentId);
            },

            toggleReplyForm(id){
                this.replyBody = null;

                if(this.showReplyForm === id){
                    this.showReplyForm = null;
                    return;
                }

                this.showReplyForm = id;
            },

            createReply(id){
                axios.post('/videos/' + this.videoUid + '/comments', {
                    body: this.replyBody,
                    parent_id: id
                }).then((response) => {
                    this.comments.map((comment, index) => {
                        if(comment.id === id){
                            this.comments[index].replies.push(response.data.comment);
                            return;
                        }
                    });

                    this.replyBody = null;
                    this.showReplyForm = null;
                });
            }

        }
    }
</script>