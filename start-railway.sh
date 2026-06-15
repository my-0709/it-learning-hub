#!/bin/bash
set -e

# SQLite データベースファイルを作成（存在しない場合）
touch database/database.sqlite

# マイグレーション＆シーディング
php artisan migrate --force
php artisan db:seed --force

# ストレージリンク（既存でも OK）
php artisan storage:link || true

# キャッシュクリア・最適化
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 起動
php -S 0.0.0.0:${PORT:-8000} -t public
