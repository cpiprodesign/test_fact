<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0>">Editar Nota de Venta</h3>
        </div>
        <div class="tab-content" v-if="loading_form">
            <div class="invoice">
               <!-- <header class="clearfix">
                    <div class="row">
                        <div class="col-sm-2 text-center mt-3 mb-0">
                            <logo url="/" :path_logo="(company.logo != null) ? `/storage/uploads/logos/${company.logo}` : ''" ></logo>
                        </div>
                        <div class="col-sm-10 text-left mt-3 mb-0">
                            <address class="ib mr-2" >
                                <span class="font-weight-bold">{{company.name}}</span>
                                <br>
                                <div v-if="establishment.address != '-'">{{ establishment.address }}, </div> {{ establishment.district.description }}, {{ establishment.province.description }}, {{ establishment.department.description }} - {{ establishment.country.description }}
                                <br>
                                {{establishment.email}} - <span v-if="establishment.telephone != '-'"></span>
                            </address>
                        </div>
                    </div>
                </header> -->
                <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-lg-3 pb-2">
                                <div class="form-group" :class="{'has-danger': errors.document_type_id}">
                                    <label class="control-label font-weight-bold text-info">Tipo de documento</label>
                                    <el-select v-model="form.document_type_id" @change="changeDocumentType" popper-class="el-select-document_type" dusk="document_type_id" class="border-left rounded-left border-info">
                                        <el-option v-for="option in document_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.document_type_id" v-text="errors.document_type_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group" :class="{'has-danger': errors.establishment_id}">
                                    <label class="control-label">Establecimiento</label>
                                    <el-select v-model="form.establishment_id" @change="changeEstablishment">
                                        <el-option v-for="option in establishments" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.establishment_id" v-text="errors.establishment_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.series_id}">
                                    <label class="control-label">Serie</label>
                                    <el-select v-model="form.series_id">
                                        <el-option v-for="option in series" :key="option.id" :value="option.id" :label="option.number"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.series_id" v-text="errors.series_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                                    <label class="control-label">Fecha de emisión</label>
                                    <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-lg-6 pb-2">
                                <div class="form-group" :class="{'has-danger': errors.customer_id}">
                                    <label class="control-label font-weight-bold text-info">
                                        Cliente
                                    </label>
                                    <el-select v-model="form.customer_id" filterable class="border-left rounded-left border-info" popper-class="el-select-customers" dusk="customer_id">
                                        <el-option v-for="option in customers" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.customer_id" v-text="errors.customer_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                                    <label class="control-label">Moneda</label>
                                    <el-select v-model="form.currency_type_id" @change="changeCurrencyType">
                                        <el-option v-for="option in currency_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.currency_type_id" v-text="errors.currency_type_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.exchange_rate_sale}">
                                    <label class="control-label">Tipo de cambio</label>
                                    <el-input v-model="form.exchange_rate_sale"></el-input>
                                    <small class="form-control-feedback" v-if="errors.exchange_rate_sale" v-text="errors.exchange_rate_sale[0]"></small>
                                </div>
                            </div>
                        </div>
                         <div class="row mt-1">
                            <div class="col-lg-3">
                                <div class="form-group" :class="{'has-danger': errors.warehouse_id}">
                                    <label class="control-label">Almacén</label>
                                    <el-select v-model="form.warehouse_id">
                                        <el-option v-for="option in warehouses" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.warehouse_id" v-text="errors.warehouse_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.status_paid}">
                                    <label class="control-label">Estado de pago</label>
                                    <el-select v-model="form.status_paid">
                                        <el-option v-for="option in status_paid" :key="option.id" :value="option.id" :label="option.nombre"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.status_paid" v-text="errors.status_paid"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1" v-show="form.status_paid == 1">
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.payment_method_id}">
                                    <label class="control-label">Método de Pago</label>
                                    <el-select v-model="pay_data.payment_method_id">
                                        <el-option v-for="option in payment_methods" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.payment_method_id" v-text="errors.payment_method_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" :class="{'has-danger': errors.account_id}">
                                    <label class="control-label">Cuenta Bancaria <span class="text-danger">*</span></label>
                                    <el-select v-model="pay_data.account_id" filterable>
                                        <el-option v-for="option in accounts" :key="option.id" :value="option.id" :label="option.name+' | '+option.account_type.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.account_id" v-text="errors.account_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group" :class="{'has-danger': errors.total}">
                                    <label class="control-label">Valor recibido<span class="text-danger">*</span></label>
                                    <el-input v-model="pay_data.total"></el-input>
                                    <small class="form-control-feedback" v-if="errors.total" v-text="errors.total[0]"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="font-weight-bold">Descripción</th>
                                                <th class="text-center font-weight-bold">Unidad</th>
                                                <th class="text-right font-weight-bold">Cantidad</th>
                                                <th class="text-right font-weight-bold">Precio Unitario</th>
                                                <th class="text-right font-weight-bold">Subtotal</th>
                                                <!--<th class="text-right font-weight-bold">Cargo</th>-->
                                                <th class="text-right font-weight-bold">Total</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="form.items.length > 0">
                                            <tr v-for="(row, index) in form.items">
                                                <td>{{ index + 1 }}</td>
                                                <td>{{ row.item.description }}<br/><small>{{ row.affectation_igv_type.description }}</small></td>
                                                <td class="text-center">{{ row.item.unit_type_id }}</td>
                                                <td class="text-right">{{ row.quantity }}</td>
                                                <td class="text-right">{{ currency_type.symbol }} {{ formaterNumber(row.unit_price, decimal) }}</td>
                                                <td class="text-right">{{ currency_type.symbol }} {{ formaterNumber(row.total_value) }}</td>
                                                <td class="text-right">{{ currency_type.symbol }} {{ formaterNumber(row.total) }}</td>
                                                <td class="text-right">
                                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickRemoveItem(index)">x</button>
                                                </td>
                                            </tr>
                                            <tr><td colspan="8"></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                                <div class="col-lg-12 col-md-6 d-flex align-items-end">
                                    <div class="form-group">
                                        <button type="button" class="btn waves-effect waves-light btn-primary" @click.prevent="showDialogAddItem = true">+ Agregar Producto</button>
                                    </div>
                                </div>
                            <div class="col-md-12">
                                <p class="text-right" v-if="form.total_exportation > 0">OP.EXPORTACIÓN: {{ currency_type.symbol }} {{ form.total_exportation }}</p>
                                <p class="text-right" v-if="form.total_free > 0">OP.GRATUITAS: {{ currency_type.symbol }} {{ form.total_free }}</p>
                                <p class="text-right" v-if="form.total_unaffected > 0">OP.INAFECTAS: {{ currency_type.symbol }} {{ form.total_unaffected }}</p>
                                <p class="text-right" v-if="form.total_exonerated > 0">OP.EXONERADAS: {{ currency_type.symbol }} {{ form.total_exonerated }}</p>
                                <p class="text-right" v-if="form.total_taxed > 0">OP.GRAVADA: {{ currency_type.symbol }} {{ form.total_taxed }}</p>
                                <p class="text-right" v-if="form.total_igv > 0">IGV: {{ currency_type.symbol }} {{ form.total_igv }}</p>
                                <h3 class="text-right" v-if="form.total > 0"><b>TOTAL A PAGAR: </b>{{ currency_type.symbol }} {{ form.total }}</h3>
                            </div>
                        </div>

                    </div>
                    <div class="form-actions text-right mt-4">
                        <el-button @click.prevent="close()">Cancelar</el-button>
                        <el-button class="submit" type="primary" native-type="submit" :loading="loading_submit" v-if="form.items.length > 0">Generar</el-button>
                    </div>
                </form>
            </div>
        </div>

        <sale-note-form-item :showDialog.sync="showDialogAddItem"
                           :operation-type-id="form.operation_type_id"
                           :currency-type-id-active="form.currency_type_id"
                           :exchange-rate-sale="form.exchange_rate_sale"
                           @add="addRow"></sale-note-form-item>

        <person-form :showDialog.sync="showDialogNewPerson"
                       type="customers"
                       :external="true"
                       :document_type_id = form.document_type_id></person-form>

        <sale-note-options :showDialog.sync="showDialogOptions"
                          :recordId="documentNewId"
                          :showClose="false"></sale-note-options>
    </div>
</template>

<script>
    import SaleNoteFormItem from './partials/item.vue'
    import PersonForm from '../persons/form.vue'
    import SaleNoteOptions from './partials/options.vue'
    import {functions, exchangeRate} from '../../../mixins/functions'
    import {calculateRowItem, formaterNumber} from '../../../helpers/functions'
    import Logo from '../companies/logo.vue'

    export default {
        props:['sale_note'],
        components: {SaleNoteFormItem, PersonForm, SaleNoteOptions, Logo},
        mixins: [functions, exchangeRate],
        data() {
            return {
                resource: 'sale-notes',
                showDialogAddItem: false,
                showDialogNewPerson: false,
                showDialogOptions: false,
                loading_submit: false,
                loading_form: false,
                errors: {},
                form: {},
                pay_data: {},
                document_types: [],
                currency_types: [],
                discount_types: [],
                charges_types: [],
                all_customers: [],
                payment_methods: [],
                accounts: [],
                customers: [],
                status_paid: [
                    {"id": "1", "nombre": "Pagado"}, 
                    {"id": "0", "nombre": "Pendiente"}
                ], 
                company: null,
                establishments: [],
                warehouses: [],
                establishment: null,
                all_series: [],
                series: [],
                currency_type: {},
                documentNewId: null,
                decimal: 2
            }
        },
        async created() {
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.warehouses = response.data.warehouses
                    this.document_types = response.data.document_types_invoice
                    this.currency_types = response.data.currency_types
                    this.establishments = response.data.establishments
                    this.operation_types = response.data.operation_types
                    this.all_series = response.data.series
                    this.all_customers = response.data.customers
                    this.company = response.data.company
                    this.document_type_03_filter = response.data.document_type_03_filter
                    this.payment_methods = response.data.payment_methods
                    this.accounts = response.data.accounts
                    this.decimal = response.data.decimal;
                    this.form.warehouse_id = response.data.warehouse_id
                    this.form.currency_type_id = (this.currency_types.length > 0)?this.currency_types[0].id:null
                    this.form.establishment_id = (this.establishments.length > 0)?this.establishments[0].id:null
                    this.form.document_type_id = (this.document_types.length > 0)?this.document_types[0].id:null
                    
                    this.changeEstablishment()
                    this.changeDateOfIssue()
                    this.changeDocumentType()
                    this.changeCurrencyType()

                })

                await this.initForm()

                
                await this.$http.get(`/${this.resource}/item/tables2/${this.sale_note.id}`)
                    .then(response => {
                        this.form.items = response.data.items
                    // this.form.total_exportation = response.data.purchase.total_exportation
                        this.all_affectation_igv_types = response.data.affectation_igv_types
                        this.system_isc_types = response.data.system_isc_types
                        this.discount_types = response.data.discount_types
                        this.charge_types = response.data.charge_types
                        this.attribute_types = response.data.attribute_types
                })

             this.calculateTotal()
            this.loading_form = true

        },
        methods: {
            initForm() {
                let me = this
                me.errors = {}
                me.form = {
                    sale_note_id: me.sale_note.id,
                    establishment_id: me.sale_note.establishment_id,
                    warehouse_id: me.sale_note.warehouse_id,
                    document_type_id: me.sale_note.document_type_id,
                    series_id: me.sale_note.series,
                    series: me.sale_note.series,
                    number: me.sale_note.number,
                    date_of_issue: me.sale_note.date_of_issue,
                    time_of_issue: me.sale_note.time_of_issue,
                    customer_id: me.sale_note.customer_id,
                    currency_type_id: me.sale_note.currency_type_id,
                    exchange_rate_sale: 0,
                    total_prepayment: me.sale_note.total_prepayment,
                    total_charge: me.sale_note.total_charge,
                    total_discount: me.sale_note.total_discount,
                    total_exportation: me.sale_note.total_exportation,
                    total_free: me.sale_note.total_free,
                    total_taxed: me.sale_note.total_taxed,
                    total_unaffected: me.sale_note.total_unaffected,
                    total_exonerated: me.sale_note.total_exonerated,
                    total_igv: me.sale_note.total_igv,
                    total_base_isc: me.sale_note.total_base_isc,
                    total_isc: me.sale_note.total_isc,
                    total_base_other_taxes: me.sale_note.total_other_taxes,
                    total_other_taxes: me.sale_note.total_other_taxes,
                    total_taxes: me.sale_note.total_taxes,
                    total_value: me.sale_note.total_value,
                    total: me.sale_note.total,
                    date_of_due: moment().format('YYYY-MM-DD'),
                    items: [],
                    actions: {
                        format_pdf:'a4',
                    },
                    status_paid: (  me.sale_note.total_paid > 0)?"1":"0",
                },

                me.pay_data = {
                    payment_method_id: 1,
                    account_id: 1,
                    total:  me.sale_note.total_paid
                }

                if(me.sale_note.payment){
                    me.pay_data.payment_method_id = me.sale_note.payment.payment_method_id
                    me.pay_data.account_id = me.sale_note.payment.account_id
                }
            },
            resetForm() {
                this.initForm()
                this.form.currency_type_id = (this.currency_types.length > 0)?this.currency_types[0].id:null
                this.form.establishment_id = (this.establishments.length > 0)?this.establishments[0].id:null
                this.form.document_type_id = (this.document_types.length > 0)?this.document_types[0].id:null
                this.changeEstablishment()
                this.changeDocumentType()
                this.changeDateOfIssue()
                this.changeCurrencyType()
            },
            changeOperationType() {

            },
            changeEstablishment() {
                this.establishment = _.find(this.establishments, {'id': this.form.establishment_id})
                this.filterSeries()
            },
            changeDocumentType() {
                this.filterSeries()
                this.filterCustomers()
            },
            changeDateOfIssue() {
                this.form.date_of_due = this.form.date_of_issue.substring(0,10) 
                this.searchExchangeRateByDate(this.form.date_of_issue.substring(0,10) ).then(response => {
                    this.form.exchange_rate_sale = response
                })
            },
            filterSeries() {
                this.form.series_id = null
                this.series = _.filter(this.all_series, {'establishment_id': this.form.establishment_id,
                                                         'document_type_id': this.form.document_type_id})
                this.form.series_id = (this.series.length > 0)?this.series[0].id:null 
            },
            filterCustomers() {
                this.form.customer_id = null
                this.customers = this.all_customers
            },
            addRow(row) {
                this.form.items.push(row)
                this.calculateTotal()
            },
            clickRemoveItem(index) {
                this.form.items.splice(index, 1)
                this.calculateTotal()
            },
            changeCurrencyType() {
                this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})
                let items = []
                this.form.items.forEach((row) => {
                    items.push(calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale))
                });
                this.form.items = items
                this.calculateTotal()
            },
            calculateTotal() {
                let total_discount = 0
                let total_charge = 0
                let total_exportation = 0
                let total_taxed = 0
                let total_exonerated = 0
                let total_unaffected = 0
                let total_free = 0
                let total_igv = 0
                let total_value = 0
                let total = 0
                this.form.items.forEach((row) => {
                    total_discount += parseFloat(row.total_discount)
                    total_charge += parseFloat(row.total_charge)

                    if (row.affectation_igv_type_id === '10') {
                        total_taxed += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '20') {
                        total_exonerated += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '30') {
                        total_unaffected += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '40') {
                        total_exportation += parseFloat(row.total_value)
                    }
                    if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) < 0) {
                        total_free += parseFloat(row.total_value)
                    }
                    if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) > -1) {
                        total_igv += parseFloat(row.total_igv)
                        total += parseFloat(row.total)
                    }
                    total_value += parseFloat(row.total_value)
                });

                this.form.total_exportation = formaterNumber(total_exportation)
                this.form.total_taxed = formaterNumber(total_taxed)
                this.form.total_exonerated = formaterNumber(total_exonerated)
                this.form.total_unaffected = formaterNumber(total_unaffected)
                this.form.total_free = formaterNumber(total_free)
                this.form.total_igv = formaterNumber(total_igv)
                this.form.total_value = formaterNumber(total_value)
                this.form.total_taxes = formaterNumber(total_igv)
                this.form.total = formaterNumber(total)
             },
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}/update/${this.sale_note.id}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.documentNewId = response.data.data.id;
                            if(this.form.status_paid == 1){
                                this.pay_data.sale_note_id = this.documentNewId;
                                this.pay_data.date_of_issue = this.form.date_of_issue;
                                this.pay_data.customer_id = this.form.customer_id;
                                this.pay_data.currency_type_id = this.form.currency_type_id;
                                this.pay_data.total_debt = this.form.total;

                                this.$http.post(`/payments`, this.pay_data)
                                .then(response => {
                                    if (response.data.success) {
                                        this.resetForm();
                                        this.showDialogOptions = true;
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
                            } else{
                                this.resetForm();
                                this.showDialogOptions = true;
                            }  
                        }else {
                            this.$message.error(response.data.message);
                        }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    }
                    else {
                        this.$message.error(error.response.data.message);
                    }
                }).then(() => {
                    this.loading_submit = false;
                });
            },
            close() {
                location.href = '/sale-notes'
            },
            reloadDataCustomers(customer_id) {
                this.$http.get(`/${this.resource}/table/customers`).then((response) => {
                    this.customers = response.data
                    this.form.customer_id = customer_id
                })
            },
            formaterNumber(value, decimal){
                return formaterNumber(value, decimal);
            }
        }
    }
</script>