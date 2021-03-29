'use strict'

import ApiTools from '../../../resources/js/lib/ApiTools';

// -----------------------------------------------------------------

describe('genelateErrorParams', () => {

  const _p = (status) => {
    return {
      response: {
        status: status,
      },
    };
  }

  const _e = (message) => {
    return {
      status:  'error',
      message: message,
    };
  };

  test('[OK] is match error pattern', () => {
    const suites = [
      // expect, error
      [_e(''),                                                         _p(400)],
      [_e('セッションが切れました。再度ログインして試してください。'), _p(401)],
      [_e(''),                                                         _p(402)],

      [_e(''),                                                   _p(404)],
      [_e('通信方法が不適切です。作った人に報告してください。'), _p(405)],
      [_e(''),                                                   _p(406)],

      [_e(''),                       _p(421)],
      [_e('送信データが不正です。'), _p(422)],
      [_e(''),                       _p(423)],

      [_e(''),                                                                 _p(428)],
      [_e('データを短時間に送信しすぎです。時間を置いてから試してください。'), _p(429)],
      [_e(''),                                                                 _p(430)],

      [_e(''),                                             _p(499)],
      [_e('謎のエラーです。作った人に報告してください。'), _p(500)],
      [_e(''),                                             _p(501)],

      [_e(''), {}],
      [_e(''), {repsonse: {}}],
      [_e(''), _p(null)],
    ];

    for (const suite of suites) {
      expect(ApiTools.genelateErrorParams(suite[1])).toEqual(suite[0]);
    }
  });
});

describe('_getErrorMessageForAdmin', () => {

  test('[OK] is match message', () => {
    const suites = [
      // expect, error
      ['',                                                         400],
      ['セッションが切れました。再度ログインして試してください。', 401],
      ['',                                                         402],

      ['',                                                   404],
      ['通信方法が不適切です。作った人に報告してください。', 405],
      ['',                                                   406],

      ['',                       421],
      ['送信データが不正です。', 422],
      ['',                       423],

      ['',                                                                 428],
      ['データを短時間に送信しすぎです。時間を置いてから試してください。', 429],
      ['',                                                                 430],

      ['',                                             499],
      ['謎のエラーです。作った人に報告してください。', 500],
      ['',                                             501],

      ['', {}],
      ['', 'a'],
      ['', null],
    ];

    for (const suite of suites) {
      expect(ApiTools._getErrorMessageForAdmin(suite[1])).toBe(suite[0]);
    }
  });
});

describe('makeFormData', () => {
  const _f = (params) => {
    const f = new FormData();

    for (const [k, v] of Object.entries(params)) {
      f.append(k, v);
    }

    return f;
  };

  test('[OK] is match form data', () => {
    const suites = [
      // expect, params
      [_f({aaa: 1, bbb: 'a'}), {aaa: 1, bbb: 'a'}],
      [_f({'aaa[bbb]': 1, 'aaa[ccc]': 0}), {aaa: {bbb: true, ccc: false}}],
    ];

    for (const suite of suites) {
      const result      = ApiTools.makeFormData(suite[1])
      const resultArray = [];
      resultArray.push(...result.entries());

      const expectArray = [];
      expectArray.push(...suite[0].entries());

      expect(result).toBeInstanceOf(FormData);
      expect(resultArray).toEqual(expectArray);
    }
  });
});

describe('_objectExpand', () => {

  test('[OK] is match object', () => {
    const suites = [
      // expect, params
      [{hoge: 0, fuga: 'test'},                    {hoge: 0, fuga: 'test'}],
      [{'hoge[aaa]': 0},                           {hoge: {aaa: 0}}],
      [{'aaa[bbb][111]': 'a', 'aaa[bbb][ccc]': 1}, {aaa: {bbb: {111: 'a', ccc: 1}}}],
      [{abc: null, 'aaa[bbb]': null},               {abc: null, aaa: {bbb: null}}],
    ];

    for (const suite of suites) {
      expect(ApiTools._objectExpand(suite[1])).toMatchObject(suite[0]);
    }
  });
});
