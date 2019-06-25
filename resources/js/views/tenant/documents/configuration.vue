<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Configuraciones</h3>
        </div>
        <div class="card-body">
            <form autocomplete="off">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Visualizar el vendedor en el comprobante</label>
                            <div class="form-group" :class="{'has-danger': errors.seller}">
                                <el-switch v-model="form.seller" active-text="Si" inactive-text="No" @change="submit"></el-switch>
                                <small class="form-control-feedback" v-if="errors.seller" v-text="errors.seller[0]"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>


<script>
    export default {
        data() {
            return {
                loading_submit: false,
                resource: 'configuration/documents',
                errors: {},
                form: {}
            }
        },
        async created() {
            await this.initForm();
            
            await this.$http.get(`/${this.resource}/record`) .then(response => {
                if (response.data !== '') this.form = response.data.data;
            });
        },
        methods: {
            initForm() {
                this.errors = {};
                
                this.form = {
                    id: null,
                    stock_control: false,
                };
            },
            submit() {
                this.loading_submit = true;
                
                this.$http.post(`/${this.resource}`, this.form).then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    }
                    else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                    else {
                        console.log(error);
                    }
                }).then(() => {
                    this.loading_submit = false;
                });
            }
        }
    }
</script>
