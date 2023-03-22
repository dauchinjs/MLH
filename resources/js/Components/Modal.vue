<template>
    <div v-if="show" class="modal-mask">
        <div class="modal-container">
            <header class="header">
                <slot name="header">
                </slot>
            </header>

            <div class="body">
                <slot name="body">
                </slot>
            </div>

            <footer>
                <slot name="footer">
                </slot>
            </footer>
        </div>
    </div>
</template>

<script setup>
defineProps({
    show: {
        type: Boolean,
    }
})
</script>

<style scoped>
.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, .5);
    display: grid;
    transition: opacity .3s ease;
    place-items: center;
}

.modal-container {
    width: 75vw;
    max-width: 500px;
    margin: 0 auto;
    padding: 20px 30px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
    transition: all .3s ease;
    font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
    margin-top: 0;
    color: #42b983;
}

.body {
    margin: 20px 0;
}

.footer {
    display: flex;
    justify-content: flex-end;
}

</style>
