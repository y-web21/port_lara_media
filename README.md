# README

## app/.env

以下のように設定する。

```env
DB_CONNECTION="${DB_CONNECTION}"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT}"
DB_DATABASE="${MYSQL_DATABASE}"
DB_USERNAME="${MYSQL_USER}"
DB_PASSWORD="${MYSQL_PASSWORD}"
```

## cloudformation setup

1. `cp scripts/.env.aws.sample scripts/.env.aws`
1. `scripts/.env.aws`に記載されいている通りリソース(ARN)を記述する。
1. `./scripts/cfn.bash cfn-create-prj-stack`

削除は、`./scripts/cfn.bash cfn-delete-prj-stack`を実行する。
