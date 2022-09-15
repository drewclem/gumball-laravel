<template>
  <div class="grid grid-cols-5 text-sm lg:text-base">
    <p class="col-span-2 font-semibold"><slot name="col-1" /></p>
    <p><slot name="col-2" /></p>
    <p><slot name="col-3" /></p>
    <p class="ml-auto text-green-500 font-bold"><slot name="col-4" /></p>
  </div>
</template>
