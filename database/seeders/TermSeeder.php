<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Quiz;
use App\Models\Term;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'network' => [
                [
                    'name'       => 'IPアドレス',
                    'definition' => 'ネットワーク上の機器を識別するための数値アドレス。IPv4では32ビット（例：192.168.1.1）、IPv6では128ビットで表される。',
                    'example'    => '家庭のWi-Fiルーターには通常 192.168.1.1 などのIPアドレスが割り当てられている。',
                    'difficulty' => 1,
                    'quiz'       => [
                        'question'    => 'IPv4アドレスは何ビットで構成されているか？',
                        'explanation' => 'IPv4は32ビット（4オクテット）で構成され、0〜255の数値を「.」で区切って表現する。',
                        'choices'     => [
                            ['body' => '16ビット', 'is_correct' => false],
                            ['body' => '32ビット', 'is_correct' => true],
                            ['body' => '64ビット', 'is_correct' => false],
                            ['body' => '128ビット', 'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => 'DNS（Domain Name System）',
                    'definition' => 'ドメイン名（例：google.com）をIPアドレスに変換する仕組み。インターネットの電話帳とも呼ばれる。',
                    'example'    => 'ブラウザで「google.com」を入力すると、DNSが「142.250.x.x」というIPアドレスに変換する。',
                    'difficulty' => 2,
                    'quiz'       => [
                        'question'    => 'DNSの主な役割として正しいものはどれか？',
                        'explanation' => 'DNSはドメイン名をIPアドレスに変換する（名前解決）サービス。',
                        'choices'     => [
                            ['body' => 'メールを送受信する',          'is_correct' => false],
                            ['body' => 'ドメイン名をIPアドレスに変換する', 'is_correct' => true],
                            ['body' => 'データを暗号化する',          'is_correct' => false],
                            ['body' => 'ファイルを転送する',          'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => 'TCP（Transmission Control Protocol）',
                    'definition' => '信頼性のあるデータ転送を保証するプロトコル。コネクション確立・順序保証・再送制御を行う。',
                    'example'    => 'WebページやメールはTCPを使用することでデータの欠落なく通信が行われる。',
                    'difficulty' => 2,
                    'quiz'       => [
                        'question'    => 'TCPの特徴として正しいものはどれか？',
                        'explanation' => 'TCPは3ウェイハンドシェイクでコネクションを確立し、信頼性の高いデータ転送を実現する。',
                        'choices'     => [
                            ['body' => 'コネクションレスで高速転送する',    'is_correct' => false],
                            ['body' => '信頼性のあるコネクション型通信を行う', 'is_correct' => true],
                            ['body' => 'データの暗号化を行う',            'is_correct' => false],
                            ['body' => 'IPアドレスを割り当てる',          'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => 'HTTP（HyperText Transfer Protocol）',
                    'definition' => 'Webブラウザとサーバー間でHTMLなどのリソースを転送するためのプロトコル。HTTPSはSSL/TLSで暗号化された版。',
                    'example'    => 'ブラウザのアドレスバーに「https://」で始まるURLを入力したとき、HTTPSが使われる。',
                    'difficulty' => 1,
                    'quiz'       => [
                        'question'    => 'HTTPSがHTTPと異なる主な点はどれか？',
                        'explanation' => 'HTTPSはSSL/TLSによる暗号化を追加したプロトコルで、通信内容を盗聴から保護する。',
                        'choices'     => [
                            ['body' => '転送速度が速い',            'is_correct' => false],
                            ['body' => 'SSL/TLSで通信を暗号化する',  'is_correct' => true],
                            ['body' => 'UDPを使用する',             'is_correct' => false],
                            ['body' => 'ポート番号80を使用する',     'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => 'ルーター',
                    'definition' => '異なるネットワーク間でデータパケットを転送する機器。宛先IPアドレスを基にルーティングテーブルを参照して最適経路を選択する。',
                    'example'    => '家庭のブロードバンドルーターは、家庭内LANとインターネット（WAN）を接続する。',
                    'difficulty' => 2,
                    'quiz'       => [
                        'question'    => 'ルーターが参照してパケット転送先を決める情報はどれか？',
                        'explanation' => 'ルーターはルーティングテーブルを参照し、宛先IPアドレスに基づいて転送先を決定する。',
                        'choices'     => [
                            ['body' => 'MACアドレステーブル',   'is_correct' => false],
                            ['body' => 'ルーティングテーブル',   'is_correct' => true],
                            ['body' => 'ARP キャッシュ',        'is_correct' => false],
                            ['body' => 'DNSキャッシュ',         'is_correct' => false],
                        ],
                    ],
                ],
            ],
            'security' => [
                [
                    'name'       => '公開鍵暗号方式',
                    'definition' => '暗号化と復号で異なる鍵（公開鍵・秘密鍵）を使用する暗号方式。公開鍵で暗号化し秘密鍵で復号する。',
                    'example'    => 'HTTPSのSSL/TLS通信で使用される。相手の公開鍵で暗号化したデータは、相手の秘密鍵でのみ復号できる。',
                    'difficulty' => 3,
                    'quiz'       => [
                        'question'    => '公開鍵暗号方式で暗号化に使う鍵はどれか？',
                        'explanation' => '公開鍵暗号方式では「公開鍵で暗号化→秘密鍵で復号」の組み合わせを使用する。',
                        'choices'     => [
                            ['body' => '秘密鍵',       'is_correct' => false],
                            ['body' => '公開鍵',       'is_correct' => true],
                            ['body' => '共通鍵',       'is_correct' => false],
                            ['body' => 'セッション鍵',  'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => 'SQL インジェクション',
                    'definition' => '悪意のあるSQLコードをWebフォーム等の入力値に埋め込み、データベースを不正操作する攻撃手法。',
                    'example'    => "ログインフォームに「' OR 1=1 --」と入力することでSQLクエリを改ざんし、認証をバイパスする。",
                    'difficulty' => 2,
                    'quiz'       => [
                        'question'    => 'SQLインジェクション対策として最も有効なものはどれか？',
                        'explanation' => 'プリペアドステートメント（バインド変数）を使うことで、入力値がSQLとして解釈されるのを防ぐ。',
                        'choices'     => [
                            ['body' => 'HTTPS通信を使用する',             'is_correct' => false],
                            ['body' => 'プリペアドステートメントを使用する', 'is_correct' => true],
                            ['body' => 'パスワードをハッシュ化する',        'is_correct' => false],
                            ['body' => 'ファイアウォールを設置する',        'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => 'XSS（クロスサイトスクリプティング）',
                    'definition' => '悪意のあるスクリプトをWebページに埋め込み、閲覧ユーザーのブラウザで実行させる攻撃。セッション情報の盗取などに使われる。',
                    'example'    => '掲示板に「<script>document.location="攻撃者サイト"</script>」を投稿し、他ユーザーのCookieを窃取する。',
                    'difficulty' => 2,
                    'quiz'       => [
                        'question'    => 'XSS対策として適切なものはどれか？',
                        'explanation' => '出力時にHTMLエスケープを行うことで、スクリプトがHTMLとして解釈されるのを防ぐ。',
                        'choices'     => [
                            ['body' => 'SQLをバインド変数で記述する', 'is_correct' => false],
                            ['body' => '出力値をHTMLエスケープする',   'is_correct' => true],
                            ['body' => 'パスワードを暗号化する',       'is_correct' => false],
                            ['body' => 'セッションIDを長くする',       'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => '多要素認証（MFA）',
                    'definition' => '知識（パスワード）・所持（スマホ）・生体（指紋）のうち2つ以上を組み合わせた認証方式。セキュリティを大幅に向上させる。',
                    'example'    => 'Googleアカウントのログイン時にパスワード入力後、スマートフォンに届く6桁のコードを入力する。',
                    'difficulty' => 2,
                    'quiz'       => [
                        'question'    => '多要素認証の「要素」として誤っているものはどれか？',
                        'explanation' => '多要素認証の要素は「知識・所持・生体」の3種類。「ネットワーク種別」は要素に含まれない。',
                        'choices'     => [
                            ['body' => '知識（パスワード）',      'is_correct' => false],
                            ['body' => 'ネットワーク種別（有線/無線）', 'is_correct' => true],
                            ['body' => '所持（スマートフォン）',   'is_correct' => false],
                            ['body' => '生体（指紋・顔認識）',     'is_correct' => false],
                        ],
                    ],
                ],
            ],
            'database' => [
                [
                    'name'       => 'トランザクション',
                    'definition' => 'データベースに対する一連の操作をひとつの単位として扱う仕組み。ACID特性（原子性・一貫性・独立性・永続性）を持つ。',
                    'example'    => '銀行の振込処理で「口座Aから引き落とし→口座Bに入金」の2操作をひとつのトランザクションとして実行する。',
                    'difficulty' => 3,
                    'quiz'       => [
                        'question'    => 'トランザクションのACID特性に含まれないものはどれか？',
                        'explanation' => 'ACID特性は原子性（Atomicity）、一貫性（Consistency）、独立性（Isolation）、永続性（Durability）の4つ。',
                        'choices'     => [
                            ['body' => '原子性（Atomicity）',     'is_correct' => false],
                            ['body' => '可用性（Availability）',  'is_correct' => true],
                            ['body' => '一貫性（Consistency）',   'is_correct' => false],
                            ['body' => '永続性（Durability）',    'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => '正規化',
                    'definition' => 'データベースの冗長性を排除し、データの整合性を保つためにテーブルを分割・整理する手法。第1〜第3正規形が基本。',
                    'example'    => '受注テーブルに顧客名・住所が直接入っている場合、顧客テーブルを分離して正規化する。',
                    'difficulty' => 3,
                    'quiz'       => [
                        'question'    => '正規化の主な目的はどれか？',
                        'explanation' => '正規化によりデータの冗長性を排除し、更新異常（挿入・削除・変更の不整合）を防ぐ。',
                        'choices'     => [
                            ['body' => 'クエリの実行速度を上げる',       'is_correct' => false],
                            ['body' => 'データの冗長性を排除し整合性を保つ', 'is_correct' => true],
                            ['body' => 'バックアップを容易にする',        'is_correct' => false],
                            ['body' => 'テーブル数を増やす',            'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => 'インデックス',
                    'definition' => 'データベースの検索速度を向上させるためのデータ構造。B木やハッシュ等を使ってデータの場所を記録する。',
                    'example'    => 'users テーブルの email 列にインデックスを張ることで、メールアドレスでの検索が高速化される。',
                    'difficulty' => 2,
                    'quiz'       => [
                        'question'    => 'インデックスのデメリットとして正しいものはどれか？',
                        'explanation' => 'インデックスはデータの追加・更新・削除のたびに更新が必要なため、書き込みパフォーマンスが低下する。',
                        'choices'     => [
                            ['body' => 'SELECT文の速度が低下する',       'is_correct' => false],
                            ['body' => '書き込み（INSERT/UPDATE/DELETE）が遅くなる', 'is_correct' => true],
                            ['body' => 'テーブルの結合ができなくなる',    'is_correct' => false],
                            ['body' => 'トランザクションが使えなくなる',  'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => 'PRIMARY KEY（主キー）',
                    'definition' => 'テーブル内の各行を一意に識別するための列（またはその組み合わせ）。NULLは許容されず、重複もできない。',
                    'example'    => 'usersテーブルの id 列を主キーとすることで、各ユーザーを一意に識別できる。',
                    'difficulty' => 1,
                    'quiz'       => [
                        'question'    => '主キー（PRIMARY KEY）の特徴として正しいものはどれか？',
                        'explanation' => '主キーはNULLを許容せず、テーブル内で値が一意でなければならない。',
                        'choices'     => [
                            ['body' => 'NULL値を持つことができる',  'is_correct' => false],
                            ['body' => '値が一意でNULLを許容しない', 'is_correct' => true],
                            ['body' => '1テーブルに複数設定できる', 'is_correct' => false],
                            ['body' => '外部テーブルを参照する',    'is_correct' => false],
                        ],
                    ],
                ],
            ],
            'algorithm' => [
                [
                    'name'       => 'バイナリサーチ（二分探索）',
                    'definition' => 'ソート済み配列に対して、探索範囲を半分ずつ絞り込むことで目的の値を効率的に探す探索アルゴリズム。計算量はO(log n)。',
                    'example'    => '辞書で単語を探すとき、真ん中のページを開いて探している単語が前後どちらにあるかを判断して絞り込む。',
                    'difficulty' => 2,
                    'quiz'       => [
                        'question'    => 'バイナリサーチの計算量はどれか？',
                        'explanation' => '二分探索は探索のたびに範囲が半分になるため、計算量はO(log n)となる。',
                        'choices'     => [
                            ['body' => 'O(1)',    'is_correct' => false],
                            ['body' => 'O(log n)','is_correct' => true],
                            ['body' => 'O(n)',    'is_correct' => false],
                            ['body' => 'O(n²)',   'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => 'バブルソート',
                    'definition' => '隣接する要素を比較・交換することでリストを整列するソートアルゴリズム。実装は簡単だが計算量はO(n²)と非効率。',
                    'example'    => '[5,3,1,4,2]を並べ替える際、隣同士を比較して大きい方を右に移動する操作を繰り返す。',
                    'difficulty' => 2,
                    'quiz'       => [
                        'question'    => 'バブルソートの平均計算量はどれか？',
                        'explanation' => 'バブルソートはn個の要素に対してn回のパスが必要なためO(n²)となる。',
                        'choices'     => [
                            ['body' => 'O(n)',      'is_correct' => false],
                            ['body' => 'O(n²)',     'is_correct' => true],
                            ['body' => 'O(n log n)','is_correct' => false],
                            ['body' => 'O(log n)',  'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => 'スタック（Stack）',
                    'definition' => '後入れ先出し（LIFO: Last In First Out）のデータ構造。PUSHで追加、POPで取り出しを行う。',
                    'example'    => 'ブラウザの「戻る」機能は、訪問ページをスタックに積んで、戻るときにPOPする仕組みで実現されている。',
                    'difficulty' => 2,
                    'quiz'       => [
                        'question'    => 'スタックのデータ取り出し方式はどれか？',
                        'explanation' => 'スタックはLIFO（Last In First Out）方式で、最後に入れたデータが最初に取り出される。',
                        'choices'     => [
                            ['body' => 'FIFO（先入れ先出し）', 'is_correct' => false],
                            ['body' => 'LIFO（後入れ先出し）', 'is_correct' => true],
                            ['body' => 'ランダムアクセス',     'is_correct' => false],
                            ['body' => '優先度順',           'is_correct' => false],
                        ],
                    ],
                ],
            ],
            'cloud' => [
                [
                    'name'       => 'IaaS（Infrastructure as a Service）',
                    'definition' => 'サーバー・ストレージ・ネットワークなどのインフラをクラウド経由で提供するサービス。OSやミドルウェアはユーザーが管理する。',
                    'example'    => 'Amazon EC2やGoogle Compute Engineが代表例。仮想マシンを必要な時だけ借りられる。',
                    'difficulty' => 2,
                    'quiz'       => [
                        'question'    => 'IaaSでユーザーが管理する対象として正しいものはどれか？',
                        'explanation' => 'IaaSではインフラ（CPU・メモリ・ストレージ）はクラウドが提供し、OSやアプリケーションはユーザーが管理する。',
                        'choices'     => [
                            ['body' => 'ハードウェア・データセンター', 'is_correct' => false],
                            ['body' => 'OS・ミドルウェア・アプリ',    'is_correct' => true],
                            ['body' => 'アプリケーションのみ',        'is_correct' => false],
                            ['body' => 'データのみ',                'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => 'コンテナ技術（Docker）',
                    'definition' => 'アプリケーションとその依存関係をコンテナとしてパッケージ化し、どこでも同じ環境で実行できる技術。VMより軽量で高速起動。',
                    'example'    => 'Dockerでアプリをコンテナ化すると「開発環境では動くが本番では動かない」問題を解消できる。',
                    'difficulty' => 3,
                    'quiz'       => [
                        'question'    => 'Dockerコンテナと仮想マシン（VM）の違いとして正しいものはどれか？',
                        'explanation' => 'コンテナはホストOSのカーネルを共有するため、VMより軽量で起動が高速。ただしホストOSに依存する。',
                        'choices'     => [
                            ['body' => 'コンテナはVMより起動が遅い',        'is_correct' => false],
                            ['body' => 'コンテナはホストOSのカーネルを共有する', 'is_correct' => true],
                            ['body' => 'コンテナは独自のOSカーネルを持つ',   'is_correct' => false],
                            ['body' => 'コンテナはVMより多くのリソースを使う', 'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => 'CDN（Content Delivery Network）',
                    'definition' => 'コンテンツを世界中の複数サーバー（エッジサーバー）に配置し、ユーザーに最も近いサーバーから配信する仕組み。',
                    'example'    => 'CloudflareやAWS CloudFrontを使い、日本のユーザーには日本のサーバーから画像を配信する。',
                    'difficulty' => 2,
                    'quiz'       => [
                        'question'    => 'CDNを利用する主な目的はどれか？',
                        'explanation' => 'CDNはユーザーに地理的に近いエッジサーバーからコンテンツを配信することでレイテンシを低減する。',
                        'choices'     => [
                            ['body' => 'データベースの処理を高速化する',         'is_correct' => false],
                            ['body' => 'ユーザーに近いサーバーから配信しレイテンシを下げる', 'is_correct' => true],
                            ['body' => 'サーバーのOSを管理する',               'is_correct' => false],
                            ['body' => 'コードを自動でデプロイする',             'is_correct' => false],
                        ],
                    ],
                ],
            ],
            'web' => [
                [
                    'name'       => 'REST（REpresentational State Transfer）',
                    'definition' => 'WebAPIの設計原則。リソースをURLで表現し、HTTPメソッド（GET/POST/PUT/DELETE）で操作する。ステートレスな通信が特徴。',
                    'example'    => 'GET /api/users/1 でID=1のユーザー情報取得、DELETE /api/users/1 で削除するAPI設計。',
                    'difficulty' => 2,
                    'quiz'       => [
                        'question'    => 'RESTのステートレスとはどういう意味か？',
                        'explanation' => 'ステートレスとは、サーバーがクライアントのセッション情報を保持しないこと。各リクエストが独立している。',
                        'choices'     => [
                            ['body' => 'サーバーが常に同じレスポンスを返す', 'is_correct' => false],
                            ['body' => 'サーバーがセッション状態を保持しない', 'is_correct' => true],
                            ['body' => 'クライアントが状態を持たない',      'is_correct' => false],
                            ['body' => '通信が暗号化される',              'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => 'Cookie',
                    'definition' => 'Webサーバーがブラウザに保存させる小さなデータ。セッション管理・ユーザー識別・トラッキングなどに使用される。',
                    'example'    => 'ショッピングサイトでログイン状態を維持するためにセッションIDをCookieに保存する。',
                    'difficulty' => 1,
                    'quiz'       => [
                        'question'    => 'CookieのHttpOnly属性の効果はどれか？',
                        'explanation' => 'HttpOnly属性を付与すると、JavaScriptからCookieにアクセスできなくなり、XSS攻撃による盗取を防止できる。',
                        'choices'     => [
                            ['body' => 'HTTPS通信でのみCookieを送信する',    'is_correct' => false],
                            ['body' => 'JavaScriptからのCookieアクセスを禁止する', 'is_correct' => true],
                            ['body' => 'CookieをHTTPで送信できなくする',    'is_correct' => false],
                            ['body' => 'Cookieの有効期限を設定する',        'is_correct' => false],
                        ],
                    ],
                ],
            ],
            'programming' => [
                [
                    'name'       => 'オブジェクト指向プログラミング（OOP）',
                    'definition' => 'データ（属性）と操作（メソッド）をひとまとめにした「オブジェクト」を基本単位とするプログラミングパラダイム。カプセル化・継承・ポリモーフィズムが3大原則。',
                    'example'    => 'Carクラスを定義し、speed・colorなどの属性とaccelerate()・brake()などのメソッドをまとめる。',
                    'difficulty' => 2,
                    'quiz'       => [
                        'question'    => 'OOPの3大原則に含まれないものはどれか？',
                        'explanation' => 'OOPの3大原則は「カプセル化・継承・ポリモーフィズム」。正規化はデータベース設計の概念。',
                        'choices'     => [
                            ['body' => 'カプセル化',   'is_correct' => false],
                            ['body' => '正規化',      'is_correct' => true],
                            ['body' => '継承',        'is_correct' => false],
                            ['body' => 'ポリモーフィズム', 'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => 'デザインパターン（GoF）',
                    'definition' => 'オブジェクト指向設計における再利用可能な設計の雛形。GoF（Gang of Four）による23のパターンが有名。生成・構造・振る舞いの3カテゴリに分類される。',
                    'example'    => 'Singletonパターン：インスタンスが1つしか存在しないことを保証するパターン（DBコネクション管理等）。',
                    'difficulty' => 3,
                    'quiz'       => [
                        'question'    => 'Singletonパターンの目的はどれか？',
                        'explanation' => 'Singletonパターンはクラスのインスタンスが1つしか生成されないことを保証し、そのインスタンスへのグローバルアクセス窓口を提供する。',
                        'choices'     => [
                            ['body' => 'オブジェクトの複製を簡単に作る',     'is_correct' => false],
                            ['body' => 'クラスのインスタンスを1つに制限する',  'is_correct' => true],
                            ['body' => 'インタフェースを統一する',           'is_correct' => false],
                            ['body' => 'アルゴリズムをカプセル化する',       'is_correct' => false],
                        ],
                    ],
                ],
            ],
            'os-linux' => [
                [
                    'name'       => 'プロセスとスレッド',
                    'definition' => 'プロセスはOSが管理するプログラムの実行単位で独立したメモリ空間を持つ。スレッドはプロセス内の実行単位でメモリを共有する。',
                    'example'    => 'Chromeブラウザは各タブを別プロセスで動作させることで、1つのタブがクラッシュしても他のタブに影響しない。',
                    'difficulty' => 3,
                    'quiz'       => [
                        'question'    => 'スレッドとプロセスの違いとして正しいものはどれか？',
                        'explanation' => 'スレッドは同一プロセス内でメモリを共有するが、プロセスは独立したメモリ空間を持つ。',
                        'choices'     => [
                            ['body' => 'スレッドはプロセスより大きな実行単位',   'is_correct' => false],
                            ['body' => 'スレッドはプロセス内のメモリを共有する', 'is_correct' => true],
                            ['body' => 'プロセスはスレッドのメモリを共有する',   'is_correct' => false],
                            ['body' => 'スレッドは独立したメモリ空間を持つ',    'is_correct' => false],
                        ],
                    ],
                ],
                [
                    'name'       => 'chmod コマンド',
                    'definition' => 'Linuxでファイルやディレクトリのパーミッション（権限）を変更するコマンド。数値（8進数）またはシンボルで権限を指定する。',
                    'example'    => 'chmod 755 script.sh で、所有者に読み書き実行権限、グループ・その他に読み実行権限を付与する。',
                    'difficulty' => 2,
                    'quiz'       => [
                        'question'    => 'chmod 644 file.txt の意味として正しいものはどれか？',
                        'explanation' => '6=rw（読み書き）、4=r（読み取り）で、所有者はrw、グループとその他はrのみとなる。',
                        'choices'     => [
                            ['body' => '所有者に実行権限、グループに書き込み権限',        'is_correct' => false],
                            ['body' => '所有者に読み書き権限、グループ・その他に読み取り権限', 'is_correct' => true],
                            ['body' => '全員に読み書き実行権限',                      'is_correct' => false],
                            ['body' => '所有者のみ全権限、他は権限なし',               'is_correct' => false],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($data as $slug => $terms) {
            $category = Category::where('slug', $slug)->first();
            if (!$category) continue;

            foreach ($terms as $termData) {
                $quizData = $termData['quiz'];
                unset($termData['quiz']);

                $term = Term::updateOrCreate(
                    ['name' => $termData['name'], 'category_id' => $category->id],
                    array_merge($termData, ['category_id' => $category->id])
                );

                $quiz = Quiz::updateOrCreate(
                    ['term_id' => $term->id, 'question' => $quizData['question']],
                    [
                        'term_id'     => $term->id,
                        'question'    => $quizData['question'],
                        'explanation' => $quizData['explanation'],
                    ]
                );

                foreach ($quizData['choices'] as $choice) {
                    \App\Models\Choice::updateOrCreate(
                        ['quiz_id' => $quiz->id, 'body' => $choice['body']],
                        array_merge($choice, ['quiz_id' => $quiz->id])
                    );
                }
            }
        }
    }
}
