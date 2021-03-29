'use strict'

import Paginate  from '../../../resources/js/model/Paginate';
import Validator from 'nonono-validator';

global.Validator = Validator;

// -----------------------------------------------------------------

describe('init', () => {
  const _e = (...args) => {
    return {
      current: args[0],
      first:   args[1],
      last:    args[2],
      next:    args[3],
      prev:    args[4],
    };
  };

  test('[OK] is match object', () => {
    const suites = [
      // expect, links, meta
      [_e(1, 1, 2, 3, 4),    {first: 'hogehoge?page=1', last: 'hogehoge?page=2', next: 'hogehoge?page=3', prev: 'hogehoge?page=4'}, {current_page: 1}],
      [_e(1, 1, 5, 2, null), {first: 'hogehoge?page=1', last: 'hogehoge?page=5', next: 'hogehoge?page=2', prev: null},              {current_page: 1}],
      [_e(5, 1, 5, null, 4), {first: 'hogehoge?page=1', last: 'hogehoge?page=5', next: null, prev: 'hogehoge?page=4'},              {current_page: 5}],
    ];

    for (const suite of suites) {
      const msg = JSON.stringify(suite);

      const p = (new Paginate).init(suite[1], suite[2]);

      expect(p, msg).toBeInstanceOf(Paginate);

      for (const [k, v] of Object.entries(suite[0])) {
        expect(p[k], msg).toBe(v);
      }
    }
  });

  test('[NG] bad params', () => {
    const suites =[
      [null, {last: 'hogehoge?page=2', next: 'hogehoge?page=3', prev: 'hogehoge?page=4'}, {current_page: 1}],
      [null, {first: 'hogehoge?page=1', next: 'hogehoge?page=3', prev: 'hogehoge?page=4'}, {current_page: 1}],
      [null, {first: 'hogehoge?page=1', last: 'hogehoge?page=2', prev: 'hogehoge?page=4'}, {current_page: 1}],
      [null, {first: 'hogehoge?page=1', last: 'hogehoge?page=2', next: 'hogehoge?page=3'}, {current_page: 1}],
      [null, {first: 'hogehoge?page=1', last: 'hogehoge?page=2', next: 'hogehoge?page=3', prev: 'hogehoge?page=4'}, {}],

      [null, {first: 2, last: 'hogehoge?page=2', next: 'hogehoge?page=3', prev: 'hogehoge?page=4'}, {current_page: 1}],
      [null, {first: 'hogehoge?page=1', last: [], next: 'hogehoge?page=3', prev: 'hogehoge?page=4'}, {current_page: 1}],
      [null, {first: 'hogehoge?page=1', last: 'hogehoge?page=2', next: {}, prev: 'hogehoge?page=4'}, {current_page: 1}],
      [null, {first: 'hogehoge?page=1', last: 'hogehoge?page=2', next: 'hogehoge?page=3', prev: false}, {current_page: 1}],
      [null, {first: 'hogehoge?page=1', last: 'hogehoge?page=2', next: 'hogehoge?page=3', prev: 'hogehoge?page=4'}, {current_page: '1'}],
    ];

    for (const suite of suites) {
      const msg = JSON.stringify(suite);

      expect((new Paginate).init(suite[1], suite[2]), msg).toBeNull();
    }
  });
});

describe('list', () => {
  const _p = (...args) => {
    const next = args[0] + 1;
    const prev = args[0] - 1;

    return [
      {
        first: 'hogehoge?page=' + args[1],
        last:  'hogehoge?page=' + args[2],
        next:  'hogehoge?page=' + (next <= args[2] ? next : null),
        prev:  'hogehoge?page=' + (prev >= args[1] ? prev : null),
      },
      {
        current_page: args[0],
      },
    ];
  };

  test('[OK] is match array', () => {
    const suites = [
      // expect, p, length
      [[1, 2, 3], _p(1, 1, 5), 3],
      [[1, 2, 3], _p(2, 1, 5), 3],
      [[2, 3, 4], _p(3, 1, 5), 3],
      [[3, 4, 5], _p(4, 1, 5), 3],
      [[3, 4, 5], _p(5, 1, 5), 3],

      [[1, 2, 3, 4], _p(1, 1, 6), 4],
      [[1, 2, 3, 4], _p(2, 1, 6), 4],
      [[1, 2, 3, 4], _p(3, 1, 6), 4],
      [[2, 3, 4, 5], _p(4, 1, 6), 4],
      [[3, 4, 5, 6], _p(5, 1, 6), 4],
      [[3, 4, 5, 6], _p(6, 1, 6), 4],
    ];

    for (const suite of suites) {
      const msg = JSON.stringify(suite);

      const p = (new Paginate).init(...suite[1]);

      expect(p, msg).toBeInstanceOf(Paginate);
      expect(p.list(suite[2]), msg).toEqual(suite[0]);
    }
  });

  test('[NG] invalid params', () => {
    const suites = [
      // expect, length
      [[], 0],
      [[], -1],

      [[1, 2, 3, 4, 5], 5],
      [[1, 2, 3, 4, 5], 6],

      [[], 'a'],
      [[], []],
      [[], true],
    ];

    const p = (new Paginate).init(..._p(1, 1, 5));
    expect(p).toBeInstanceOf(Paginate);

    for (const suite of suites) {
      const msg = JSON.stringify(suite);

      expect(p.list(suite[1]), msg).toEqual(suite[0]);
    }
  });
});

describe('_getPageAsUrl', () => {
  test('[OK] is match page number', () => {
    const suites = [
      // expect, url
      [10,   'http://nononotyaya.net/?page=10'],
      [0,    'http://nononotyaya.net/?page=0'],
      [null, 'http://nononotyaya.net/?page=-1'],
    ];

    for (const suite of suites) {
      const msg = JSON.stringify(suite);

      expect(Paginate._getPageAsUrl(suite[1]), msg).toBe(suite[0]);
    }
  });

  test('[NG] invalid param', () => {
    const suites = [
      1,
      'aaa',
      'http://nononotyaya.net/?_page=10',
      'http://nononotyaya.net/?page=aaa',
      [],
      {},
      true,
    ];

    for (const suite of suites) {
      expect(Paginate._getPageAsUrl(suite), suite).toBeNull();
    }
  });
});
