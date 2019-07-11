<template>
    <ul class="notifications">
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
                                <span class="title">Tiene comprobantes por vencer <span class="badge badge-warning"></span></span>
                                <span class="message">Pendientes de envio a SUNAT</span>
                            </a>
                        </li>
                    </ul>
                    {{-- <hr>
                    <div class="text-right">
                        <a href="#" class="view-more">View All</a>
                    </div> --}}
                </div>
            </div>
        </li>
    </ul>
</template>

<script>

    export default {
        data() {
            return {
                showDialog: false,
                resource: 'alerts/documents',
                recordId: null,
                records: [],
            }
        },
        created() {
            this.$eventHub.$on('reloadData', () => {
                this.getData()
            })
            this.getData()
        },
        methods: {
            getData() {
                // this.$http.get(`/${this.resource}/records`)
                //     .then(response => {
                //         this.records = response.data.records
                //     })
            }
        }
    }
</script>