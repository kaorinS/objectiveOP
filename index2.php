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
        <!-- スタート画面 -->
        <!-- <div class="main main-color">
            <div class="title-container">
                <h1 class="h1 title">あっちむいてホイ</h1>
            </div>
            <form class="start-container" method="post">
                <input type="submit" class="startbutton" name="start" value="はじめる">
            </form>
        </div> -->
        <!-- 結果発表 -->
        <div class="result main-color">
            <div class="star-box">
                <i class="fas fa-star -result"></i><i class="fas fa-star -result"></i><i class="fas fa-star -result"></i>
            </div>
            <div class="result-comment">
                すごいね！
            </div>
            <div class="result-enemy-num-box">
                ぜんぶで　<span class="result-enemy-num">5</span>　にん　に　かったよ
            </div>
            <div class="result-grade">
                <table class="result-table">
                    <tbody>
                        <tr class="result-table-tr">
                            <th class="result-table-th" rowspan="2">じゃんけん</th>
                            <td>かち</td>
                            <td class="result-table-num">40</td>
                            <td>かい</td>
                        </tr>
                        <tr class="result-table-tr">
                            <td>まけ</td>
                            <td class="result-table-num">26</td>
                            <td>かい</td>
                        </tr>
                    </tbody>
                </table>
                <table class="result-table">
                    <tbody>
                        <tr class="result-table-tr">
                            <th class="result-table-th" rowspan="2">あっちむいて<br>ホイ</th>
                            <td>かち</td>
                            <td class="result-table-num">15</td>
                            <td>かい</td>
                        </tr>
                        <tr class="result-table-tr">
                            <td>まけ</td>
                            <td class="result-table-num">10</td>
                            <td>かい</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <form class="result-replay-box" method="post">
                <input type="submit" class="result-replay" name="restart" value="もういちど　あそぶ">
            </form>
        </div>
        <!-- ゲームスタート -->
        <!-- タイトル -->
        <!-- <div class="game-logo">
            ゲーム「あっちむいてホイ」
        </div>
        <!-- 上部 -->
        <!-- <div class="game-top">
            <div class="game-main main-color">
                <div class="enemy-box">
                    <div class="enemy-box-1">
                        <div class="enemy-name-box">
                            <span class="name enemy-name">おかあさん</span>
                        </div>
                        <div class="enemy-img-box">
                            <img src="image/obasan03_smile.png" class="enemy-img">
                        </div>
                        <div class="enemy-life-box">
                            <span class="enemy-life -h"><i class="fas fa-heart"></i></span>
                            <span class="enemy-life -h"><i class="fas fa-heart"></i></span>
                            <span class="enemy-life -b"><i class="fas fa-heart-broken"></i></span>
                        </div>
                    </div>
                    <div class="enemy-box-2">
                        <div class="enemy-comment-box">
                            <img src="image/comment5.png" class="enemy-comment-img">
                            <div class="enemy-comment-box2">
                                <span class="enemy-comment">うふふ♡</span>
                            </div>
                        </div>
                        <div class="enemy-action-box"> -->
        <!-- じゃんけん -->
        <!-- <img src="image/j-choki.png" alt="" class="enemy-action-img"> -->
        <!-- <div class="enemy-hoi-box">
                                <span class="enemy-hoi"><i class="fas fa-arrow-alt-circle-up"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="game-sub"> -->
        <!-- 新しい相手が出てきたとき -->
        <!-- <div class="sub-comment-start">
                    <span class="sub-num">1</span>かいせんめ<br>
                    <span class="sub-enemy-name">おかあさん</span>と<br>
                    <span class="sub-comment-bottom">しょうぶ！！</span>
                </div>
                <form class="sub-start-box" method="post">
                    <input type="submit" class="sub-start-ok" name="ok" value="O K">
                </form> -->
        <!-- じゃんけん・あっちむいてホイののとき -->
        <!-- <div class="sub-comment-box">
                    <span class="select-comment hidden">▼▼ えらんでね ▼▼</span>
                    <span class="sub-result-comment win">かち</span>
                </div> -->
        <!-- じゃんけん選択
                <form class="janken-select-box" method="post">
                    <input type="image" class="janken -guu" name="guu" src="image/j-guu.png">
                    <input type="image" class="janken -choki" name="choki" src="image/j-choki.png">
                    <input type="image" class="janken -paa" name="paa" src="image/j-paa.png">
                </form>
                じゃんけん選択後
                <div class="janken-select-box">
                    <img src="image/j-guu.png" alt="ぐー" class="janken -guu">
                    <img src="image/j-choki.png" alt="ちょき" class="janken -choki not-select">
                    <img src="image/j-paa.png" alt="ぱー" class="janken -paa not-select">
                </div> -->
        <!-- あっちむいてホイ　選択 -->
        <!-- <form class="hoi-select-box" method="post">
                    <input type="submit" class="fas hoi-select -up" name="up" value="&#xf35b">
                    <div class="hoi-lr-box">
                        <input type="submit" class="fas hoi-select -left" name="left" value="&#xf359">
                        <input type="submit" class="fas hoi-select -right" name="right" value="&#xf35a">
                    </div>
                    <input type="submit" class="fas hoi-select -down" name="down" value="&#xf358">
                </form> -->
        <!-- <div class="hoi-select-box">
                    <i class="fas fa-arrow-alt-circle-up hoi-select-r -up"></i>
                    <div class="hoi-lr-box">
                        <i class="fas fa-arrow-alt-circle-left hoi-select-r -left not-select"></i>
                        <i class="fas fa-arrow-alt-circle-right hoi-select-r -right not-select"></i>
                    </div>
                    <i class="fas fa-arrow-alt-circle-down hoi-select-r -down not-select"></i>
                </div>
            </div>
        </div> -->
        <!-- 下部 -->
        <!-- <div class="game-bottom">
            <div class="game-info-box">
                <span class="info-num">1</span>かいせんめ<br>
                <table class="info-table">
                    <tbody>
                        <tr class="info-table-tr">
                            <th class="info-table-th" rowspan="2">じゃんけん</th>
                            <td>かち</td>
                            <td class="table-num">1</td>
                            <td>かい</td>
                        </tr>
                        <tr class="info-table-tr">
                            <td>まけ</td>
                            <td class="table-num">0</td>
                            <td>かい</td>
                        </tr>
                    </tbody>
                </table>
                <table class="info-table">
                    <tbody>
                        <tr class="info-table-tr">
                            <th class="info-table-th" rowspan="2">あっちむいて<br>ホイ</th>
                            <td>かち</td>
                            <td class="table-num">1</td>
                            <td>かい</td>
                        </tr>
                        <tr class="info-table-tr">
                            <td>まけ</td>
                            <td class="table-num">0</td>
                            <td>かい</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="game-info-side">
                <div class="game-life-box">
                    <div class="game-life-1">
                        <span class="my-life"><i class="fas fa-heart"></i></span>
                        <span class="my-life"><i class="fas fa-heart"></i></span>
                        <span class="my-life"><i class="fas fa-heart"></i></span>
                        <span class="my-life"><i class="fas fa-heart"></i></span>
                        <span class="my-life"><i class="fas fa-heart"></i></span>
                    </div>
                    <div class="game-life-2">
                        <span class="my-life"><i class="fas fa-heart"></i></span>
                        <span class="my-life"><i class="fas fa-heart"></i></span>
                        <span class="my-life"><i class="fas fa-heart"></i></span>
                        <span class="my-life"><i class="fas fa-heart"></i></span>
                        <span class="my-life"><i class="fas fa-heart"></i></span>
                    </div>
                </div>
                <form class="game-restart-box" method="post">
                    <input type="submit" class="game-restart" name="restart" value="はじめから">
                </form> -->
        <!-- </div>  -->
    </div>
</body>

</html>