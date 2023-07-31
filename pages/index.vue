<template>
  <div class="container">
    <div class="header"><Header /></div>
    <div class="main_contents"><TodoApp /></div>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'nuxt-property-decorator';
import Header from "../components/Header.vue";
import TodoApp from "../components/TodoApp.vue";
import TaskList from "../components/TaskList.vue";
import Modal from "../components/Modal.vue";

@Component({
  components: {
    Header,
    TodoApp,
    TaskList,
    Modal,
  }
})
export default class extends Vue {};
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700&family=Inter:wght@300;400;500;600;700;800;900&display=swap");
body {
  padding: 0;
  margin: 0;
  font-size: 12px;
  font-family: 'Inter', sans-serif;
}

.container {
  position: relative;
  display: flex;
  flex-direction: column;

}

.main_contents {
  padding: 50px 0;
  flex: 1;
  width: 100%;
  min-height: 100vh;
  background-color: whitesmoke;
}

</style>
