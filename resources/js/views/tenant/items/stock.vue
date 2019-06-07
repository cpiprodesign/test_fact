<template>
    <el-dialog :title="titleDialog" :visible="showStockDialog" @close="close" @open="create" top="2vh" append-to-body>
        <!-- <div class="card-header bg-info">
            <h3 class="my-0">Listado de cuentas bancarias</h3>
        </div> -->
        <div class="row">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ubicación</th>
                            <th class="text-right">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in stock_details">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.almacen }}</td>
                        <td class="text-right">{{ formaterDecimal(row.stock) }}</td>                       
                    </tr>
                    </tbody>
                </table>
            </div>            
        </div>
    </el-dialog>
</template>

<script>
    import {formaterDecimal} from '../../../helpers/functions'
    export default {
        props: ['showStockDialog', 'recordId'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'items',
                stock_details: []                
            }
        },
        created() {
            this.initForm()
            // this.$http.get(`/${this.resource}/stock_details`)
            //     .then(response => {
            //             this.stock_details = response.data.stock_details
            //         }
            //     )
        },
        methods: {
            initForm() {
                this.loading_submit = false,
                this.stock_details = []                
            }
            ,
            resetForm() {
                this.initForm()                
            }
            ,
            create() {
                this.titleDialog = 'Detalle Stock'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/stock_details/${this.recordId}`)
                        .then(response => {
                                this.stock_details = response.data.stock_details                               
                            }
                        )
                }
            },
            close() {
                this.$emit('update:showStockDialog', false)
                this.resetForm()
            },
            formaterDecimal(stock){
                return formaterDecimal(stock);
            }
        }
    }
</script>
