<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="p-3 text-center">Notifications history</h2>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Message</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="notification in notifications"
                            :key="notification.id"
                        >
                            <td>{{ notification.message }}</td>
                            <td>{{ notification.schedule }}</td>
                            <td>{{ notification.type }}</td>
                            <td>{{ notification.status }}</td>
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
            notifications: [],
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
                this.notificationsList();
            }
        },
        notificationsList() {
            axios
                .get(`/api/notifications?page=${this.page}`)
                .then((response) => {
                    console.log(response);
                    if (response.data.success) {
                        this.notifications = response.data.data.data;

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
        this.notificationsList();
    },
};
</script>
