<template>
    <div>
        <h4 class="text-center">Edit Book</h4>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="updateBook">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" v-model="book.title">
                    </div><br>
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" class="form-control" v-model="book.author">
                    </div><br>
                    <div class="form-group">
                        <label>Genre</label>
                        <input type="text" class="form-control" v-model="book.genre">
                    </div><br>
                    <div class="form-group">
                        <label>ISBN</label>
                        <input type="number" class="form-control" v-model="book.isbn">
                    </div><br>
                    <div class="form-group">
                        <label>Published</label>
                        <input type="date" class="form-control" v-model="book.published">
                    </div><br>
                    <div class="form-group">
                        <label>Publisher</label>
                        <input type="text" class="form-control" v-model="book.publisher">
                    </div><br>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" v-model="book.description"></textarea>
                    </div><br>
                    <button type="submit" class="btn btn-primary">Update Book</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            book: {}
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get(`/api/books/edit/${this.$route.params.id}`)
                .then(response => {
                    this.book = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
        updateBook() {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post(`/api/books/update/${this.$route.params.id}`, this.book)
                    .then(response => {
                        this.$router.push({name: 'books'});
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}
</script>
