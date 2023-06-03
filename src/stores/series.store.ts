import { defineStore } from 'pinia'
import { createActions } from '../core'
import { seriesStore } from '../core'

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
    ...createActions(seriesStore)
    // searchName(name: string) {
    //   fetchWrapper
    //     .get(`${seriesStore.baseURL.value}/searchName/${name}?co=erstires`)
    //     .then((response: any) => {
    //       this.usersList = response.data
    //     })
    // }
  }
})
