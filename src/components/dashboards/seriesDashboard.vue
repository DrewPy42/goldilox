<template>
  <div>
    <h1>SeriesModel Dashboard</h1>
  </div>
</template>

<script setup lang="ts">
import { SeriesModel } from '@/core'

const props = defineProps<{
  series: SeriesModel[]
}>()

props.series
console.log(props.series)
<script>

<style scoped lang="scss"></style>