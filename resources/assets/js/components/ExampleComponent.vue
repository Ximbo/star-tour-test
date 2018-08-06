<template>
    <div class="container">

        <h1>Тестовое задание</h1>

        <div class="row">
            <textarea v-model="text" placeholder="Введите текст" class="col-md-12" rows="20"></textarea>
            <div class="float-left">
                <button class="btn btn-primary" @click="send" :disabled="isButtonDisabled">
                    <span v-if="isLoading">Отправка...</span><span v-else>Отправить</span>
                </button>
            </div>
        </div>

        <div class="row" v-if="!!error">
            <div class="alert alert-danger" role="alert">
                {{ error.exception }} {{ error.message }} {{ error.errors }}
            </div>
        </div>

        <hr v-if="isTextsNotEmpty">

        <div class="row" v-if="isTextsNotEmpty">
            <h3>Результат</h3>
            <div v-for="text in texts">
                <code class="col-md-12">{{ text.text }}</code>
                <hr>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        data: function() {
            return {
                error: false,
                loading: false,
                text: '',
                texts: []
            }
        },
        computed: {
            isLoading: function() {
                return this.loading;
            },
            isTextsNotEmpty: function() {
                return this.texts.length !== 0;
            },
            isButtonDisabled: function() {
                return this.text.length === 0 || this.loading;
            }
        },
        methods: {
            setTexts: function(texts) {
                this.texts = texts;
            },
            clearTexts: function() {
                this.setTexts([]);
            },
            clearError: function() {
                this.error = false;
            },
            setError: function(error) {
                this.error = error;
            },
            startLoading: function() {
                this.loading = true;
            },
            stopLoading: function() {
                this.loading = false;
            },
            send: function() {
                if (this.text.length === 0) {
                    return;
                }
                this.clearTexts();
                this.clearError();
                this.startLoading();
                axios.post('/parse', {text: this.text})
                    .then((response) => this.setTexts(response.data))
                    .catch((error) => this.setError(error.response.data))
                    .catch((error) => console.log(error.response.data))
                    .then(() => this.stopLoading());
            }
        }
    }
</script>
