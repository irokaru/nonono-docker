let isHack = false;
const readTxt = ['mod_a', 'mod_b', 'mod_c', 'mod_d', 'mod_e', 'mod_f'];
const txtOpt = {
    speed: 100,
    delay: 250
}
let count = 0;
let txt = [];
let txtCount = [];

$(function () {
    $('#hack').click(function () {
        $('#modal').fadeIn(100);
        if (isHack) return;
        delTxt();
        setCount();
        itimozi();
        isHack = true;
    });
    $('#modal').click(function () {
        $('#modal').fadeOut();
    });
});

function delTxt() {
    for (let i = 0; i < readTxt.length; i++) {
        readTxt[i] = document.getElementById(readTxt[i]);
        txt[i] = readTxt[i].firstChild.nodeValue;
        readTxt[i].innerHTML = '';
    }
}

function setCount() {
    for (let i = 0; i < readTxt.length; i++) {
        txtCount[i] = 0;
    }
}

function itimozi() { //　一文字ずつ表示させる
    readTxt[count].innerHTML = txt[count].substr(0, ++txtCount[count]) + "_"; // テキストの指定した数の間の要素を表示
    if (txt[count].length != txtCount[count]) { // Count が初期の文字列の文字数と同じになるまでループ
        setTimeout("itimozi()", txtOpt.speed); // 次の文字へ進む
    } else {
        readTxt[count].innerHTML = txt[count].substr(0, ++txtCount[count]); // テキストの指定した数の間の要素を表示
        count++; // 次の段落に進む為のカウントアップ
        if (count != readTxt.length) { // id数が最後なら終了
            setTimeout("itimozi()", txtOpt.delay); // 次の段落へ進む
        }
    }
}