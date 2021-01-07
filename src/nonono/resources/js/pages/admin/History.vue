<template>
<div>
  <h2>管理者用履歴管理ページ</h2>

  <div class="content">

    <router-link to="/admin">管理者ページ</router-link> <v-fa :icon="['fas', 'chevron-right']"/> 管理者用履歴管理ページ

    <h3>新規更新履歴</h3>

    <form class="form" autocomplete="off" onsubmit="return false;">

      <input type="text" v-model="form.date" placeholder="yyyy-mm-dd">
      <input type="text" v-model="form.discription" placeholder="説明">
      <button class="btn" @click="sendStore(form)">追加</button>

    </form>

    <h3>既存更新履歴</h3>

    <form class="form" autocomplete="off" onsubmit="return false;">

      <p v-if="histories.length === 0">更新履歴は無いです</p>

      <ul>
        <li class="history-data" v-for="history in histories" :key="history.id">
          <input type="hidden" :value="history.id">
          <input type="text" :input="history.date" v-model="history.date" placeholder="yyyy-mm-dd">
          <input type="text" :input="history.discription" v-model="history.discription" placeholder="説明">
          <button class="btn" @click="sendUpdate(history)">更新</button>
          <button class="btn" @click="sendDelete(history.id)">削除</button>
        </li>
      </ul>

    </form>

  </div>

  <div class="load-modal" v-if="isLoading || isSending">
    <div class="load-modal__bg">
      <Loading class="center"></Loading>
    </div>
  </div>

</div>
</template>

<script>
import HistoryApi from '../../api/HistoryApi';
import Loading    from '../../components/Loading';

export default {
  data () {
    return {
      form: {
        date: null,
        discription: null,
      },
      histories: [],
      isLoading: true,
      isSending: false,
    }
  },
  methods: {
    /**
     * 更新履歴を取得する処理
     * @returns {array}
     */
    async getHistory () {
      const api = HistoryApi.get();

      return await api.then(histories => {
        return histories.data;
      }).catch(e => {
        console.log(e);
        return [];
      });
    },
    /**
     * 更新処理
     * @param {object} history
     * @returns {void}
     */
    async sendStore (history) {
      if (this.isSending || this.isLoading) return;

      this.isSending = true;
      const api = HistoryApi.store(history);

      const storeResult = await api.then(res => {
        return true;
      }).catch(e => {
        console.log(e);
        return false;
      });

      if (storeResult) {
        Vue.$resetStore('$history');
      }

      this.histories = await this.getHistory();
      this.resetForm();

      this.isSending = false;
    },

    /**
     * 更新処理
     * @param {object} history
     * @returns {void}
     */
    async sendUpdate (history) {
      if (this.isSending || this.isLoading) return;

      this.isSending = true;
      const historyResult = HistoryApi.update(history);

      const updateResult = await api.then(res => {
        return true;
      }).catch(e => {
        console.log(e);
        return false;
      });
      this.isSending = false;

      this.isLoading = true;
      this.histories = await this.getHistory();
      this.isLoading = false;
    },

    /**
     * 削除処理
     * @param {int} id
     * @returns {void}
     */
    async sendDelete (id) {
      if (this.isSending || this.isLoading) return;

      this.isSending = true;
      const api = HistoryApi.delete(id);

      const deleteResult = await api.then(res => {
        return true;
      }).catch(e => {
        console.log(e);
        return false;
      });

      this.isSending = false;

      this.isLoading = true;
      this.histories = await this.getHistory();
      this.isLoading = false;
    },

    /**
     * 入力フォームを初期化するやつ
     * @returns {void}
     */
    resetForm() {
      const date     = new Date();
      const year     = date.getFullYear();
      const month    = ('0' + (date.getMonth() + 1)).slice(-2);
      const day      = ('0' + (date.getDay() + 1)).slice(-2);
      const today    = `${year}-${month}-${day}`;
      this.form.date = today;
      this.form.discription = '';

    }
  },
  async mounted () {
    this.isLoading = true;

    this.resetForm();

    this.histories = await this.getHistory();
    this.isLoading = false;
  },
  components: {
    Loading,
  }
}
</script>

<style>

</style>
