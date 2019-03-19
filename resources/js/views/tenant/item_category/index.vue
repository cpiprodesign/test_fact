<template>
    <div class="card card-collapsed">
        <div class="card-header bg-info">
            <h3 class="my-0">Categorias de Productos</h3>
            <div class="card-actions">
                <a href="#" class="card-action card-action-toggle text-white" data-card-toggle=""></a>
<!--                <a href="#" class="card-action card-action-dismiss text-white" data-card-dismiss=""></a>-->
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i
                        class="fa fa-plus-circle"></i> Nuevo
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr width="100%">
                        <th width="5%">#</th>
                        <th width="65%">Categoria</th>
                        <th width="30%" class="text-right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(row, row_index) in records">

                        <tr>
                            <td>{{ row_index+1 }}</td>
                            <td>{{ row.description }}</td>
                            <td class="text-right">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                        @click.prevent="clickCreate(row.id)">Editar
                                </button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                                        @click.prevent="clickDelete(row.id)">Eliminar
                                </button>
                            </td>
                        </tr>

                        <tr v-for="(child, child_index) in row.childrens">
                            <td>{{ row_index+1 }}.{{ child_index+1 }}</td>
                            <td>{{ row.description }} > {{ child.description }}</td>
                            <td class="text-right">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                        @click.prevent="clickCreate(child.id)">Editar
                                </button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                                        @click.prevent="clickDelete(child.id)">Eliminar
                                </button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i
                        class="fa fa-plus-circle"></i> Nuevo
                    </button>
                </div>
            </div>
        </div>

        <item-category-form :showDialog.sync="showDialog" :recordId="recordId"></item-category-form>

    </div>
</template>
<script>


    import ItemCategoryForm from './form.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        mixins: [deletable],
        components: {ItemCategoryForm},
        data() {
            return {

                showDialog: false,
                resource: 'item_category',
                recordId: null,
                records: [],
            }
        },
        created() {
            this.$eventHub.$on('reloadData', () => {
                this.getData()
            });
            this.getData();
        },
        methods: {
            getData() {
                this.$http.get(`/${this.resource}/records`)
                    .then(response => {
                        this.records = response.data.data;
                    })
            },
            clickCreate(recordId = null) {
                this.recordId = recordId;
                this.showDialog = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
