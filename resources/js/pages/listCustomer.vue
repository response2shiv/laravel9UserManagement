<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="p-3 text-center">List Customer</h2>
            </div>
        </div>

        <div class="row">
            <div class="col text-right">
                <form class="form mt-5" @submit.prevent="customersList">
                    <div class="row">
                        <div class="col-4"></div>
                        <div class="col-6">
                            <input
                                type="text"
                                class="form-control"
                                v-model="searchKey"
                                placeholder="Search Customer"
                            />
                        </div>
                        <div class="col-2">
                            <button type="submit" class="btn btn-primary">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="customer in filteredCustomers"
                            :key="customer.id"
                        >
                            <td>{{ customer.name }}</td>
                            <td>{{ customer.email }}</td>
                            <td>{{ customer.mobile }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row" v-if="pagination.last_page > 1">
            <div class="col">
                <ul class="pagination">
                    <li
                        class="page-item"
                        :class="{ active: link.active, disabled: link.active }"
                        v-for="link in links"
                        :key="link.id"
                    >
                        <a
                            class="page-link"
                            @click.prevent="paginationLinkClick(link.url)"
                            v-html="link.label"
                        ></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            customers: [],
            searchKey: "",
            filteredCustomers: [],
            pagination: {
                total: 0,
                per_page: 0,
                current_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
            },
            links: [],
            page: 1,
        };
    },
    methods: {
        getPageFromUrl(url_string) {
            const url = new URL(url_string);
            return url.searchParams.get("page");
        },
        paginationLinkClick(url) {
            if(url) {
                this.page = this.getPageFromUrl(url);
                this.customersList();
            }
        },
        customersList() {
            axios
                .get(`/api/customers?page=${this.page}&search=${this.searchKey}`)
                .then((response) => {
                    console.log(response);
                    if (response.data.success) {
                        this.customers = response.data.data.data;
                        this.filteredCustomers = response.data.data.data;

                        this.pagination.total = response.data.data.total;
                        this.pagination.per_page = response.data.data.per_page;
                        this.pagination.current_page =
                            response.data.data.current_page;
                        this.pagination.last_page =
                            response.data.data.last_page;
                        this.pagination.from = response.data.data.from;
                        this.pagination.to = response.data.data.to;

                        this.links = response.data.data.links;
                    }
                })
                .catch((e) => {
                    console.log(e.response.data.message);
                });
        },
    },
    created() {
        this.customersList();
    },
};
</script>
