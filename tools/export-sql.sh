#!/bin/sh
# Regenerates database/sql/complete_database.sql from a freshly migrated+seeded database.
# Usage: DB=orisa_cms ./tools/export-sql.sh   (needs mysqldump access to that database)
set -e
DB="${DB:-orisa_cms}"
OUT="database/sql/complete_database.sql"
{
  echo "-- Orisa CMS - complete database (structure + initial data)"
  echo "-- Import into an EMPTY MySQL 5.7+/MariaDB 10.3+ database (utf8mb4) via phpMyAdmin > Import."
  echo "-- Equivalent to: php artisan migrate --seed. Generated from the Laravel migrations."
  echo "SET NAMES utf8mb4; SET FOREIGN_KEY_CHECKS=0; SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';"
  mysqldump "$DB" --no-tablespaces --skip-add-locks --skip-add-drop-table --skip-comments \
    --skip-set-charset --single-transaction --complete-insert --default-character-set=utf8mb4 \
    --routines=0 --triggers=0 | grep -v '^/\*M!' | grep -v '^/\*!999999' \
    | sed -E 's/^CREATE TABLE `/CREATE TABLE IF NOT EXISTS `/'
  echo "SET FOREIGN_KEY_CHECKS=1;"
} > "$OUT"
echo "Wrote $OUT"
