<template>
    <li class="open">
        <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown" aria-expanded="true">
            <i class="fa fa-bell"></i>
            <span class="badge">1</span>
        </a>
        <div class="dropdown-menu notification-menu">
            <div class="notification-title bg-primary">
                <span class="pull-right label label-default text-primary">1</span>Alerts
            </div>
            <div class="content">
                <ul>
                    <li>
                        <a href="#" class="clearfix">
                            <div class="image">
                                <i class="fa fa-thumbs-down bg-danger"></i>
                            </div>
                            <span class="title">Comprobantes por vencer <span class="badge badge-warning">Warning</span></span>
                            <span class="message">Pendientes de envio a SUNAT</span>
                        </a>
                    </li>                            
                </ul>
                <hr>   
                <div class="text-right">
                    <a href="#" class="view-more">View All</a>
                </div>
            </div>
        </div>
    </li>
</template>
<script>

    import ItemsForm from './form.vue'
    import DataTable from '../../../components/DataTable.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        mixins: [deletable],
        components: {ItemsForm, DataTable},
        data() {
            return {
                showDialog: false,                
                resource: 'accounts',
                recordId: null,
            }
        },
        created() {
        },
        methods: {
            clickCreate(recordId = null) {
                this.recordId = recordId
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
