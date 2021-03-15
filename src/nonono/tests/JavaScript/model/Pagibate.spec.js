'use strict'

import Paginate from '../../../resources/js/model/Paginate';

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

  const suites = [
    // expect, links, meta
    [_e(null, null, null, null, null), {}, {}],
  ];

  for (const suite of suites) {
    expect(Paginate.setup(suite[1], suite[2])).toMatchObject(suite[0]);
  }
});
