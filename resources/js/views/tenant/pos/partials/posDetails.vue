<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" top="4vh" width="90vw">
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
                        <span class="text-success">S/. {{registers.open_amount}}</span><br>
                    </p>
                </div>
                <div class="col-2">
                    <p>
                        <b>Ingresos:</b><br>
                        <span class="text-success">S/. {{registers.close_amount}}</span><br>
                    </p>
                </div>
                <div class="col-2">
                    <p>
                        <b>Saldo:</b><br>
                        <span class="text-success">S/. {{registers.open_amount+registers.close_amount}}</span><br>
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
                                <th>Documento</th>
                                <th>Saldo</th>
                                <th>Pagado</th>
                                <th>Devolución</th>
                                <th>Items</th>
                                <th>Detalles de Pago</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(sale,index) in registers.sales">
                                <td class="text-center"> {{ sale.document.series}} - {{ sale.document.number}}</td>
                                <td class="text-right"> {{ sale.document.currency_type.symbol }} {{ sale.total}}</td>
                                <td class="text-right"> {{ sale.document.currency_type.symbol }} {{ sale.payed}}</td>
                                <td class="text-right"> {{ sale.document.currency_type.symbol }} {{ sale.delta}}</td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li v-for="item in sale.document.items" class="list-item">
                                            <b>{{ `${item.item.internal_id} - ${item.item.description}` }}</b>
                                            x {{ item.quantity}}
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li v-for="operation in  sale.details" class="list-item">
                                            {{operation.type}}:
                                            {{ sale.document.currency_type.symbol }} {{operation.amount}}
                                            {{operation.reference? ` - (Ref: ${operation.reference})`: '' }}
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <a class="btn waves-effect waves-light btn-xs text-white btn-primary"
                                       :href="`http://erp1.multifacturalo.local/print/document/${sale.document.external_id}/a4`"
                                       target="_blank"
                                    >
                                        <i class="fas fa-file-alt"></i> A4
                                    </a>
                                    <a class="btn waves-effect waves-light btn-xs text-white btn-primary"
                                       :href="`http://erp1.multifacturalo.local/print/document/${sale.document.external_id}/ticket`"
                                       target="_blank"
                                    >
                                        <i class="fas fa-receipt"></i> Ticket
                                    </a>
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
                titleDialog: 'Detalles de operaciones en Caja',
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
