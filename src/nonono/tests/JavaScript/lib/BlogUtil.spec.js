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

      // 記事詳細
      [true,  _r('post', '100', '')],
      [true,  _r('post', '1',   '')],
      [false, _r('post', '0',   '')],
      [false, _r('post', '-1',  '')],

      // カテゴリリスト
      [true, _r('category', 'aaaaa', '')],
      [true, _r('category', '1',     '')],
      [true, _r('category', '0',     '')],
      [true, _r('category', '-1',    '')],

      // カテゴリリスト(ページ付き)
      [true,  _r('category', 'aaaaa', '1')],
      [true,  _r('category', '1',     '100')],
      [false, _r('category', '0',     '-1')],
      [false, _r('category', '-1',    '0')],
    ];

    for (const suite of suites) {
      const msg = JSON.stringify(suite);

      expect(BlogUtil.validateRoute(suite[1]), msg).toBe(suite[0]);
    }
  });

  test('[NG] invalid params', () => {
    const suites = [
      // expect, route
      [false, _r('aaa', 'bbb', 'ccc')],

      [false, _r('post', 'aaaaa', '')],
      [false, _r('post', '111', '222')],

      [false, _r('category', Array(31).fill('*').join(''), '')],
      [false, _r('category', Array(31).fill('*').join(''), '1')],
    ];

    for (const suite of suites) {
      const msg = JSON.stringify(suite);

      expect(BlogUtil.validateRoute(suite[1]), msg).toBe(suite[0]);
    }
  });
});

