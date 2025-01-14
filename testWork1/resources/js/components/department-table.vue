<template>
    <div style="margin-left: 10px;">
        <p>Фильтр по интервалу ЗП работников</p>
        <div style="display: flex;">
            <span> От:</span> 
            <input type="number" v-model="minValue" class="form-control inputWidth"> 
            <span> До:</span>
            <input type="number" v-model="maxValue" class="form-control inputWidth"> 
        </div>
        <button @click="fetchData" class="btn btn-sm btn-outline-success allMargin">Применить</button>
        <button @click="cleanFilters" class="btn btn-sm btn-outline-primary allMargin">Сбросить фильтр</button>
        <hr>
        <table class=" table table-bordered table-striped table-hover" style="width: auto; margin: 10px 0px">
            <thead>
            <tr>
                <th>Id</th>
                <th @click="sortDepartmentsName" style="cursor:pointer;">Name</th>
                <th>started_at</th>
                <th>count worker</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="item in tableData.data">
                <td>{{ item.id }}</td>
                <td>{{ item.name }}</td>
                <td>{{ item.started_at }}</td>
                <td>{{ item.countWorkers }}</td>
                <td><a :href="'/departmentEdit?id='+item.id" class="btn btn-sm btn-outline-primary allMargin">Редактировать</a>
                    <button @click="deleteDepartment(item.id)" class="btn btn-sm btn-outline-danger allMargin">Удалить</button></td>
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
    </div>
</template>

<script>
import LaravelVuePagination from "laravel-vue-pagination";
export default {
    name: "ContestCertificate",
    components: {
        pagination: LaravelVuePagination,
    },
    data() {
        return {
            sortDep: "asc",
            minValue:0,
            maxValue:0,
            tableData: {
                id: '',
                name: '',
                started_at: '',
                countWorkers: '',
            },
            error: {},
        }
    },
    beforeMount() {
        this.fetchData();
    },
    methods: {

        fetchData(page = 1) 
        {      
            axios.get('/getDepartments?page='+page+"&minValue="+this.minValue+"&maxValue="+this.maxValue+"&sort="+this.sortDep).then(res => {
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
            this.sortDep="asc";
            this.fetchData();
        },

        deleteDepartment(id)
        {
            if(confirm("Вы уверены что хотите удалить департамент?"))
                axios.delete("/deleteDepartment?id="+id).then(res =>{
                if(res.data.success)
                    this.fetchData();
                 else 
                    alert(res.data.error);});
        },

        sortDepartmentsName()
        {
            if(this.sortDep=="asc")
                this.sortDep = "desc";
            else
                this.sortDep = "asc";

            this.fetchData();
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
    margin: 0 10px 0 10px;
}
.allMargin
{
    margin: 5px;
}
.sr-only{ display: none !important; }
</style>
