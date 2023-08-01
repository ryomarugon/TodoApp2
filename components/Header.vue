<template>
  <div class="header_nav">
    <NuxtLink to="/" class="nav_logo"><strong>ToDo</strong></NuxtLink>
  </div>
</template>
<script>
import { Component, Vue } from "nuxt-property-decorator";

@Component
export default class Header extends Vue {}
</script>

<style>
.header_nav {
  height: 50px;
  width: 100%;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  border-bottom: 2px solid rgba(0, 0, 0, 0.2);
}
.nav_logo {
  padding: 0 50px;
  font-size: 2em;
  color: black;
  text-decoration: none;
}
</style>

