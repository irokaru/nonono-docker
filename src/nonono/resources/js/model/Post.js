import DateUtil from '../lib/DateUtil';

export default {
  /**
   * モデル
   */
  model: {
    id:           0,
    title:        '',
    date:         DateUtil.formatDate('YYYY-MM-dd'),
    release_flag: true,
    detail:       '',
    categories:   [],
  },

  dummy(id) {
    const model = this.model;
    model.id    = id;
    model.title = '－－－';

    return model;
  },

  /**
   * カテゴリのモデル
   */
  category: {
    category: '－－－',
    count:    '-',
  },
};
