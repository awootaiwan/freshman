freshman markdown功能簡介
===
功能介紹
===

工作區
===
## 模式
左半邊 - markdown編輯頁面
右半邊 - markdown預覽頁面 （使用者在前台所看到的內容）
F10 - 按鍵可開啟全螢幕編輯模式
esc - 可離開全螢幕編輯模式


編輯
===
## 快速鍵
跟又快又方便的Sublime text很像
> 更多訊息請至 [這裡](https://codemirror.net/demo/sublime.html)

## 標題
會使用 **第一個第一級標頭** 作為標題

## 標籤
###### tags: `功能` `酷` `更新`

## ToC:
請使用此語法 `[[TOC]]` 將目錄嵌入到您的課程之中

[[TOC]]

## 表情符號
您可以像是這樣使用表情符號 :smile: :smiley: :cry: :wink: :mortar_board:
> 完整的表情符號列表 [在這裡](http://www.emoji-cheat-sheet.com/)

## 待辦清單
- [ ] 待辦
    - [x] 買些沙拉
    - [x] 刷牙
    - [ ] 喝水

## 程式碼區塊
我們支援非常多程式語言，使用自動完成來看看有些什麼
```javascript
var s = "JavaScript syntax highlighting";
alert(s);
function $initHighlight(block, cls) {
    try {
        if (cls.search(/\bno\-highlight\b/) != -1)
        return process(block, true, 0x0F) + 
                ' class=""';
    } catch (e) {
        /* handle exception */
    }
    for (var i = 0 / 2; i < classes.length; i++) {
        if (checkCondition(classes[i]) === undefined)
        return /\d+[\s/]/g;
    }
}
```


## 外部

### iframe
/i/https://drive.google.com/file/d/16zfZFq35UW1bGkrZwEHS-uE8-ATDZZVs/preview

### Youtube

@[youtube](9A0VpcK_oNk)

### Vimeo

@[vimeo](124148255)


### PDF
**注意：請使用 https 的網址，否則可能會被您的瀏覽器阻擋載入**

@[pdf](https://papers.nips.cc/paper/5346-sequence-to-sequence-learning-with-neural-networks.pdf)

### 網站傳送訊息功能
```
[按鈕的標題]{%message 這邊輸入要傳送給管理員的訊息 %}
```
[我是按鈕的標題]{%message 這邊輸入要傳送給管理員的訊息 %}

警告區塊
---
:::success
成功 :tada:
:::

:::info
這是訊息 :mega:
:::

:::warning
注意 :zap:
:::

:::danger
危險 :fire:
:::


## 排版

### 標頭

```
# h1 標頭
## h2 標頭
### h3 標頭
#### h4 標頭
##### h5 標頭
###### h6 標頭
```

### 水平分隔線

___

---

***


### 字形替換

(c) (C) (r) (R) (tm) (TM) (p) (P) +-

測試.. 測試... 測試..... 測試?..... 測試!....

!!!!!! ???? ,,

Remarkable -- awesome

"Smartypants, 雙引號"

'Smartypants, 單引號'

### 強調

**這是粗體文字**

__這是粗體文字__

*這是斜體文字*

_這是斜體文字_

~~這是刪除文字~~

上標： 19^th^

下標： H~2~O

++這是底線文字++

==這是標記文字==


### 引用區塊


> 引用區塊也可以是巢狀的喔...
>> ...可以多層次的使用...
> > > ...或是用空白隔開 


### 清單

#### 項目

+ 在行開頭使用 `+` `-` 或是 `*` 來建立清單
+ 空兩個空白就可以產生子清單
- 當清單標記使用的字元不同，會強制建立新的清單
    * Ac tristique libero volutpat at
    + Facilisis in pretium nisl aliquet
    - Nulla volutpat aliquam velit
+ 非常簡單！

#### 編號

1. Lorem ipsum dolor sit amet
2. Consectetur adipiscing elit
3. Integer molestie lorem at massa


1. 您可以逐次增加項目數字...
1. ...或是全部都使用 `1.`
1. feafw
2. 332
3. 242
4. 2552
1. e2

從其他範圍開始編號清單

57. foo
1. bar

### 程式碼

行內 `程式碼`

縮排程式碼

    // Some comments
    line 1 of code
    line 2 of code
    line 3 of code


程式碼區塊

```
Sample text here...
```

語法標色

```javascript
var foo = function (bar) {
return bar++;
};

console.log(foo(5));
```

### 表格

| 選項 | 描述 |
| ------ | ----------- |
| data   | path to data files to supply the data that will be passed into templates. |
| engine | engine to be used for processing templates. Handlebars is the default. |
| ext    | extension to be used for dest files. |

向右對齊

| 選項 | 描述 |
| ------:| -----------:|
| data   | path to data files to supply the data that will be passed into templates. |
| engine | engine to be used for processing templates. Handlebars is the default. |
| ext    | extension to be used for dest files. |

向左對齊

| 選項 | 描述 |
|:------ |:----------- |
| data   | path to data files to supply the data that will be passed into templates. |
| engine | engine to be used for processing templates. Handlebars is the default. |
| ext    | extension to be used for dest files. |

置中對齊

| 選項 | 描述 |
|:------:|:-----------:|
| data   | path to data files to supply the data that will be passed into templates. |
| engine | engine to be used for processing templates. Handlebars is the default. |
| ext    | extension to be used for dest files. |


### 連結
[連結文字](http://dev.nodeca.com)
[加上標題的連結文字](http://nodeca.github.io/pica/demo/ "標題文字！")
自動轉換連結 https://github.com/nodeca/pica


### 圖片
![Minion](https://octodex.github.com/images/minion.png)
![Stormtroopocat](https://octodex.github.com/images/stormtroopocat.jpg "The Stormtroopocat")
如同連結一般，圖片也可以用註腳語法
![Alt text][id]
使用參考，可以在稍後的文件中再定義圖片網址

[id]: https://octodex.github.com/images/dojocat.jpg  "The Dojocat"

![Minion](https://octodex.github.com/images/minion.png =200x200)
使用指定的大小顯示圖片


### 定義清單

名詞 1

:   定義 1 快速連續項目

名詞 2 加上 *行內標記*

:   定義 2

        { 這些程式碼屬於 定義 2 的一部分 }

    定義 2 的第三段落

_緊密樣式：_

名詞 1
~ 定義 1

名詞 2
~ 定義 2a
~ 定義 2b

### 縮寫

這是 HTML 的縮寫範例
它會轉換 "HTML"，但是縮寫旁邊其他的部分，例如："xxxHTMLyyy"，不受影響

*[HTML]: Hyper Text Markup Language