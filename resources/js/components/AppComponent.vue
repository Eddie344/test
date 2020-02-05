<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark navbar-laravel">
            <div class="container">
                <ul class="navbar-nav">
                    <router-link :to="{ name: 'main' }" class="nav-link" exact>Главная</router-link>
                    <router-link :to="{ name: 'departments' }" class="nav-link" >Отделы</router-link>
                    <router-link :to="{ name: 'employees' }" class="nav-link" >Сотрудники</router-link>
                </ul>
            </div>
        </nav>
        <main>
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
    export default {
        name: "AppComponent"
    }
</script>

<style scoped>
    .navbar-dark .navbar-nav a.router-link-active {
        color: white;
    }
</style>
