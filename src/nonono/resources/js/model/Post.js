import DateUtil from '../lib/DateUtil';

export default {
  /**
   * モデル
   */
  model: {
    id:           0,
    title:        'ーーー',
    date:         DateUtil.formatDate('YYYY-MM-dd'),
    release_flag: true,
    detail:       '',
    categories:   [{post_id: 0, category: 'ーーー'}],
  },

  dummy(id) {
    const model                 = Object.create(this.model);
    model.id                    = id;
    model.categories[0].post_id = id;

    return model;
  },

  dummysArray(len) {
    return Array(len).fill().map((_, i) => {
      return this.dummy((i + 1) * -1);
    });
  },

  /**
   * カテゴリのモデル
   */
  category: {
    category: 'ーーー',
    count:    '-',
  },
};
