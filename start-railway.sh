#!/bin/bash
set -e

# マイグレーション
php artisan migrate --force

# シード（DatabaseSeeder 内で重複チェック済み、再デプロイ時は既存データを保持）
php artisan db:seed --force

# ストレージリンク
php artisan storage:link || true

# キャッシュ最適化
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 起動
php -S 0.0.0.0:${PORT:-8000} -t public
