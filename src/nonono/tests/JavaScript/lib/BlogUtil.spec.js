'use strict'

import BlogUtil  from '../../../resources/js/lib/BlogUtil';
import Validator from 'nonono-validator';

global.Validator = Validator;

// -----------------------------------------------------------------

const _r = (mode = '', key = '', page = '') => {
  return {
    params: {
      mode: mode,
      key : key,
      page: page,
    },
  };
};

describe('validateRoute', () => {
  test('[OK] is match boolean', () => {
    const suites = [
      // expect, route
      // /
      [true, _r('', '', '')],

      // 記事リスト
      [true,  _r('100', '', '')],
      [true,  _r('1',   '', '')],
      [false, _r('0',   '', '')],
      [false, _r('-1',  '', '')],
    ];

    for (const suite of suites) {
      const msg = JSON.stringify(suite);

      expect(BlogUtil.validateRoute(suite[1]), msg).toBe(suite[0]);
    }
  });
});

