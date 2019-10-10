<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card" v-if="$gate.isAdmin()">
                    <div class="card-header">
                        <h3 class="card-title"> User's Information</h3>

                        <div class="card-tools">
                            <div class="btn btn-success" @click="openModal">Add New
                                <i class="fas fa-user-plus fa-fw"></i>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Registered At</th>
                                <th>Modify</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in users.data" :key="user.id">
                                <td>{{user.id}}</td>
                                <td>{{user.name}}</td>
                                <td>{{user.email}}</td>
                                <td>{{user.type | firstUp}}</td>
                                <td>{{user.created_at | dateTime}}</td>
                                <td>
                                    <a href="#" @click="editModal(user)">
                                        <i href="#" class="fa fa-edit blue"></i>
                                    </a>
                                    /
                                    <a href="#" @click="deleteUser(user.id)">
                                        <i class="fa fa-trash red"></i>
                                    </a>

                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                    <pagination :data="users" @pagination-change-page="getResults">


                    </pagination>
                    </div>
                </div>
                <!-- /.card -->
                <div v-if="!$gate.isAdmin()">
                    <notfound ></notfound>
                </div>
            </div>
        </div>
    <!-- Modal -->
    <div class="modal fade"  id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"  v-show="editMode" id="addNewLabel">Update User</h5>
                    <h5  class="modal-title" v-show="!editMode" id="addNewLabel">Add New User</h5>
                    <button type="button " class="close red " data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateUser() : createUser()">
                <div class="modal-body">

                    <div class="form-group">
                        <input v-model="form.name" type="text" name="name"
                               placeholder="Enter your name"
                               class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                    </div>


                    <div class="form-group">
                        <input v-model="form.email" type="email" name="email"
                               placeholder="Enter your email"
                               class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                        <has-error :form="form" field="email"></has-error>
                    </div>

                    <div class="form-group">
                        <textarea v-model="form.bio"  name="bio" id="bio"
                               placeholder="Enter your bio"
                               class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }">
                        </textarea>
                        <has-error :form="form" field="bio"></has-error>
                    </div>

                    <div class="form-group">
                        <select v-model="form.type" type="text" name="type"
                               class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                            <option value="">Select User Role</option>
                            <option value="admin">Admin</option>
                            <option value="user">Standard User</option>
                            <option value="author">Author</option>
                        </select>
                        <has-error :form="form" field="name"></has-error>
                    </div>

                    <div class="form-group">
                        <input v-model="form.password" type="password" name="password" id="password"
                               placeholder="Enter your password"
                               class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                        <has-error :form="form" field="password"></has-error>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
                    <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</template>

<script>

    //import Form from 'vform';
    export default {

        data(){

            return {


                editMode: false,
                users: {},
                form: new Form({
                    id:'',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: ''
                })
            }
        },
        methods:{
            getResults(page = 1) {
                axios.get('api/user?page=' + page)
                    .then(response => {
                        this.users = response.data;
                    });
            },
            updateUser(){

                this.form.put('api/user/'+this.form.id)
                    .then(()=> {
                        Fire.$emit('AfterCreate');
                        $('#addNew').modal('hide')

                        Toast.fire({
                            type: 'success',
                            title: 'User Updated in successfully'
                        })
                        this.$Progress.finish();
                    })
                    .catch(()=>{
                        this.$Progress.fail();
                    })

            },
            openModal(){
                this.editMode=false;
                this.form.reset();
                $('#addNew').modal('show')
            },
            editModal(user)
            {
                this.editMode = true;
                this.form.reset();
                $('#addNew').modal('show')
                this.form.fill(user)
            },
            loadUsers(){
                if(this.$gate.isAdmin())
                    axios.get("api/user").then(({ data }) => (this.users = data));
            },
            createUser(){
                this.$Progress.start();

                this.form.post('api/user')
                    .then(()=>{
                        Fire.$emit('AfterCreate');
                        $('#addNew').modal('hide')

                        Toast.fire({
                            type: 'success',
                            title: 'User Created in successfully'
                        })
                        this.$Progress.finish();

                    })
                    .catch(()=>{
                        this.$Progress.fail();
                    })
            },
            deleteUser(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {

                    // Send http request to server
                    if (result.value) {
                        this.form.delete('api/user/' + id).then(() => {
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            Fire.$emit('AfterCreate');

                        }).catch(() => {
                            Swal.fire(
                                "Failed!",
                                "There was something wrong.",
                                "warning");
                        });
                    }
                })


            }


        },
        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findUser?q=' + query)
                    .then((data) => {
                        this.users = data.data
                    })
                    .catch(() => {

                    })
            })
            this.loadUsers();
            Fire.$on('AfterCreate',()=>{
                this.loadUsers();
            });

           // setInterval(()=>this.loadUsers(),3000);
        },
    }
</script>
