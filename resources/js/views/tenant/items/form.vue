<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" top="2vh" append-to-body>
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.internal_id}">
                            <label class="control-label">Código Interno</label>
                            <el-input v-model="form.internal_id" dusk="internal_id"></el-input>
                            <small class="form-control-feedback" v-if="errors.internal_id"
                                   v-text="errors.internal_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.unit_type_id}">
                            <label class="control-label">Unidad</label>
                            <el-select v-model="form.unit_type_id" dusk="unit_type_id">
                                <el-option v-for="option in unit_types" :key="option.id" :value="option.id"
                                           :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.unit_type_id"
                                   v-text="errors.unit_type_id[0]"></small>
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
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.item_code}">
                            <label class="control-label">Código Sunat</label>
                            <el-input v-model="form.item_code" dusk="item_code"></el-input>
                            <small class="form-control-feedback" v-if="errors.item_code"
                                   v-text="errors.item_code[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.item_code_gs1}">
                            <label class="control-label">Código GSL</label>
                            <el-input v-model="form.item_code_gs1" dusk="item_code_gs1"></el-input>
                            <small class="form-control-feedback" v-if="errors.item_code_gs1"
                                   v-text="errors.item_code_gs1[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                            <label class="control-label">Moneda</label>
                            <el-select v-model="form.currency_type_id" dusk="currency_type_id">
                                <el-option v-for="option in currency_types" :key="option.id" :value="option.id"
                                           :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.currency_type_id"
                                   v-text="errors.currency_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.stock_min}">
                            <label class="control-label">Stock Mínimo</label>
                            <el-input v-model="form.stock_min"></el-input>
                            <small class="form-control-feedback" v-if="errors.stock_min"
                                   v-text="errors.stock_min[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.sale_unit_price}">
                            <label class="control-label">Precio Unitario (Venta)</label>
                            <el-input v-model="form.sale_unit_price" dusk="sale_unit_price" @blur="changePrice()"></el-input>
                            <small class="form-control-feedback" v-if="errors.sale_unit_price"
                                   v-text="errors.sale_unit_price[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group" :class="{'has-danger': errors.sale_affectation_igv_type_id}">
                            <label class="control-label">Tipo de afectación (Venta)</label>
                            <el-select v-model="form.sale_affectation_igv_type_id">
                                <el-option v-for="option in affectation_igv_types" :key="option.id" :value="option.id"
                                           :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.sale_affectation_igv_type_id"
                                   v-text="errors.sale_affectation_igv_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.included_igv}">
                            <label class="control-label d-block">IGV incluido</label>
                            <el-checkbox v-model="form.included_igv" class="d-block"></el-checkbox>                          
                            <small class="form-control-feedback d-block" v-if="errors.included_igv" v-text="errors.included_igv[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.purchase_unit_price}">
                            <label class="control-label">Costo Unidad</label>
                            <el-input v-model="form.purchase_unit_price" dusk="purchase_unit_price"></el-input>
                            <small class="form-control-feedback" v-if="errors.purchase_unit_price"
                                   v-text="errors.purchase_unit_price[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.purchase_affectation_igv_type_id}">
                            <label class="control-label">Tipo de afectación (Compra)</label>
                            <el-select v-model="form.purchase_affectation_igv_type_id">
                                <el-option v-for="option in affectation_igv_types" :key="option.id" :value="option.id"
                                           :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.purchase_affectation_igv_type_id"
                                   v-text="errors.purchase_affectation_igv_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.icbper}">
                            <label class="control-label d-block">ICBPER (Impuesto a la bolsa)</label>
                            <el-checkbox v-model="form.icbper" class="d-block"></el-checkbox>
                            <small class="form-control-feedback d-block" v-if="errors.icbper" v-text="errors.icbper[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.trademark_id}">
                            <label class="control-label">Marca</label>
                            <el-select v-model="form.trademark_id" filterable>
                                <el-option :value="null" label="No selecccionado"></el-option>
                                <el-option v-for="option in trademarks" :key="option.id"
                                           :value="option.id" :label="option.name"
                                >
                                </el-option>

                            </el-select>
                            <small class="form-control-feedback" v-if="errors.trademark_id"
                                   v-text="errors.purchase_affectation_igv_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.item_category_id}">
                            <label class="control-label">Categoria</label>
                            <el-select v-model="form.item_category_id" filterable>
                                <el-option :value="null" label="No selecccionado"></el-option>
                                <template v-for="p_option in item_category">
                                    <el-option :key="p_option.id" :value="p_option.id" :label="p_option.description">
                                    </el-option>
                                    <el-option v-for="c_option in p_option.childrens"
                                               :key="c_option.id" :value="c_option.id"
                                               :label="`${p_option.description} > ${c_option.description}`">
                                    </el-option>
                                </template>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.item_category_id"
                                   v-text="errors.purchase_affectation_igv_type_id[0]"></small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h4>
                            Listado de precios <a data-toggle="collapse" href="#price_list" class="control-label font-weight-bold text-info"> [ + ]</a>
                        </h4>
                    </div>
                    <div class="col-md-12 collapse" id="price_list">
                        <div class="row">
                            <div class="col-8">
                                <h4>Nombre</h4>
                            </div>
                            <div class="col-4">
                                <h4>Valor</h4>
                            </div>
                        </div>
                        <div class="row pb-1" v-for="(row,index) in form.item_price_list" :key="row.id">
                            <div class="col-8">
                                {{ row.name }} <span v-if="row.type==1">(porcentaje {{ row.percentage }}%)</span>
                            </div>
                            <div class="col-4">
                                <el-input v-model.sync="row.value" v-if="row.type==1" readonly=""></el-input>
                                <el-input v-model.sync="row.value" v-if="row.type==2"></el-input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="recordId==null">
                <div class="row pt-3">
                    <div class="col-8">
                        <h4>Almacén</h4>
                    </div>
                    <div class="col-4">
                        <h4>Stock Inicial</h4>
                    </div>
                </div>
                <div class="row pb-1" v-for="(row,index) in form.item_warehouse" :key="row.id">
                    <div class="col-8">
                        {{ row.description }}
                    </div>
                    <div class="col-4">
                        <el-input v-model.sync="row.quantity"></el-input>
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
                list_price: [],
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
                        this.price_list = response.data.price_list
                        this.item_category = response.data.item_category
                    }
                )
        },
        methods: {
            clickAddRow() {
                this.form.item_price_list.push({
                    price_list_id: null,
                    value: null
                })
            },
            clickCancel(index) {
                this.form.item_price_list.splice(index, 1)
            },
            changePrice(){
                for (let i = 0; i < this.price_list.length; i++) {

                    if(this.price_list[i].type == 1)
                    {
                        this.form.item_price_list[i].value = this.form.sale_unit_price*(1-this.form.item_price_list[i].percentage/100)
                    }
                }
            },
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
                    included_igv: true,
                    has_isc: false,
                    system_isc_type_id: null,
                    percentage_isc: 0,
                    suggested_price: 0,
                    sale_affectation_igv_type_id: null,
                    purchase_affectation_igv_type_id: null,
                    item_warehouse: [],
                    stock: 0,
                    stock_min: 1,                    
                    item_price_list:[]
                }
            },
            resetForm() {
                this.initForm()
                this.form.sale_affectation_igv_type_id = (this.affectation_igv_types.length > 0) ? this.affectation_igv_types[0].id : null
                this.form.purchase_affectation_igv_type_id = (this.affectation_igv_types.length > 0) ? this.affectation_igv_types[0].id : null
            },
            create() {
                this.titleDialog = (this.recordId) ? 'Editar Producto' : 'Nuevo Producto'

                //item_warehouse
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data

                            let temp_item_price_list = [];

                            for (let i = 0; i < this.price_list.length; i++) {
                                let filter = {"price_list_id": this.price_list[i].id, "item_id": this.recordId};
                                let item = _.find(this.form.item_price_list, filter); 
                                let value = 0                               

                                if(item != undefined)
                                {
                                    value = item.value
                                }

                                if (typeof item === 'object') {
                                    temp_item_price_list.push({
                                        "price_list_id": this.price_list[i].id,
                                        "name": this.price_list[i].name,
                                        "type": this.price_list[i].type,
                                        "percentage": this.price_list[i].value,
                                        "value": value
                                    })
                                } else {
                                    temp_item_price_list.push({
                                        "price_list_id": this.price_list[i].id,
                                        "name": this.price_list[i].name,
                                        "type": this.price_list[i].type,
                                        "percentage": this.price_list[i].value,
                                        "value": value
                                    })
                                }
                            }
                            this.form.item_price_list = temp_item_price_list;

                            this.form.stock = 0;
                        })
                } else {
                    let temp_item_warehouse = [];
                    let temp_item_price_list = [];

                    for (let i = 0; i < this.warehouses.length; i++) {

                        temp_item_warehouse.push({
                            "warehouse_id": this.warehouses[i].id,
                            "description": this.warehouses[i].description,
                            "quantity": 0
                        })
                    }
                   
                    for (let i = 0; i < this.price_list.length; i++) {

                        temp_item_price_list.push({
                            "price_list_id": this.price_list[i].id,
                            "name": this.price_list[i].name,
                            "type": this.price_list[i].type,
                            "percentage": this.price_list[i].value,
                            "value": 0
                        })
                    }

                    this.form.item_warehouse = temp_item_warehouse;
                    this.form.item_price_list = temp_item_price_list;
                    this.form.stock = 0;
                }
            },
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
            },
            close() {
                this.$emit('update:showDialog', false)
                this.resetForm()
            }
        }
    }
</script>
