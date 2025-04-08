<script setup>
const emit = defineEmits(['onClickRemove'])
const props = defineProps({
  id: Number,
  imageUrl: String,
  title: String,
  price: Number,
})
</script>

<template>
  <div class="flex items-center border border-slate-200 p-4 rounded-xl gap-4">
    <img class="w-16 h-16" :src="imageUrl" :alt="title" />
    <div class="flex flex-col">
      <p>{{ title }}</p>
      <div class="flex justify-between mt-2">
        <b class="flex items-center space-x-1">
          <span>{{ price }}</span>
          <img class="w-3" src="/eiroicon.svg" />
        </b>
        <img
          @click="emit('onClickRemove')"
          class="ml-40 opacity-40 hover:opacity-100 cursor-pointer transition"
          src="/close.svg"
        />
      </div>
    </div>
  </div>
</template>
