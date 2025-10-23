<script setup lang="ts">
// no script needed for this simple spinner
</script>

<template>
  <div class="fixed inset-0 flex items-center justify-center bg-white/80 backdrop-blur-sm z-50">
    <div class="flex flex-col items-center space-y-4">
      <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin" />
      <p class="text-gray-600 font-medium tracking-wide">Loading...</p>
    </div>
  </div>
</template>
