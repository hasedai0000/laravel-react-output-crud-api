# 本プロジェクトのディレクトリ構成およびアーキテクチャ

app/
├── Application/          # アプリケーション層
    └── Services/         # ユースケース実装
├── Domain/               # ドメイン層（ビジネスロジックの中心）
│   ├── Post/             # 「投稿」というドメイン
│   │   ├── Entities/     # エンティティ（ビジネスの主要オブジェクト）
│   │   ├── ValueObjects/ # 値オブジェクト（シンプルな値の表現）
│   │   ├── Repositories/ # データアクセス抽象化
│   │   └── Services/     # ドメイン固有のロジック
│   └── User/             # 「ユーザー」というドメイン
│       └── ...
├── Http/                 # プレゼンテーション層
│   ├── Controllers/      # コントローラー（シンプルに）
│   └── Requests/         # 入力検証
├── Infrastructure/       # インフラストラクチャ層
│   └── Repositories/     # データアクセスの実装
├── Models/               # Eloquentモデル（シンプルなDBマッピング）


