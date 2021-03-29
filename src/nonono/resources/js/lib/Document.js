import marked from 'marked';
import hljs   from 'highlight.js';

export default {
  /**
   * mdからhtmlに変換するやつ
   * @param {string} md
   * @returns {string}
   */
  md2html(md) {
    return marked(md);
  },

  /**
   * html内の素のリスト要素にclassを追加する
   * @param {string} html
   * @returns {string}
   */
  addClassToList(html) {
    return html.replace(/<ul>/g, "<ul class='dash'>").replace(/<li>/g, "<li class='arrow'>");
  },

  /**
   * html内のaタグ全てにblankを付与する
   * @param {string} html
   * @returns {string}
   */
  anchorBlank(html) {
    const anchors = html.match(/<a href=("(.+?)"|'(.+?)')/g);

    if (anchors !== null) {
      anchors.map(anchor => {
        html = html.replace(anchor, anchor + 'target="_blank"');
      });
    }

    return html;
  },

  /**
   * html内のコードブロックにハイライトを付ける
   * @param {string} html
   * @returns {string}
   */
  highlightCode(html) {
    const codes = this._getCode(html);

    if (codes === []) {
      return html;
    }

    const highlights = [];
    for (const code of codes) {
      highlights.push(hljs.highlightAuto(code).value);
    }

    for (let i = 0; i < highlights.length; i++) {
      html = html.replace(codes[i], highlights[i]).replace('<pre><code>', '<pre><code class="hljs">');
    }

    return html;
  },

  /**
   * html内のcodeタグ内の文字列を配列で取得する
   * @param {string} html
   * @returns {array}
   */
  _getCode(html) {
    let codes = html.match(/<pre><code>(.|\s)*?<\/code><\/pre>/g);

    if (codes === null) {
      return [];
    }

    const ret = [];

    for (const code of codes) {
      ret.push(code.replace('<pre><code>', '').replace('</code></pre>', ''));
    }

    return codes;
  },
};
