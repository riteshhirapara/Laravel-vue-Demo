<template>
    <div v-if="is_admin == 1">
        <h4 class="text-center">All Books</h4><br />
        <form name="fsearch" id="fsearch" class="search-form" method="get" style="padding-bottom: 10px;">
            <div style="display: flex;justify-content: space-between;">
                <button type="button" class="btn btn-info" @click="this.$router.push('/books/add')" v-if="is_admin == 1">Add
                    Book</button>
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
                    <th v-if="is_admin == 1">Actions</th>
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
                    <td v-if="is_admin == 1">
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
    <div v-if="is_admin == 0">
        <div style="width: 100%; display: inline-block !important">
            <div class="np-title">Search book by title</div>
            <div style="width: 100%">
                <div class="auto-search-container">
                    <input type="text" class="np-input-search" v-model="inputSearchText" placeholder="Search books"
                        autocomplete="off" v-on:keyup="searchNames" />
                </div>
            </div>
            <div class="np-result-container">
                <div v-if="filteredResult.length > 0">
                    <div v-for="(result, resultIndex) in filteredResult" :key="resultIndex">
                        <div class="np-result-item" @click="showCharacterDetails(result)">
                            <div class="np-ib" style="width: 50px">
                                <img src="images/book-image.png" class="np-avatar" />
                            </div>
                            <div class="np-ib np-text-container">
                                <div>
                                    <b>{{ result.title }}</b>
                                </div>
                                <div class="np-result-description">
                                    <span>Author:</span>{{ result.author }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="np-details" style="width: 100%; display: inline-block !important" v-if="characterDetails != null">
            <div v-if="characterDetails != null">
                <div>
                    <img src="images/book-image.png" class="np-result-details-img" />
                </div>
                <div class="np-result-details-title">
                    <b style="font-size: 20px;">{{ characterDetails.title }}</b>
                </div>
                <div class="np-result-details-title">
                    {{ characterDetails.author }}
                </div>
                <div class="np-result-details-title">
                    {{ characterDetails.isbn }}
                </div>
                <div class="np-result-details-title">
                    {{ characterDetails.gener }}
                </div>
                <div class="np-result-details-title">
                    {{ characterDetails.published }}
                </div>
                <div class="np-result-details-title">
                    {{ characterDetails.publisher }}
                </div>
                <div class="np-result-details-description">
                    {{ characterDetails.description }}
                </div>
            </div>
        </div>
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
            is_admin: '',
            inputSearchText: "",
            filteredResult: [],
            characterDetails: null,
        }
    },
    created() {
        if (typeof localStorage !== 'undefined') {
            const user = localStorage.getItem('user');
            if (user) {
                const parsedData = JSON.parse(user);
                // Now, `parsedData` contains your data items
                this.is_admin = parsedData.is_admin;
            }
        }

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

        searchNames() {
            this.filteredResult = [];
            this.characterDetails = null;
            if (
                this.inputSearchText == null ||
                (this.inputSearchText != null && this.inputSearchText.length === 0)
            ) {
                this.filteredResult = [];
                this.characterDetails = null;
            } else {
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.get(`/api/books/search?skeyword=${this.inputSearchText}`)
                    .then(response => {
                        this.filteredResult = response.data.data;
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
                })
            }
        },
        showCharacterDetails(result) {
            this.characterDetails = result;
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