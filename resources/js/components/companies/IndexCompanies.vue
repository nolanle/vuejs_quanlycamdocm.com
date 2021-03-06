<template>
    <div>
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h4 class="mb-0">Danh Sách Công Ty</h4>
                    <div class="delete-button"></div>
                </div>
                <div class="col-sm-6">
                    <nav class="float-left float-sm-right" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'dashboard'}"><i class="ti-home"></i> Dashboard</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Danh Sách Công Ty</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mb-30 col-xl-12">
                <div class="card-statistics h-100 card">
                    <div class="card-body react-bs-table-container">

                        <div class="react-bs-table-tool-bar ">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-8">
                                    <div class="btn-group" role="group">
                                        <router-link v-if="isCreateCompany" :to="{name: 'createCompany'}" class="btn btn-success"><i class="fa fa-plus"></i> THÊM MỚI</router-link>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-4">
                                    <div class="form-group form-group-sm react-bs-table-search-form">
                                        <input placeholder="Tìm kiếm công ty" class="form-control" type="text" style="z-index: 0;"><span class="input-group-btn"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="react-bs-table react-bs-table-bordered" style="height: 100%;">
                            <div class="react-bs-container-body" style="height: 100%;">
                                <table class="table table-striped table-bordered table-hover table-condensed">
                                    <thead>
                                        <tr>
                                            <th>TÊN CÔNG TY</th>
                                            <th>ĐIỆN THOẠI</th>
                                            <th>NGƯỜI ĐẠI DIỆN</th>
                                            <th>ĐỊA CHỈ</th>
                                            <th>VỐN KHỞI TẠO</th>
                                            <th>SỐ DƯ</th>
                                            <th>NGÀY TẠO</th>
                                            <th>TRẠNG THÁI</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(company, index) in companies.data">
                                            <td>
                                                <router-link v-if="isEditCompany" :to="{name: 'editCompany', params: {id: company.id}}" :class="'btn btn-xs btn-default'">
                                                    <span :class="'text-success'"><strong>{{ company.name }}</strong></span>
                                                </router-link>
                                                <span v-else>{{ company.name }}</span>
                                            </td>
                                            <td>{{ company.phone }}</td>
                                            <td>{{ company.represent }}</td>
                                            <td>{{ company.district.name }} - {{ company.district.province.name }}</td>
                                            <td>{{ company.initial_capital | currency }}</td>
                                            <td>{{ company.current_balance | currency }}</td>
                                            <td>{{ company.created_at | moment("D/M/Y") }}</td>
                                            <td><switches v-model="company.activated" theme="bootstrap" color="success" disabled></switches></td>
                                            <td><a v-if="isDeleteCompany" href="javascript:;" class="btn btn-xs btn-danger" v-on:click="deleteEntry(company.id, index)"><i class="fa fa-trash"></i></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="react-bs-table-pagination">
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4 offset-4">
                                    <pagination :data="companies" @pagination-change-page="getResults"></pagination>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "IndexCompanies",
        data: function () {
            return {
                // checking
                isCreateCompany: false,
                isEditCompany: false,
                isDeleteCompany: false,

                page: 1,
                companies: {}
            }
        },
        mounted() {
            this.getResults();

            // check permissions
            this.checkIsCreateCompany();
            this.checkIsEditCompany();
            this.checkIsDeleteCompany();
        },
        methods: {
            checkIsCreateCompany() {
                axios.get('/api/auth/check/permission/' + 'create-company', {
                    headers: {Authorization: 'Bearer ' + localStorage.getItem('token')}
                }).then(response => {
                    this.isCreateCompany = response.data.access;
                })
            },
            checkIsEditCompany() {
                axios.get('/api/auth/check/permission/' + 'edit-company', {
                    headers: {Authorization: 'Bearer ' + localStorage.getItem('token')}
                }).then(response => {
                    this.isEditCompany = response.data.access;
                })
            },
            checkIsDeleteCompany() {
                axios.get('/api/auth/check/permission/' + 'delete-company', {
                    headers: {Authorization: 'Bearer ' + localStorage.getItem('token')}
                }).then(response => {
                    this.isDeleteCompany = response.data.access;
                })
            },

            getResults(page = 1) {
                let app = this;
                app.page = page;
                axios.get('/api/v1/companies?page=' + page, {
                    headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }
                }).then(response => {
                    app.companies = response.data;
                });
            },
            deleteEntry(id, index) {
                var app = this;
                this.$swal({
                    type: 'warning',
                    title: 'Bạn có chắc xóa?',
                    text: "Hành động này không thể hoàn tác, vui lòng xác nhận!",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Chắc chắn, xóa luôn đi!',
                    cancelButtonText: '<i class="fa fa-ban"></i> Hủy bỏ',
                }).then((result) => {
                    if (result.value) {
                        axios.delete('/api/v1/companies/' + id, {
                            headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }
                        }).then(function (response) {
                            app.getResults(app.page);
                            app.$swal({
                                type: 'success',
                                title: 'Đã xóa thành công!',
                                text: 'Công ty do bạn quản lý đã bị xóa khỏi hệ thống.'
                            });
                        }).catch(function (error) {
                            app.$swal({
                                type: 'error',
                                title: 'Xóa thất bại!',
                                text: 'Lỗi hệ thống ' + error + ', vui lòng thử lại sau.'
                            });
                        });
                    }
                });
            }
        }
    }
</script>

<style scoped>

</style>