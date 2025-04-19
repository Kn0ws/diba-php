![image](./diba-php.png)

# DIBA PHP

**Declarative Intent Based Architecture** ~ PHP製の意図駆動型マイクロフレームワーク ~  

意図駆動型アーキテクチャは、開発者が「何をしたいか」を中心に設計されており、意図を明確にすることで、コードの可読性と保守性を向上させます。  

YAMLでIntentを宣言し、Executorで実行、柔軟に拡張可能なイベント/状態/レスポンス設計を行っています。

---

## 🔰 DIBA PHP の特徴と拡張性

### 1. Intentベース設計によるスケーラビリティ
- Intentは1ファイル1定義で疎結合な設計です。
- YAMLにより「設定で定義 → 実装を差し替え」が可能です。
- サービスごとにIntentを分離してモジュール化が可能です。

### 2. Executorのチェーン実行・多段階処理
```yaml
executors:
  - AuthExecutor@check
  - UserExecutor@create
  - MailExecutor@notify
```
Intentにより、フローを柔軟に組み立てられます。

### 3. on_effects によるIntent連鎖
```yaml
effects:
  - user.created
on_effects:
  user.created: SendWelcomeMail
```
`state->emit()` による非同期イベント連携。

### 4. カスタムレスポンスの追加
- Json, Html, Redirect, View に加えて、CSV, PDF, XML なども簡単に拡張可能。
```bash
php cli/diba.php make:response Csv
```

### 5. バリデーションの再利用
- RequireName, RequireToken, CheckPermission などのConstraintを組み合わせて再利用可能。

### 6. CLI経由で高速開発
- Intent/Executor/Model/Service 生成、シミュレート、ドキュメント化まで一括。

### 7. マルチプロジェクト対応
- `app/`以下を分割、Intentディレクトリを複数指定可能。

### 8. DB接続の柔軟性
- `config/database.php` により MySQL, PostgreSQL, SQLite, MSSQL など切り替え可。

### 9. ミドルウェア構造の柔軟性
- constraints を middlewares に拡張可能。before/after構文もOK。

### 10. 非同期ジョブ/イベント処理への拡張性
- `state->emit()` → `JobQueue` → `worker.php` 実行 → 非同期処理対応済。

---

## ✅ 必要要件
- PHP >= 8.0
- Composer >= 2.8

---

## 🚀 インストール手順
```bash
git clone https://github.com/kn0ws/diba-php.git
cd diba-php
composer install
cp env.sample .env
php -S localhost:8080 -t public
```

## ✅ 動作確認
```bash
curl http://localhost:8080/welcome
```
レスポンス:
```json
{
  "message": "Welcome to DIBA PHP!",
  "time": "2025-04-16 00:00:00"
}
```

---

## 📂 ディレクトリ構成
```
app/
├── Constraints/     ← バリデーション・ミドルウェア相当
├── Executors/       ← 実行処理
├── Intents/         ← Intent定義 (YAML)
├── Models/          ← Eloquent ORM
├── Responses/       ← カスタムレスポンス（任意）
├── Services/        ← 業務ロジック
├── Views/           ← PHP Viewテンプレート
diba/                ← フレームワーク本体
config/
├── database.php     ← DB接続情報
├── env.php          ← .env読み込み
├── events.php       ← イベント定義
├── queue.php        ← ジョブキュー定義
├── route_types.php  ← Intentルーティングの型制約
public/
├── index.php        ← エントリーポイント
cli/
├── diba.php         ← CLIコマンドランチャー
├── Commands/        ← 各コマンド（クラス）
├── worker.php       ← ジョブワーカー
logs/                ← ログファイル（event.logなど）
stubs/               ← 各 make: コマンドのテンプレート
plugins/             ← 外部Intent/Executor群のプラグイン
vendor/              ← Composer依存ライブラリ
```

---

## 🛠 CLIコマンド一覧

```bash
# Intent/Executor/Response/Constraint/Model/Service 生成
php cli/diba.php make:intent Sample
php cli/diba.php make:executor Sample
php cli/diba.php make:response Html
php cli/diba.php make:constraint RequireName
php cli/diba.php make:model Product
php cli/diba.php make:service UserService

# RESTful API一式を自動生成
php cli/diba.php make:rest Product

# Intent一覧・ルーティング確認
php cli/diba.php list:intents
php cli/diba.php list:routes

# Intent構文バリデーション
php cli/diba.php validate:intents

# Intent構造のMermaid図出力
php cli/diba.php graph:intents --tag core
php cli/diba.php graph:intents --include-tags api,event --save docs/graph.mmd

# IntentのExecutor/Response実行（シミュレート）
php cli/diba.php simulate Sample '{"key":"value"}'
php cli/diba.php simulate Sample '{"key":"value"}' --dump

# Intentのテスト実行
php cli/diba.php run:test tests/welcome.test.yaml
php cli/diba.php run:tests tests/

# ジョブ/イベント関連
php cli/diba.php run:queue
php cli/diba.php make:job NotifyAdmin
php cli/diba.php list:jobs

# プラグインの作成
php cli/diba.php make:plugin SamplePlugin

# Intent自動ドキュメント生成
php cli/diba.php generate:docs
php cli/diba.php generate:docs --plugin-only
php cli/diba.php generate:docs --format=json
```

---

## 📜 ライセンス
MIT License

---

## 🤝 Contribute
Issue / PR 、問答無用で歓迎します！
サンプルやドキュメントの整備も随時進めていきます。
「誰でも使える・拡張できる」Intent駆動型フレームワークを一緒に育てましょう！

---

## 👤 Author
Kawaguchi Chihiro [@kn0ws](https://github.com/kn0ws)
