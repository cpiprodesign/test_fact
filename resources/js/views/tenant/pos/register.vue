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

                    <input type="text" class="form-control" placeholder="Buscar productos" v-model="searchBox"
                           @keyup.enter="enterAddItem">
                    <button type="button" class="btn" @click.prevent="showDialogNewItem=!showDialogNewItem">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <div class="mt-3 overflow-auto h-100 border border-primary rounded rounded-0" style="max-height: 77vh">
                    <div class="d-flex flex-row flex-wrap p-2">

                        <div v-for="option in findItem" class="card m-1 btn-outline-success "
                             style="width: 32%; cursor: pointer"
                             @click.prevent="selectItem(option.id)"
                        >
                            <div class="card-header p-1 bg-success text-white">{{option.internal_id}}</div>
                            <div class="card-body p-1 text-dark" style="width: 100%">
                                <span class="font-weight-blod">{{option.description}}</span> <br>
                                <small>{{option.unit_type_id}}</small>
                                <br>
                                <span class="font-weight-bold text-primary">
                                    {{option.currency_type_symbol}} {{ calculateIgv(option.included_igv, option.sale_affectation_igv_type_id, option.sale_unit_price) }}
                                </span>
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
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-xs btn-danger"
                                            @click.prevent="clickRemoveItem(index)">x
                                    </button>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-dark" style="height: 44vh">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                                    <label class="control-label text-white">Moneda</label>
                                    <el-select v-model="form.currency_type_id" @change="changeCurrencyType">
                                        <el-option v-for="option in currency_types" :key="option.id" :value="option.id"
                                                   :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.currency_type_id"
                                           v-text="errors.currency_type_id[0]"></small>
                                </div>
                                <div class="form-group" :class="{'has-danger': errors.exchange_rate_sale}">
                                    <label class="control-label text-white">Tipo de cambio
                                        <el-tooltip class="item" effect="dark" content="Valor obtenido de SUNAT"
                                                    placement="top-end">
                                            <i class="fa fa-info-circle"></i>
                                        </el-tooltip>
                                    </label>
                                    <el-input v-model="form.exchange_rate_sale"></el-input>
                                    <small class="form-control-feedback" v-if="errors.exchange_rate_sale"
                                           v-text="errors.exchange_rate_sale[0]"></small>
                                </div>

                                <div class="form-group">
                                    <button type="button" class="btn waves-effect waves-light btn-primary btn-xs"
                                            @click.prevent="showDialogAddItem = true">+ Agregar Producto Detallado
                                    </button>
                                </div>

                            </div>
                            <div class="col-6 pt-3">
                                <p class="text-right text-white mb-1" v-if="form.total_exportation > 0">
                                    OP.EXPORTACIÓN: {{ currency_type.symbol }} {{ form.total_exportation }}
                                </p>
                                <p class="text-right text-white mb-1" v-if="form.total_free > 0">
                                    OP.GRATUITAS: {{ currency_type.symbol}} {{ form.total_free }}
                                </p>
                                <p class="text-right text-white mb-1" v-if="form.total_unaffected > 0">
                                    OP.INAFECTAS: {{ currency_type.symbol }} {{ form.total_unaffected }}
                                </p>
                                <p class="text-right text-white mb-1" v-if="form.total_exonerated > 0">
                                    OP.EXONERADAS: {{ currency_type.symbol }} {{ form.total_exonerated }}
                                </p>
                                <p class="text-right text-white mb-1" v-if="form.total_taxed > 0">
                                    OP.GRAVADA: {{ currency_type.symbol }} {{ form.total_taxed }}
                                </p>
                                <p class="text-right text-white mb-1" v-if="form.total_igv > 0">
                                    IGV: {{ currency_type.symbol }} {{ form.total_igv }}
                                </p>
                                <h4 class="text-right text-white mb-1" v-if="form.total > 0">
                                    <b>TOTAL A PAGAR: </b>{{ currency_type.symbol }} {{ form.total }}
                                </h4>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col-3">
                                <el-button class="btn btn-danger btn-lg btn-block" type="danger"
                                           @click.prevent="close">
                                    Cancelar
                                </el-button>
                            </div>
                            <div class="col-9">
                                <el-button class="btn btn-submit btn-lg btn-block" type="primary" native-type="submit"
                                           @click.prevent="showDialogMakeSale=true"
                                           v-if="form.items.length > 0"> Procesar Pago
                                </el-button>
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
        <div class="tab-content" v-if="false">
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
                                    <button type="button" class="btn waves-effect waves-light btn-primary btn-xs"
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


        <item-form :showDialog.sync="showDialogNewItem"
                   :external="true"></item-form>

        <form autocomplete="off" @submit.prevent="submit">
            <el-dialog
                :visible.sync="showDialogMakeSale"
                :close-on-modal="false"
                :show-close="false"
                top="5vh" width="50%"
            >
                <div class="row" v-if="payment.total>0">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="form-group">

                            <label class="control-label">
                                Documento
                            </label>

                            <div>
                                <label
                                    v-for="option in document_types"
                                    class="btn btn-primary btn-sm mr-1 mb-1"
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
                                    {{option.description.split(' ')[0]}}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="form-group">

                            <label class="control-label">
                                Serie
                            </label>

                            <div>
                                <label
                                    v-for="option in series"
                                    class="btn btn-info btn-sm mr-1 mb-1"
                                    v-bind:for="'idS'+option.id"
                                    :class="{'active':form.series_id==option.id}"
                                >
                                    <input
                                        type="radio"
                                        v-model="form.series_id"
                                        v-bind:value="option.id"
                                        v-bind:id="'idS'+option.id"
                                    />
                                    {{option.number}}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="form-group">
                            <label class="control-label">
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
                <div class="row pt-4" v-if="payment.total>0">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Forma de Pago
                                <button class="btn btn-success btn-xs"
                                        v-if="informacion_adicional.pagos.length<4"
                                        @click.prevent="informacion_adicional.pagos.push({tipo:''})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </label>

                            <div class="row" v-for="(info,index) in informacion_adicional.pagos">
                                <div class="col-1 pt-4">
                                    <button class="btn btn-danger btn-xs"
                                            v-if="index>0"
                                            @click.prevent="informacion_adicional.pagos.splice(index,1)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="control-label">Forma</label>
                                        <select v-model="info.tipo" class="form-control form-control-sm" required>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Credito">Tarjeta de crédito</option>
                                            <option value="Debito">Tarjeta de débito</option>
                                            <option value="Cheque">Cheque</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label class="control-label">Monto en {{ currency_type.symbol }}</label>
                                        <input v-model="info.monto" class="form-control form-control-sm text-right"
                                               type="number" step="0.1" required>
                                    </div>
                                </div>
                                <div class="col-4">

                                    <div class="form-group" v-if="info.tipo!=='Efectivo' && info.tipo!==''">
                                        <label class="control-label">Num. Referencia</label>
                                        <input v-model="info.ref"
                                               class="form-control form-control-sm text-right"
                                               type="text" required>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="row " v-if="payment.total<=0">
                    <div class="col-lg-12 col-md-12 col-sm-12 embed-responsive embed-responsive-16by9">
                        <embed v-if="this.model == 'sale-notes'" class="embed-responsive-item" v-bind:src="'/print2/salenote/'+this.factura_d.id+'/ticket#toolbar=1&view=FitH,top'"
                               type="application/pdf"/>
                        <embed v-else class="embed-responsive-item" v-bind:src="'/print/document/'+this.factura_d.external_id+'/ticket#toolbar=1&view=FitH,top'"
                               type="application/pdf"/>
                    </div>
                </div>

                <div slot="title" class="dialog-header">
                    <div class="row" v-if="payment.total>0">
                        <div class="col-8">
                            <h2 class="pt-1 mt-0">
                                Procesar Pago
                            </h2>
                            <h5 class="text-success">
                                {{tituloPago}}
                            </h5>
                        </div>
                        <div class="col-4 text-right">
                            <h4 class="m-0 pb-1">
                                <span class="font-whe text-primary">Total a pagar</span>
                                {{currency_type.symbol}} {{payment.total}}
                            </h4>
                            <h5 class="m-0 pb-1">
                                <span class="text-success">Pagando:</span>
                                {{currency_type.symbol}} {{payment.pagando}}
                            </h5>
                            <h5 class="m-0 pb-1">
                                <span class="text-info">Diferencia:</span>
                                {{currency_type.symbol}} {{payment.delta}}
                            </h5>
                        </div>
                    </div>
                    <h2 v-if="payment.total<=0">Documento {{ factura_d.number }}</h2>
                </div>

                <span slot="footer" class="dialog-footer">

                <el-button size="mini" type="danger"
                           @click.prevent="close"
                           v-if="payment.total>0"
                           class="btn-danger btn-xs"
                >
                    Cancelar venta
                </el-button>
                <el-button type="warning" @click.prevent="showDialogMakeSale = false"
                           class="btn-warning btn-lg"
                >
                    Atras
                </el-button>
                <el-button type="primary" native-type="submit"
                           v-if="payment.total>0"
                           :loading="loading_submit"
                           :disabled="payment.delta>0"
                           class="btn-primary btn-lg"
                >
                    Generar
                        </el-button>

            </span>
            </el-dialog>
        </form>
    </div>
</template>

<script>
    import ItemForm from './../items/form.vue'; //

    import DocumentFormItem from './partials/item.vue';
    import PersonForm from '../persons/form.vue'
    import DocumentOptions from './partials/options.vue'
    import {functions, exchangeRate} from '../../../mixins/functions'
    import {calculateRowItem, calculateIgv} from '../../../helpers/functions'
    import Logo from '../companies/logo.vue'


    export default {
        components: {
            ItemForm, DocumentFormItem, PersonForm, DocumentOptions, Logo
        },
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

                searchBox: '', // cuadro de busqueda de productos
                showDialogNewItem: false, // dialogo para crear items
                showDialogMakeSale: false, // dialogo para procesar la venta
                factura_d: {},
                informacion_adicional: {
                    general: [],
                    pagos: [
                        {tipo: 'Efectivo', monto: ''}
                    ]
                },
                items: [],
                tempItem: [],
                tituloPago: "",
                affectation_igv_types: {}
            }
        },
        async created() {
            await this.initForm();
            await this.initTempItem();
            await this.refreshItems();
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.document_types = response.data.document_types_invoice2
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
        computed: {
            payment() {
                var pago = 0;
                _.forEach(this.informacion_adicional.pagos, function (p) {
                    if (p.monto > 0) {
                        pago += p.monto * 1;
                    }
                });
                return {
                    total: _.round((this.form.total), 2),
                    pagando: _.round(pago, 2),
                    delta: _.round((this.form.total - pago), 2)
                }
            },
            adicionalInfo() {
                var _currency = this.currency_type.symbol;
                let pagos = _.flatMap(this.informacion_adicional.pagos, function (p) {
                    let v = ' -- ';
                    v += p.tipo + ': ';
                    v += _currency + '.' + p.monto;
                    if (p.tipo !== 'Efectivo' && p.tipo !== '') {
                        v += ' - (Ref:' + (p.ref ? p.ref : '###') + ')';
                    }

                    return v;
                });

                return _.join([
                    'Forma de Pago:',
                    _.join(pagos, '|')
                ], '|');
            },
            findItem() {

                var data = this.searchBox;
                var lista = this.items
                    .filter(function (item) {

                        if(item.internal_id == null){
                            item.internal_id = "---"
                        }

                        return item.internal_id.toLowerCase().indexOf(data.toLowerCase()) > -1 || item.description.toLowerCase().indexOf(data.toLowerCase()) > -1;
                    })
                    .sort(function (a, b) {
                        if (a.internal_id > b.internal_id) {
                            return 1;
                        }
                        if (a.internal_id < b.internal_id) {
                            return -1;
                        }
                        // a must be equal to b
                        return 0;
                    });
               
                return lista;
            },
        },

        watch: {
            "form.document_type_id": function () {
                this.titlePay();
            },
            "form.series_id": function () {
                this.titlePay();
            },
            "form.customer_id": function () {
                this.titlePay();
            },
            showDialogNewItem: function () {
                this.refreshItems();
            }
        },

        methods: {
            enterAddItem(e) {
                // console.info(e);
                // console.info(this.findItem);
                var lista = this.findItem;
                if (lista.length === 1) {
                    this.selectItem(lista[0].id);
                    this.searchBox = '';
                }
            },
            titlePay() {
                // form.document_type_id
                // form.series_id
                // form.customer_id
                let document = _.find(this.document_types, {id: this.form.document_type_id});
                let serie = _.find(this.series, {id: this.form.series_id});
                let customer = this.form.customer_id ? _.find(this.customers, {id: this.form.customer_id}) : null;
                this.tituloPago = [
                    document.description,
                    serie.number,
                    this.form.customer_id ? customer.description : ""
                ].join(' / ');
            },
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
                    informacion_adicional: "",
                    actions: {
                        format_pdf: 'ticket',
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

                this.informacion_adicional.pagos = [
                    {tipo: 'Efectivo', monto: ''}
                ]
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
                } else if(this.form.document_type_id === '100'){
                    this.customers = _.filter(this.all_customers)
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
                this.form.informacion_adicional = this.adicionalInfo;

                if(this.form.document_type_id == '100'){
                    this.model = 'sale-notes'
                    this.table_name = 'sale_sales'
                }else{
                    this.model = 'documents'
                    this.table_name = 'documents'
                }

                this.$http.post(`/${this.model}`, this.form).then(response => {
                    
                    if (response.data.success) {

                        // this.showDialogOptions = true;
                        // this.documentNewId = response.data.data.id;
                        var temp_add = {
                            table_name: this.table_name,
                            balance: this.payment,
                            sale: this.informacion_adicional.pagos
                        };
                        this.resetForm();

                        this.$http.get(`/${this.model}/record/${response.data.data.id}`).then(response => {
                            this.factura_d = response.data.data;
                            this.$http.post(`/pos/${this.factura_d.id}/operations`, temp_add);
                            //window.open(`/print/document/${this.factura_d.external_id}/ticket`, '_blank');
                            //this.titleDialog = 'Comprobante: ' + this.form.number;
                        });

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

                this.$confirm('Desea cancelar la venta actual?', 'Cancelar Venta', {
                    confirmButtonText: 'Continuar',
                    cancelButtonText: 'Cancelar',
                    type: 'danger'
                }).then(() => {
                    this.$message({
                        type: 'success',
                        message: 'La venta ha sido cancelada con exito'
                    });
                    this.showDialogMakeSale = false;
                    this.resetForm();

                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Cancelacion suspendida'
                    });
                });
            },
            reloadDataCustomers(customer_id) {
                this.$http.get(`/${this.resource}/table/customers`).then((response) => {
                    this.customers = response.data
                    this.all_customers = response.data
                    this.form.customer_id = customer_id
                })
            },
            ////////////////////////////////
            refreshItems() {
                this.$http.post(`/dispatches/tables`).then(response => {
                    this.items = response.data.items;
                });
                this.$http.get(`/documents/item/tables`).then(response => {
//                this.categories = response.categories
                    this.affectation_igv_types = response.data.affectation_igv_types
                })
            },

            initTempItem() {
                this.errors = {}
                this.tempItem = {
//                    category_id: [1],
                    item_id: null,
                    item: {},
                    affectation_igv_type_id: null,
                    affectation_igv_type: {},
                    has_isc: false,
                    system_isc_type_id: null,
                    percentage_isc: 0,
                    suggested_price: 0,
                    quantity: 1,
                    unit_price: 0,
                    charges: [],
                    discounts: [],
                    attributes: [],
                }
            },

            selectItem(id) {
                var index = _.findIndex(this.form.items, {item: {id: id}})

                this.tempItem.item = _.find(this.items, {'id': id})
                if (index > -1) {
                    this.tempItem.quantity += this.form.items[index].quantity;
                }

                this.tempItem.id = this.tempItem.item.id
                this.tempItem.unit_price = this.tempItem.item.sale_unit_price
                this.tempItem.affectation_igv_type_id = this.tempItem.item.sale_affectation_igv_type_id
                this.tempItem.item.unit_price = this.tempItem.unit_price
                this.tempItem.affectation_igv_type = _.find(this.affectation_igv_types, {'id': this.tempItem.affectation_igv_type_id})
                this.tempItem = calculateRowItem(this.tempItem, this.form.currency_type_id, this.form.exchange_rate_sale)

                if (index > -1) {
                    this.form.items[index] = this.tempItem;
                } else {
                    this.form.items.push(this.tempItem);
                }

                //this.initForm()
                //this.initializeFields()
                //this.$emit('add', this.row)
                this.initTempItem();
                this.calculateTotal();
            },
            calculateIgv(included_igv, affectation_igv_type_id, value){
                let price_array = calculateIgv(included_igv, affectation_igv_type_id, value)

                return price_array[1]
            }
        }
    }
</script>
