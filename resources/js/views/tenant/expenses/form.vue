<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" top="2vh" append-to-body>
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.has_voucher}">
                            <div class="form-group">
                                <el-radio v-model="form.has_voucher" label="0">Sin comprobante</el-radio>
                                <el-radio v-model="form.has_voucher" label="1">Con comprobante</el-radio>
                            </div>
                            <small class="form-control-feedback" v-if="errors.has_voucher" v-text="errors.has_voucher[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                            <label class="control-label">Fecha de emisión</label>
                            <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label class="control-label">Descripción <span class="text-danger">*</span></label>
                            <el-input v-model="form.description" dusk="description"></el-input>
                            <small class="form-control-feedback" v-if="errors.description"
                                   v-text="errors.description[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    
                </div>
                <div class="row">                    
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.stock_min}">
                            <label class="control-label">Monto</label>
                            <el-input v-model="form.stock_min"></el-input>
                            <small class="form-control-feedback" v-if="errors.stock_min"
                                   v-text="errors.stock_min[0]"></small>
                        </div>
                    </div>
                    
                </div>
                
            </div>           

            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

    export default {
        props: ['showDialog', 'recordId', 'external'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'items',
                errors: {},
                form: {},
                unit_types: [],
                currency_types: [],
                system_isc_types: [],
                affectation_igv_types: [],
                warehouses: [],

                trademarks: [],
                item_category: []
            }
        },
        created() {
            this.initForm()
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                        this.unit_types = response.data.unit_types
                        this.currency_types = response.data.currency_types
                        this.system_isc_types = response.data.system_isc_types
                        this.affectation_igv_types = response.data.affectation_igv_types

                        this.form.stock = 0

                        this.form.sale_affectation_igv_type_id = (this.affectation_igv_types.length > 0) ? this.affectation_igv_types[0].id : null
                        this.form.purchase_affectation_igv_type_id = (this.affectation_igv_types.length > 0) ? this.affectation_igv_types[0].id : null

                        this.warehouses = response.data.warehouses;
                        this.trademarks = response.data.trademarks
                        this.item_category = response.data.item_category
                    }
                )
        },
        methods: {
            initForm() {
                this.loading_submit = false,
                    this.errors = {}
                this.form = {
                    id: null,
                    item_type_id: '01',

                    trademark_id: null,
                    item_category_id: null,

                    internal_id: null,
                    item_code: null,
                    item_code_gs1: null,
                    description: null,
                    unit_type_id: 'NIU',
                    currency_type_id: 'PEN',
                    sale_unit_price: 0,
                    purchase_unit_price: 0,
                    has_isc: false,
                    system_isc_type_id: null,
                    percentage_isc: 0,
                    suggested_price: 0,
                    sale_affectation_igv_type_id: null,
                    purchase_affectation_igv_type_id: null,

                    item_warehouse: [],

                    stock: 0,
                    stock_min: 1,
                }
            }
            ,
            resetForm() {
                this.initForm()
                this.form.sale_affectation_igv_type_id = (this.affectation_igv_types.length > 0) ? this.affectation_igv_types[0].id : null
                this.form.purchase_affectation_igv_type_id = (this.affectation_igv_types.length > 0) ? this.affectation_igv_types[0].id : null
            }
            ,
            create() {
                this.titleDialog = (this.recordId) ? 'Editar Gasto' : 'Nuevo Gasto'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                                this.form = response.data.data

                                let temp_item_warehouse = [];

                                for (let i = 0; i < this.warehouses.length; i++) {
                                    let filter = {"warehouse_id": this.warehouses[i].id, "item_id": this.recordId};
                                    let item = _.find(this.form.item_warehouse, filter);

                                    if (typeof item === 'object') {
                                        temp_item_warehouse.push({
                                            "warehouse_id": this.warehouses[i].id,
                                            "description": this.warehouses[i].description,
                                            "item_id": this.recordId,
                                            "quantity": item.stock
                                        })
                                    } else {
                                        temp_item_warehouse.push({
                                            "warehouse_id": this.warehouses[i].id,
                                            "description": this.warehouses[i].description,
                                            "item_id": this.recordId,
                                            "quantity": 0
                                        })
                                    }
                                }
                                this.form.item_warehouse = temp_item_warehouse;
                                this.form.stock = 0;
                                temp_item_warehouse = [];
                            }
                        )
                } else {
                    let temp_item_warehouse = [];

                    for (let i = 0; i < this.warehouses.length; i++) {

                        temp_item_warehouse.push({
                            "warehouse_id": this.warehouses[i].id,
                            "description": this.warehouses[i].description,
                            // "item_id": this.recordId,
                            "quantity": 0
                        })
                    }
                    this.form.item_warehouse = temp_item_warehouse;
                    this.form.stock = 0;
                    temp_item_warehouse = [];
                }
            }
            ,
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            if (this.external) {
                                this.$eventHub.$emit('reloadDataItems', response.data.id)
                            } else {
                                this.$eventHub.$emit('reloadData')
                            }
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            }
            ,
            close() {
                this.$emit('update:showDialog', false)
                this.resetForm()
            }
            ,
            changeHasIsc() {
                this.form.system_isc_type_id = null
                this.form.percentage_isc = 0
                this.form.suggested_price = 0
            }
            ,
            changeSystemIscType() {
                if (this.form.system_isc_type_id !== '03') {
                    this.form.suggested_price = 0
                }
            }
        }
    }
</script>
