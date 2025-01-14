<template>
        <div style="margin-left: 10px;">
            <p style="font-size: 20px; font-weight: bold;">Редактирование работника</p>
            <hr>
            <div style="margin-top: 10px;">
                <span style="font-size: 18px; ">Имя:</span>
                <input class="form-control inputWidth" type="text" v-model="form.name">
                <br>
                <span style="font-size: 18px;">Зарплата:</span>
                <input class="form-control inputWidth" type="number" v-model="form.salary">
                <br>
                <button @click="sendForm" class="btn btn-sm btn-outline-success" style="margin: 5px;">Сохранить</button>
                <a href="/workers" class="btn btn-sm btn-outline-primary" style="margin: 5px;">Назад</a>
            </div>
        </div>
</template>

<script>
export default {
    name: "ContestCertificate",
    props: ["worker"],
    data() {
        return {
            form: {
                id: '',
                name: '',
                salary: '',
            },
            error: {},
        }
    },
    beforeMount() {
        this.form.id=this.worker.id;
        this.form.name=this.worker.name;
        this.form.salary=this.worker.salary;
        
    },
    
    methods: {

        sendForm()
        {
            axios.post("/setWorker",this.form).then(res =>{
                if(res.data.success)
                    alert("Сохранено");
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
    font-size: 14px;
}
</style>
