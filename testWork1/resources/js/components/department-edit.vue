<template>
    <div style="margin-left: 10px;">
        <div>
            <p style="font-size: 20px; font-weight: bold;">Редактирование департамента</p>
            <hr>
            <div>
                <span style="font-size: 18px;">name:</span>
                <input class="form-control inputWidth" type="text" v-model="form.name">

                <span style="font-size: 18px;">started_at:</span>
                <input class="form-control inputWidth" type="date" v-model="form.started_at">

                <button @click="sendForm" class="btn btn-sm btn-outline-success" style="margin: 5px 5px 5px 0">Сохранить</button>
                <a href="/departments" class="btn btn-sm btn-outline-primary" >Назад</a>
            </div>
        </div>
        <br/>
    
        <div class="row">
            <div class="col-sm-4">
                <p>Работники в департаменте</p>
                <table class=" table table-bordered table-striped table-hover" style="width: auto; margin-top: 5px">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Salary</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="worker in workers.data">
                            <td>{{ worker.id }}</td>
                            <td>{{ worker.name }}</td>
                            <td>{{ worker.salary }}</td>
                            <td><button @click="deleteFromDepartment(form.id,worker.id)" class="btn btn-sm btn-outline-primary">Удалить из департамента</button></td>
                        </tr>
                    </tbody>
                </table>
                    <pagination :data="workers" @pagination-change-page="getWorkers" size="small" :limit=4 :keepLength="true" style="margin: 10px 10px 20px;">
                        <template #prev-nav>
                            <span>Пред.</span>
                        </template>
                        <template #next-nav>
                            <span>След.</span>
                        </template>
                    </pagination>
            </div>
            <div class="col-sm-4">
                <p>Работники не в департаменте</p>
                <table class=" table table-bordered table-striped table-hover" style="width: auto; margin-top: 5px">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name <input type="text" @keyup="searchInFreeWorker" v-model="searchName"></th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="worker in freeWorkers.data">
                            <td>{{ worker.id }}</td>
                            <td>{{ worker.name }}</td>
                            <td><button @click="addWorker(worker.id)" class="btn btn-sm btn-outline-primary">Добавить в департамент</button></td>
                        </tr>
                    </tbody>
                </table>
                <pagination :data="freeWorkers" @pagination-change-page="getFreeWorkers" size="small" :limit=4 :keepLength="true" style="margin: 10px 10px 20px;">
                            <template #prev-nav>
                                <span>Пред.</span>
                            </template>
                            <template #next-nav>
                                <span>След.</span>
                            </template>
                        </pagination>
            </div>
        </div>



    </div>
</template>

<script>
import LaravelVuePagination from "laravel-vue-pagination";
export default {
    name: "ContestCertificate",
    props: ["department"],
    components: {
        pagination: LaravelVuePagination,
    },
    data() {
        return {
            freeWorkers:[],
            searchName:"",
            workers:"",
            form: {
                id: '',
                name: '',
                started_at: '',
                workers:[],
            },
            error: {},
        }
    },
    beforeMount() {
        this.form.id=this.department.id;
        this.form.name=this.department.name;
        this.form.started_at=this.department.started_at;
        console.log(this.form.started_at);
        this.getWorkers();
        
    },
    
    methods: {

        searchInFreeWorker()
        {
            this.getFreeWorkers(1,this.searchName);
        },
    

        deleteFromDepartment(depId,workerId)
        {
            if(confirm("Вы уверены что хотите удалить работника из департамента?"))
                axios.delete("/deleteFromDepartment?depId="+depId+"&workerId="+workerId).then(res =>{
                if(res.data.success)
                    this.getWorkers();
                 else 
                    alert(res.data.error);  });
        },

        addWorker(workerId)
        {
            axios.post("/addWorkerToDepartment?workerId="+workerId+"&departmentId="+this.form.id).then(res =>{
                    if(res.data.success)
                    {
                        this.getWorkers();
                        this.getFreeWorkers();
                    }
                    else 
                        alert(res.data.error);
                });
            
        },

        getFreeWorkers(page=1,name="")
        {
            if(this.searchName)
                name=this.searchName;

            axios.get("/getWorkersForDepartment?departmentId="+this.form.id+"&page="+page+"&name="+name+"&free=1").then(res =>{
                    if(res.data.success)
                        this.freeWorkers = res.data.data;
                    else 
                        alert(res.data.error);
            });
            
        },

        sendForm()
        {
            axios.post("/setDepartment",this.form).then(res =>{
                if(res.data.success)
                    alert("Сохранено");
                else 
                    alert(res.data.error);
            });
        },

        getWorkers(page=1) 
        {      

            axios.get("/getWorkersForDepartment?departmentId="+this.form.id+"&page="+page+"&free=0").then(res =>{
                if(res.data.success)
                {
                    this.workers = res.data.data;
                    this.getFreeWorkers();
                }
                else 
                    alert(res.data.error);
                
            });

        },

       
    }
}
</script>

<style scoped>
.error-message {
    color: #FF0000FF;
}
.inputWidth
{
    width: 200px;
}
.sr-only{ display: none !important; }
</style>
