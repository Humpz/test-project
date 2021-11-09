<template>
    <div class="flex bg-white justify-center items-center">
        <div class="flex-column w-full md:w-auto">
            <h1 class="text-center">Posts</h1>
            <div class="max-w-screen-md py-4 px-8 bg-white shadow-lg rounded-md mt-10 mb-20">
                <div class="flex justify-between">
                    <div class="flex">
                        <div class="mr-3">
                            <img src="https://www.pngall.com/wp-content/uploads/5/Profile-Avatar-PNG.png" width="50" height="50" alt="Profile Pic" class="rounded-full" />
                        </div>
                        <div>
                            <h2 class="mb-0 font-semibold">Humphrey Aljas</h2>
                            <p class="text-xs text-gray-500">Posted 4 minutes ago</p>
                        </div>
                    </div>
                    <div class="pt-2 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-gray-800 text-3xl font-semibold">Sample Post</h2>
                    <p class="mt-2 text-gray-600 break-words">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae dolores deserunt ea doloremque natus error, rerum quas odio quaerat nam ex commodi hic, suscipit in a veritatis pariatur minus consequuntur!</p>
                </div>
                <div v-if="showCommentForm">
                    <input type="text" v-model="form.name" placeholder="Enter Name (required)" class="p-2 mb-2 w-full border"/>
                    <textarea rows="2" v-model="form.comment" maxlength="50" class="border p-2 rounded w-full" placeholder="Write a comment...(required)"></textarea>
                    <button type="button" @click="submitComment()" :class="{ 'opacity-25': !form.name || !form.comment }" :disabled="!form.name && !form.comment" class="mt-1 px-4 py-1 bg-green-600 text-white rounded font-light hover:bg-green-700">Submit</button>
                    <button type="button" class="mt-1 px-4 py-1 bg-red-600 text-white rounded font-light hover:bg-red-700" @click="showCommentForm = false">Cancel</button>
                </div>
                <p v-else @click="showCommentForm = true" class="text-blue-500 cursor-pointer">Write Comment</p>
                <div v-if="comments && comments.length">
                    <p class="pb-1 my-1">Comments:</p>
                    <div v-for="comment in comments" :key="comment.id">
                        <div>
                            <p class="text-sm mb-1 text-black font-semibold">{{comment.name}}</p>
                            <p class="mb-0 text-gray-600 break-words">{{comment.comment}}</p>
                            <div v-if="clickedId === comment.id && showReplyForm" class="pb-4">
                                <input type="text" v-model="replyForm.name" placeholder="Enter Name (required)" class="p-2 mb-2 w-full border"/>
                                <textarea rows="2" maxlength="50" v-model="replyForm.comment" class="border p-2 rounded w-full" placeholder="Write a reply..."></textarea>
                                <button type="button" @click="submitReply(comment.id, comment.tier)" :class="{ 'opacity-25': !replyForm.name || !replyForm.comment }" :disabled="!replyForm.name && !replyForm.comment" class="mt-1 px-4 py-1 bg-green-600 text-white rounded font-light hover:bg-green-700">Submit</button>
                                <button type="button" class="mt-1 px-4 py-1 bg-red-600 text-white rounded font-light hover:bg-red-700" @click="showReplyForm = false">Cancel</button>
                            </div>
                            <p v-else @click="clickedId = comment.id,showReplyForm = true" class="text-blue-400 cursor-pointer">Reply</p>
                            <div v-if="comment.replies" v-for="reply in comment.replies" :key="reply.id">
                                <comment-form :reply="reply" @replied="getComments"></comment-form>
                                <div v-if="reply.replies" v-for="rep in reply.replies" :key="rep.id">
                                    <comment-form :reply="rep" @replied="getComments"></comment-form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h5 v-else class="text-center">No Comments Yet</h5>
            </div>
        </div>
    </div>
</template>

<script>
    import CommentForm from "./CommentForm";
    export default {
        name: "app",
        components: {CommentForm},
        mounted(){
            this.getComments()
        },
        data() {
            return {
                comments: null,
                clickedId:null,
                showCommentForm:false,
                showReplyForm:false,
                form:{
                    parent_id:null,
                    name: null,
                    comment: null
                },
                replyForm:{
                    parent_id:null,
                    tier:null,
                    name: null,
                    comment: null
                }
            }
        },
        methods:{
            async getComments(){
                await axios.get('/api/comments').then((res) => {
                    this.comments = res.data
                })
            },
            submitComment(){
                axios.post('/api/comment/store', this.form)
                    .then((res) => {
                        this.form.name = null;
                        this.form.comment = null;
                        this.showCommentForm = false;
                        this.getComments()
                    })
                    .catch((error) => {

                    });
            },
            submitReply(index,tier)
            {
                this.replyForm.parent_id = index;
                this.replyForm.tier = tier;
                axios.post('/api/comment/reply/store', this.replyForm)
                    .then((res) => {
                        this.replyForm.name = null;
                        this.replyForm.comment = null;
                        this.showReplyForm = false;
                        this.getComments()
                    })
                    .catch((error) => {

                    });
            }
        }
    }
</script>

<style scoped>

</style>
