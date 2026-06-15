<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Choice;
use App\Models\Quiz;
use App\Models\Term;
use Illuminate\Database\Seeder;

class RequestedTermSeeder extends Seeder
{
    public function run(): void
    {
        $data = $this->programmingTerms()
            + $this->databaseTerms()
            + $this->webTerms();

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

    private function programmingTerms(): array
    {
        return ['programming' => [
            [
                'name' => 'ORM',
                'definition' => 'Object-Relational Mappingの略。オブジェクト指向プログラミング言語でリレーショナルデータベースを操作する際に、SQLを直接書かずにオブジェクトとして扱えるようにするフレームワーク。',
                'example' => 'LaravelのEloquentやRuby on RailsのActive Recordが代表例。`User::find(1)` のようにSQLなしでDBを操作できる。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'ORMの主な目的はどれか？',
                    'explanation' => 'ORMはオブジェクトとDBテーブルをマッピングし、SQLを書かずにオブジェクト操作でDBを扱えるようにする。',
                    'choices' => [
                        ['body' => 'データベースのバックアップを行う', 'is_correct' => false],
                        ['body' => 'オブジェクト操作でDBを扱えるようにする', 'is_correct' => true],
                        ['body' => 'データを暗号化する', 'is_correct' => false],
                        ['body' => 'クエリの実行計画を最適化する', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Dependency Injection',
                'definition' => '依存性注入（DI）。クラスが依存するオブジェクトを内部で生成せず、外部から渡す（注入する）設計パターン。テスト容易性と疎結合を実現する。',
                'example' => 'コントローラーがDBに直接依存する代わりに、Repositoryインターフェースをコンストラクタで受け取ることでテスト時にモックに差し替えられる。',
                'difficulty' => 3,
                'quiz' => [
                    'question' => 'Dependency Injectionの主なメリットはどれか？',
                    'explanation' => 'DIにより依存先を外部から差し替えられるようになり、単体テストで本物の代わりにモックを注入できる。',
                    'choices' => [
                        ['body' => '実行速度が向上する', 'is_correct' => false],
                        ['body' => 'テスト時に依存先をモックに差し替えられる', 'is_correct' => true],
                        ['body' => 'データベース接続が不要になる', 'is_correct' => false],
                        ['body' => 'コードの行数が減る', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Repository Pattern',
                'definition' => 'データアクセスロジックをビジネスロジックから分離するデザインパターン。永続化の詳細（SQLやORMの種類）を隠蔽し、データソースの切り替えを容易にする。',
                'example' => '`UserRepository`インターフェースを定義し、本番用は`EloquentUserRepository`、テスト用は`InMemoryUserRepository`を注入して使い分ける。',
                'difficulty' => 3,
                'quiz' => [
                    'question' => 'Repository Patternを使う主な目的はどれか？',
                    'explanation' => 'Repositoryはデータアクセスをビジネスロジックやデータソース（DB種類）から分離し、テストと変更を容易にする。',
                    'choices' => [
                        ['body' => 'UIを再利用可能にする', 'is_correct' => false],
                        ['body' => 'データアクセスロジックをビジネスロジックから分離する', 'is_correct' => true],
                        ['body' => '通信を暗号化する', 'is_correct' => false],
                        ['body' => 'キャッシュを管理する', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Service Layer',
                'definition' => 'ビジネスロジックをコントローラーとモデルの間に配置するアーキテクチャパターン。コントローラーを薄く保ち、複数のコントローラーから同じビジネスロジックを再利用できる。',
                'example' => '`OrderService::createOrder()`が在庫確認・決済処理・メール送信を統括し、コントローラーはサービスを呼ぶだけにする。',
                'difficulty' => 3,
                'quiz' => [
                    'question' => 'Service Layerを設ける主な目的はどれか？',
                    'explanation' => 'ServiceLayerはビジネスロジックを集約し、コントローラーを薄くして複数箇所から同じロジックを再利用可能にする。',
                    'choices' => [
                        ['body' => 'データベースの接続を管理する', 'is_correct' => false],
                        ['body' => 'ビジネスロジックを分離して再利用性を高める', 'is_correct' => true],
                        ['body' => 'フロントエンドのコンポーネントを管理する', 'is_correct' => false],
                        ['body' => 'HTTPリクエストを受け付ける', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Middleware',
                'definition' => 'リクエストとレスポンスの処理パイプラインに挿入できる機能モジュール。認証チェック・ログ記録・CORS対応など横断的関心事を処理する。',
                'example' => 'Laravelの`auth`ミドルウェアはリクエスト到達前に認証状態を確認し、未認証ならログインページへリダイレクトする。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'Middlewareが処理される主なタイミングはどれか？',
                    'explanation' => 'MiddlewareはHTTPリクエストがコントローラーに到達する前（または後）に実行される。',
                    'choices' => [
                        ['body' => 'データベースに書き込むとき', 'is_correct' => false],
                        ['body' => 'リクエストがコントローラーに到達する前後', 'is_correct' => true],
                        ['body' => 'フロントエンドがビルドされるとき', 'is_correct' => false],
                        ['body' => 'サーバーが起動するとき', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Authentication',
                'definition' => '認証。ユーザーが「誰であるか」を確認するプロセス。パスワード・トークン・生体情報などを使って本人確認を行う。',
                'example' => 'ログインフォームでメールとパスワードを送信し、サーバーがDBと照合してJWTトークンを発行する。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'AuthenticationとAuthorizationの違いとして正しいものはどれか？',
                    'explanation' => 'Authentication（認証）は「誰か」を確認し、Authorization（認可）は「何ができるか」を判断する。',
                    'choices' => [
                        ['body' => 'Authenticationはアクセス権限の確認', 'is_correct' => false],
                        ['body' => 'Authenticationはユーザーの身元確認', 'is_correct' => true],
                        ['body' => '両者は同じ意味である', 'is_correct' => false],
                        ['body' => 'AuthenticationはAPIの速度制限', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Authorization',
                'definition' => '認可。認証済みのユーザーが「何をしてよいか」を判断するプロセス。リソースへのアクセス可否・操作権限を制御する。',
                'example' => 'ログイン済みユーザーでも、自分が作成していない記事の編集は拒否する（Policyによる権限チェック）。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'Authorizationが判断するものはどれか？',
                    'explanation' => 'Authorization（認可）は認証済みユーザーが特定のリソースや操作へのアクセス権を持つかを判断する。',
                    'choices' => [
                        ['body' => 'ユーザーのメールアドレスが正しいか', 'is_correct' => false],
                        ['body' => 'ユーザーが特定のリソースにアクセスできるか', 'is_correct' => true],
                        ['body' => 'パスワードが8文字以上か', 'is_correct' => false],
                        ['body' => 'セッションが有効か', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Validation',
                'definition' => '入力データが期待する形式・値・制約を満たしているか検証する処理。セキュリティと一貫性を守るためにシステム境界（APIやフォーム）で行う。',
                'example' => 'メールアドレスの形式チェック、パスワードの最小文字数確認、必須項目の入力チェックをサーバー側で行う。',
                'difficulty' => 1,
                'quiz' => [
                    'question' => 'Validationを行う主な場所として適切なものはどれか？',
                    'explanation' => 'クライアント側だけでは迂回可能なため、サーバー側でも必ずValidationを行う必要がある。',
                    'choices' => [
                        ['body' => 'クライアント側のみ', 'is_correct' => false],
                        ['body' => 'サーバー側でも必ず行う', 'is_correct' => true],
                        ['body' => 'データベース保存後に行う', 'is_correct' => false],
                        ['body' => 'Validationはフロントのみで十分', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Interface',
                'definition' => 'クラスが実装すべきメソッドのシグネチャを定義する契約（抽象的な型）。具体的な実装は持たず、複数の異なる実装を同一の型として扱える。',
                'example' => '`PaymentInterface`を定義し`StripePayment`と`PaypalPayment`の両クラスに実装させることで、コントローラーは決済手段を意識せずに使える。',
                'difficulty' => 3,
                'quiz' => [
                    'question' => 'Interfaceを使う主なメリットはどれか？',
                    'explanation' => 'Interfaceにより異なる実装を同一の型として扱え、依存する側が実装詳細を知らなくてよくなる。',
                    'choices' => [
                        ['body' => '実行速度が向上する', 'is_correct' => false],
                        ['body' => '異なる実装を同一型として扱い疎結合にできる', 'is_correct' => true],
                        ['body' => 'データベース接続が不要になる', 'is_correct' => false],
                        ['body' => 'メモリ使用量が減る', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'UseCase',
                'definition' => 'アプリケーションの特定のユーザー操作（ユースケース）を実現するビジネスロジックをカプセル化するクラス。Clean ArchitectureやDDDで用いられる。',
                'example' => '`RegisterUserUseCase`がユーザー登録の全手順（バリデーション・保存・メール送信）を担い、コントローラーはUseCaseを呼ぶだけにする。',
                'difficulty' => 4,
                'quiz' => [
                    'question' => 'UseCaseクラスの責務として正しいものはどれか？',
                    'explanation' => 'UseCaseは単一のアプリケーション機能のビジネスロジックを担い、コントローラーやDBの詳細から独立する。',
                    'choices' => [
                        ['body' => 'HTTPリクエストのルーティング', 'is_correct' => false],
                        ['body' => '特定のアプリケーション機能のビジネスロジックを実行する', 'is_correct' => true],
                        ['body' => 'データベースのマイグレーション', 'is_correct' => false],
                        ['body' => 'UIコンポーネントのレンダリング', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Domain',
                'definition' => 'ドメイン駆動設計（DDD）における、ビジネスの問題領域とそのルール・概念を表す層。Entity・Value Object・Aggregateなどで構成する。',
                'example' => 'ECサイトの注文ドメインには`Order`エンティティや`Money`値オブジェクト、「在庫が足りなければ注文不可」などのビジネスルールが含まれる。',
                'difficulty' => 4,
                'quiz' => [
                    'question' => 'ドメイン駆動設計（DDD）におけるDomainとして正しいものはどれか？',
                    'explanation' => 'DDDのDomainはビジネスの問題領域とそのルールを表す中核的な概念で、技術的な詳細に依存しない。',
                    'choices' => [
                        ['body' => 'データベースのテーブル設計', 'is_correct' => false],
                        ['body' => 'ビジネスの問題領域とルールを表す層', 'is_correct' => true],
                        ['body' => 'HTTPルーティングの設定', 'is_correct' => false],
                        ['body' => 'フロントエンドの画面設計', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'DTO',
                'definition' => 'Data Transfer Objectの略。レイヤー間でデータを転送するためだけのオブジェクト。ビジネスロジックを持たず、データの構造を定義する。',
                'example' => 'APIリクエストのJSONを`CreateUserDTO`に詰め替えてUseCaseに渡すことで、UseCaseはHTTPの詳細を知らずに済む。',
                'difficulty' => 3,
                'quiz' => [
                    'question' => 'DTOの主な役割はどれか？',
                    'explanation' => 'DTOはビジネスロジックを持たず、レイヤー間のデータ転送に特化したオブジェクトで、型安全性とレイヤー分離を高める。',
                    'choices' => [
                        ['body' => 'データベースのCRUD操作を行う', 'is_correct' => false],
                        ['body' => 'レイヤー間でデータを転送するための型を定義する', 'is_correct' => true],
                        ['body' => 'UIコンポーネントの状態を管理する', 'is_correct' => false],
                        ['body' => 'ビジネスロジックを実行する', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Exception Handling',
                'definition' => '例外処理。予期しないエラーが発生した際に、プログラムのクラッシュを防ぎ適切に対処する仕組み。try-catch構文でエラーをキャッチしてログ記録やレスポンス返却を行う。',
                'example' => 'DBへの接続に失敗した場合にtry-catchで例外をキャッチし、503エラーレスポンスを返してサービスの継続稼働を維持する。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'Exception Handlingの目的として正しいものはどれか？',
                    'explanation' => '例外処理によりエラーをキャッチして適切に処理し、プログラムのクラッシュを防いでユーザーに適切な応答を返せる。',
                    'choices' => [
                        ['body' => '実行速度を向上させる', 'is_correct' => false],
                        ['body' => 'エラー発生時にクラッシュを防ぎ適切に対処する', 'is_correct' => true],
                        ['body' => 'コードの重複を排除する', 'is_correct' => false],
                        ['body' => 'データベースのトランザクションを管理する', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Unit Test',
                'definition' => '単体テスト。個々の関数・メソッド・クラスを、他のコンポーネントから切り離して独立してテストする手法。高速に実行でき、バグの箇所を特定しやすい。',
                'example' => '`calculateTax(price, rate)`関数に対してさまざまな入力値を与え、期待する計算結果が返ることをアサートする。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'Unit Testの特徴として正しいものはどれか？',
                    'explanation' => 'Unit Testは最小単位のコードを単独でテストするため実行が高速で、バグの特定がしやすい。',
                    'choices' => [
                        ['body' => '複数のコンポーネントを組み合わせてテストする', 'is_correct' => false],
                        ['body' => '個々の関数・クラスを独立してテストする', 'is_correct' => true],
                        ['body' => '実際のブラウザ操作をテストする', 'is_correct' => false],
                        ['body' => 'データベースへの書き込みが必要', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Integration Test',
                'definition' => '統合テスト。複数のコンポーネントやシステムを組み合わせた状態で連携動作を検証するテスト。APIエンドポイントやDB操作を含む実際の動作を確認する。',
                'example' => 'POST /usersエンドポイントにリクエストを送り、DBにユーザーが保存されていること・201レスポンスが返ることを検証する。',
                'difficulty' => 3,
                'quiz' => [
                    'question' => 'Integration TestとUnit Testの主な違いはどれか？',
                    'explanation' => 'Integration TestはDBやAPIなど複数コンポーネントを組み合わせた動作を確認し、Unit Testより実際の動作に近い検証ができる。',
                    'choices' => [
                        ['body' => 'Integration Testの方が実行速度が速い', 'is_correct' => false],
                        ['body' => 'Integration Testは複数コンポーネントの連携動作を検証する', 'is_correct' => true],
                        ['body' => 'Integration TestはUIのみをテストする', 'is_correct' => false],
                        ['body' => 'Integration Testにはモックが必要', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'CQRS',
                'definition' => 'Command Query Responsibility Segregationの略。データの書き込み（Command）と読み取り（Query）の責務を分離するアーキテクチャパターン。',
                'example' => '注文作成（Command）はトランザクション付きDBに書き込み、注文一覧表示（Query）は読み取り最適化されたRead DBや検索インデックスから取得する。',
                'difficulty' => 4,
                'quiz' => [
                    'question' => 'CQRSで分離するものはどれか？',
                    'explanation' => 'CQRSはCommandとQueryを分離し、書き込みと読み取りで異なるモデルやDBを使うことで、それぞれの最適化を可能にする。',
                    'choices' => [
                        ['body' => 'フロントエンドとバックエンド', 'is_correct' => false],
                        ['body' => 'データの書き込み（Command）と読み取り（Query）', 'is_correct' => true],
                        ['body' => '認証と認可', 'is_correct' => false],
                        ['body' => '同期処理と非同期処理', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Event Driven',
                'definition' => 'イベント駆動アーキテクチャ。特定のイベント（注文完了・ユーザー登録など）の発生をトリガーに処理を実行するパターン。コンポーネント間の疎結合を実現する。',
                'example' => '「注文完了」イベントを発行すると、在庫更新・請求書生成・メール送信のハンドラーが非同期に実行される。',
                'difficulty' => 4,
                'quiz' => [
                    'question' => 'Event Drivenアーキテクチャの主なメリットはどれか？',
                    'explanation' => 'イベント駆動ではイベント発行者と処理者が直接依存しないため、疎結合になり新しい処理を追加しやすい。',
                    'choices' => [
                        ['body' => 'データベースの処理が高速になる', 'is_correct' => false],
                        ['body' => 'コンポーネント間を疎結合にし拡張性を高める', 'is_correct' => true],
                        ['body' => 'フロントエンドのレンダリングが速くなる', 'is_correct' => false],
                        ['body' => 'テストが不要になる', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Microservices',
                'definition' => 'アプリケーションを小さく独立したサービスに分割して構築するアーキテクチャスタイル。各サービスは独立してデプロイ・スケールでき、異なる言語・DBを選択できる。',
                'example' => 'ECサイトを「ユーザーサービス」「注文サービス」「在庫サービス」「通知サービス」に分け、APIで連携させる。',
                'difficulty' => 4,
                'quiz' => [
                    'question' => 'Microservicesアーキテクチャの特徴として正しいものはどれか？',
                    'explanation' => 'Microservicesは各サービスが独立してデプロイできるため、一部のサービスだけ更新・スケールすることが可能。',
                    'choices' => [
                        ['body' => '全機能を単一プロセスで動作させる', 'is_correct' => false],
                        ['body' => '各サービスを独立してデプロイ・スケールできる', 'is_correct' => true],
                        ['body' => 'データベースは必ず1つを共有する', 'is_correct' => false],
                        ['body' => '通信はすべて同一プロセス内で行う', 'is_correct' => false],
                    ],
                ],
            ],
        ]];
    }

    private function databaseTerms(): array
    {
        return ['database' => [
            [
                'name' => 'Transaction',
                'definition' => '複数のDB操作を一つの単位として扱う仕組み。ACID特性（原子性・一貫性・独立性・永続性）を保証し、すべて成功またはすべてロールバックされる。',
                'example' => '送金処理で「Aの残高を減らす」と「Bの残高を増やす」を1トランザクションにまとめ、片方だけ成功する状態を防ぐ。',
                'difficulty' => 3,
                'quiz' => [
                    'question' => 'トランザクションのACID特性のうち「原子性」の説明として正しいものはどれか？',
                    'explanation' => '原子性（Atomicity）はトランザクション内の操作がすべて成功するか、すべて失敗（ロールバック）するかのどちらかを保証する。',
                    'choices' => [
                        ['body' => '複数のトランザクションが同時に実行できる', 'is_correct' => false],
                        ['body' => 'トランザクション内の操作は全成功か全失敗のどちらかになる', 'is_correct' => true],
                        ['body' => 'データは永続的に保存される', 'is_correct' => false],
                        ['body' => 'データは常に一貫した状態を保つ', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Primary Key',
                'definition' => 'テーブルの各行を一意に識別するカラム（または列の組み合わせ）。NULL値は許容せず、重複も許可されない。',
                'example' => 'usersテーブルの`id`カラムをPrimary Keyとして設定することで、各ユーザーを一意に特定できる。',
                'difficulty' => 1,
                'quiz' => [
                    'question' => 'Primary Keyの制約として正しいものはどれか？',
                    'explanation' => 'Primary KeyはNULL不可かつ一意（ユニーク）でなければならない。テーブルごとに1つだけ設定できる。',
                    'choices' => [
                        ['body' => 'NULLが許容される', 'is_correct' => false],
                        ['body' => 'NULL不可かつ一意の値でなければならない', 'is_correct' => true],
                        ['body' => '重複した値を持てる', 'is_correct' => false],
                        ['body' => '1つのテーブルに複数設定できる', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Foreign Key',
                'definition' => '別テーブルのPrimary Keyを参照するカラム。テーブル間の参照整合性を保証し、存在しない値への参照を防ぐ。',
                'example' => 'postsテーブルの`user_id`カラムをusersテーブルのidへの外部キーとして定義し、存在しないユーザーへの投稿を防ぐ。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'Foreign Keyの主な役割はどれか？',
                    'explanation' => 'Foreign Keyは参照先テーブルに存在しない値を参照できないよう制約し、データの参照整合性を保証する。',
                    'choices' => [
                        ['body' => '検索を高速化する', 'is_correct' => false],
                        ['body' => 'テーブル間の参照整合性を保証する', 'is_correct' => true],
                        ['body' => '行を一意に識別する', 'is_correct' => false],
                        ['body' => 'NULLを禁止する', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Index',
                'definition' => 'テーブルの特定カラムに作成するデータ構造。検索・ソートを高速化するが、書き込み時のオーバーヘッドとストレージ使用量が増加する。',
                'example' => 'usersテーブルのemailカラムにインデックスを付けることで、`WHERE email = ?`の検索が全件スキャンなしに高速化される。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'Indexを使うデメリットとして正しいものはどれか？',
                    'explanation' => 'Indexは検索を高速化するが、データの追加・更新・削除のたびにインデックスも更新が必要なため書き込み性能が低下する。',
                    'choices' => [
                        ['body' => 'SELECT文が遅くなる', 'is_correct' => false],
                        ['body' => '書き込み（INSERT/UPDATE/DELETE）が遅くなる', 'is_correct' => true],
                        ['body' => 'NULL値が許容されなくなる', 'is_correct' => false],
                        ['body' => 'テーブルの結合ができなくなる', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Normalization',
                'definition' => '正規化。データの重複を排除し整合性を保つために、テーブルを適切に分割する手法。第1〜第3正規形などの段階がある。',
                'example' => '注文テーブルに顧客名と住所を直接持つ代わりに、顧客テーブルを分離してForeign Keyで参照することで重複を排除する。',
                'difficulty' => 3,
                'quiz' => [
                    'question' => '正規化の主な目的はどれか？',
                    'explanation' => '正規化はデータの重複排除と更新時の不整合防止が目的。過度な正規化はJOINコストが増えるためトレードオフがある。',
                    'choices' => [
                        ['body' => 'クエリの実行速度を最大化する', 'is_correct' => false],
                        ['body' => 'データの重複を排除し整合性を保つ', 'is_correct' => true],
                        ['body' => 'テーブルのカラム数を増やす', 'is_correct' => false],
                        ['body' => 'Indexを自動生成する', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'JOIN',
                'definition' => '複数のテーブルを関連するカラムで結合して一つの結果セットとして取得するSQL操作。INNER JOIN・LEFT JOIN・RIGHT JOINなどの種類がある。',
                'example' => 'INNER JOINで注文テーブルとユーザーテーブルを結合し、各注文に対してユーザー名を含む一覧を取得する。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'LEFT JOINの動作として正しいものはどれか？',
                    'explanation' => 'LEFT JOINは左テーブルの全行を返し、右テーブルに一致する行がなければNULLを補完する。',
                    'choices' => [
                        ['body' => '両テーブルに一致する行のみ返す', 'is_correct' => false],
                        ['body' => '左テーブルの全行と右テーブルの一致行を返す', 'is_correct' => true],
                        ['body' => '右テーブルの全行を返す', 'is_correct' => false],
                        ['body' => '両テーブルの全行を返す', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'N+1問題',
                'definition' => '1回のクエリで一覧を取得した後、各レコードの関連データを個別にN回クエリする非効率なパターン。ORMのLazy Loadingで発生しやすい。',
                'example' => '投稿一覧（1回）を取得した後、各投稿の著者名を取得するために著者ごとに1回ずつクエリが走り、100投稿なら101回のクエリが発生する。',
                'difficulty' => 3,
                'quiz' => [
                    'question' => 'N+1問題の解決策として最も適切なものはどれか？',
                    'explanation' => 'Eager Loading（ORMのwith()など）で関連データを最初のクエリと同時またはIN句でまとめて取得することでN+1を解消できる。',
                    'choices' => [
                        ['body' => 'クエリをキャッシュする', 'is_correct' => false],
                        ['body' => 'Eager Loadingで関連データをまとめて取得する', 'is_correct' => true],
                        ['body' => 'インデックスを追加する', 'is_correct' => false],
                        ['body' => 'テーブルを正規化する', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Explain',
                'definition' => 'SQLクエリの実行計画を表示するコマンド。インデックスが使われているか・全件スキャンが発生していないかなどを確認してクエリのボトルネックを特定する。',
                'example' => '`EXPLAIN SELECT * FROM users WHERE email = ?`を実行し、typeが`ALL`（フルスキャン）ならインデックス不足を疑う。',
                'difficulty' => 3,
                'quiz' => [
                    'question' => 'EXPLAINコマンドを使う目的はどれか？',
                    'explanation' => 'EXPLAINはクエリの実行計画を表示し、インデックス使用有無や結合順序を確認してチューニングに役立てる。',
                    'choices' => [
                        ['body' => 'SQLの構文エラーを修正する', 'is_correct' => false],
                        ['body' => 'クエリの実行計画を確認してボトルネックを特定する', 'is_correct' => true],
                        ['body' => 'テーブルのカラム定義を表示する', 'is_correct' => false],
                        ['body' => 'データをエクスポートする', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Query Optimization',
                'definition' => 'SQLクエリの実行速度や効率を向上させる手法の総称。インデックス活用・不要なカラム取得の削減・JOINの最適化などが含まれる。',
                'example' => '`SELECT *`を`SELECT id, name`に変更したり、WHERE句で使われるカラムにインデックスを付けてクエリ速度を改善する。',
                'difficulty' => 3,
                'quiz' => [
                    'question' => 'Query Optimizationの手法として適切でないものはどれか？',
                    'explanation' => '全テーブルに無差別にインデックスを付けると、書き込み性能が低下し逆効果になる場合がある。',
                    'choices' => [
                        ['body' => 'SELECT * を必要なカラムのみに絞る', 'is_correct' => false],
                        ['body' => '検索条件のカラムにインデックスを追加する', 'is_correct' => false],
                        ['body' => 'すべてのカラムに無条件でインデックスを付ける', 'is_correct' => true],
                        ['body' => 'EXPLAINで実行計画を確認する', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Lock',
                'definition' => '複数のトランザクションが同時に同じデータを変更しないよう排他的に制御する仕組み。共有ロック（読み取り）と排他ロック（書き込み）がある。',
                'example' => '商品の在庫数を更新するトランザクション開始時に行ロックをかけ、他のトランザクションが同じ行を同時に変更できないようにする。',
                'difficulty' => 3,
                'quiz' => [
                    'question' => '排他ロックの説明として正しいものはどれか？',
                    'explanation' => '排他ロック（X lock）はロック中の行に対して他のトランザクションからの読み書きをすべてブロックする。',
                    'choices' => [
                        ['body' => '他のトランザクションからの読み取りは許可する', 'is_correct' => false],
                        ['body' => '他のトランザクションからの読み書きをすべてブロックする', 'is_correct' => true],
                        ['body' => '読み取りのみを許可し書き込みをブロックする', 'is_correct' => false],
                        ['body' => 'ロックをかけてもパフォーマンスに影響しない', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Deadlock',
                'definition' => 'デッドロック。2つ以上のトランザクションが互いに相手のロック解放を待ち合い、永久に進まなくなる状態。DBMSが検知してどちらかをロールバックする。',
                'example' => 'トランザクションAがテーブル1をロックしてテーブル2を待ち、トランザクションBがテーブル2をロックしてテーブル1を待つことで発生する。',
                'difficulty' => 4,
                'quiz' => [
                    'question' => 'デッドロックが発生した場合のDBMSの一般的な対処方法はどれか？',
                    'explanation' => 'DBMSはデッドロックを検知すると、片方のトランザクションを犠牲（ロールバック）にしてデッドロックを解消する。',
                    'choices' => [
                        ['body' => 'データベースを再起動する', 'is_correct' => false],
                        ['body' => '一方のトランザクションを自動的にロールバックする', 'is_correct' => true],
                        ['body' => '両方のトランザクションを強制コミットする', 'is_correct' => false],
                        ['body' => '管理者が手動で解決するまで待つ', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Pagination',
                'definition' => 'ページネーション。大量のデータを一度に返さず、ページ番号やカーソルで分割して取得する手法。パフォーマンスとUXを向上させる。',
                'example' => '10万件の商品一覧を1ページ20件ずつ返し、`?page=2`でオフセットを計算してLIMIT/OFFSETクエリで取得する。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'Paginationを実装する主な理由はどれか？',
                    'explanation' => 'Paginationにより大量データを一括返却せずに済み、メモリ使用量・転送量・レスポンス時間を削減できる。',
                    'choices' => [
                        ['body' => 'データを安全に暗号化するため', 'is_correct' => false],
                        ['body' => '大量データを分割して転送量とレスポンス時間を削減する', 'is_correct' => true],
                        ['body' => '重複データを排除するため', 'is_correct' => false],
                        ['body' => 'テーブルを結合するため', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Partition',
                'definition' => 'パーティショニング。巨大なテーブルを特定のルール（日付・IDの範囲など）で複数の物理的な区画に分割し、クエリ性能とメンテナンス性を向上させる手法。',
                'example' => 'ログテーブルを月ごとにパーティションに分割することで、過去月のデータへのクエリが当該パーティションのみをスキャンして高速化される。',
                'difficulty' => 4,
                'quiz' => [
                    'question' => 'テーブルパーティショニングの主なメリットはどれか？',
                    'explanation' => 'パーティショニングにより大規模テーブルのクエリが該当パーティションのみをスキャンするため高速化し、古いパーティションの削除も容易になる。',
                    'choices' => [
                        ['body' => 'テーブルの結合が不要になる', 'is_correct' => false],
                        ['body' => '大規模テーブルのクエリ性能向上と管理容易化', 'is_correct' => true],
                        ['body' => 'データの重複を自動排除する', 'is_correct' => false],
                        ['body' => 'バックアップが不要になる', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Replication',
                'definition' => 'レプリケーション。データベースのデータを複数のサーバーにリアルタイムまたは非同期でコピーする技術。可用性向上・読み取り負荷分散・バックアップに使われる。',
                'example' => 'Masterに書き込み、Slaveに読み取りを分散させることで、読み取り負荷が高い場合でもMasterへの影響を抑えられる。',
                'difficulty' => 3,
                'quiz' => [
                    'question' => 'Master-Slaveレプリケーションの典型的な使い方はどれか？',
                    'explanation' => 'MasterへはWrite、SlaveへはReadを振り分けることで、読み取り負荷をスケールアウトしMasterの書き込み処理を保護する。',
                    'choices' => [
                        ['body' => 'Slaveに書き込み、Masterから読み取る', 'is_correct' => false],
                        ['body' => 'Masterに書き込み、Slaveで読み取り負荷を分散する', 'is_correct' => true],
                        ['body' => '両方に同時に書き込む', 'is_correct' => false],
                        ['body' => 'テーブルを複数DBに分割して保存する', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Sharding',
                'definition' => 'シャーディング。データを特定のキー（ユーザーIDや地域など）によって複数のデータベースサーバーに水平分散して保存するスケーリング手法。',
                'example' => 'ユーザーIDの末尾1桁でDB0〜DB9に振り分け、1台に集中する書き込み負荷を10台に分散させる。',
                'difficulty' => 4,
                'quiz' => [
                    'question' => 'ShardingとReplicationの違いとして正しいものはどれか？',
                    'explanation' => 'Shardingは異なるデータを別サーバーに分散（書き込みスケールアウト）し、Replicationは同じデータをコピー（読み取りスケールアウト・冗長化）する。',
                    'choices' => [
                        ['body' => 'ShardingはReplicationの一種である', 'is_correct' => false],
                        ['body' => 'Shardingは異なるデータを複数DBに分散し、Replicationは同じデータをコピーする', 'is_correct' => true],
                        ['body' => '両者は完全に同じ技術である', 'is_correct' => false],
                        ['body' => 'Shardingは読み取り専用のコピーを作る', 'is_correct' => false],
                    ],
                ],
            ],
        ]];
    }

    private function webTerms(): array
    {
        return ['web' => [
            [
                'name' => 'SPA',
                'definition' => 'Single Page Applicationの略。一つのHTMLページをベースにJavaScriptで動的にコンテンツを更新するWebアプリ。ページ遷移ごとに全ページを再読み込みしない。',
                'example' => 'ReactやVue.jsで構築されたWebアプリ。ルーティングもJavaScriptが担い、APIからデータを取得してUIを更新する。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'SPAの特徴として正しいものはどれか？',
                    'explanation' => 'SPAはページ遷移時にページ全体をリロードせず、JavaScriptが必要な部分だけ更新するため高速なUXを実現する。',
                    'choices' => [
                        ['body' => 'ページ遷移のたびに全HTMLをサーバーから取得する', 'is_correct' => false],
                        ['body' => 'JavaScriptでコンテンツを動的に更新しページ全体のリロードを避ける', 'is_correct' => true],
                        ['body' => 'サーバーサイドでHTMLを生成して返す', 'is_correct' => false],
                        ['body' => 'JavaScriptを使用しない', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Component',
                'definition' => 'UIを再利用可能な独立した部品に分割したもの。テンプレート・ロジック・スタイルをまとめて一つの単位として管理する。',
                'example' => 'ボタン・モーダル・ナビゲーションバーなどをそれぞれComponentとして作成し、複数ページで再利用する。',
                'difficulty' => 1,
                'quiz' => [
                    'question' => 'Componentを使う主な目的はどれか？',
                    'explanation' => 'ComponentはUIを独立した部品に分割することで再利用性を高め、コードの保守性と可読性を向上させる。',
                    'choices' => [
                        ['body' => 'サーバーへのリクエスト数を減らす', 'is_correct' => false],
                        ['body' => 'UIを再利用可能な部品に分割して保守性を高める', 'is_correct' => true],
                        ['body' => 'データベースへのアクセスを抽象化する', 'is_correct' => false],
                        ['body' => '通信を暗号化する', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Props',
                'definition' => 'Properties（プロパティ）の略。親コンポーネントから子コンポーネントへデータを渡す仕組み。単方向のデータフローを実現し、コンポーネント間の依存を明示する。',
                'example' => '`<Button label="送信" color="primary" />`のように親からlabelとcolorをPropsとして渡す。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'Propsの特徴として正しいものはどれか？',
                    'explanation' => 'Propsは親→子への単方向データフロー。子はPropsを受け取るだけで、直接変更することは原則禁止されている。',
                    'choices' => [
                        ['body' => '子コンポーネントがPropsを直接変更できる', 'is_correct' => false],
                        ['body' => '親から子へ一方向にデータを渡す', 'is_correct' => true],
                        ['body' => '子から親へデータを渡す仕組みである', 'is_correct' => false],
                        ['body' => 'グローバルな状態管理に使う', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'State',
                'definition' => 'コンポーネントが内部で保持するデータ。変更されると自動的にUIが再レンダリングされる。ローカルな状態はコンポーネント内で管理し、共有状態はストアで管理する。',
                'example' => 'モーダルの開閉状態・フォームの入力値・APIのロード中フラグなどをStateとして管理する。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'Stateが変更されたときに起こることはどれか？',
                    'explanation' => 'StateはReactiveなデータで、変更されるとフレームワークが差分を検知して自動的にUIを再レンダリングする。',
                    'choices' => [
                        ['body' => 'ページ全体がリロードされる', 'is_correct' => false],
                        ['body' => 'UIが自動的に再レンダリングされる', 'is_correct' => true],
                        ['body' => 'サーバーにリクエストが送信される', 'is_correct' => false],
                        ['body' => '何も起こらない', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Lifecycle',
                'definition' => 'コンポーネントのライフサイクル。作成（mounted）・更新（updated）・破棄（unmounted）などの各フェーズで特定の処理を実行するフックを提供する。',
                'example' => '`onMounted()`でAPIを叩いてデータを取得し、`onUnmounted()`でタイマーやイベントリスナーをクリーンアップする。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'VueのonMounted()フックが実行されるタイミングはどれか？',
                    'explanation' => 'onMounted()はコンポーネントがDOMにマウントされた後に実行される。この時点でDOM操作やAPIコールが安全に行える。',
                    'choices' => [
                        ['body' => 'コンポーネントが定義されたとき', 'is_correct' => false],
                        ['body' => 'コンポーネントがDOMにマウントされた後', 'is_correct' => true],
                        ['body' => 'Stateが変更されたとき', 'is_correct' => false],
                        ['body' => 'コンポーネントが破棄されたとき', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Reactive',
                'definition' => 'データの変更を自動的に検知してUIを更新する仕組み。リアクティブなデータを宣言すると、そのデータに依存するUIが変更と連動して自動更新される。',
                'example' => 'VueのreactiveオブジェクトやRefを使うと、値を変えるだけでテンプレートが自動的に再レンダリングされる。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'Reactiveシステムの特徴として正しいものはどれか？',
                    'explanation' => 'Reactiveシステムはデータの変更を検知し、そのデータに依存するUI部分を自動で更新するため手動のDOM操作が不要になる。',
                    'choices' => [
                        ['body' => 'データ変更後に手動でUIを更新する必要がある', 'is_correct' => false],
                        ['body' => 'データの変更を検知してUIを自動更新する', 'is_correct' => true],
                        ['body' => 'サーバーからデータをプッシュで受信する', 'is_correct' => false],
                        ['body' => 'バックエンドのAPIを自動生成する', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'CSR',
                'definition' => 'Client Side Renderingの略。HTMLの生成・データの取得・レンダリングをすべてブラウザ（クライアント）側のJavaScriptで行うレンダリング方式。SPAの基本的なアプローチ。',
                'example' => '初期ロードでほぼ空のHTMLとJSバンドルを受け取り、ブラウザ側でAPIを叩いてコンテンツを構築して表示する。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'CSRのデメリットとして正しいものはどれか？',
                    'explanation' => 'CSRは初期HTMLがほぼ空のためSEOに不利で、JavaScriptのダウンロード・解析・実行が完了するまでコンテンツが表示されない。',
                    'choices' => [
                        ['body' => 'サーバーの負荷が高い', 'is_correct' => false],
                        ['body' => '初期表示が遅くSEOに不利', 'is_correct' => true],
                        ['body' => 'リアルタイム更新ができない', 'is_correct' => false],
                        ['body' => 'JavaScriptが使えない', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'SSR',
                'definition' => 'Server Side Renderingの略。サーバー側でHTMLを完全に生成してクライアントに返すレンダリング方式。SEOに有利で初期表示が速い。',
                'example' => 'Next.js（React）やNuxt.js（Vue）でSSRを使うと、サーバーがAPIを叩いてデータを埋め込んだHTMLを返す。',
                'difficulty' => 3,
                'quiz' => [
                    'question' => 'SSRがCSRと比べて優れている点はどれか？',
                    'explanation' => 'SSRはサーバーがHTMLを生成して返すため、ブラウザがJavaScriptを実行する前にコンテンツが表示でき、SEOにも有利。',
                    'choices' => [
                        ['body' => 'サーバーリソースを使わない', 'is_correct' => false],
                        ['body' => '初期表示が速くSEOに有利', 'is_correct' => true],
                        ['body' => 'インタラクティブな操作が即座に使える', 'is_correct' => false],
                        ['body' => 'クライアントのJavaScriptが不要', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Hydration',
                'definition' => 'SSRで受け取ったサーバー生成の静的HTMLに、クライアント側でJavaScriptのイベントリスナーやリアクティブな機能を付与するプロセス。',
                'example' => 'Next.jsはサーバーでHTMLを生成してブラウザに送り、その後ブラウザでHydrationを実行してReactのイベントハンドラを紐付ける。',
                'difficulty' => 4,
                'quiz' => [
                    'question' => 'HydrationはSSRのどのフェーズで行われるか？',
                    'explanation' => 'HydrationはSSRで返された静的HTMLをブラウザが受け取った後、JavaScriptが読み込まれてUIをインタラクティブにするプロセス。',
                    'choices' => [
                        ['body' => 'サーバーでHTMLを生成するとき', 'is_correct' => false],
                        ['body' => 'ブラウザがHTMLを受け取った後にJavaScriptで機能を付与するとき', 'is_correct' => true],
                        ['body' => 'APIからデータを取得するとき', 'is_correct' => false],
                        ['body' => 'データベースへ書き込むとき', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Composition API',
                'definition' => 'Vue 3で導入されたAPIスタイル。setup()関数内でref・reactive・computed・watchなどの関数を使ってリアクティブなロジックを組み合わせる。再利用性と型推論が向上する。',
                'example' => '`<script setup>`内でuseCounter()のようなComposable関数を作り、カウンターロジックを複数コンポーネントで再利用する。',
                'difficulty' => 3,
                'quiz' => [
                    'question' => 'Composition APIがOptions APIより優れている点はどれか？',
                    'explanation' => 'Composition APIはロジックを機能単位でまとめたComposable関数として抽出でき、コンポーネント間での再利用性とTypeScriptの型推論が向上する。',
                    'choices' => [
                        ['body' => 'テンプレートの書き方が変わる', 'is_correct' => false],
                        ['body' => 'ロジックをComposable関数として再利用でき型推論も向上する', 'is_correct' => true],
                        ['body' => 'サーバーサイドのコードが不要になる', 'is_correct' => false],
                        ['body' => 'CSSの記述量が減る', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Pinia',
                'definition' => 'Vue 3向けの状態管理ライブラリ。Vuexの後継として設計され、TypeScriptとComposition APIとの親和性が高く、シンプルなAPIでグローバル状態を管理する。',
                'example' => '`defineStore()`でauthストアを定義し、ログイン中ユーザー情報を複数コンポーネントで共有する。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'PiniaがVuexより優れている主な点はどれか？',
                    'explanation' => 'PiniaはTypeScriptとの親和性が高く、mutations不要でシンプルなAPIを持ち、DevToolsとのintegrationも良好。',
                    'choices' => [
                        ['body' => 'Vuexより動作が重い', 'is_correct' => false],
                        ['body' => 'TypeScript親和性が高くmutations不要のシンプルなAPI', 'is_correct' => true],
                        ['body' => 'Reactでも使用できる', 'is_correct' => false],
                        ['body' => 'グローバル状態を管理できない', 'is_correct' => false],
                    ],
                ],
            ],
            [
                'name' => 'Lazy Loading',
                'definition' => '遅延読み込み。コンポーネントやモジュール・画像などを初期ロード時ではなく、必要になったタイミングで読み込む手法。初期ロード時間を短縮する。',
                'example' => 'Vue Routerでルートコンポーネントを`() => import(\'./pages/SettingsPage.vue\')`と定義し、そのページにアクセスしたときだけJSを読み込む。',
                'difficulty' => 2,
                'quiz' => [
                    'question' => 'Lazy Loadingを使う主な目的はどれか？',
                    'explanation' => 'Lazy Loadingにより、初期ロード時に不要なコードや画像の読み込みを遅延させ、最初の表示速度（TBT・FCP）を改善できる。',
                    'choices' => [
                        ['body' => 'データの整合性を保証する', 'is_correct' => false],
                        ['body' => '初期ロード時間を短縮する', 'is_correct' => true],
                        ['body' => 'サーバーの負荷を増やす', 'is_correct' => false],
                        ['body' => 'リアルタイム通信を実現する', 'is_correct' => false],
                    ],
                ],
            ],
        ]];
    }
}
