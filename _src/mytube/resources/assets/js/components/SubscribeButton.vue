<template>
    
    <div v-if="subscribers !== null">
        {{ subscribers }} subscribers &nbsp;
        <button class="btn btn-default" type="button" v-if="canSubscribe" @click.prevent="handle">{{ userSubscribed ? 'Unsubscribe' : 'Subscribe' }}</button>
    </div>

</template>
<script>
    export default {
        data(){
            return {
                subscribers: null,
                userSubscribed: false,
                canSubscribe: false
            }
        },
        props: {
            channelSlug: null
        },
        methods: {
            getSubscriptionStatus(){
                axios.get('/subscriptions/' + this.channelSlug)
                     .then((response) => {
                         console.log(response);
                         this.subscribers = response.data.data.count;
                         this.userSubscribed = response.data.data.userSubscribed;
                         this.canSubscribe = response.data.data.canSubscribe;
                     });
            },
            handle(){
                if(this.userSubscribed){
                    this.unsubcribe();
                }
                else{
                    this.subscribe();
                }
            },
            subscribe(){
                this.userSubscribed = true;
                this.subscribers++;

                axios.post('/subscriptions/' + this.channelSlug);
            },
            unsubcribe(){
                this.userSubscribed = false;
                this.subscribe--;

                axios.delete('/subscriptions/' + this.channelSlug);
            }
        },
        mounted(){
            this.getSubscriptionStatus();
        }
    }
</script>