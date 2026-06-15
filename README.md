# IT Learning Hub

**デモ**: https://it-learning-hub-production.up.railway.app

## デモアカウント

| 項目           | 値                 |
| -------------- | ------------------ |
| メールアドレス | `demo@example.com` |
| パスワード     | `password`         |

---

## 概要

IT Learning Hub は、IT エンジニアを目指す学習者のための **IT 用語学習 Web アプリ**です。  
単語帳で知識をインプットし、クイズでアウトプットしながら、学習履歴や苦手分析によって効率的な復習を実現します。

単なる用語集ではなく、

- 学習
- 理解度確認
- 復習
- 苦手分析

を一つのサービスで完結できる学習支援プラットフォームとして開発しました。

---

## 開発背景

技術学習を進める中で、IT用語を体系的に学習できるサービスの必要性を感じました。

既存の学習サービスは、

- 単語帳のみ
- 問題集のみ
- 学習履歴が残らない

といったケースが多く、

「覚える → 解く → 分析する → 復習する」

までを一貫して行えるサービスが少ないと感じました。

そこで、

IT用語学習と学習分析を組み合わせたWebアプリとして開発しました。

---

## このプロジェクトで取り組んだこと

- 要件定義
- 画面設計
- DB設計
- API設計
- Laravel実装
- Vue実装
- Railwayデプロイ
- 運用環境構築

設計から実装・デプロイまで一貫して担当しています。

また、Claude Codeを活用しながら開発を進め、設計方針の検討・実装・デバッグ・リファクタリングを行いました。

AIを利用しながらも、要件整理・設計判断・実装方針の決定は自身で行っています。

---

## 今後の展望

今後は以下の機能追加を予定しています。

- AIによる用語解説生成
- AIによる問題自動生成
- 学習計画自動作成
- 知識マップ機能
- 関連用語可視化機能

単なる学習アプリではなく、エンジニア学習を支援するプラットフォームへ発展させることを目指しています。

※本アプリはClaude Codeを活用しながら設計・実装・デバッグ・デプロイまで実施しました。

---

## スクリーンショット

| ダッシュボード                                    | クイズ                               |
| ------------------------------------------------- | ------------------------------------ |
| ![ダッシュボード](docs/screenshots/dashboard.png) | ![クイズ](docs/screenshots/quiz.png) |

| 単語帳                                | 苦手分析                               |
| ------------------------------------- | -------------------------------------- |
| ![単語帳](docs/screenshots/terms.png) | ![苦手分析](docs/screenshots/weak.png) |

| マイページ                                 |
| ------------------------------------------ |
| ![マイページ](docs/screenshots/mypage.png) |

---

## 機能一覧

| 機能               | 説明                                                                                     |
| ------------------ | ---------------------------------------------------------------------------------------- |
| **ダッシュボード** | 学習進捗・正答率・今日のおすすめ用語をひと目で確認                                       |
| **クイズ**         | カテゴリ・難易度を選んで 4 択クイズに挑戦。正誤と解説を即時表示                          |
| **単語帳**         | 444 語の IT 用語を検索・カテゴリ別に閲覧。お気に入り登録可能                             |
| **学習履歴**       | クイズ結果の推移をグラフで確認。月別・カテゴリ別集計                                     |
| **苦手分析**       | 正答率が低い用語を自動抽出して重点学習                                                   |
| **お気に入り**     | 気になる用語をブックマーク。後でまとめて復習                                             |
| **マイページ**     | アバター画像・名前・メール・ステータスを編集。パスワード変更・退会機能                   |
| **認証 UX**        | パスワード入力欄にアイコンで表示/非表示を切り替え。エラーメッセージは日本語で表示         |

---

## IT 用語データベース

- **444 語** ／ **8 カテゴリ**
    - ネットワーク / セキュリティ / データベース / OS・インフラ / アルゴリズム / プログラミング / クラウド / Web 技術
- 各用語に **定義・具体例・難易度・クイズ問題（4 択＋解説）** を収録

---

## 技術スタック

### バックエンド

| 技術            | バージョン／用途                          |
| --------------- | ----------------------------------------- |
| PHP             | ^8.2                                      |
| Laravel         | ^12.0                                     |
| Laravel Sanctum | SPA 認証                                  |
| SQLite          | ローカル開発用 DB                         |
| PostgreSQL      | 本番用 DB（Railway 永続ボリューム）        |

### フロントエンド

| 技術         | バージョン |
| ------------ | ---------- |
| Vue 3        | ^3.5       |
| TypeScript   | ~6.0       |
| Vite         | ^8.0       |
| Tailwind CSS | ^4.3 (v4)  |
| Pinia        | ^3.0       |
| Vue Router   | ^4.6       |

---

## ローカル開発環境のセットアップ

### 前提条件

- PHP 8.2 以上
- Composer
- Node.js 18 以上
- npm

### 手順

```bash
# 1. リポジトリをクローン
git clone <リポジトリ URL>
cd it-learning-hub

# 2. バックエンドの依存パッケージをインストール
composer install

# 3. 環境変数ファイルを作成
cp .env.example .env
php artisan key:generate

# 4. データベースを初期化（マイグレーション＋シーディング）
php artisan migrate --seed

# 5. ストレージリンクを作成（アバター画像配信用）
php artisan storage:link

# 6. フロントエンドの依存パッケージをインストール
cd frontend
npm install
cd ..

# 7. 開発サーバーを起動（バックエンド）
php artisan serve

# 8. 開発サーバーを起動（フロントエンド）— 別ターミナルで
cd frontend
npm run dev
```

起動後、ブラウザで `http://localhost:5173` にアクセスしてください。

### 一括起動（PowerShell）

```powershell
.\start.ps1
```

---

## Railway へのデプロイ

### 前提

- [Railway](https://railway.app) アカウント
- GitHub リポジトリ（パブリックまたはプライベート）

### 手順

1. **Railway ダッシュボード** で「New Project」→「Deploy from GitHub repo」を選択
2. このリポジトリを選択して「Deploy Now」
3. **PostgreSQL サービスを追加**: ダッシュボードの「+ New」→「Database」→「PostgreSQL」を選択
4. アプリサービスの「Variables」タブで以下の環境変数を設定：

| 変数名           | 値                                                       |
| ---------------- | -------------------------------------------------------- |
| `APP_ENV`        | `production`                                             |
| `APP_KEY`        | `php artisan key:generate --show` の出力値               |
| `APP_DEBUG`      | `false`                                                  |
| `APP_URL`        | Railway が発行した URL（例: `https://xxxx.railway.app`） |
| `APP_LOCALE`     | `ja`                                                     |
| `SESSION_DRIVER` | `file`                                                   |
| `CACHE_STORE`    | `file`                                                   |
| `DB_CONNECTION`  | `pgsql`                                                  |
| `DB_HOST`        | PostgreSQL サービスの `PGHOST` 値を参照                  |
| `DB_PORT`        | `5432`                                                   |
| `DB_DATABASE`    | PostgreSQL サービスの `PGDATABASE` 値を参照              |
| `DB_USERNAME`    | PostgreSQL サービスの `PGUSER` 値を参照                  |
| `DB_PASSWORD`    | PostgreSQL サービスの `PGPASSWORD` 値を参照              |

5. Variables 設定後、Railway が自動で再デプロイ
6. `https://xxxx.railway.app` にアクセスしてデモアカウントでログイン

### ビルド・起動の仕組み

- **ビルド**: `frontend/` の Vue 3 アプリをビルドして `public/spa/` に出力、その後 Composer で PHP 依存関係をインストール
- **起動**: マイグレーション → シーディング（初回のみ） → PHP 組み込みサーバー起動

### データの永続化

本番環境は Railway の PostgreSQL（永続ボリューム付き）を使用しているため、**デプロイのたびにデータがリセットされることはありません**。シーダーは `categories` テーブルが空の場合のみ実行されます。ローカル開発は引き続き SQLite を使用します。

---

## バックエンドアーキテクチャ

Controller → Service → Repository の 3 層構造を採用しています。

```
リクエスト
    ↓
Controller          # HTTP の入出力（バリデーション・レスポンス）のみ
    ↓
Service             # ビジネスロジック・複数 Repository の調整
    ↓
Repository          # Eloquent クエリのみ（Interface で抽象化）
    ↓
DB（SQLite: ローカル / PostgreSQL: 本番）
```

| 層 | 責務 | 例 |
|---|---|---|
| Controller | バリデーション・認可・レスポンス整形 | `QuizController` |
| Service | ビジネスロジック・複数リポジトリの調整 | `QuizService` |
| Repository | Eloquent クエリのカプセル化 | `QuizRepository` |

Repository は Interface を経由して DI コンテナにバインドされているため、実装を差し替えてもコントローラ・サービスへの影響がありません。

---

## ディレクトリ構成

```
it-learning-hub/
├── app/
│   ├── Http/Controllers/Api/       # Controller（HTTP 入出力のみ）
│   ├── Services/                   # Service（ビジネスロジック）
│   ├── Repositories/
│   │   ├── Interfaces/             # Repository Interface（抽象）
│   │   └── *.php                   # Eloquent 実装
│   ├── Models/                     # Eloquent モデル
│   └── Providers/AppServiceProvider.php  # Interface ↔ 実装のバインド
├── database/
│   ├── migrations/                 # DB マイグレーション
│   └── seeders/                    # IT 用語シーダー
├── routes/
│   └── api.php                     # API ルーティング
├── frontend/                       # Vue 3 フロントエンド
│   ├── src/
│   │   ├── pages/                  # 各ページコンポーネント
│   │   ├── stores/                 # Pinia ストア
│   │   ├── layouts/                # レイアウトコンポーネント
│   │   └── style.css               # グローバルスタイル
│   └── vite.config.ts
└── docs/screenshots/               # アプリのスクリーンショット
```

---

## API エンドポイント（主要）

ベース URL: `/api/v1`

| メソッド | エンドポイント                        | 認証 | 説明             |
| -------- | ------------------------------------- | ---- | ---------------- |
| POST     | `/auth/register`                      |      | 新規登録         |
| POST     | `/auth/login`                         |      | ログイン         |
| POST     | `/auth/logout`                        | ✓    | ログアウト       |
| GET      | `/auth/me`                            | ✓    | 認証ユーザー取得 |
| GET      | `/categories`                         |      | カテゴリ一覧     |
| GET      | `/terms`                              |      | 用語一覧（フィルタ対応）|
| GET      | `/terms/{id}`                         |      | 用語詳細         |
| GET      | `/quizzes/random`                     |      | ランダムクイズ取得|
| POST     | `/quiz-sessions`                      | ✓    | クイズセッション開始|
| POST     | `/quiz-sessions/{id}/answer`          | ✓    | クイズ回答       |
| POST     | `/quiz-sessions/{id}/end`             | ✓    | クイズセッション終了|
| GET      | `/learning-records`                   | ✓    | 学習履歴取得     |
| GET      | `/learning-records/stats`             | ✓    | 学習統計取得     |
| GET      | `/favorites`                          | ✓    | お気に入り一覧   |
| POST     | `/favorites/{termId}`                 | ✓    | お気に入りトグル |
| GET      | `/weak-points`                        | ✓    | 苦手分析取得     |
| PUT      | `/user/profile`                       | ✓    | プロフィール更新 |
| POST     | `/user/avatar`                        | ✓    | アバター画像更新 |
| PUT      | `/user/password`                      | ✓    | パスワード変更   |
| DELETE   | `/user/history`                       | ✓    | 学習履歴リセット |
| DELETE   | `/user`                               | ✓    | アカウント削除   |

---

## ライセンス

MIT License
