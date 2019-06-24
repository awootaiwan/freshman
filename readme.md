Freshman v1.2.4
===

:accept: 需加入`awoo團隊`才能使用本專案 

## Introduction

本專案可有效解決公司新人到職時，不知要找誰報到或者做什麼事，系統會將主管事先寫好的流程依每個分配給新人，新人只需照著流程跑完，可以快速的完成新手所要做的事情，並且能讓新人很快的進入狀況。

除了流程系統，本專案還提供上手教程系統，主管須先建立部門所需的課程，加入教程內，新人就可以透過上手教程觀看教學，減少新人找資料的時間，就能快速地上手了 :100:。 

## Feature

* 遊客登入： 沒有會員可先透過遊客登入看前面的流程

* 會員登入： Google Sign-In 使用申請的awoo google帳號即可進入

* 新人前台

    1. 入門攻略：可以清楚瞭解，報到流程、工具使用…等等。

    2. 上手教程：新人所需的資訊補充，使新人能夠快速上手。

* 管理者後台

    1. 管理流程項目：可以管理所有流程。
    
    2. 指定部門流程：可以指定部門需要流程。
    
    3. 指定新人流程：可以指定新人額外需要走的流程。
    
    4. 教程學習管理：編輯想要新人學習課程並組成教程。
    
    5. 部門管理：管理員可以新增、刪除、修改部門。
    
    6. 人員管理：管理員可以新增、修改新人帳號。

## Installation

``` bash
$ git clone https://github.com/awootaiwan/freshman.git freshman

$ cd freshman

$ git submodule update --init

$ vim .htaccess
```

### .htaccess內容

``` shell
<Files ~ "^\.(htaccess|htpasswd|git|svn)$">
deny from all
</Files>
SetEnv CI_ENV development  #環境變數  production or development
Options -Indexes
RewriteEngine on
RewriteBase /freshman
RewriteCond $1 !^(index.php|css|images|js|favicon.ico|$)
RewriteRule ^(.*)$ index.php/$1 [L,QSA]

```
### dbConfig配置

``` bash
$ mkdir dbConfig

$ cd dbConfig

$ vim freshman_dev_db.json
```

### freshman_dev_db.json內容
> 資料庫連線資料

測試機資料庫名稱使用：`intern_dev_db`  

正式機資料庫名稱使用：`intern_stage_db`
```json
{ 
    "type": "mysql",
    "database": "intern_dev_db",   
    "username": "帳號",
    "password": "密碼",
    "host": "資料庫位址ip"
}
```
### config 配置
``` bash
$ mkdir application/config/development

$ mkdir application/config/production

$ cp application/config/config.php application/config/development/config.php

$ cp application/config/config.php application/config/production/config.php
```
```
修改devplopment and production 內的config.php 
$config['base_url']變數 改成網站的網址
```
## Submodule Project

需加入`awoo團隊`才能使用

1. [bob-the-builder v3](https://github.com/awootaiwan/bob-the-builder)

2. [model v2](https://github.com/awootaiwan/model)

## 遊客登入

![](https://github.com/awootaiwan/freshman/blob/master/images/gifs/%E9%81%8A%E5%AE%A2%E7%99%BB%E5%85%A5.gif)

## 入門攻略

![](https://github.com/awootaiwan/freshman/blob/master/images/gifs/%E5%85%A5%E9%96%80%E6%94%BB%E7%95%A5.gif)

## 上手教程

![](https://github.com/awootaiwan/freshman/blob/master/images/gifs/%E4%B8%8A%E6%89%8B%E6%95%99%E7%A8%8B.gif)

## 管理流程項目

![](https://github.com/awootaiwan/freshman/blob/master/images/gifs/%E6%B5%81%E7%A8%8B%E9%A0%85%E7%9B%AE%E7%AE%A1%E7%90%86.gif)

![](https://github.com/awootaiwan/freshman/blob/master/images/gifs/%E9%A0%85%E7%9B%AE%E6%B5%81%E7%A8%8B%E6%8E%92%E5%BA%8F.gif)

## 指定部門流程

![](https://github.com/awootaiwan/freshman/blob/master/images/gifs/%E6%8C%87%E5%AE%9A%E9%83%A8%E9%96%80%E6%B5%81%E7%A8%8B.gif)

## 指定新人流程

![](https://github.com/awootaiwan/freshman/blob/master/images/gifs/%E6%8C%87%E5%AE%9A%E6%96%B0%E4%BA%BA%E6%B5%81%E7%A8%8B.gif)

## 教程學習管理

![](https://github.com/awootaiwan/freshman/blob/master/images/gifs/%E6%95%99%E7%A8%8B%E7%AE%A1%E7%90%86.gif)

## 部門管理

![](https://github.com/awootaiwan/freshman/blob/master/images/gifs/%E9%83%A8%E9%96%80%E7%AE%A1%E7%90%86.gif)

## 人員管理

![](https://github.com/awootaiwan/freshman/blob/master/images/gifs/%E4%BA%BA%E5%93%A1%E7%AE%A1%E7%90%86.gif)
