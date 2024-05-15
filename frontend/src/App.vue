<template>
  <v-app>
    <v-main>
      <v-container>
        <SearchingForm />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import SearchingForm from './components/SearchingForm.vue';

export default {
  name: 'App',
  components: {
    SearchingForm
  }
};
</script>

<style>
@import '~vuetify/styles';
</style>
