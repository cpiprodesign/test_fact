<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" top="4vh">
        <div class="form-body">
            <div class="row">
                <div class="col-6">
                    <p>
                        <b>Aperturado por:</b><br>
                        <span class="text-primary">{{registers.user.name}}</span><br>
                        <small>{{registers.user.email}}</small>
                    </p>
                </div>
                <div class="col-2">
                    <p>
                        <b>Apertura:</b><br>
                        <span class="text-success">S/. {{registers.box.open_amount}}</span><br>
                    </p>
                </div>
                <div class="col-2">
                    <p>
                        <b>Ingresos:</b><br>
                        <span class="text-success">S/. {{registers.box.close_amount}}</span><br>
                    </p>
                </div>
                <div class="col-2">
                    <p>
                        <b>Saldo:</b><br>
                        <span class="text-success">S/. {{registers.box.open_amount+registers.box.close_amount}}</span><br>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h3>Operaciones</h3>
                    <div class="table-responsive">
                        <table class="table  table-condensed table-striped table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>Tipo de Operación</th>
                                <th>Total</th>
                                <th>Detalles de Pago</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, index) in registers.detail_box">
                                    <td> {{row.operation_type}}</td>
                                    <td> {{ row.symbol }} {{ row.total}}</td>
                                    <td v-if="row.operation_type == 'Gasto'">
                                        {{ row.detail}}
                                    </td>
                                    <td v-else>
                                        Documento N° {{ row.series}} - {{ row.number}}
                                    </td>                                 
                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="form-actions text-right pt-2">
            <el-button @click.prevent="close()">Cerrar</el-button>
            <!--                <el-button class="add" type="primary" native-type="submit">Apertutrar</el-button>-->
        </div>

    </el-dialog>
</template>

<script>


    export default {
        props: ['showDialog', 'registers'],
        // mixins: [formDocumentItem],
        data() {
            return {
                titleDialog: 'Detalles de Operaciones en Caja',
                resource: 'pos',
            }
        },
        created() {
            // this.getData();

        },
        methods: {
            // getData() {
            //     this.$http.get(`/${this.resource}/details/${this.posId}`).then(response => {
            //         this.table = response.data.sales;
            //     });
            //
            // },

            // create() {
            //     this.$http.get(`/${this.resource}/tables`).then(response => {
            //         this.initForm();
            //
            //         this.user = response.data.user;
            //         this.establishment = response.data.user.establishment;
            //
            //         this.form.user_id = response.data.user.id;
            //         this.form.establishment_id = response.data.user.establishment.id;
            //     });
            // },
            close() {
                this.$emit('update:showDialog', false)
            },
            // sendForm() {
            //     this.$emit('OpenPos', this.form)
            // }
        }
    }

</script>
