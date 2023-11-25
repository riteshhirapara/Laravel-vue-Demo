<template>
    <div>
        <h4 class="text-center">All Books</h4><br />
        <form name="fsearch" id="fsearch" class="search-form" method="get" style="padding-bottom: 10px;">
            <div style="display: flex;justify-content: space-between;">
                <button type="button" class="btn btn-info" @click="this.$router.push('/books/add')">Add Book</button>
                <div>
                    <input type="text" name="skeyword" v-model="skeyword" placeholder="Search" autocomplete="off">
                    <button class="normal-btn dark-btn" type="button" @click="search"><i class="fas fa-search"></i>
                        Search
                    </button>
                </div>
            </div>
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>ISBN</th>
                    <th>Published</th>
                    <th>Publisher</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="book in books" :key="book.id">
                    <td>{{ book.id }}</td>
                    <td>{{ book.title }}</td>
                    <td>{{ book.author }}</td>
                    <td>{{ book.genre }}</td>
                    <td>{{ book.isbn }}</td>
                    <td>{{ book.published }}</td>
                    <td>{{ book.publisher }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <router-link :to="{ name: 'editbook', params: { id: book.id } }" class="btn btn-primary">Edit
                            </router-link>
                            <button class="btn btn-danger" @click="deleteBook(book.id)">Delete</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <pagination :currentPage="page" :totalRecords="totalRecords" :perPage="listnum" @pageChange="handlePageChange" />

    </div>
</template>

<script>
import pagination from './Pagination.vue';
export default {
    components: {
        pagination
    },
    data() {
        return {
            books: [],
            totalRecords: 0,
            page: 1,
            listnum: 10,
            skeyword: '',
        }
    },
    created() {
        this.fetch();

    },
    methods: {
        fetch() {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.get(`/api/books?page=${this.page}&listnum=${this.listnum}&skeyword=${this.skeyword}`)
                    .then(response => {
                        this.books = response.data.data;
                        this.totalRecords = response.data.total;
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
        deleteBook(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.delete(`/api/books/delete/${id}`)
                    .then(response => {
                        let i = this.books.map(item => item.id).indexOf(id); // find index of your object
                        this.books.splice(i, 1)
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },

        handlePageChange(newPage) {
            this.page = newPage;
            this.fetch(); // Call the fetch method to update the data
        },

        search() {
            this.fetch();
        },
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    },
}
</script>
