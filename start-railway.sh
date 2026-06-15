#!/bin/bash
set -e

# マイグレーション
php artisan migrate --force

# terms テーブルが空の場合のみシード（初回デプロイ時のみ実行）
TERM_COUNT=$(php artisan tinker --execute="echo \DB::table('terms')->count();" 2>/dev/null | grep -E '^[0-9]+$' | head -1)
if [ -z "$TERM_COUNT" ] || [ "$TERM_COUNT" -eq "0" ]; then
  echo "Seeding database..."
  php artisan db:seed --force
else
  echo "Database already has $TERM_COUNT terms, skipping seed."
fi

# ストレージリンク
php artisan storage:link || true

# キャッシュ最適化
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 起動
php -S 0.0.0.0:${PORT:-8000} -t public
