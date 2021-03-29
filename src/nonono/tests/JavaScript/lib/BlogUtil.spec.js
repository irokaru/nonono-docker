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
      [true, _r()],

      // 記事リスト
      [true,  _r('100')],
      [true,  _r('1')],
      [false, _r('0')],
      [false, _r('-1')],

      // 記事詳細
      [true,  _r('post', '100')],
      [true,  _r('post', '1')],
      [false, _r('post', '0')],
      [false, _r('post', '-1')],

      // カテゴリリスト
      [true, _r('category', 'aaaaa')],
      [true, _r('category', '1')],
      [true, _r('category', '0')],
      [true, _r('category', '-1')],

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

describe('mainComponentName', () => {
  test('[OK] is match array', () => {
    const suites = [
      // expect, route
      [['BlogPostDetail', false], _r('post', '100')],
      [['BlogPostDetail', false], _r('post', '1')],
      [['BlogPostDetail', false], _r('post', '0')],
      [['BlogPostDetail', false], _r('post', '-1')],

      [['BlogCategoryList', true],  _r('category', 'aaaaa')],
      [['BlogCategoryList', true],  _r('category', '1')],
      [['BlogCategoryList', true],  _r('category', '0')],
      [['BlogCategoryList', true],  _r('category', '-1')],

      [['BlogCategoryList', true],  _r('category', 'aaaaa', '1')],
      [['BlogCategoryList', true],  _r('category', '1',     '100')],
      [['BlogCategoryList', true],  _r('category', '0',     '-1')],
      [['BlogCategoryList', true],  _r('category', '-1',    '0')],

      [['BlogPostList', true], _r('posts')],
      [['BlogPostList', true], _r('11111')],
      [['BlogPostList', true], _r('categories')],
      [['BlogPostList', true], _r('list')],
    ];

    for (const suite of suites) {
      const msg = JSON.stringify(suite);

      expect(BlogUtil.mainComponentName(suite[1]), msg).toEqual(suite[0]);
    }
  });
});

describe('mdDetail2html', () => {
  test('[OK] is match string', () => {
    const suites = [
      ["<p>aaaaa</p>\n<p>bbbbb</p>\n", "aaaaa\n\nbbbbb"],
      ["<p>aaaa<a href=\"https://nononotyaya.net\" target=\"_blank\">bbbb</a>cccc</p>\n", "aaaa[bbbb](https://nononotyaya.net)cccc"],
      ["<ul class=\"dash\">\n<li class=\"arrow\">aaa</li>\n<li class=\"arrow\">bbb</li>\n</ul>\n", "- aaa\n- bbb"],
      ["<p><code>aaaaa</code></p>\n", "`aaaaa`"],
    ];

    for (const suite of suites) {
      const msg = JSON.stringify(suite);

      expect(BlogUtil.mdDetail2html(suite[1]), msg).toBe(suite[0]);
    }
  });

  test('[NG] invalid param', () => {
    const suites = [
      1, ['aaa', 'vvv'], {}, true,
    ];

    for (const suite of suites) {
      const msg = JSON.stringify(suite);

      expect(BlogUtil.mdDetail2html(suite), msg).toBe('');
    }
  });
});
