<?php
// ===================================
// ini_set(ログ・タイムゾーン）設定
// ===================================
//全てのエラーを報告する
error_reporting(E_ALL);
//画面にエラーを表示する
ini_set('display_errors','on');
//ログを取るか
ini_set('log_errors','on');
//ログの出力ファイルを指定
ini_set('error_log','php.log');
//タイムゾーン設定
ini_set('date.timezone','Asia/Tokyo');

// ===================================
// デバッグ
// ===================================
// デバッグフラグ（開発中のみtrue）
$debug_flg = true;
// デバッグログ関数
function debug($str){
  global $debug_flg;
  if(!empty($debug_flg)) {
    error_log('***** デバッグ ****'.$str);
  }
}

//セッション開始
session_start();

// ===================================
// 変数（初期）
// ===================================
?>