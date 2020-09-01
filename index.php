<?php
// 変数・関数読み込み
require('function.php');

// ===================================
// 処理
// ===================================
// POST送信の有無
if (!empty($_POST)) {
    $startFlg = (!empty($_POST['start'])) ? true : false;
    $checkFlg = (!empty($_POST['check'])) ? true : false;
    $jankenFlg = (!empty($_POST['janken'])) ? true : false;
    $aikoFlg = (!empty($_POST['aiko'])) ? true : false;
    $jankenResultFlg = (!empty($_POST['janken-result'])) ? true : false;
    $hoiFlg = (!empty($_POST['hoi'])) ? true : false;
    $rematchFlg = (!empty($_POST['rematch'])) ? true : false;
    debug('***** POST送信されました *****');
    debug('***** POSTの中身→→→' . print_r($_POST, true) . ' *****');

    if ($startFlg) {  //ゲームスタート
        debug('***** ゲームスタート *****');
        init();
        $_SESSION['enemy']->sayGreeting();
    } elseif ($checkFlg) {  //OKボタンを押した
        $_SESSION['enemy']->sayWord('じゃんけんぽんっ');
    } elseif ($aikoFlg) {  //じゃんけんがあいこだった場合
        $_SESSION['enemy']->sayWord('あいこでしょっ');
    } elseif ($jankenFlg) {  //じゃんけんする
        debug('***** じゃんけん *****');
        // 相手の出した手
        $enemyHand = (int)$_SESSION['enemy']->selectHand;
        $handImg = displayJanken($enemyHand);
        // プレイヤーが出した手
        $myHand = (int)$_POST['janken'][0];
        // じゃんけんする
        playJanken($myHand, $enemyHand);
    } elseif ($jankenResultFlg) {  //じゃんけんの結果が勝ちか負けだった
        $_SESSION['enemy']->sayWord('あっちむいてホイ！');
    } elseif ($hoiFlg) {  //あっちむいてホイする
        debug('***** あっちむいてホイ *****');
        // 相手の選択した方向
        $enemyDirection = (int)$_SESSION['enemy']->selectDirection();
        $directionImg = displayHoi($enemyDirection);
        // プレイヤーの選択した方向
        $myDirection = (int)$_POST['hoi'][0];
        // あっちむいてホイする
        playHoi($myDirection, $enemyDirection);

        // 自分のライフが0になったらゲームオーバー
        if ((int)$_SESSION['myself']->getHp() === 0) {
            gameOver();
        }
    } elseif ($rematchFlg) {
        // 相手のライフがだったらライフを回復して次の相手へ
        if ((int)$_SESSION['enemy']->getHp() === 0) {
            $_SESSION['myself']->recovery();
            createEnemy();
            $_SESSION['takeDownCount'] += 1;
        }
    }
    $_POST = array();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>あっちむいてホイ</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kosugi+Maru&display=swap" rel="stylesheet">
    <!-- font awesome -->
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <?php if (empty($_SESSION)) : ?>
            <!-- スタート画面 -->
            <div class="main main-color">
                <div class="title-container">
                    <h1 class="h1 title">あっちむいてホイ</h1>
                </div>
                <form class="start-container" method="post">
                    <input type="submit" class="startbutton" name="start" value="はじめる">
                </form>
            </div>
        <?php else : ?>
            <?php if ($_SESSION['gameOver']) : ?>
                <!-- 結果画面 -->
                <div class="result main-color">
                    <div class="star-box">
                        <i class="fas fa-star -result"></i><i class="fas fa-star -result <?php if ($_SESSION['takeDownCount'] < $great) echo 'star-off'; ?>"></i><i class="fas fa-star -result <?php if ($_SESSION['takeDownCount'] >= $great && $_SESSION['takeDownCount'] < $excellent) echo 'star-off'; ?>"></i>
                    </div>
                    <div class="result-comment">
                        <?php resultComment(); ?>
                    </div>
                    <div class="result-enemy-num-box">
                        ぜんぶで　<span class="result-enemy-num"><?= $_SESSION['takeDownCount'] ?></span>　にん　に　かったよ
                    </div>
                    <div class="result-grade">
                        <table class="result-table">
                            <tbody>
                                <tr class="result-table-tr">
                                    <th class="result-table-th" rowspan="2">じゃんけん</th>
                                    <td>かち</td>
                                    <td class="result-table-num"><?= $_SESSION['totalJankenWinCount'] ?></td>
                                    <td>かい</td>
                                </tr>
                                <tr class="result-table-tr">
                                    <td>まけ</td>
                                    <td class="result-table-num"><?= $_SESSION['totalJankenLoseCount'] ?></td>
                                    <td>かい</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="result-table">
                            <tbody>
                                <tr class="result-table-tr">
                                    <th class="result-table-th" rowspan="2">あっちむいて<br>ホイ</th>
                                    <td>かち</td>
                                    <td class="result-table-num"><?= $_SESSION['totalHoiWinCount'] ?></td>
                                    <td>かい</td>
                                </tr>
                                <tr class="result-table-tr">
                                    <td>まけ</td>
                                    <td class="result-table-num"><?= $_SESSION['totalHoiLoseCount'] ?></td>
                                    <td>かい</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <form class="result-replay-box" method="post">
                        <input type="submit" class="result-replay" name="start" value="もういちど　あそぶ">
                    </form>
                </div>
            <?php else : ?>
                <!-- ゲームスタート -->
                <!-- タイトル -->
                <div class="game-logo">
                    ゲーム「あっちむいてホイ」
                </div>
                <!-- 上部 -->
                <div class="game-top">
                    <div class="game-main main-color">
                        <div class="enemy-box">
                            <div class="enemy-box-1">
                                <div class="enemy-name-box">
                                    <span class="name enemy-name"><?= $_SESSION['enemy']->getName(); ?></span>
                                </div>
                                <div class="enemy-img-box">
                                    <img src="<?= $enemyImg ?>" class="enemy-img">
                                </div>
                                <div class="enemy-life-box">
                                    <?php if ($_SESSION['enemy']->getHp() >= 1) : ?>
                                        <?php for ($i = 0; $i < $_SESSION['enemy']->getHp(); $i++) : ?>
                                            <span class="enemy-life -h"><i class="fas fa-heart"></i></span>
                                        <?php endfor; ?>
                                    <?php endif; ?>
                                    <?php if ($_SESSION['enemy']->getHpMax() > $_SESSION['enemy']->getHp()) : ?>
                                        <?php for ($i = 0; $_SESSION['enemy']->getHpMax() - $_SESSION['enemy']->getHp(); $i++) : ?>
                                            <span class="enemy-life -b"><i class="fas fa-heart-broken"></i></span>
                                        <?php endfor; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="enemy-box-2">
                                <div class="enemy-comment-box">
                                    <img src="image/comment5.png" class="enemy-comment-img">
                                    <div class="enemy-comment-box2">
                                        <span class="enemy-comment"><?= $_SESSION['word'] ?></span>
                                    </div>
                                </div>
                                <div class="enemy-action-box">
                                    <?php if (!empty($_POST['janken'])) : ?>
                                        <!-- じゃんけん -->
                                        <img src="<?= $handImg ?>" alt="" class="enemy-action-img">
                                    <?php elseif (!empty($_POST['hoi'])) : ?>
                                        <div class="enemy-hoi-box">
                                            <span class="enemy-hoi"><?= $directionImg ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="game-sub">
                        <?php if ($startFlg || $rematchFlg) : ?>
                            <!-- 新しい相手が出てきたとき -->
                            <div class="sub-comment-start">
                                <span class="sub-num"><?= $_SESSION['matchNum'] ?></span>かいせんめ<br>
                                <span class="sub-enemy-name"><?= $_SESSION['enemy']->getName() ?></span>と<br>
                                <span class="sub-comment-bottom">しょうぶ！！</span>
                            </div>
                            <form class="sub-start-box" method="post">
                                <input type="submit" class="sub-start-ok" name="check" value="O K">
                            </form>
                        <?php else : ?>
                            <!-- じゃんけん・あっちむいてホイのとき -->
                            <div class="sub-comment-box">
                                <span class="select-comment <?php if ($jankenFlg || $hoiFlg) echo 'hidden'; ?>">▼▼ えらんでね ▼▼</span>
                                <form class="sub-play-result" method="post">
                                    <!-- <span class="sub-result-comment <?php if ($jankenFlg) {
                                                                                playResultCss(1, $_SESSION['jankenResult']);
                                                                            } elseif ($hoiFlg) {
                                                                                playResultCss(2, $hoiResult);
                                                                            } ?>"><?php if ($jankenFlg) {
                                                                                        playResultDisplay(1, $_SESSION['jankenResult']);
                                                                                    } elseif ($hoiFlg) {
                                                                                        playResultDisplay(2, $hoiResult);
                                                                                    } ?></span> -->
                                    <?php if ($jankenFlg) : ?>
                                        <input type="submit" class="sub-result-comment <?php playResultCss(1, $_SESSION['jankenResult']); ?>" value="<?php playResultDisplay(1, $_SESSION['jankenResult']); ?>">
                                    <?php elseif ($hoiFlg) : ?>
                                        <input type="submit" class="sub-result-comment <?php playResultCss(2, $hoiResult); ?>" value="<?php playResultDisplay(2, $hoiResult); ?>">
                                    <?php endif; ?>
                                </form>

                            </div>
                            <?php if ($checkFlg || $aikoFlg) : ?>
                                <!-- じゃんけん選択 -->
                                <form class="janken-select-box" method="post">
                                    <input type="image" class="janken -guu" name="janken[]" value="0" src="image/j-guu.png">
                                    <input type="image" class="janken -choki" name="janken[]" value="1" src="image/j-choki.png">
                                    <input type="image" class="janken -paa" name="janken[]" value="2" src="image/j-paa.png">
                                </form>
                            <?php elseif ($jankenFlg) : ?>
                                <!-- じゃんけん選択後 -->
                                <div class="janken-select-box">
                                    <img src="image/j-guu.png" alt="ぐー" class="janken -guu <?php if ($myHand !== 0) echo 'not-select'; ?>">
                                    <img src="image/j-choki.png" alt="ちょき" class="janken -choki <?php if ($myHand !== 1) echo 'not-select'; ?>">
                                    <img src="image/j-paa.png" alt="ぱー" class="janken -paa <?php if ($myHand !== 2) echo 'not-select'; ?>">
                                </div>
                            <?php elseif ($jankenResultFlg) : ?>
                                <!-- あっちむいてホイ　選択 -->
                                <form class="hoi-select-box" method="post">
                                    <input type="submit" class="fas hoi-select -up" name="hoi[0]" value="&#xf35b">
                                    <div class="hoi-lr-box">
                                        <input type="submit" class="fas hoi-select -left" name="hoi[2]" value="&#xf359">
                                        <input type="submit" class="fas hoi-select -right" name="hoi[3]" value="&#xf35a">
                                    </div>
                                    <input type="submit" class="fas hoi-select -down" name="hoi[1]" value="&#xf358">
                                </form>
                            <?php elseif ($hoiFlg) : ?>
                                <!-- あっちむいてホイ選択後 -->
                                <div class="hoi-select-box">
                                    <i class="fas fa-arrow-alt-circle-up hoi-select-r -up <?php if ($myDirection !== 0) echo 'not-select'; ?>"></i>
                                    <div class="hoi-lr-box">
                                        <i class="fas fa-arrow-alt-circle-left hoi-select-r -left <?php if ($myDirection !== 2) echo 'not-select'; ?>"></i>
                                        <i class="fas fa-arrow-alt-circle-right hoi-select-r -right <?php if ($myDirection !== 3) echo 'not-select'; ?>"></i>
                                    </div>
                                    <i class="fas fa-arrow-alt-circle-down hoi-select-r -down <?php if ($myDirection !== 1) echo 'not-select'; ?>"></i>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- 下部 -->
                <div class="game-bottom">
                    <div class="game-info-box">
                        <span class="info-num"><?= $_SESSION['matchNum'] ?></span>かいせんめ<br>
                        <table class="info-table">
                            <tbody>
                                <tr class="info-table-tr">
                                    <th class="info-table-th" rowspan="2">じゃんけん</th>
                                    <td>かち</td>
                                    <td class="table-num"><?= $_SESSION['jankenWinCount'] ?></td>
                                    <td>かい</td>
                                </tr>
                                <tr class="info-table-tr">
                                    <td>まけ</td>
                                    <td class="table-num"><?= $_SESSION['jankenLoseCount'] ?></td>
                                    <td>かい</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="info-table">
                            <tbody>
                                <tr class="info-table-tr">
                                    <th class="info-table-th" rowspan="2">あっちむいて<br>ホイ</th>
                                    <td>かち</td>
                                    <td class="table-num"><?= $_SESSION['hoiWinCount'] ?></td>
                                    <td>かい</td>
                                </tr>
                                <tr class="info-table-tr">
                                    <td>まけ</td>
                                    <td class="table-num"><?= $_SESSION['hoiLoseCount'] ?></td>
                                    <td>かい</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="game-info-side">
                        <div class="game-life-box">
                            <div class="game-life-1">
                                <?php if ($_SESSION['myself']->getHp() > 5) : ?>
                                    <?php for ($i = 0; $i < 5; $i++) : ?>
                                        <span class="my-life"><i class="fas fa-heart"></i></span>
                                    <?php endfor; ?>
                                <?php else : ?>
                                    <?php for ($i = 0; $i < $_SESSION['myself']->getHp(); $i++) : ?>
                                        <span class="my-life"><i class="fas fa-heart"></i></span>
                                    <?php endfor; ?>
                                    <?php for ($i = 0; $i < $_SESSION['myself']->getHpMax() - $_SESSION['myself']->getHp(); $i++) : ?>
                                        <span class="my-life"><i class="fas fa-heart-broken"></i></span>
                                    <?php endfor; ?>
                                <?php endif; ?>
                            </div>
                            <div class="game-life-2">
                                <?php if ($_SESSION['myself']->getHp() < 6) : ?>
                                    <?php for ($i = 0; $i < 5; $i++) : ?>
                                        <span class="my-life"><i class="fas fa-heart-broken"></i></span>
                                    <?php endfor; ?>
                                <?php else : ?>
                                    <?php for ($i = 5; $i < $_SESSION['myself']->getHp(); $i++) : ?>
                                        <span class="my-life"><i class="fas fa-heart"></i></span>
                                    <?php endfor; ?>
                                    <?php for ($i = 5; $i < $_SESSION['myself']->getHpMax() - $_SESSION['myself']->getHp(); $i++) : ?>
                                        <span class="my-life"><i class="fas fa-heart-broken"></i></span>
                                    <?php endfor; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <form class="game-restart-box" method="post">
                            <input type="submit" class="game-restart" name="start" value="はじめから">
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>

</html>