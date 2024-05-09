import { defineStore } from 'pinia'
import { createActions, fetchWrapper } from '../core'
import { seriesStore } from '@/core'

seriesStore.storeName = 'series'
seriesStore.baseURL.value = '/api/series.php'
seriesStore.currentSort.value = 'title_id'
seriesStore.currentSortDir.value = 'asc'

export const useSeriesStore = defineStore({
  id: 'series',
  state: () => ({
    ...seriesStore
  }),
  actions: {
    ...createActions(seriesStore),
    getAll() {
      fetchWrapper.get(`${seriesStore.baseURL.value}`).then((response: any) => {
        this.seriesList = response.data
      })
    }
  }
})
