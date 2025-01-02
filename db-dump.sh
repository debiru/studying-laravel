#!/bin/bash

cd $(dirname $0)

PROJECT_DIR="studying-laravel"
SQLCNF_FILE="${PROJECT_DIR}/.sql.cnf"
DOTENV_FILE="${PROJECT_DIR}/.env"
DB_DIR="db"

DB_FILES=$(find ${DB_DIR} -type f -name "*.dump" | sort)

function confirm() {
    read -p "$1 [Y/n]: " yn
    if [[ "$yn" = [yY]* ]]; then
        return 0
    else
        return 1
    fi
}

function get_dotenv() {
    grep "$1" ${DOTENV_FILE} | sed -s "s/^$1=//"
}

function dump_db() {
    DB_HOST="$(get_dotenv DB_HOST)"
    DB_USERNAME="$(get_dotenv DB_USERNAME)"
    DB_PASSWORD="$(get_dotenv DB_PASSWORD)"
    DB_DATABASE="$(get_dotenv DB_DATABASE)"
    echo -e "[client]\nuser = ${DB_USERNAME}\npassword = ${DB_PASSWORD}\nhost = ${DB_HOST}" > "${SQLCNF_FILE}"
    DB_FILE="${DB_DIR}/$1"
    mysqldump --defaults-extra-file=${SQLCNF_FILE} ${DB_DATABASE} > $DB_FILE && echo "DB のダンプを実行しました"
}

echo "既存の dump ファイル"
for FILE in ${DB_FILES}; do
    echo " - $(echo $FILE | sed s@^${DB_DIR}/@@)"
done
echo

read -p "dump ファイル名を入力してください（中断は 0 を指定）> " NEW_FILE
if [ "${NEW_FILE}" = "0" ]; then
    echo "処理を中断しました"
elif [ "$(find $DB_DIR -name $NEW_FILE)" != "" ]; then
    echo "同名のファイルが既に存在しています"
else
    confirm "本当に保存しますか？" && dump_db "${NEW_FILE}"
fi
