<script setup lang="ts">
import PageLoader from './components/PageLoader.vue';

</script>

<template>
  <Suspense>
    <template #default>
      <RouterView />
    </template>
    <template #fallback>
      <PageLoader />
    </template>
  </Suspense>
</template>
