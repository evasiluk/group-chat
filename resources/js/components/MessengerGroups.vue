<template>
    <div class="row">
        <div class="col-md-2 groups-list">
            <div v-bind:class="{'group-item': true, 'active': (current == group.id) }" v-for="group in groups" v-on:click="showThread(group.id)">
                <span>{{ group.name }} <span v-if="(newMessages.indexOf(group.id) != -1)" class="new-circle">&nbsp;</span></span>
            </div>
        </div>

        <div class="col-md-10">
            <div v-if="current == -1" class="group-messages border init">
                <p>Start Messaging!</p>
            </div>
            <messenger-group-chat v-for="group in groups" v-bind:group="group" v-bind:user="user" v-bind:key="group.id"></messenger-group-chat>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['initialGroups', 'user'],
        data() {
            return {
                groups: [],
                current: -1,
                newMessages: []
            }
        },
        mounted() {
            this.groups = this.initialGroups;
            Bus.$on('groupCreated', (group) => {
                this.groups.push(group);

            });
            this.listenForNewGroups();
            Bus.$on('messageInAnotherThread', (id) => {
                this.newMessages.push(id);
            });
        },
        methods: {
            listenForNewGroups() {
                Echo.private('users.' + this.user.id)
                    .listen('GroupCreated', (e) => {
                        this.groups.push(e.group);
                    });
            },
            showThread(id) {
                Bus.$emit('showThread', id);
                this.current = id;

                var index = this.newMessages.indexOf(id);
                if(index != -1) {
                    this.newMessages.splice(index, 1);
                }
            }
        }
    }
</script>