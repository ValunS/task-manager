
<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'Login' }">Вход</router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'Register' }">Регистрация</router-link>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <router-view></router-view>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

