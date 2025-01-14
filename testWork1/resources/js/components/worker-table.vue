<template>
    <div style="margin-left: 10px;">
        <div class="row" >
            <div class="col-sm-4">
                <div>
                    <p>Интервал ЗП</p>
                </div>
                <div>
                    <span> От:</span>
                    <input class="form-control inputWidth" type="number" v-model="minValue"> 
                    <span> До:</span>
                    <input class="form-control inputWidth" type="number" v-model="maxValue"> 
                </div>
            </div>
            <div class="col-sm-4" >
                <p>Департамент</p>
                <vselect :options="allDepartments" label="name" v-model="selectedDep"></vselect>
                
            </div>

            
        </div>
        <div>
            <button @click="fetchData" class="btn btn-sm btn-outline-success" style="margin: 5px;">Применить</button>
            <button @click="cleanFilters" class="btn btn-sm btn-outline-primary" style="margin: 5px;">Сбросить фильтры</button>
        </div>
        <hr>
    </div>
    <table class=" table table-bordered table-striped table-hover" style="width: auto; margin: 10px 10px">
        <thead>
          <tr>
            <th>Id</th>
            <th @click="sortWorkersName" style="cursor:pointer;">Name</th>
            <th>salary</th>
            <th>Действия</th>
          </tr>
        </thead>
        <tbody>
            <tr v-for="item in tableData.data">
            <td>{{ item.id }}</td>
            <td>{{ item.name }}</td>
            <td>{{ item.salary }}</td>
            <td><a :href="'/workerEdit?id='+item.id" class="btn btn-xs btn-outline-primary" style="margin: 5px;">Редактировать</a>
                <button @click="deleteWorker(item.id)" class="btn btn-xs btn-outline-danger" style="margin: 5px;">Удалить</button></td>
          </tr>
        </tbody>
      </table>

      <pagination :data="tableData" @pagination-change-page="fetchData" size="small" :limit=4 :keepLength="true" style="margin: 10px 10px 20px;">
            <template #prev-nav>
                <span>Пред.</span>
            </template>

            <template #next-nav>
                <span>След.</span>
            </template>
        </pagination>
</template>

<script>
import LaravelVuePagination from "laravel-vue-pagination";
import 'vue-select/dist/vue-select.css';
import vselect from "vue-select";
export default {
    name: "ContestCertificate",
    components: {
        vselect:vselect,
        pagination: LaravelVuePagination,
    },
    data() {
        return {
            sortWorker: "asc",
            minValue:0,
            maxValue:0,
            allDepartments:[],
            selectedDep:"",
            tableData: {
                id: '',
                name: '',
                salary: '',
            },
            error: {},
        }
    },
    beforeMount() {
        this.fetchData();
        this.getAllDepartments();
    },
    methods: {

        fetchData(page = 1) 
        {      
            let departmentId = "";
            if(this.selectedDep.id)
                departmentId = "&departmentId="+this.selectedDep.id;

            axios.get('/getWorkers?page='+page+"&minValue="+this.minValue+"&maxValue="+this.maxValue+"&sort="+this.sortWorker+departmentId).then(res => {
                if(res.data.success)
                    this.tableData = res.data.data;
                else 
                    alert(res.data.error);
            })
            
        },

        cleanFilters()
        {
            this.minValue=0;
            this.maxValue=0;
            this.sortWorker="asc";
            this.selectedDep=[];
            this.fetchData();
        },

        deleteWorker(id)
        {
            if(confirm("Вы уверены что хотите удалить работника?"))
                axios.delete("/deleteWorker?id="+id).then(res =>{ this.fetchData(); });
        },

        sortWorkersName()
        {
            if(this.sortWorker=="asc")
                this.sortWorker = "desc";
            else
                this.sortWorker = "asc";

            this.fetchData();
        },


        getAllDepartments()
        {
            axios.get('/getAllDepartments').then(res => {
                this.allDepartments = res.data.data;
            })
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
    display: inline;
    width: 200px;
}
.sr-only{ display: none !important; }
</style>
