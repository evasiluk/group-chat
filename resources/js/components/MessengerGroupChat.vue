<template>
        <div v-if="chatIsOpen">
            <div :id="group_id" class="group-messages border">
                <div v-for="message in conversations" :class="message.user_id == user.id ? 'message send' : 'message received'">
                    <p>
                        <strong v-if="message.user_id != user.id">{{message.user.name}}: </strong> {{message.message}}
                    </p>
                </div>
            </div>
            <span v-if="PeerIsTyping" class="text-secondary">{{PeerIsTyping.name}} is typing...</span>
            <hr>
            <input type="text" class="form-control" v-model="textMessage" @keyup.enter="sendMessage" @keydown="tapListener">
        </div>
</template>

<script>
    export default {
        props: ['group', 'user'],
                data() {
            return {
                conversations: [],
                textMessage: '',
                group_id: this.group.id,
                PeerIsTyping: false,
                typingTimer: false,
                chatIsOpen: false,
                newUnreadMessage: false,

            }
        },
        computed: {
            collapseId: function() {
                return "collapse-" + this.group_id;
            },
            collapseIdTarget: function() {
                return "#collapse-" + this.group_id;
            },
        },
        mounted() {
            this.listenForNewMessage();
            this.getMessages();

            Bus.$on('showThread', (id) => {
            if(id == this.group_id) {
            this.chatIsOpen = true;
        } else {
            this.chatIsOpen = false;
        }
    });
        },
        updated: function () {
            this.scrollFeed();
        },
        methods: {
            getMessages(){
                axios.post("/group/" + this.group_id + "/messages")
                        .then((response) => {
                    this.conversations = response.data;
            });
        },
        sendMessage() {
            axios.post('/conversations', {message: this.textMessage, group_id: this.group.id})
                    .then((response) => {
                if(response.status == 201) {
                this.conversations.push({message: this.textMessage, user_id: response.data.user_id, user: response.data.user});
                this.textMessage = "";
            }
        },
        (error) => {
            alert(error.response.data.message);
        });
    },
    tapListener(){
        Echo.private('groups.' + this.group.id).whisper('typing', {
            name: this.user.name
        });
    },
    listenForNewMessage() {
        Echo.private('groups.' + this.group.id)
                .listen('NewMessage', ({conversation}) => {
            this.conversations.push(conversation);
        this.PeerIsTyping = false;

        if(!this.chatIsOpen) {
            this.newUnreadMessage = true;
            Bus.$emit('messageInAnotherThread', this.group_id);
        }
    })
    .listenForWhisper('typing', e => {
        this.PeerIsTyping = e;
    if(this.typingTimer) clearTimeout(this.typingTimer);
    this.typingTimer = setTimeout(()=> {
        this.PeerIsTyping = false;
    }, 3000)
    });
    },
    scrollFeed() {
        $("#" + this.group_id).scrollTop(this.conversations.length * 50);
    }
    }
    }
</script>