<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const newTitle = ref('')
const newContent = ref('')
</script>
<template>
  Inertiaテストです。<br>
  <a href="/">aタグ経由です</a><br>
  <Link href="/">Link経由です。</Link><br>
  <!-- controllerのnameで設定した名前 -->
  <Link :href="route('inertia.index')">名前付きルート</Link><br>
  <Link :href="route('inertia.show',{id:1})">ルートパラメータのテスト</Link>

  <div class="mb-8"></div>
  <input type="text" name="newTitle" v-model="newTitle">{{ newTitle }}<br>
  <input type="text" name="newContent" v-model="newContent">{{ newContent }}<br>
  <!-- controllerに渡したいデータがあれば、:dataと記入する -->
  <!-- 上のinput name属性で指定した値を中に入れ込めばinputの内容が渡される -->
  <Link as="button" method="post" :href="route('inertia.store')"
  
  :data="{
    title:newTitle,
    content:newContent,
  }"
  >DB保存テスト</Link>
</template>