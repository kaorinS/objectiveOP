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
$enemies = array();
// あっちむいてホイの結果
$hoiResult = '';
// じゃんけん、あっちむいてホイの結果表示用コメント
$playResultDisplay = '';
// 結果画面のグレード
$excellent = 10;
$great = 5;

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
  }
  // 使用可能なあっちむいてホイの方向を決める
  public static function canBeUseDirection($pattern)
  {
    $pattern = (int)$pattern;
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
  const GRANDPARENTS = 11;
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
// 生き物クラス
abstract class Life
{
  protected $hp;
  protected $hpMax;
  public function setHp($num)
  {
    $this->hp = $num;
  }

  public function getHp()
  {
    return $this->hp;
  }

  public function getHpMax()
  {
    return $this->hpMax;
  }

  // ライフを1減らす
  public function Damage()
  {
    $this->setHp($this->getHp() - 1);
  }
}
// 相手クラス
class Enemy extends Life
{
  private $name;
  private $imgNormal;
  private $imgLaugh;
  private $imgSad;
  private $jHand;
  private $hDirection;

  public function __construct($name, $hp, $type, $imgNormal, $imgLaugh, $imgSad, $jHand, $hDirection)
  {
    $this->name = $name;
    $this->hp = $hp;
    $this->hpMax = $hp;
    $this->type = $type;
    $this->imgNormal = $imgNormal;
    $this->imgLaugh = $imgLaugh;
    $this->imgSad = $imgSad;
    $this->jHand = $jHand;
    $this->hDirection = $hDirection;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getType()
  {
    return $this->type;
  }

  public function getImgNormal()
  {
    return $this->imgNormal;
  }

  public function getImgLaugh()
  {
    return $this->imgLaugh;
  }

  public function getImgSad()
  {
    return $this->imgSad;
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
        $this->sayWord('よーし、<br>しょうぶだ！');
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
      case TYPE::GRANDPARENTS:
        $this->sayWord('よいしょっと');
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
      case TYPE::GRANDPARENTS:
        $this->sayWord('ほっほっほ');
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
      case TYPE::GRANDPARENTS:
        $this->sayWord('おやまあ');
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
      case TYPE::GRANDPARENTS:
        $this->sayWord('あいこだねぇ');
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
      case TYPE::GRANDPARENTS:
        $this->sayWord('ほっほっほ');
        break;
    }
  } // あっちむいてホイ（当てられた)
  public function sayPicked()
  {
    switch ($this->type) {
      case Type::MAN1:
        $this->sayWord('しまった！');
        break;
      case Type::MAN2:
        $this->sayWord('あたってしまったね');
        break;
      case Type::WOMAN1:
        $this->sayWord('きゃ〜');
        break;
      case Type::WOMAN2:
        $this->sayWord('あたっちゃったわ');
        break;
      case Type::BOY1:
        $this->sayWord('やっちゃった！');
        break;
      case TYPE::BOY2:
        $this->sayWord('うわわ');
        break;
      case TYPE::GIRL1:
        $this->sayWord('うそ〜');
        break;
      case TYPE::GIRL2:
        $this->sayWord('いや〜ん');
        break;
      case TYPE::DOG:
        $this->sayWord('クゥ〜ン！');
        break;
      case TYPE::CAT:
        $this->sayWord('ニャァ〜');
        break;
      case TYPE::GRANDPARENTS:
        $this->sayWord('ホッホッホ');
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
      case TYPE::GRANDPARENTS:
        $this->sayWord('ホッホッホ');
        break;
    }
  } // あっちむいてホイ（命中）
  public function sayHit()
  {
    switch ($this->type) {
      case Type::MAN1:
        $this->sayWord('よ〜し！');
        break;
      case Type::MAN2:
        $this->sayWord('ふふふっ');
        break;
      case Type::WOMAN1:
        $this->sayWord('当てたわっ！');
        break;
      case Type::WOMAN2:
        $this->sayWord('うふふ♡');
        break;
      case Type::BOY1:
        $this->sayWord('よっしゃー！');
        break;
      case TYPE::BOY2:
        $this->sayWord('やったやった！');
        break;
      case TYPE::GIRL1:
        $this->sayWord('やった〜〜');
        break;
      case TYPE::GIRL2:
        $this->sayWord('わーいわーい');
        break;
      case TYPE::DOG:
        $this->sayWord('やったワン！');
        break;
      case TYPE::CAT:
        $this->sayWord('ニャァ〜〜ン');
        break;
      case TYPE::GRANDPARENTS:
        $this->sayWord('ほっほっほ');
        break;
    }
  }

  // じゃんけんで出す手を選ぶ(選ばれた手の中からじゃんけんする)
  public function selectHand()
  {
    debug('***** selectHand 実行*****');
    debug('相手が使えるじゃんけんの手→→→' . print_r($this->jHand, true));
    $key = array_rand($this->jHand);
    debug('相手が選んだじゃんけん→→→' . print_r($this->jHand[$key], true));
    return $key;
  }
  // あっちむいてホイする(選ばれた方向の中から選択する)
  public function selectDirection()
  {
    debug('***** selectDirection 実行 *****');
    debug('相手が使える方向→→→' . print_r($this->hDirection));
    $key = array_rand($this->jHand);
    debug('相手が選んだ方向→→→' . print_r($this->jHand[$key], true));
    return $key;
  }
}
// プレイヤー
class Myself extends Life
{
  public function __construct($hp, $hpMax)
  {
    $this->hp = $hp;
    $this->hpMax = $hpMax;
  }

  // HPの回復
  public function recovery()
  {
    if (!mt_rand(0, 3)) {  //4分の1でHPを2回復する
      $this->hp += 2;
    } else {
      $this->hp += 1;
    }
    // 最大値を超えないようにする
    $this->hp = min($this->hp, $this->hpMax);
    return $this->hp;
  }
}

// ===================================
// インスタンス生成
// ===================================
// プレイヤー自身
$myself = new Myself(10, 10);
// 相手
$enemies[] = new Enemy('おにいさん', mt_rand(2, 3), Type::MAN1, 'image/blackman1_smile.png', 'image/blackman1_laugh.png', 'image/blackman1_cry.png', Play::canBeUsedHand(jankenPattern::ALL), Play::canBeUseDirection(hoiPattern::RAND2));
$enemies[] = new Enemy('おねえさん', mt_rand(2, 3), Type::WOMAN1, 'image/blackwoman1_smile.png', 'image/blackwoman1_laugh.png', 'image/blackwoman1_cry.png', Play::canBeUsedHand(jankenPattern::RAND1), Play::canBeUseDirection(hoiPattern::RAND3));
$enemies[] = new Enemy('おとこのこ', 3, Type::BOY1, 'image/boy03_smile.png', 'image/boy01_laugh.png', 'image/boy04_cry.png', Play::canBeUsedHand(jankenPattern::RAND2), Play::canBeUseDirection(hoiPattern::RAND1));
$enemies[] = new Enemy('おにいさん', 2, Type::MAN2, 'image/business03_smile.png', 'image/business01_laugh.png', 'image/business04_cry.png', Play::canBeUsedHand(jankenPattern::ALL), Play::canBeUseDirection(hoiPattern::RAND2));
$enemies[] = new Enemy('ねこちゃん', mt_rand(1, 3), Type::CAT, 'image/cat1_smile.png', 'image/cat4_laugh.png', 'image/cat3_cry.png', Play::canBeUsedHand(jankenPattern::GUU), Play::canBeUseDirection(hoiPattern::RAND1));
$enemies[] = new Enemy('おいしゃさん', mt_rand(2, 3), Type::MAN2, 'image/doctor1_smile.png', 'image/doctor1_laugh.png', 'image/doctor1_cry.png', Play::canBeUsedHand(jankenPattern::RAND2), Play::canBeUseDirection(hoiPattern::ALL));
$enemies[] = new Enemy('おいしゃさん', mt_rand(2, 3), Type::WOMAN1, 'image/doctorw1_smile.png', 'image/doctorw1_laugh.png', 'image/doctorw1_cry.png', Play::canBeUsedHand(jankenPattern::CHOKI), Play::canBeUseDirection(hoiPattern::RAND2));
$enemies[] = new Enemy('いぬさん', mt_rand(2, 4), Type::DOG, 'image/dog1_smile.png', 'image/dog4_laugh.png', 'image/dog3_cry.png', Play::canBeUsedHand(jankenPattern::RAND1), Play::canBeUseDirection(hoiPattern::RAND1));
$enemies[] = new Enemy('おんなのこ', mt_rand(2, 3), Type::GIRL1, 'image/girl03_smile.png', 'image/girl01_laugh.png', 'image/girl04_cry.png', Play::canBeUsedHand(jankenPattern::RAND2), Play::canBeUseDirection(hoiPattern::DOWN));
$enemies[] = new Enemy('おとうさん', mt_rand(3, 4), Type::MAN1, 'image/man03_smile.png', 'image/man01_laugh.png', 'image/man04_cry.png', Play::canBeUsedHand(jankenPattern::ALL), Play::canBeUseDirection(hoiPattern::ALL));
$enemies[] = new Enemy('かんごしさん', 2, Type::MAN1, 'image/nurse_man1_smile.png', 'image/nurse_man1_laugh.png', 'image/nurse_man1_cry.png', Play::canBeUsedHand(jankenPattern::PAA), Play::canBeUseDirection(hoiPattern::RAND3));
$enemies[] = new Enemy('おばあちゃん', mt_rand(1, 3), Type::GRANDPARENTS, 'image/obaasan03_smile.png', 'image/obaasan01_laugh.png', 'image/obaasan01_laugh.png', Play::canBeUsedHand(jankenPattern::RAND1), Play::canBeUseDirection(hoiPattern::RAND1));
$enemies[] = new Enemy('おかあさん', mt_rand(3, 4), Type::WOMAN2, 'image/obasan03_smile.png', 'image/obasan01_laugh.png', 'image/obasan04_cry.png', Play::canBeUsedHand(jankenPattern::ALL), Play::canBeUseDirection(hoiPattern::ALL));
$enemies[] = new Enemy('おじいちゃん', mt_rand(1, 3), Type::GRANDPARENTS, 'image/ojiisan03_smile.png', 'image/ojiisan01_laugh.png', 'image/ojiisan01_laugh.png', Play::canBeUsedHand(jankenPattern::RAND2), Play::canBeUseDirection(hoiPattern::RAND2));
$enemies[] = new Enemy('おとこのこ', 3, Type::BOY2, 'image/whiteboy1_1smile.png', 'image/whiteboy1_laugh.png', 'image/whiteboy1_3cry.png', Play::canBeUsedHand(jankenPattern::RAND1), Play::canBeUseDirection(hoiPattern::LEFT));
$enemies[] = new Enemy('おんなのこ', mt_rand(2, 3), Type::GIRL2, 'image/whitegirl1_1smile.png', 'image/whitegirl1_2laugh.png', 'image/whitegirl1_3cry.png', Play::canBeUsedHand(jankenPattern::ALL), Play::canBeUseDirection(hoiPattern::RAND1));
$enemies[] = new Enemy('おにいさん', mt_rand(1, 3), Type::MAN1, 'image/whiteman1_smile.png', 'image/whiteman1_laugh.png', 'image/whiteman1_cry.png', Play::canBeUsedHand(jankenPattern::RAND1), Play::canBeUseDirection(hoiPattern::ALL));
$enemies[] = new Enemy('おねえさん', 2, Type::WOMAN2, 'image/whitewoman1_smile.png', 'image/whitewoman1_laugh.png', 'image/whitewoman1_cry.png', Play::canBeUsedHand(jankenPattern::ALL), Play::canBeUseDirection(hoiPattern::RAND2));
$enemies[] = new Enemy('おねえさん', 2, Type::WOMAN1, 'image/woman03_smile.png', 'image/woman01_laugh.png', 'image/woman04_cry.png', Play::canBeUsedHand(jankenPattern::GUU), Play::canBeUseDirection(hoiPattern::RIGHT));

// ===================================
// 関数
// ===================================
function createEnemy()
{
  global $enemies;
  // $enemies のキーの最大値を調べる
  $keys = array_keys($enemies);
  $keysMax = max($keys);
  // 相手をランダムで選ぶ
  $enemy = $enemies[mt_rand(0, $keysMax)];
  $_SESSION['enemy'] = $enemy;
  // セッションを更新する
  $_SESSION['jankenWinCount'] = 0;
  $_SESSION['jankenLoseCount'] = 0;
  $_SESSION['hoiWinCount'] = 0;
  $_SESSION['hoiLoseCount'] = 0;
  $_SESSION['matchNum'] += 1;
  $_SESSION['enemyImg'] = $_SESSION['enemy']->getImgNormal();
}

function createMyself()
{
  global $myself;
  $_SESSION['myself'] = $myself;
}

function init()
{
  $_SESSION['totalJankenWinCount'] = 0;
  $_SESSION['totalJankenLoseCount'] = 0;
  $_SESSION['totalHoiWinCount'] = 0;
  $_SESSION['totalHoiLoseCount'] = 0;
  $_SESSION['takeDownCount'] = 0;
  $_SESSION['matchNum'] = 0;
  $_SESSION['jankenResult'] = '';
  $_SESSION['gameOver'] = false;
  createEnemy();
  createMyself();
}

function gameOver()
{
  $_SESSION['gameOver'] = true;
}

function playJanken($player, $enemy)
{
  $player = (int)$player;
  $enemy = (int)$enemy;
  if ($enemy !== $player) {  //異なる手を出した
    // プレイヤーが勝った場合
    if (($enemy === 0 && $player === 2) || ($enemy === 1 && $player === 0) || ($enemy === 2 && $player === 1)) {
      // 相手の表情
      $_SESSION['enemyImg'] = $_SESSION['enemy']->getImgSad();
      // 相手の台詞
      $_SESSION['enemy']->sayLose();
      // 勝ち加算
      $_SESSION['jankenWinCount'] += 1;
      $_SESSION['totalJankenWinCount'] += 1;
      // 結果表示
      $_SESSION['jankenResult'] = 0; //かち
    } else {  //プレイヤーが負けた場合
      // 相手の表情
      $_SESSION['enemyImg'] = $_SESSION['enemy']->getImgLaugh();
      // 相手の台詞
      $_SESSION['enemy']->sayWin();
      // 負け加算
      $_SESSION['jankenLoseCount'] += 1;
      $_SESSION['totalJankenLoseCount'] += 1;
      // 結果表示
      $_SESSION['jankenResult'] = 1; //まけ
    }
  } else {  //同じ手を出した＝あいこ
    // 相手の表情
    $_SESSION['enemyImg'] = $_SESSION['enemy']->getImgNormal();
    // 相手の台詞
    $_SESSION['enemy']->sayDraw();
    // 結果表示
    $_SESSION['jankenResult'] = 2; //あいこ
  }
}

function displayJanken($key)
{
  if ($key === 0) {
    echo 'image/j-guu.png';
  } elseif ($key === 1) {
    echo 'image/j-choki.png';
  } else {
    echo 'image/j-paa.png';
  }
}

function playHoi($player, $enemy)
{
  global $hoiResult;
  $player = (int)$player;
  $enemy = (int)$enemy;
  if ($player === $enemy) {  //同じ方向を選んだ
    if ((int)$_SESSION['jankenResult'] === 0) {  // プレイヤーがじゃんけんに勝った
      // 相手の表情
      $_SESSION['enemyImg'] = $_SESSION['enemy']->getImgSad();
      // 相手の台詞
      $_SESSION['enemy']->sayPicked();
      // 勝ち加算
      $_SESSION['hoiWinCount'] += 1;
      $_SESSION['totalHoiWinCount'] += 1;
      // 結果表示
      $hoiResult = 0;  //じゃんけんに勝って当てた
      // 相手のライフを減らす
      $_SESSION['enemy']->Damage();
    } else {  //プレイヤーがじゃんけんに負けた
      // 相手の表情
      $_SESSION['enemyImg'] = $_SESSION['enemy']->getImgLaugh();
      // 相手の台詞
      $_SESSION['enemy']->sayHit();
      // 負け加算
      $_SESSION['hoiLoseCount'] += 1;
      $_SESSION['totalHoiLoseCount'] += 1;
      // 結果表示
      $hoiResult = 1; //じゃんけんに負けて当てられた
      // プレイヤーのライフを減らす
      $_SESSION['myself']->damage();
    }
  } else {  //異なる方向を選んだ
    if ((int)$_SESSION['jankenResult'] === 0) {  //プレイヤーがじゃんけんに勝った
      // 相手の表情
      $_SESSION['enemyImg'] = $_SESSION['enemy']->getImgLaugh();
      // 相手の台詞
      $_SESSION['enemy']->sayAvoid();
      // 結果表示
      $hoiResult = 2; //じゃんけんに勝ったけど外した
    } else {  //プレイヤーがじゃんけんに負けた
      // 相手の表情
      $_SESSION['enemyImg'] = $_SESSION['enemy']->getImgSad();
      // 相手の台詞
      $_SESSION['enemy']->sayMiss();
      // 結果表示
      $hoiResult = 3;  // じゃんけんに負けたけど外れた
    }
  }
}

function displayHoi($key)
{
  switch ($key) {
    case 0:
      echo '<i class="fas fa-arrow-alt-circle-up"></i>';
      break;
    case 1:
      echo '<i class="fas fa-arrow-alt-circle-down"></i>';
      break;
    case 2:
      echo '<i class="fas fa-arrow-alt-circle-left"></i>';
      break;
    case 3:
      echo '<i class="fas fa-arrow-alt-circle-right"></i>';
      break;
  }
}

function playResultCss($str, $key)
{
  if ($str === 1) { //じゃんけんの場合
    switch ($key) {
      case 0: //勝ち
        echo 'win';
        break;
      case 1: //負け
        echo 'lose';
        break;
      case 2: //あいこ
        echo 'draw';
        break;
    }
  } else { //あっちむいてホイの場合
    switch ($key) {
      case 0: //じゃんけん勝ち&当てた
        echo 'win';
        break;
      case 1: //じゃんけん負け&当てられた
        echo 'lose';
        break;
      default:
        echo 'draw';
    }
  }
}

function playResultDisplay($str, $key)
{
  if ($str === 1) { //じゃんけんの場合
    switch ($key) {
      case 0: //勝ち
        echo 'かち';
        break;
      case 1: //負け
        echo 'まけ';
        break;
      case 2: //あいこ
        echo 'あいこ';
        break;
    }
  } else { //あっちむいてホイの場合
    switch ($key) {
      case 0: //じゃんけん勝ち&当てた
      case 1: //じゃんけん負け&当てられた
        echo 'あたった';
        break;
      default:
        echo 'ミス';
    }
  }
}

function resultComment()
{
  global $excellent;
  global $great;
  if ($_SESSION['takeDownCount'] >= $excellent) {
    echo 'すごいね！';
  } else {
    if ($_SESSION['takeDownCount'] >= $great) {
      echo 'やったね！';
    } else {
      echo 'がんばったね！';
    }
  }
}
