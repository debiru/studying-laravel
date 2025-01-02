#!/bin/bash

cd $(dirname $0)

PROJECT_DIR="studying-laravel"
SQLCNF_FILE="${PROJECT_DIR}/.sql.cnf"
DOTENV_FILE="${PROJECT_DIR}/.env"
DB_DIR="db"

DB_FILES=$(find ${DB_DIR} -type f -name "*.dump" | sort)

if [ "${DB_FILES}" = "" ]; then
    echo "dump ファイルが見つかりませんでした"
    exit
fi

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

function restore_db() {
    DB_HOST="$(get_dotenv DB_HOST)"
    DB_USERNAME="$(get_dotenv DB_USERNAME)"
    DB_PASSWORD="$(get_dotenv DB_PASSWORD)"
    DB_DATABASE="$(get_dotenv DB_DATABASE)"
    echo -e "[client]\nuser = ${DB_USERNAME}\npassword = ${DB_PASSWORD}\nhost = ${DB_HOST}" > "${SQLCNF_FILE}"
    mysql --defaults-extra-file=${SQLCNF_FILE} ${DB_DATABASE} < $1 && echo "DB のリストアを実行しました"
}

echo "適用したい dump ファイルの番号を指定してください（中断は 0 を指定）"
select SELECT_FILE in ${DB_FILES}; do
    if [ -n "${SELECT_FILE}" ]; then
        echo "${SELECT_FILE} を選択しました"
        confirm "本当に DB のリストアを実行しますか？" && restore_db "${SELECT_FILE}"
    else
        echo "処理を中断しました"
    fi
    break
done
