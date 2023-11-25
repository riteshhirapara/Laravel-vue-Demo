<template>
    <div class="vgt-wrap__footer vgt-clearfix" style="">
        <div class="footer__navigation vgt-pull-right">
            <div class="footer__navigation__page-info ">
                <form @submit.prevent="changePage">
                    <label for="vgt-page-input" class="page-info__label">
                        <span class="pages_c">Page</span>
                        <input ref="pageInput" id="vgt-page-input" aria-describedby="change-page-hint"
                            aria-controls="vgb-table" type="text" v-model="currant"
                            class="input_c">
                        <span class="pages_c">of {{ totalPages }}</span>
                    </label>
                </form>
            </div>
            <!---->
            <button type="button" aria-controls="vgt-table"
                class="footer__navigation__page-btn"
                :class="{ 'disabled': isPreviousDisabled }" @click="previousPage">
                <span class="hidden">Prev</span>
            </button>
            <button type="button" aria-controls="vgt-table"
                class="footer__navigation__page-btn"
                :class="{ 'disabled': isNextDisabled }" @click="nextPage">
                <span class="hidden">Next</span>
            </button>
            <!---->
        </div>
    </div>
</template>

<script>
export default {
    name: 'Pagination',
    props: ['currentPage', 'totalRecords', 'perPage'],
    data: () => ({
        currant:null
    }),
    computed: {
        totalPages() {
            return Math.ceil(this.totalRecords / this.perPage);
        },
        isPreviousDisabled() {
            return this.currentPage == 1;
        },
        isNextDisabled() {
            return this.currentPage == this.totalPages || this.totalPages == 0;
        },
    },
    mounted() {
        this.currant = this.currentPage;
    },
    methods: {
        previousPage() {
            if (!this.isPreviousDisabled) {
                this.$emit('pageChange', Number(this.currentPage) - 1);
                this.currant = Number(this.currentPage) - 1;
            }
        },
        nextPage() {
            console.log('fdsfds');
            if (!this.isNextDisabled) {
                this.$emit('pageChange', Number(this.currentPage) + 1);
                this.currant = Number(this.currentPage) + 1;
            }
        },
        changePage() {
            let newPage = parseInt(this.currant);

            // Check if the entered page number is within valid range
            if (newPage >= 1 && newPage <= this.totalPages) {
                this.$emit('pageChange', newPage);
            } else if (newPage > this.totalPages || newPage <= 0) {
                this.currant = this.currentPage;
            } else {
                this.$emit('pageChange', 1);
            }
        },
    },
}
</script>
<style scoped>
.vgt-clearfix {
    padding: 0.25rem; 
font-size: 0.75rem;
line-height: 1rem; 
color: #9CA3AF; 
}
.vgt-pull-right {
    float: right; 
font-size: 0.75rem;
line-height: 1rem; 
}
.footer__navigation__page-info {
    display: inline-block; 
margin-right: 1rem; 
margin-left: 1rem; 
color: #4B5563; 
vertical-align: middle; 
}
.pages_c {
    display: inline-block; 
font-size: 0.75rem;
line-height: 1rem; 
vertical-align: middle; 
}
.input_c {
    display: inline-block; 
padding: 0; 
margin-right: 0.5rem; 
margin-left: 0.5rem; 
width: 2rem; 
font-weight: 700; 
text-align: center; 
vertical-align: middle; 
}
.footer__navigation__page-btn {
    display: inline-block; 
padding: 0.5rem; 
margin-right: 1rem; 
border-style: none; 
font-weight: 700; 
text-decoration: none; 
vertical-align: middle; 
appearance: none; 
}
.chevron {
    position: relative; 
margin: 0; 
width: 1.5rem; 
height: 1.5rem; 
}
</style>