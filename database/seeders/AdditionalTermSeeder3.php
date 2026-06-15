<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Choice;
use App\Models\Quiz;
use App\Models\Term;
use Illuminate\Database\Seeder;

class AdditionalTermSeeder3 extends Seeder
{
    public function run(): void
    {
        $data = $this->securityTerms()
            + $this->databaseTerms()
            + $this->algorithmTerms()
            + $this->cloudTerms()
            + $this->webTerms()
            + $this->programmingTerms()
            + $this->osLinuxTerms();

        foreach ($data as $slug => $terms) {
            $cat = Category::where('slug', $slug)->first();
            if (!$cat) continue;
            foreach ($terms as $t) {
                $term = Term::updateOrCreate(
                    ['name' => $t['name'], 'category_id' => $cat->id],
                    [
                        'definition' => $t['definition'],
                        'example'    => $t['example'] ?? null,
                        'difficulty' => $t['difficulty'],
                    ]
                );
                if (!empty($t['quiz'])) {
                    $quiz = Quiz::updateOrCreate(
                        ['term_id' => $term->id, 'question' => $t['quiz']['question']],
                        ['explanation' => $t['quiz']['explanation']]
                    );
                    foreach ($t['quiz']['choices'] as $c) {
                        Choice::updateOrCreate(
                            ['quiz_id' => $quiz->id, 'body' => $c['body']],
                            ['is_correct' => $c['is_correct']]
                        );
                    }
                }
            }
        }
    }

    // +1語
    private function securityTerms(): array
    {
        return ['security' => [
            ['name'=>'多要素認証（MFA）','definition'=>'パスワード（知識）に加え、スマートフォンのOTPアプリ（所持）や指紋（生体）など2つ以上の認証要素を組み合わせる認証方式。','example'=>'GoogleアカウントにMFAを設定し、パスワード入力後にGoogle Authenticatorの6桁コードを入力して認証する。','difficulty'=>2,'quiz'=>['question'=>'MFAで使われる認証要素の組み合わせとして正しいものはどれか？','explanation'=>'MFAは「知識（パスワード）」「所持（スマホOTP）」「生体（指紋）」の3要素のうち2つ以上を組み合わせる。同じカテゴリ2つは要素が1つしかないため不十分。','choices'=>[['body'=>'パスワードとPIN番号','is_correct'=>false],['body'=>'パスワードとスマートフォンのOTPコード','is_correct'=>true],['body'=>'ユーザー名とパスワード','is_correct'=>false],['body'=>'秘密の質問と生年月日','is_correct'=>false]]]],
        ]];
    }

    // +2語
    private function databaseTerms(): array
    {
        return ['database' => [
            ['name'=>'コールドスタンバイ / ホットスタンバイ','definition'=>'障害時に切り替えるバックアップシステムの種類。ホットスタンバイは常時起動・即時切替。コールドスタンバイは停止中で起動に時間がかかる。','example'=>'金融システムはホットスタンバイで数秒以内にフェイルオーバー。社内業務システムはコストを抑えコールドスタンバイで対応する。','difficulty'=>3,'quiz'=>['question'=>'ホットスタンバイとコールドスタンバイの主な違いはどれか？','explanation'=>'ホットスタンバイは待機系が常時稼働しているため即時切替可能。コールドスタンバイは待機系が停止しており、切替に起動時間を要する。','choices'=>[['body'=>'ホットスタンバイは電源を切って保管する','is_correct'=>false],['body'=>'ホットスタンバイは常時起動で即時切替、コールドは停止中で起動に時間がかかる','is_correct'=>true],['body'=>'両者はコスト面でのみ異なる','is_correct'=>false],['body'=>'コールドスタンバイの方が高速に切り替わる','is_correct'=>false]]]],
            ['name'=>'クエリチューニング','definition'=>'遅いSQLクエリのパフォーマンスを改善する作業。EXPLAINで実行計画を確認し、インデックス追加・クエリ書き換え・統計情報更新などを行う。','example'=>'EXPLAIN ANALYZEで全件スキャン（Seq Scan）を発見し、WHERE句の列にインデックスを追加してIndex Scanに改善する。','difficulty'=>4,'quiz'=>['question'=>'クエリチューニングの最初のステップとして最も適切なものはどれか？','explanation'=>'EXPLAINで実行計画を確認することが最初のステップ。どこでSeq Scanが起きているか・どのインデックスが使われているかを把握してから対策を考える。','choices'=>[['body'=>'いきなりインデックスをすべての列に追加する','is_correct'=>false],['body'=>'EXPLAINで実行計画を確認して問題箇所を特定する','is_correct'=>true],['body'=>'テーブルをすべて削除して再作成する','is_correct'=>false],['body'=>'DBサーバーのメモリを増設する','is_correct'=>false]]]],
        ]];
    }

    // +4語
    private function algorithmTerms(): array
    {
        return ['algorithm' => [
            ['name'=>'スライディングウィンドウ法','definition'=>'配列・文字列上でウィンドウをスライドさせながら部分列の最大・最小・合計などを効率よく求めるアルゴリズムパターン。O(n)で解ける問題が多い。','example'=>'長さkの連続部分配列の最大和を求めるとき、都度再計算せずウィンドウをスライドして差分更新するとO(n)で解ける。','difficulty'=>3,'quiz'=>['question'=>'スライディングウィンドウ法が有効な問題の典型例はどれか？','explanation'=>'連続する部分配列・部分文字列の最大・最小・和・カウントを求める問題に適用でき、全組み合わせのO(n²)をO(n)に削減できる。','choices'=>[['body'=>'全頂点間の最短経路','is_correct'=>false],['body'=>'連続する部分配列の最大和や固定長ウィンドウの集計','is_correct'=>true],['body'=>'グラフのトポロジカルソート','is_correct'=>false],['body'=>'文字列のパターンマッチング（KMP）','is_correct'=>false]]]],
            ['name'=>'二分探索木（BST）','definition'=>'各ノードの左の子が小さく、右の子が大きい性質を持つ木構造。探索・挿入・削除がO(h)（hは木の高さ）で実行できる。','example'=>'辞書の50000語をBSTに格納すると、バランスが取れていれば探索がO(log n)≒16回の比較で完了する。','difficulty'=>3,'quiz'=>['question'=>'二分探索木で中順走査（In-order traversal）を行うと何が得られるか？','explanation'=>'BSTを中順走査（左→根→右）すると、全要素が昇順に出力される。BSTの重要な性質の一つ。','choices'=>[['body'=>'全要素が降順に出力される','is_correct'=>false],['body'=>'全要素が昇順に出力される','is_correct'=>true],['body'=>'木の高さが出力される','is_correct'=>false],['body'=>'最大値のみが出力される','is_correct'=>false]]]],
            ['name'=>'累積和（Prefix Sum）','definition'=>'配列の先頭から各インデックスまでの和を事前に計算しておくテクニック。任意の区間和をO(1)で求めるために使われる。','example'=>'prefix[i] = prefix[i-1] + arr[i] と前計算すると、区間[l,r]の和が prefix[r] - prefix[l-1] でO(1)で求まる。','difficulty'=>3,'quiz'=>['question'=>'累積和を使うと区間和クエリの時間計算量がどう変わるか？','explanation'=>'前計算なし：毎回O(n)。累積和前計算O(n)後：各クエリO(1)。複数の区間和クエリがある場合に全体計算量を大幅に削減できる。','choices'=>[['body'=>'O(n²)からO(n log n)になる','is_correct'=>false],['body'=>'前計算O(n)で各クエリO(1)になる','is_correct'=>true],['body'=>'O(log n)からO(1)になる','is_correct'=>false],['body'=>'計算量は変わらない','is_correct'=>false]]]],
            ['name'=>'シミュレーションアルゴリズム','definition'=>'問題の手順を忠実にコードで再現して答えを求めるアプローチ。高度な数学的洞察より実装の正確さが求められる競技プログラミングの定番問題。','example'=>'ロボットが「LRRF...」の命令列に従って移動するとき、全命令を順に処理して最終座標を求める。','difficulty'=>2,'quiz'=>['question'=>'シミュレーションアルゴリズムの主な特徴はどれか？','explanation'=>'シミュレーションは複雑な数式やデータ構造より「問題の手順をそのままコードで再現する」実装力が求められ、条件処理の正確さが重要。','choices'=>[['body'=>'数学的証明が必要なアルゴリズム','is_correct'=>false],['body'=>'問題の手順を忠実に実装して答えを求めるアプローチ','is_correct'=>true],['body'=>'動的計画法の一種','is_correct'=>false],['body'=>'常にO(1)で解ける','is_correct'=>false]]]],
        ]];
    }

    // +5語
    private function cloudTerms(): array
    {
        return ['cloud' => [
            ['name'=>'クラウドコスト最適化','definition'=>'未使用リソースの削除・リザーブドインスタンス活用・オートスケーリング調整・ライトサイジングなどでクラウド費用を削減する実践。','example'=>'Trusted Advisorでアイドル状態のEC2を検出して停止し、常時起動のDBをリザーブドインスタンスに切り替えて月額30%削減する。','difficulty'=>3,'quiz'=>['question'=>'クラウドコスト削減に最も効果的な手法はどれか？','explanation'=>'使用率が低いインスタンスをダウンサイズ（ライトサイジング）し、長期稼働のワークロードをリザーブドインスタンスに変えるのがコスト最適化の定番手法。','choices'=>[['body'=>'全リソースを削除する','is_correct'=>false],['body'=>'ライトサイジングとリザーブドインスタンスへの切り替え','is_correct'=>true],['body'=>'リージョンを増やす','is_correct'=>false],['body'=>'ストレージのみ削減する','is_correct'=>false]]]],
            ['name'=>'コンテナオーケストレーション','definition'=>'多数のコンテナのデプロイ・スケーリング・ネットワーキング・障害復旧を自動管理する仕組み。Kubernetesが業界標準。','example'=>'Kubernetesがコンテナの死活監視を行い、クラッシュしたPodを自動再起動してサービスの継続性を保つ。','difficulty'=>3,'quiz'=>['question'=>'コンテナオーケストレーションが解決する主な課題はどれか？','explanation'=>'コンテナが増えると手動管理は限界になる。オーケストレーションは自動スケーリング・ロードバランシング・障害復旧・ローリングアップデートを自動化する。','choices'=>[['body'=>'コンテナイメージのビルドを高速化する','is_correct'=>false],['body'=>'多数のコンテナの自動スケーリング・障害復旧・デプロイ管理','is_correct'=>true],['body'=>'Dockerfileを自動生成する','is_correct'=>false],['body'=>'コンテナのセキュリティスキャン','is_correct'=>false]]]],
            ['name'=>'サーバーレスアーキテクチャ','definition'=>'サーバーの管理・プロビジョニングをクラウドに任せ、関数（コード）の実行単位でリソースを消費するアーキテクチャスタイル。','example'=>'Lambda + API Gateway + DynamoDB + S3で構成されたフルサーバーレスアーキテクチャではサーバー管理が一切不要。','difficulty'=>3,'quiz'=>['question'=>'サーバーレスアーキテクチャのデメリットはどれか？','explanation'=>'サーバーレス（FaaS）はコールドスタート（最初の呼び出し時の起動遅延）・実行時間制限・ベンダーロックインがデメリットとして挙げられる。','choices'=>[['body'=>'サーバーの台数管理が必要','is_correct'=>false],['body'=>'コールドスタートによる遅延・実行時間制限・ベンダーロックイン','is_correct'=>true],['body'=>'スケーリングが自動でできない','is_correct'=>false],['body'=>'コードが実行できない','is_correct'=>false]]]],
            ['name'=>'マネージドサービス','definition'=>'クラウドプロバイダーが運用・パッチ適用・バックアップ・スケーリングを管理するサービス。RDS・ElastiCache・SQS等が代表例。','example'=>'自前でMySQLを構築・運用する代わりにRDS（マネージドMySQL）を使えば、バックアップ・フェイルオーバー・パッチ適用をAWSが担う。','difficulty'=>2,'quiz'=>['question'=>'マネージドサービスを使う主なメリットはどれか？','explanation'=>'マネージドサービスはインフラの運用（パッチ・バックアップ・監視・HA構成）をクラウドが担当するため、開発チームはアプリ開発に集中できる。','choices'=>[['body'=>'フルカスタマイズが可能','is_correct'=>false],['body'=>'インフラ運用をクラウドに任せ、開発に集中できる','is_correct'=>true],['body'=>'必ずオンプレより高コスト','is_correct'=>false],['body'=>'セキュリティが完全に保証される','is_correct'=>false]]]],
            ['name'=>'クラウドマイグレーション','definition'=>'オンプレミスシステムをクラウドに移行する取り組み。Lift-and-Shift（そのまま移行）・リファクタリング・再構築など戦略を選ぶ。','example'=>'既存のオンプレミスWebサーバーをまずLift-and-ShiftでEC2に移行し、安定後にコンテナ化・サーバーレス化を段階的に進める。','difficulty'=>3,'quiz'=>['question'=>'Lift-and-Shift（リフト＆シフト）マイグレーションの特徴はどれか？','explanation'=>'Lift-and-Shiftはアプリを大きく変更せずそのままクラウドVMに移行する最も素早い手法。クラウドネイティブな最適化は行わないため、コスト削減効果は限定的。','choices'=>[['body'=>'アプリをマイクロサービスに分割して移行する','is_correct'=>false],['body'=>'アプリを大きく変えずそのままクラウドVMに移行する','is_correct'=>true],['body'=>'アプリを完全に再開発する','is_correct'=>false],['body'=>'オンプレとクラウドを同時に運用する','is_correct'=>false]]]],
        ]];
    }

    // +9語
    private function webTerms(): array
    {
        return ['web' => [
            ['name'=>'WebSocket（双方向通信）','definition'=>'HTTPのハンドシェイク後にTCP接続を維持し、サーバー・クライアント間でリアルタイムに双方向通信できるプロトコル。','example'=>'チャットアプリでWebSocketを使い、サーバーが新しいメッセージを受信した瞬間にポーリングなしで全クライアントにプッシュ配信する。','difficulty'=>3,'quiz'=>['question'=>'WebSocketが従来のHTTPポーリングより優れている点はどれか？','explanation'=>'WebSocketは一度接続を確立すると双方向通信が常時可能。HTTPポーリングは定期的にリクエストを送り続ける必要があり、遅延とオーバーヘッドが大きい。','choices'=>[['body'=>'セキュリティが高い','is_correct'=>false],['body'=>'接続後はサーバーからリアルタイムにプッシュでき、ポーリング不要','is_correct'=>true],['body'=>'HTTPより多くのデータを送れる','is_correct'=>false],['body'=>'モバイルのみで動作する','is_correct'=>false]]]],
            ['name'=>'JSONとXML','definition'=>'データ交換に使われる2大フォーマット。JSONはJavaScriptとの親和性が高く軽量。XMLはスキーマ定義・名前空間など厳密な構造化が可能。','example'=>'REST APIでは可読性・軽量さからJSONが主流。SOAP系APIや設定ファイル（Maven, AndroidManifest等）ではXMLが使われる。','difficulty'=>1,'quiz'=>['question'=>'Web APIでJSONがXMLより広く使われる主な理由はどれか？','explanation'=>'JSONはJavaScriptのオブジェクト表記と互換性があり、軽量・可読性が高い。XMLはタグが冗長でサイズが大きくなる。','choices'=>[['body'=>'JSONはスキーマ定義が必須だから','is_correct'=>false],['body'=>'JSONが軽量でJavaScriptとの親和性が高いから','is_correct'=>true],['body'=>'XMLはブラウザで使えないから','is_correct'=>false],['body'=>'JSONはXMLより安全だから','is_correct'=>false]]]],
            ['name'=>'CDN（Content Delivery Network）','definition'=>'世界各地のエッジサーバーにコンテンツをキャッシュして、ユーザーに近いサーバーから配信するネットワーク。レイテンシ低減と負荷軽減が目的。','example'=>'CloudFrontのエッジロケーションがS3の画像をキャッシュし、日本のユーザーへの配信レイテンシを米国オリジンの200msから10msに短縮する。','difficulty'=>2,'quiz'=>['question'=>'CDNを使う主なメリットはどれか？','explanation'=>'CDNはユーザーに地理的に近いエッジサーバーからコンテンツを配信するためレイテンシが低下し、オリジンサーバーへの負荷も軽減される。','choices'=>[['body'=>'データベースの性能が向上する','is_correct'=>false],['body'=>'ユーザーに近いエッジサーバーから配信し遅延低減・オリジン負荷軽減','is_correct'=>true],['body'=>'セキュリティが自動的に強化される','is_correct'=>false],['body'=>'サーバーサイドの処理を高速化する','is_correct'=>false]]]],
            ['name'=>'ミドルウェア（Web）','definition'=>'リクエスト/レスポンスの処理パイプラインに挿入されるソフトウェア層。認証・ロギング・レート制限などの横断的関心事を処理する。','example'=>'Laravelのミドルウェアで認証チェック→ロギング→レート制限を順に実行し、通過したリクエストのみコントローラーに届ける。','difficulty'=>2,'quiz'=>['question'=>'Webフレームワークのミドルウェアの役割はどれか？','explanation'=>'ミドルウェアはリクエストをコントローラーに渡す前（または後）に実行される共通処理。認証・ロギング・CORS・レート制限などを横断的に処理する。','choices'=>[['body'=>'データベースとの接続を管理する','is_correct'=>false],['body'=>'リクエスト/レスポンスパイプラインで認証・ロギング等の共通処理を実行','is_correct'=>true],['body'=>'フロントエンドのJavaScriptを実行する','is_correct'=>false],['body'=>'HTMLテンプレートをレンダリングする','is_correct'=>false]]]],
            ['name'=>'バンドラー（webpack）','definition'=>'複数のJavaScript・CSS・画像ファイルを依存関係を解決しながら1つまたは少数のバンドルファイルにまとめるビルドツール。','example'=>'webpackがimport文を解析してすべての依存モジュールを束ね、本番用にminify・tree shakingして最適化されたbundle.jsを生成する。','difficulty'=>3,'quiz'=>['question'=>'webpackのTree Shakingの説明として正しいものはどれか？','explanation'=>'Tree Shakingはバンドル時に実際に使われていない（dead code）エクスポートを静的解析で検出して除外し、バンドルサイズを削減する最適化。','choices'=>[['body'=>'CSSをJavaScriptに変換する','is_correct'=>false],['body'=>'使われていないコード（dead code）を静的解析で検出・除外する最適化','is_correct'=>true],['body'=>'コードを複数ファイルに分割する','is_correct'=>false],['body'=>'ソースマップを生成する','is_correct'=>false]]]],
            ['name'=>'Babel','definition'=>'最新のJavaScript（ES2015+）やJSXをより古いブラウザでも動作する形式に変換するトランスパイラ。','example'=>'BabelでES2022のオプショナルチェーニング（?.）やTop-level awaitを含むコードをIE11対応のES5に変換する。','difficulty'=>2,'quiz'=>['question'=>'BabelとTypeScriptコンパイラの主な違いはどれか？','explanation'=>'BabelはJSの構文変換（トランスパイル）のみを行い型チェックはしない。TypeScriptコンパイラは型チェック+トランスパイルを行う。両者を組み合わせることもある。','choices'=>[['body'=>'Babelは型チェックも行う','is_correct'=>false],['body'=>'Babelは構文変換のみで型チェックはせず、TSCは型チェック+変換を行う','is_correct'=>true],['body'=>'BabelはTypeScriptのみを処理する','is_correct'=>false],['body'=>'両者は全く同じ機能を持つ','is_correct'=>false]]]],
            ['name'=>'ESLint','definition'=>'JavaScriptおよびTypeScriptのコードを静的解析し、構文エラー・スタイル違反・バグになりやすいパターンを検出するLintツール。','example'=>'ESLintをCIに組み込み、未使用変数・console.logの残存・型エラーを自動検出してPRをブロックする。','difficulty'=>2,'quiz'=>['question'=>'ESLintとPrettierを両方使う場合の役割分担はどれか？','explanation'=>'ESLintはコード品質（バグ・型・ロジック）のチェック担当。Prettierはコードフォーマット（インデント・クォート・行長）の統一担当。役割が異なるため併用が推奨される。','choices'=>[['body'=>'ESLintがフォーマット、PrettierがLintを担当','is_correct'=>false],['body'=>'ESLintはコード品質チェック、Prettierはコードフォーマット統一','is_correct'=>true],['body'=>'両者はどちらか一方を使えばよい','is_correct'=>false],['body'=>'ESLintはTypeScriptのみ、PrettierはJavaScriptのみ対応','is_correct'=>false]]]],
            ['name'=>'Tailwind CSS','definition'=>'ユーティリティクラスをHTMLに直接書いてスタイリングするCSSフレームワーク。カスタムCSSをほぼ書かずにUIを構築できる。','example'=>'<button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"> でカスタムCSS不要でボタンをスタイリングする。','difficulty'=>2,'quiz'=>['question'=>'Tailwind CSSがBootstrapと異なる主な特徴はどれか？','explanation'=>'Tailwindはユーティリティクラスの組み合わせで自由なデザインを実現する。Bootstrapは事前定義されたコンポーネント（btn, card等）を提供するため、デザインの自由度が異なる。','choices'=>[['body'=>'Tailwindは事前定義コンポーネントを多数提供する','is_correct'=>false],['body'=>'Tailwindはユーティリティクラスを組み合わせて自由なデザインを実現する','is_correct'=>true],['body'=>'TailwindはCSSを一切使わない','is_correct'=>false],['body'=>'TailwindはSassのみで動作する','is_correct'=>false]]]],
            ['name'=>'Storybook','definition'=>'UIコンポーネントを独立して開発・テスト・ドキュメント化できるツール。アプリから切り離してコンポーネント単体を確認できる。','example'=>'StorybookでButtonコンポーネントの「通常・ホバー・無効・ローディング」各状態を独立して確認・テストし、デザイナーとの共有に使う。','difficulty'=>3,'quiz'=>['question'=>'Storybookの主な用途はどれか？','explanation'=>'StorybookはUIコンポーネントをアプリから独立して開発・テスト・閲覧できる環境を提供する。デザインシステムの管理やデザイナーとの協業にも使われる。','choices'=>[['body'=>'E2Eテストを自動実行する','is_correct'=>false],['body'=>'UIコンポーネントを独立して開発・確認・ドキュメント化する','is_correct'=>true],['body'=>'バックエンドAPIをモックする','is_correct'=>false],['body'=>'CSSをコンパイルする','is_correct'=>false]]]],
        ]];
    }

    // +6語
    private function programmingTerms(): array
    {
        return ['programming' => [
            ['name'=>'プロトコルバッファ（Protocol Buffers）','definition'=>'Googleが開発したバイナリシリアライゼーション形式。JSONより小さく高速でgRPCの標準フォーマット。.protoファイルでスキーマを定義する。','example'=>'.protoでUserメッセージを定義→コード生成ツールでGoやPythonのクラスを自動生成し、gRPCで高速なマイクロサービス間通信を実現する。','difficulty'=>4,'quiz'=>['question'=>'Protocol BuffersがJSONより優れている点はどれか？','explanation'=>'Protocol Buffersはバイナリ形式なのでJSON（テキスト）より小さく（3〜10倍）、シリアライズ/デシリアライズも高速。型付きスキーマで型安全も保証される。','choices'=>[['body'=>'人間が読みやすい','is_correct'=>false],['body'=>'バイナリ形式でJSONより小さく高速・型安全','is_correct'=>true],['body'=>'ブラウザでネイティブサポートされている','is_correct'=>false],['body'=>'スキーマが不要','is_correct'=>false]]]],
            ['name'=>'並行性 vs 並列性','definition'=>'並行性は複数タスクを切り替えながら進める（シングルコアでも可）。並列性は複数タスクを同時に実行する（マルチコア必須）。','example'=>'Node.jsはシングルスレッドで並行性（イベントループ）を実現。RustやGoはマルチスレッドで並列性を活用してCPU集約型処理を高速化する。','difficulty'=>3,'quiz'=>['question'=>'シングルCPUコアでも実現できるものはどれか？','explanation'=>'並行性はCPUを切り替えながら複数タスクを進めるため、シングルコアでも実現できる（OSのタイムシェアリング等）。並列性は文字通り同時実行なので複数コアが必要。','choices'=>[['body'=>'並列性（Parallelism）','is_correct'=>false],['body'=>'並行性（Concurrency）','is_correct'=>true],['body'=>'両方ともシングルコアが必要','is_correct'=>false],['body'=>'両方ともマルチコアが必要','is_correct'=>false]]]],
            ['name'=>'オブザーバーパターン','definition'=>'オブジェクト（Subject）の状態変化を複数のオブジェクト（Observer）に自動的に通知するデザインパターン。イベント駆動の基本。','example'=>'ユーザー登録イベントにEmailSender・SlackNotifier・AuditLoggerを登録し、登録完了時に全Observerが自動で呼ばれる設計。','difficulty'=>3,'quiz'=>['question'=>'オブザーバーパターンとイベント駆動設計の関係はどれか？','explanation'=>'イベント駆動設計の基盤がオブザーバーパターン。DOM EventListener・Laravelのイベント/リスナー・Vue.jsのemit等、多くのフレームワークがこのパターンを採用している。','choices'=>[['body'=>'全く関係ない別の概念','is_correct'=>false],['body'=>'イベント駆動設計はオブザーバーパターンを基盤として実装される','is_correct'=>true],['body'=>'オブザーバーはデータベースのトリガーのみに使われる','is_correct'=>false],['body'=>'オブザーバーはシングルトンパターンの一種','is_correct'=>false]]]],
            ['name'=>'冪等性（Idempotency）','definition'=>'同じ操作を何度繰り返しても結果が同じになる性質。APIのPUT・DELETE、データベースの特定の操作、メッセージキューの重複処理防止に重要。','example'=>'注文確定APIを冪等に設計し、同一リクエストIDの二重送信を検知して同じレスポンスを返すことで二重決済を防ぐ。','difficulty'=>3,'quiz'=>['question'=>'HTTP GETが冪等であるとはどういう意味か？','explanation'=>'GETリクエストを何度送っても（キャッシュを除き）サーバー側の状態は変化せず、同じ結果が返る性質が冪等性。POSTは新規リソースを作成するため非冪等。','choices'=>[['body'=>'GETは常に同じURLにリダイレクトされる','is_correct'=>false],['body'=>'GETを何度送ってもサーバー側の状態が変化しない','is_correct'=>true],['body'=>'GETはキャッシュされない','is_correct'=>false],['body'=>'GETは認証なしに実行できる','is_correct'=>false]]]],
            ['name'=>'プロファイリング','definition'=>'プログラムの実行時に各部分のCPU時間・メモリ使用・関数呼び出し回数を計測してボトルネックを特定する手法。','example'=>'PythonのcProfileでアプリを実行し、累積実行時間の上位関数を特定して、データベースクエリが95%の時間を占めることを発見する。','difficulty'=>3,'quiz'=>['question'=>'プロファイリングとベンチマークの違いはどれか？','explanation'=>'プロファイリングはコードのどの部分がボトルネックかを特定する診断ツール。ベンチマークは特定の処理の実行速度を定量的に測定して比較するもの。','choices'=>[['body'=>'両者は全く同じ意味','is_correct'=>false],['body'=>'プロファイリングはボトルネックを特定し、ベンチマークは速度を定量的に測定する','is_correct'=>true],['body'=>'プロファイリングはメモリのみを計測する','is_correct'=>false],['body'=>'ベンチマークはコードの品質を評価する','is_correct'=>false]]]],
            ['name'=>'継続的デリバリー（CD）','definition'=>'コードの変更を自動テスト・ビルド後にいつでも本番デプロイできる状態に保つ開発プラクティス。継続的デプロイとは異なり手動承認を含む場合もある。','example'=>'CI通過後にステージング環境へ自動デプロイ→QAが確認→ボタン1つで本番デプロイできる状態が継続的デリバリー。','difficulty'=>3,'quiz'=>['question'=>'継続的デリバリー（CD）と継続的デプロイ（Continuous Deployment）の違いはどれか？','explanation'=>'継続的デリバリーは本番デプロイの最終ステップを手動承認で制御できる。継続的デプロイはCIパス後に本番まで全自動でデプロイする。','choices'=>[['body'=>'継続的デリバリーはテストを省略する','is_correct'=>false],['body'=>'継続的デリバリーは手動承認を含む場合があり、継続的デプロイは全自動','is_correct'=>true],['body'=>'両者は全く同じ概念','is_correct'=>false],['body'=>'継続的デプロイはステージング環境のみに適用される','is_correct'=>false]]]],
        ]];
    }

    // +2語
    private function osLinuxTerms(): array
    {
        return ['os-linux' => [
            ['name'=>'Linuxディストリビューション','definition'=>'Linuxカーネルにパッケージマネージャー・デスクトップ・ツール群を組み合わせた配布パッケージ。Ubuntu・Debian・CentOS・AlmaLinuxなどがある。','example'=>'Webサーバー用にUbuntu LTSを選択し、5年間のセキュリティサポートと豊富なaptパッケージを活用する。','difficulty'=>1,'quiz'=>['question'=>'UbuntuとCentOSのパッケージマネージャーはそれぞれどれか？','explanation'=>'Ubuntu/Debianはapt（Advanced Package Tool）、CentOS/RHEL系はyum（またはdnf）を使う。パッケージ形式もdeb vs rpmで異なる。','choices'=>[['body'=>'両方ともapt','is_correct'=>false],['body'=>'Ubuntuはapt、CentOSはyum/dnf','is_correct'=>true],['body'=>'Ubuntuはpacman、CentOSはapt','is_correct'=>false],['body'=>'両方ともyum','is_correct'=>false]]]],
            ['name'=>'コンテキストスイッチ','definition'=>'OSが実行中のプロセス/スレッドのCPU使用権を切り替える操作。CPUレジスタ・プログラムカウンタ・スタックポインタの保存・復元が伴う。','example'=>'コンテキストスイッチが頻繁に発生するとオーバーヘッドが増え、実際の処理時間より切り替えコストが増大してパフォーマンスが低下する。','difficulty'=>4,'quiz'=>['question'=>'コンテキストスイッチのコストが問題になる場面はどれか？','explanation'=>'スレッド数がCPUコア数を大幅に超える場合（C10K問題等）、頻繁なコンテキストスイッチのオーバーヘッドがシステム性能を低下させる。','choices'=>[['body'=>'ディスクへの書き込みが多い場合','is_correct'=>false],['body'=>'スレッド数がコア数を大幅に超えて頻繁に切り替えが発生する場合','is_correct'=>true],['body'=>'メモリが不足している場合','is_correct'=>false],['body'=>'ネットワーク通信が遅い場合','is_correct'=>false]]]],
        ]];
    }
}
