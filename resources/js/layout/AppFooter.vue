<template>
    <footer class="footer">
        <div class="container">
            <span class="text-muted">Made with ♥ by <a href="http://tusharkant.com">Tushar Kant</a>, © {{year}}</span>
        </div>
    </footer>
</template>
<script>
  export default {
    name: 'app-footer',
    data() {
      return {
        year: new Date().getFullYear()
      }
    }
  };
</script>
<style>
</style>
