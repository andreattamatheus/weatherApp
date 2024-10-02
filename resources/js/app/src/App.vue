<template>
  <div>
    <router-view />
  </div>
</template>

<script>
export default {
  computed: {
    userLogged() {
      return true
    },
  },
};
</script>
<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

nav {
  padding: 30px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #42b983;
}

.bg-background-primary {
  background-color: rgba(248, 248, 248, 0.329);
}

.bg-background-secondary {
  background-color: #2E2E2E;
}
</style>
