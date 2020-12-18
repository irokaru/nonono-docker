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
};
