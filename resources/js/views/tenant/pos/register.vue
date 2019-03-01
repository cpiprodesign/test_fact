<template>

    <div class="container-fluid m-0 pb-0">
        <div class="row flex-row">
            <!--            articulos-->
            <div class="col-6">
                <div class="input-group input-group-lg border border-primary rounded">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-barcode"></i>
                        </span>
                    </div>

                    <input type="search" class="form-control" placeholder="Buscar productos">
                </div>

                <div class="mt-3 overflow-auto h-100 border border-primary rounded rounded-0" style="max-height: 77vh">
                    <div class="d-flex flex-row flex-wrap justify-content-around py-2">


                        <div v-for="option in items" class="card m-1 border border-success"
                             style="height: 30vh; width: 30vh">
                            <div class="card-header">{{option.full_description}}</div>
                            <div class="card-body p-2" style="width: 100%">
                                {{option}}
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!--            pago-->
            <div class="col-6">
                <div class="card mb-0">
                    <div class="card-header  bg-success p-1">
                        <h5 class="m-0 p-0">
                            <i class="fas fa-shopping-cart m-1 mr-2 p-0"></i>
                            Venta
                        </h5>
                    </div>
                    <div class="card-body table-responsive p-0 overflow-auto" style="height: 40vh">
                        <table class="table table-striped table-bordered table-sm m-0 p-0">
                            <thead class="thead-dark">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center w-50">Art.</th>
                                <th class="text-center">UN.</th>
                                <th class="text-center">PU.</th>
                                <th class="text-center">Sub-T.</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Act</th>
                            </tr>
                            </thead>
                            <tbody v-if="form.items.length > 0">
                            <tr v-for="(row, index) in form.items">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    {{ row }}
                                    {{ row.item.description }}
                                    <br/>
                                    <small>
                                        {{ row.affectation_igv_type.description }}/{{ row.item.unit_type_id }}
                                    </small>
                                </td>
                                <td class="text-right">{{ row.quantity }}</td>
                                <td class="text-right">{{ currency_type.symbol }} {{ row.unit_price }}</td>
                                <td class="text-right">{{ currency_type.symbol }} {{ row.total_value }}</td>
                                <td class="text-right">{{ currency_type.symbol }} {{ row.total }}</td>

                                <td class="text-center pl-0 pr-0">
                                    <div class="btn-group btn-group-xs">
                                        <button class="btn btn-danger btn-xs"
                                                @click.prevent="recalcItem(index,row.quantity--)">
                                            <i class="fas fa-minus-square"></i>
                                        </button>
                                        <button class="btn btn-success btn-xs"
                                                @click.prevent="recalcItem(index,row.quantity++)">
                                            <i class="fas fa-plus-square"></i>
                                        </button>
                                    </div>
                                    <button class="btn btn-warning btn-xs ">
                                        <i class="fas fa-pen-alt"></i>
                                    </button>
                                </td>


                                <!--                                <td class="text-right">{{ currency_type.symbol }} {{ row.total_value }}</td>-->
                                <!--<td class="text-right">{{ currency_type.symbol }} {{ row.total_charge }}</td>-->
                                <!--                                <td class="text-right">-->
                                <!--                                    <button type="button"-->
                                <!--                                            class="btn waves-effect waves-light btn-xs btn-danger"-->
                                <!--                                            >x-->
                                <!--                                    </button>-->
                                <!--                                </td>-->

                            </tr>
                            </tbody>

                        </table>
                    </div>
                    <!--                    <div class="card-footer d-flex bg-light-info flex-row flex-wrap justify-content-around"-->
                    <div class="card-footer bg-dark p-1" style="height: 44vh">
                        <div class="col-8">
                            <div class="form-group mb-1">

                                <label class="text-white">
                                    Documento
                                </label>

                                <div>
                                    <label
                                        v-for="option in document_types"
                                        class="btn btn-primary btn-xs mr-2 mb-1"
                                        v-bind:for="'dt'+option.id"
                                        :class="{'active':form.document_type_id==option.id}"
                                    >
                                        <input
                                            type="radio"
                                            @change="changeDocumentType"
                                            v-model="form.document_type_id"
                                            v-bind:value="option.id"
                                            v-bind:id="'dt'+option.id"
                                        />
                                        {{option.description}}
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mb-1">

                                <label class="text-white">
                                    Serie
                                </label>

                                <div>
                                    <label
                                        v-for="option in series"
                                        class="btn btn-info btn-xs mr-2 mb-1"
                                        v-bind:for="'idS'+option.id"
                                        :class="{'active':form.series_id==option.id}"
                                    >
                                        <input
                                            type="radio"
                                            @change="changeDocumentType"
                                            v-model="form.series_id"
                                            v-bind:value="option.id"
                                            v-bind:id="'idS'+option.id"
                                        />
                                        {{option.number}}
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mb-1">
                                <label class="control-label text-white">
                                    Cliente
                                    <a href="#" class="btn btn-xs btn-primary"
                                       @click.prevent="showDialogNewPerson = true">+</a>
                                </label>
                                <el-select v-model="form.customer_id" filterable
                                           class="border-left rounded-left border-info"
                                           popper-class="el-select-customers" dusk="customer_id">
                                    <el-option v-for="option in customers" :key="option.id" :value="option.id"
                                               :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.customer_id"
                                       v-text="errors.customer_id[0]"></small>
                            </div>

                        </div>
                    </div>


                </div>


            </div>
        </div>
        <!--    </div>-->

        <!--    <div class="card mb-0 pt-2 pt-md-0">-->
        <!-- <div class="card-header bg-info">
            <h3 class="my-0">Nuevo Comprobante</h3>
        </div> -->
        <div class="tab-content" v-if="loading_form">
            <div class="invoice">
                <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-lg-2 pb-2">

                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.establishment_id}">
                                    <label class="control-label">Establecimiento</label>
                                    <el-select v-model="form.establishment_id" @change="changeEstablishment">
                                        <el-option v-for="option in establishments" :key="option.id" :value="option.id"
                                                   :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.establishment_id"
                                           v-text="errors.establishment_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.operation_type_id}">
                                    <label class="control-label">Tipo Operación</label>
                                    <el-select v-model="form.operation_type_id" @change="changeOperationType">
                                        <el-option v-for="option in operation_types" :key="option.id" :value="option.id"
                                                   :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.operation_type_id"
                                           v-text="errors.operation_type_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">

                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                                    <label class="control-label">Moneda</label>
                                    <el-select v-model="form.currency_type_id" @change="changeCurrencyType">
                                        <el-option v-for="option in currency_types" :key="option.id" :value="option.id"
                                                   :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.currency_type_id"
                                           v-text="errors.currency_type_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.exchange_rate_sale}">
                                    <label class="control-label">Tipo de cambio
                                        <el-tooltip class="item" effect="dark" content="Valor obtenido de SUNAT"
                                                    placement="top-end">
                                            <i class="fa fa-info-circle"></i>
                                        </el-tooltip>
                                    </label>
                                    <el-input v-model="form.exchange_rate_sale"></el-input>
                                    <small class="form-control-feedback" v-if="errors.exchange_rate_sale"
                                           v-text="errors.exchange_rate_sale[0]"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1">

                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.purchase_order}">
                                    <label class="control-label">Orden Compra</label>
                                    <el-input v-model="form.purchase_order"></el-input>
                                    <small class="form-control-feedback" v-if="errors.purchase_order"
                                           v-text="errors.purchase_order[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                                    <label class="control-label">Fecha de emisión</label>
                                    <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd"
                                                    :clearable="false" @change="changeDateOfIssue"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_of_issue"
                                           v-text="errors.date_of_issue[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.date_of_due}">
                                    <label class="control-label full-text">Fecha de vencimiento</label>
                                    <label class="control-label short-text">F. vencimiento</label>
                                    <el-date-picker v-model="form.date_of_due" type="date" value-format="yyyy-MM-dd"
                                                    :clearable="false"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_of_due"
                                           v-text="errors.date_of_due[0]"></small>
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
                                            <td>{{ row.item.description }}<br/>
                                                <small>{{ row.affectation_igv_type.description }}</small>
                                            </td>
                                            <td class="text-center">{{ row.item.unit_type_id }}</td>
                                            <td class="text-right">{{ row.quantity }}</td>
                                            <td class="text-right">{{ currency_type.symbol }} {{ row.unit_price }}</td>
                                            <td class="text-right">{{ currency_type.symbol }} {{ row.total_value }}</td>
                                            <!--<td class="text-right">{{ currency_type.symbol }} {{ row.total_charge }}</td>-->
                                            <td class="text-right">{{ currency_type.symbol }} {{ row.total }}</td>
                                            <td class="text-right">
                                                <button type="button"
                                                        class="btn waves-effect waves-light btn-xs btn-danger"
                                                        @click.prevent="clickRemoveItem(index)">x
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6 d-flex align-items-end">
                                <div class="form-group">
                                    <button type="button" class="btn waves-effect waves-light btn-primary"
                                            @click.prevent="showDialogAddItem = true">+ Agregar Producto
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <p class="text-right" v-if="form.total_exportation > 0">OP.EXPORTACIÓN: {{
                                    currency_type.symbol }} {{ form.total_exportation }}</p>
                                <p class="text-right" v-if="form.total_free > 0">OP.GRATUITAS: {{ currency_type.symbol
                                    }} {{ form.total_free }}</p>
                                <p class="text-right" v-if="form.total_unaffected > 0">OP.INAFECTAS: {{
                                    currency_type.symbol }} {{ form.total_unaffected }}</p>
                                <p class="text-right" v-if="form.total_exonerated > 0">OP.EXONERADAS: {{
                                    currency_type.symbol }} {{ form.total_exonerated }}</p>
                                <p class="text-right" v-if="form.total_taxed > 0">OP.GRAVADA: {{ currency_type.symbol }}
                                    {{ form.total_taxed }}</p>
                                <p class="text-right" v-if="form.total_igv > 0">IGV: {{ currency_type.symbol }} {{
                                    form.total_igv }}</p>
                                <h3 class="text-right" v-if="form.total > 0"><b>TOTAL A PAGAR: </b>{{
                                    currency_type.symbol }} {{ form.total }}</h3>
                            </div>
                        </div>

                    </div>
                    <div class="form-actions text-right mt-4">
                        <el-button @click.prevent="close()">Cancelar</el-button>
                        <el-button class="submit" type="primary" native-type="submit" :loading="loading_submit"
                                   v-if="form.items.length > 0">Generar
                        </el-button>
                    </div>
                </form>
            </div>
        </div>

        <document-form-item :showDialog.sync="showDialogAddItem"
                            :operation-type-id="form.operation_type_id"
                            :currency-type-id-active="form.currency_type_id"
                            :exchange-rate-sale="form.exchange_rate_sale"
                            @add="addRow"></document-form-item>

        <person-form :showDialog.sync="showDialogNewPerson"
                     type="customers"
                     :external="true"
                     :document_type_id=form.document_type_id></person-form>

        <document-options :showDialog.sync="showDialogOptions"
                          :recordId="documentNewId"
                          :showClose="false"></document-options>
    </div>
</template>

<script>
    import DocumentFormItem from './partials/item.vue'
    import PersonForm from '../persons/form.vue'
    import DocumentOptions from './partials/options.vue'
    import {functions, exchangeRate} from '../../../mixins/functions'
    import {calculateRowItem} from '../../../helpers/functions'
    import Logo from '../companies/logo.vue'

    export default {
        components: {DocumentFormItem, PersonForm, DocumentOptions, Logo},
        mixins: [functions, exchangeRate],
        data() {
            return {
                resource: 'documents',
                showDialogAddItem: false,
                showDialogNewPerson: false,
                showDialogOptions: false,
                loading_submit: false,
                loading_form: false,
                errors: {},
                form: {},
                document_types: [],
                currency_types: [],
                discount_types: [],
                charges_types: [],
                all_customers: [],
                customers: [],
                company: null,
                document_type_03_filter: null,
                operation_types: [],
                establishments: [],
                establishment: null,
                all_series: [],
                series: [],
                currency_type: {},
                documentNewId: null,

                items: []
            }
        },
        async created() {
            await this.initForm()
            await this.$http.post(`/dispatches/tables`).then(response => {
                this.items = response.data.items;
            });
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.document_types = response.data.document_types_invoice
                    this.currency_types = response.data.currency_types
                    this.establishments = response.data.establishments
                    this.operation_types = response.data.operation_types
                    this.all_series = response.data.series
                    this.all_customers = response.data.customers
                    this.discount_types = response.data.discount_types
                    this.charges_types = response.data.charges_types
                    this.company = response.data.company
                    this.document_type_03_filter = response.data.document_type_03_filter

                    this.form.currency_type_id = (this.currency_types.length > 0) ? this.currency_types[0].id : null
                    this.form.establishment_id = (this.establishments.length > 0) ? this.establishments[0].id : null
                    this.form.document_type_id = (this.document_types.length > 0) ? this.document_types[0].id : null
                    this.form.operation_type_id = (this.operation_types.length > 0) ? this.operation_types[0].id : null

                    this.changeEstablishment()
                    this.changeDateOfIssue()
                    this.changeDocumentType()
                    this.changeCurrencyType()
                })
            this.loading_form = true
            this.$eventHub.$on('reloadDataPersons', (customer_id) => {
                this.reloadDataCustomers(customer_id)
            })
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    establishment_id: null,
                    document_type_id: null,
                    series_id: null,
                    number: '#',
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    time_of_issue: moment().format('HH:mm:ss'),
                    customer_id: null,
                    currency_type_id: null,
                    purchase_order: null,
                    exchange_rate_sale: 0,
                    total_prepayment: 0,
                    total_charge: 0,
                    total_discount: 0,
                    total_exportation: 0,
                    total_free: 0,
                    total_taxed: 0,
                    total_unaffected: 0,
                    total_exonerated: 0,
                    total_igv: 0,
                    total_base_isc: 0,
                    total_isc: 0,
                    total_base_other_taxes: 0,
                    total_other_taxes: 0,
                    total_taxes: 0,
                    total_value: 0,
                    total: 0,
                    operation_type_id: null,
                    date_of_due: moment().format('YYYY-MM-DD'),
                    items: [],
                    charges: [],
                    discounts: [],
                    attributes: [],
                    guides: [],
                    actions: {
                        format_pdf: 'a4',
                    }
                }
            },
            resetForm() {
                this.initForm()
                this.form.currency_type_id = (this.currency_types.length > 0) ? this.currency_types[0].id : null
                this.form.establishment_id = (this.establishments.length > 0) ? this.establishments[0].id : null
                this.form.document_type_id = (this.document_types.length > 0) ? this.document_types[0].id : null
                this.form.operation_type_id = (this.operation_types.length > 0) ? this.operation_types[0].id : null
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
                this.form.date_of_due = this.form.date_of_issue
                this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                    this.form.exchange_rate_sale = response
                })
            },
            filterSeries() {
                this.form.series_id = null
                this.series = _.filter(this.all_series, {
                    'establishment_id': this.form.establishment_id,
                    'document_type_id': this.form.document_type_id
                })
                this.form.series_id = (this.series.length > 0) ? this.series[0].id : null
            },
            filterCustomers() {
                this.form.customer_id = null
                if (this.form.document_type_id === '01') {
                    this.customers = _.filter(this.all_customers, {'identity_document_type_id': '6'})
                } else {
                    if (this.document_type_03_filter) {
                        this.customers = _.filter(this.all_customers, (c) => {
                            return c.identity_document_type_id !== '6'
                        })
                    } else {
                        this.customers = this.all_customers
                    }
                }
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

                this.form.total_exportation = _.round(total_exportation, 2)
                this.form.total_taxed = _.round(total_taxed, 2)
                this.form.total_exonerated = _.round(total_exonerated, 2)
                this.form.total_unaffected = _.round(total_unaffected, 2)
                this.form.total_free = _.round(total_free, 2)
                this.form.total_igv = _.round(total_igv, 2)
                this.form.total_value = _.round(total_value, 2)
                this.form.total_taxes = _.round(total_igv, 2)
                this.form.total = _.round(total, 2)
            },
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form).then(response => {
                    console.log(response);

                    if (response.data.success) {
                        this.resetForm();

                        this.documentNewId = response.data.data.id;
                        this.showDialogOptions = true;
                    } else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    } else {
                        this.$message.error(error.response.data.message);
                    }
                }).then(() => {
                    this.loading_submit = false;
                });
            },
            close() {
                location.href = '/documents'
            },
            reloadDataCustomers(customer_id) {
                this.$http.get(`/${this.resource}/table/customers`).then((response) => {
                    this.customers = response.data
                    this.form.customer_id = customer_id
                })
            },
            recalcItem(index, value) {

                // let Items = _.clone(this.form.items);
                // Items[index].quantity = value;
                // Items[index].total_value = _.round(Items[index].unit_price * value, 2);
                // Items[index].total = _.round(Items[index].unit_value * value, 2);
                //
                // console.info(this.form.items)
                //
                //
                // this.form.items = Items;
                // this.calculateTotal();

            }
        }
    }
</script>
