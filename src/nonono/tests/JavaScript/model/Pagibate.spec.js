'use strict'

import Paginate  from '../../../resources/js/model/Paginate';
import Validator from 'nonono-validator';

global.Validator = Validator;

// -----------------------------------------------------------------

describe('model', () => {
  test('[OK] is match object', () => {
    expect(Paginate.model()).toMatchObject({
      current: null,
      first:   null,
      last:    null,
      next:    null,
      prev:    null,
    });
  });
});

describe('setup', () => {
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
      [_e(null, null, null, null, null), {}, {}],
      [_e('hogehoge', 1, 2, 3, 4),    {first: 'hogehoge?page=1', last: 'hogehoge?page=2', next: 'hogehoge?page=3', prev: 'hogehoge?page=4'}, {current_page: 'hogehoge'}],
      [_e('hogehoge', null, 2, 3, 4), {last: 'hogehoge?page=2', next: 'hogehoge?page=3', prev: 'hogehoge?page=4'}, {current_page: 'hogehoge'}],
      [_e('hogehoge', 1, null, 3, 4), {first: 'hogehoge?page=1', next: 'hogehoge?page=3', prev: 'hogehoge?page=4'}, {current_page: 'hogehoge'}],
      [_e('hogehoge', 1, 2, null, 4), {first: 'hogehoge?page=1', last: 'hogehoge?page=2', prev: 'hogehoge?page=4'}, {current_page: 'hogehoge'}],
      [_e('hogehoge', 1, 2, 3, null), {first: 'hogehoge?page=1', last: 'hogehoge?page=2', next: 'hogehoge?page=3'}, {current_page: 'hogehoge'}],
      [_e(null, 1, 2, 3, 4), {first: 'hogehoge?page=1', last: 'hogehoge?page=2', next: 'hogehoge?page=3', prev: 'hogehoge?page=4'}, {current: 'hogehoge'}],
    ];

    for (const suite of suites) {
      const msg = JSON.stringify(suite);
      expect(Paginate.setup(suite[1], suite[2]), msg).toMatchObject(suite[0]);
    }
  });
});

describe('list', () => {
  const _p = (...args) => {
    const next = args[0] + 1;
    const prev = args[0] - 1;
    return {
      current: args[0],
      first:   args[1],
      last:    args[2],
      next:    next <= args[2] ? next : null,
      prev:    prev >= args[1] ? prev : null,
    };
  };

  test('[OK] is match object', () => {
    const suites = [
      // expect, p, number
      [[1, 2, 3, 4, 5],  _p(1, 1, 10),  5],
      [[1, 2, 3, 4, 5],  _p(2, 1, 10),  5],
      [[1, 2, 3, 4, 5],  _p(3, 1, 10),  5],
      [[2, 3, 4, 5, 6],  _p(4, 1, 10),  5],
      [[3, 4, 5, 6, 7],  _p(5, 1, 10),  5],
      [[4, 5, 6, 7, 8],  _p(6, 1, 10),  5],
      [[5, 6, 7, 8, 9],  _p(7, 1, 10),  5],
      [[6, 7, 8, 9, 10], _p(8, 1, 10),  5],
      [[6, 7, 8, 9, 10], _p(9, 1, 10),  5],
      [[6, 7, 8, 9, 10], _p(10, 1, 10), 5],

      [[1], _p(1, 1, 10), 1],

      [[1, 2, 3], _p(1, 1, 3), 100],
    ];

    for (const suite of suites) {
      const msg = JSON.stringify(suite);
      expect(Paginate.list(suite[1], suite[2]), msg).toEqual(suite[0]);
    }
  });
});
