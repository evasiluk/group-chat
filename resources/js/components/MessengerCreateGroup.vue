<template>
    <form>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Create Group</label>
                <input class="form-control" type="text" v-model="name" placeholder="Group Name">
            </div>
            <div class="form-group col-md-4">
                <label>&nbsp;</label>
                <select v-model="users" multiple class="form-control">
                    <option v-for="user in initialUsers" :value="user.id">
                        {{ user.name }}
                    </option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>&nbsp;</label><br>
                <button type="submit" @click.prevent="createGroup" class="btn btn-primary">Create Group</button>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        props: ['initialUsers'],
        data() {
            return {
                name: '',
                users: []
            }
        },
        methods: {
            createGroup() {
                if(this.users.length < 1) {
                    alert('Выберите собеседников!');
                } else {
                    axios.post('/groups', {name: this.name, users: this.users})
                        .then((response) => {
                            this.name = '';
                            this.users = [];
                            Bus.$emit('groupCreated', response.data);
                        });
                }
            }
        }
    }
</script>