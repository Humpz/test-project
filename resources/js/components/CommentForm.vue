<template>
    <div :class="[reply.tier === 2 ? 'pl-4' : '', reply.tier === 3 ? 'pl-12 pb-2' : '']">
        <p class="text-sm mb-1 text-black font-semibold">{{reply.name}}</p>
        <p class="mb-0 text-gray-600 break-words">{{reply.comment}}</p>
        <div v-if="clickedId === reply.id && showReplyForm" class="pb-4">
            <input type="text" v-model="replyForm.name" placeholder="Enter Name (required)" class="p-2 mb-2 w-full border"/>
            <textarea rows="2" maxlength="50" v-model="replyForm.comment" class="border p-2 rounded w-full" placeholder="Write a reply..."></textarea>
            <button type="button" @click="submitReply(reply.id, reply.tier)" :class="{ 'opacity-25': !replyForm.name || !replyForm.comment }" :disabled="!replyForm.name && !replyForm.comment" class="mt-1 px-4 py-1 bg-green-600 text-white rounded font-light hover:bg-green-700">Submit</button>
            <button type="button" class="mt-1 px-4 py-1 bg-red-600 text-white rounded font-light hover:bg-red-700" @click="showReplyForm = false">Cancel</button>
        </div>
        <p v-else v-show="reply.tier < 3" @click="clickedId = reply.id,showReplyForm = true" class="text-blue-400 cursor-pointer">Reply</p>
    </div>
</template>

<script>
    export default {
        name: "CommentForm",
        props: ['reply'],
        data() {
            return {
                clickedId: null,
                showReplyForm:false,
                replyForm:{
                    parent_id:null,
                    tier:null,
                    name: null,
                    comment: null
                }
            }
        },
        methods:{
            submitReply(index,tier)
            {
                this.replyForm.parent_id = index;
                this.replyForm.tier = tier;
                axios.post('/api/comment/reply/store', this.replyForm)
                    .then((res) => {
                        this.replyForm.name = null;
                        this.replyForm.comment = null;
                        this.showReplyForm = false;
                        this.$emit('replied')
                    })
                    .catch((error) => {

                    });
            }
        }
    }
</script>

<style scoped>

</style>
