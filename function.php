<?php
// ===================================
// ini_set(ログ・タイムゾーン）設定
// ===================================
//全てのエラーを報告する
error_reporting(E_ALL);
//画面にエラーを表示する
ini_set('display_errors', 'on');
//ログを取るか
ini_set('log_errors', 'on');
//ログの出力ファイルを指定
ini_set('error_log', 'php.log');
//タイムゾーン設定
ini_set('date.timezone', 'Asia/Tokyo');

// ===================================
// デバッグ
// ===================================
// デバッグフラグ（開発中のみtrue）
$debug_flg = true;
// デバッグログ関数
function debug($str)
{
  global $debug_flg;
  if (!empty($debug_flg)) {
    error_log('***** デバッグ ****' . $str);
  }
}

//セッション開始
session_start();

// ===================================
// 変数（初期）
// ===================================
// 相手格納用
$enemy = array();

// ===================================
// クラス
// ===================================
// 遊びインターフェイス
interface PlayInterface
{
  public static function canBeUsedHand($patten);
  public static function canBeUseDirection($pattern);
}
// 遊びクラス
class Play implements PlayInterface
{
  // 使用可能なじゃんけんの手を決める
  public static function canBeUsedHand($pattern)
  {
    $pattern = (int)$pattern;
    debug('***** canBeUsedHand 実行 *****');
    // じゃんけん
    $janken = array('guu', 'choki', 'paa');
    if ($pattern === 1) {  //ぐーちょきぱー全部
      return $janken;
    } elseif ($pattern === 2) {  //ぐーちょきぱーから2つ選ぶ
      $keys = array_rand($janken, 2);
      $select1 = $janken[$keys[0]];
      $select2 = $janken[$keys[1]];
      $janken = array($keys[0] => $select1, $keys[1] => $select2);
      return $janken;
    } elseif ($pattern === 3) {  //ぐーちょきぱーから1つ選ぶ
      $key = array_rand($janken);
      $select1 = $janken[$key];
      $janken = array($key => $select1);
      return $janken;
    } elseif ($pattern === 4) {  //ぐーだけ
      $choice = array_slice($janken, 0, 1, true);
      $janken = $choice;
      return $janken;
    } elseif ($pattern === 5) {  //ちょきだけ
      $choice = array_slice($janken, 1, 1, true);
      $janken = $choice;
      return $janken;
    } elseif ($pattern === 6) {  //ぱーだけ
      $choice = array_slice($janken, 2, 1, true);
      $janken = $choice;
      return $janken;
    }
    debug('使えるじゃんけんの手→→→' . print_r($janken, true));
  }
  // 使用可能なあっちむいてホイの方向を決める
  public static function canBeUseDirection($pattern)
  {
    $pattern = (int)$pattern;
    debug('***** canBeUsedDirection 実行 *****');
    // あっちむいてホイ
    $hoi = array('up', 'down', 'left', 'right');
    if ($pattern === 1) {  //上下左右全部使える
      return $hoi;
    } elseif ($pattern === 2) {  //ランダムで3つ選ぶ
      $keys = array_rand($hoi, 3);
      $select1 = $hoi[$keys[0]];
      $select2 = $hoi[$keys[1]];
      $select3 = $hoi[$keys[2]];
      $hoi = array($keys[0] => $select1, $keys[1] => $select2, $keys[2] => $select3);
      return $hoi;
    } elseif ($pattern === 3) {  //ランダムで2つ選ぶ
      $keys = array_rand($hoi, 2);
      $select1 = $hoi[$keys[0]];
      $select2 = $hoi[$keys[1]];
      $hoi = array($keys[0] => $select1, $keys[1] => $select2);
      return $hoi;
    } elseif ($pattern === 4) {  //ランダムで1つ選ぶ
      $key = array_rand($hoi);
      $select1 = $hoi[$key];
      $hoi = array($key => $select1);
      return $hoi;
    } elseif ($pattern === 5) {  //上だけ
      $choice = array_slice($hoi, 0, 1, true);
      $hoi = $choice;
      return $hoi;
    } elseif ($pattern === 6) {  //下だけ
      $choice = array_slice($hoi, 1, 1, true);
      $hoi = $choice;
      return $hoi;
    } elseif ($pattern === 7) {  //左だけ
      $choice = array_slice($hoi, 2, 1, true);
      $hoi = $choice;
      return $hoi;
    } elseif ($pattern === 8) {  //右だけ
      $choice = array_slice($hoi, 3, 1, true);
      $hoi = $choice;
      return $hoi;
    }
    debug('使えるあっちむいてホイの方向→→→' . print_r($hoi, true));
  }
}
// 種別クラス
class Type
{
  const MAN1 = 1;
  const MAN2 = 2;
  const WOMAN1 = 3;
  const WOMAN2 = 4;
  const BOY1 = 5;
  const BOY2 = 6;
  const GIRL1 = 7;
  const GIRL2 = 8;
  const DOG = 9;
  const CAT = 10;
}
// じゃんけんパターンクラス
class jankenPattern
{
  const ALL = 1;
  const RAND2 = 2;
  const RAND1 = 3;
  const GUU = 4;
  const CHOKI = 5;
  const PAA = 6;
}
// あっちむいてホイパターンクラス
class hoiPattern
{
  const ALL = 1;
  const RAND3 = 2;
  const RAND2 = 3;
  const RAND1 = 4;
  const UP = 5;
  const DOWN = 6;
  const LEFT = 7;
  const RIGHT = 8;
}
// 相手クラス
class Enemy
{
  private $name;
  private $hp;
  private $img1;
  private $img2;
  private $img3;
  private $jHand;
  private $hDirection;

  public function __construct($name, $hp, $type, $img1, $img2, $img3, $jHand, $hDirection)
  {
    $this->name = $name;
    $this->hp = $hp;
    $this->type = $type;
    $this->img1 = $img1;
    $this->img2 = $img2;
    $this->img3 = $img3;
    $this->jHand = $jHand;
    $this->hDirection = $hDirection;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setHp($num)
  {
    $this->hp = $num;
  }

  public function getHp()
  {
    return $this->hp;
  }

  public function getType()
  {
    return $this->type;
  }

  public function getImg1()
  {
    return $this->img1;
  }

  public function getImg2()
  {
    return $this->img2;
  }

  public function getImg3()
  {
    return $this->img3;
  }

  public function getJHand()
  {
    return $this->jHand;
  }

  public function getHDirection()
  {
    return $this->hDirection;
  }

  // セリフ
  public function sayWord($str)
  {
    // セッションwordが作られてなければ作る
    if (empty($_SESSION['word'])) $_SESSION['word'] = '';
    $_SESSION['word'] = $str;
  }
  // 挨拶
  public function sayGreeting()
  {
    switch ($this->type) {
      case Type::MAN1:
        $this->sayWord('よーし、しょうぶだ！');
        break;
      case Type::MAN2:
        $this->sayWord('よろしくね');
        break;
      case Type::WOMAN1:
        $this->sayWord('いくわよ♪');
        break;
      case Type::WOMAN2:
        $this->sayWord('うふふ♡');
        break;
      case Type::BOY1:
        $this->sayWord('がんばるぞー');
        break;
      case TYPE::BOY2:
        $this->sayWord('いくぞー');
        break;
      case TYPE::GIRL1:
        $this->sayWord('やっほ〜');
        break;
      case TYPE::GIRL2:
        $this->sayWord('るんるん♪');
        break;
      case TYPE::DOG:
        $this->sayWord('しょうぶだワン！');
        break;
      case TYPE::CAT:
        $this->sayWord('ニャァ〜');
        break;
    }
  }
  // 勝利
  public function sayWin()
  {
    switch ($this->type) {
      case Type::MAN1:
        $this->sayWord('やった！');
        break;
      case Type::MAN2:
        $this->sayWord('ぼくの　かちだね');
        break;
      case Type::WOMAN1:
        $this->sayWord('やったわ♪');
        break;
      case Type::WOMAN2:
        $this->sayWord('わたしの　かちね♡');
        break;
      case Type::BOY1:
        $this->sayWord('わーい！');
        break;
      case TYPE::BOY2:
        $this->sayWord('かったー！');
        break;
      case TYPE::GIRL1:
        $this->sayWord('やった〜');
        break;
      case TYPE::GIRL2:
        $this->sayWord('えへへ♪');
        break;
      case TYPE::DOG:
        $this->sayWord('かったワン！');
        break;
      case TYPE::CAT:
        $this->sayWord('かったニャ〜');
        break;
    }
  }
  // 敗北
  public function sayLose()
  {
    switch ($this->type) {
      case Type::MAN1:
        $this->sayWord('まけた！');
        break;
      case Type::MAN2:
        $this->sayWord('ぼくの　まけだね');
        break;
      case Type::WOMAN1:
        $this->sayWord('まけちゃったわ');
        break;
      case Type::WOMAN2:
        $this->sayWord('わたしの　まけね');
        break;
      case Type::BOY1:
        $this->sayWord('まけた！');
        break;
      case TYPE::BOY2:
        $this->sayWord('わーん');
        break;
      case TYPE::GIRL1:
        $this->sayWord('まけちゃった〜');
        break;
      case TYPE::GIRL2:
        $this->sayWord('えーんえーん');
        break;
      case TYPE::DOG:
        $this->sayWord('まけたワン！');
        break;
      case TYPE::CAT:
        $this->sayWord('まけたニャ〜');
        break;
    }
  }
  // 引き分け
  public function sayDraw()
  {
    switch ($this->type) {
      case Type::MAN1:
        $this->sayWord('あいこだ');
        break;
      case Type::MAN2:
        $this->sayWord('あいこだったね');
        break;
      case Type::WOMAN1:
        $this->sayWord('あいこだわ');
        break;
      case Type::WOMAN2:
        $this->sayWord('あいこね');
        break;
      case Type::BOY1:
        $this->sayWord('あいこ！');
        break;
      case TYPE::BOY2:
        $this->sayWord('あいこ！');
        break;
      case TYPE::GIRL1:
        $this->sayWord('あいこ〜');
        break;
      case TYPE::GIRL2:
        $this->sayWord('あいこ〜');
        break;
      case TYPE::DOG:
        $this->sayWord('あいこだワン！');
        break;
      case TYPE::CAT:
        $this->sayWord('あいこニャ〜');
        break;
    }
  }
  // あっちむいてホイ（回避)
  public function sayAvoid()
  {
    switch ($this->type) {
      case Type::MAN1:
        $this->sayWord('あぶなかった！');
        break;
      case Type::MAN2:
        $this->sayWord('まだ　だいじょうぶ');
        break;
      case Type::WOMAN1:
        $this->sayWord('あぶなかったわ');
        break;
      case Type::WOMAN2:
        $this->sayWord('あぶない　あぶない');
        break;
      case Type::BOY1:
        $this->sayWord('セーフ！');
        break;
      case TYPE::BOY2:
        $this->sayWord('よかった〜');
        break;
      case TYPE::GIRL1:
        $this->sayWord('セーフね！');
        break;
      case TYPE::GIRL2:
        $this->sayWord('うふふん');
        break;
      case TYPE::DOG:
        $this->sayWord('だいじょうぶだったワン！');
        break;
      case TYPE::CAT:
        $this->sayWord('ニャ〜ン');
        break;
    }
  }
  // あっちむいてホイ（外す）
  public function sayMiss()
  {
    switch ($this->type) {
      case Type::MAN1:
        $this->sayWord('ざんねん！');
        break;
      case Type::MAN2:
        $this->sayWord('あたらなかったか〜');
        break;
      case Type::WOMAN1:
        $this->sayWord('ざんねーん');
        break;
      case Type::WOMAN2:
        $this->sayWord('あらあら');
        break;
      case Type::BOY1:
        $this->sayWord('あちゃー');
        break;
      case TYPE::BOY2:
        $this->sayWord('ダメだった〜');
        break;
      case TYPE::GIRL1:
        $this->sayWord('やだ〜');
        break;
      case TYPE::GIRL2:
        $this->sayWord('えー　うそー');
        break;
      case TYPE::DOG:
        $this->sayWord('あてられなかったワン！');
        break;
      case TYPE::CAT:
        $this->sayWord('ニャオ〜');
        break;
    }
  }

  // じゃんけんする(選ばれた手の中からじゃんけんする)
  public function playJanken()
  {
    debug('***** playJanken 実行*****');
    debug('使えるじゃんけんの手→→→' . print_r($this->jHand, true));
    $key = array_rand($this->jHand);
    debug('選んだじゃんけん→→→' . print_r($this->jHand[$key], true));
    return $key;
  }
  // あっちむいてホイする(選ばれた方向の中から選択する)
  public function playHoi()
  {
    debug('***** playHoi 実行 *****');
    debug('使える方向→→→' . print_r($this->hDirection));
    $key = array_rand($this->jHand);
    debug('選んだ方向→→→' . print_r($this->jHand[$key], true));
    return $key;
  }
}
