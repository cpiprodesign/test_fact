<template>
    <div class="box ">
        <div class="box-body no-padding">
            <div class="el-form-item  col-xs-12">
                <small>Fecha Inicio:</small>
                <br>
                <div class="el-form-item__content">
                    <el-date-picker :inline=true v-model="d" type="date" name="d" placeholder="Inicio"></el-date-picker>
                </div>
            </div>
            <div class="el-form-item  col-xs-12">
                <small>Fecha Fin:</small>
                <br>
                <div class="el-form-item__content">
                    <el-date-picker v-model="a" :inline=true type="date" name="a" placeholder="Término"></el-date-picker>
                </div>
            </div>
            <div class="el-form-item  col-xs-12">
                <small>Usuario:</small>
                <br>
                <div class="el-form-item__content">
                    <el-select v-model="user" name="user" filterable class="border-left rounded-left border-info" popper-class="el-select-customers" dusk="user_td">
                        <el-option v-for="option in users" :key="option.id" :value="option.id" :label="option.name"></el-option>
                    </el-select>
                </div>
            </div>
            <div class="el-form-item  col-xs-12">
                <small>Establecimiento:</small>
                <br>
                <div class="el-form-item__content">
                    <el-select v-model="establishment" name="establishment" filterable class="border-left rounded-left border-info" popper-class="el-select-customers" dusk="establishment_td">
                        <el-option v-for="option in establishments" :key="option.id" :value="option.id" :label="option.description"></el-option>
                    </el-select>
                </div>
            </div>
            <div class="el-form-item  col-xs-12">
                <small></small>
                <br>
                <div class="el-form-item__content">
                    <button class="btn btn-custom" type="submit"><i class="fa fa-search"></i> Buscar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            'data_d': {
                required: false,
                default: ''
            },
            'data_a': {
                required: false,
                default: ''
            },
            'user_td': {
                required: false,
                default: ''
            },
            'users': {
                required: false,
                default: ''
            },
            'establishment_td': {
                required: false,
                default: ''
            },
            'establishments': {
                required: false,
                default: ''
            }
        },
        data() {
            return {
                d: '',
                a: '',
                user: null,
                establishment: null
            }
        },
        created() {
            this.user = (this.user_td != '') ? this.users.find(row => row.id == this.user_td).id : null;
            this.establishment = (this.establishment_td != '') ? this.establishments.find(row => row.id == this.establishment_td).id : null;
            this.d = (this.data_d != '') ? moment(this.data_d) : '';
            this.a = (this.data_a != '') ? moment(this.data_a) : '';
        }
    }
</script>
