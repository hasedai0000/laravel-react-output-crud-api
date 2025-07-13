# Laravel React Docker プロジェクト

## Docker のディレクトリ構成

```text
project-root/
├── docker/           # Docker 関連ファイル
│   ├── nginx/        # Nginx の設定
│   ├── php/          # PHP の設定
│   └── mysql/        # MySQL の設定
├── backend/          # Laravel プロジェクト
├── frontend/         # React プロジェクト
├── docker-compose.yml # Docker Compose 設定
└── .env              # 環境変数
```

## Laravel プロジェクトの作成

### Docker イメージのビルド

```bash
docker compose build
```

### Laravel 10 プロジェクトを作成

```bash
# --rm　オプションをつけることで不要なコンテナを残さないようにしています。
# Laravel 10を明示的に指定してインストール
docker compose run --rm app composer create-project laravel/laravel:^10.0 .
```

### Laravel の環境設定

backend/.env ファイルの データベース接続情報を以下のように編集する。

```.env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel
DB_PASSWORD=secret
```

## React プロジェクトの作成

```bash
# --rm　オプションをつけることで不要なコンテナを残さないようにしています。
docker compose run --rm node sh -c "npx create-react-app . --template typescript"
```

### React の設定

frontend/package.json に以下の行を追加する

```bash
"proxy": "http://web"
```

## Laravel Sanctum による API 認証の設定

### Sanctum のインストール

```bash
docker compose run --rm app composer require laravel/sanctum
```

### マイグレーションの実行

```bash
docker compose run --rm app php artisan migrate
```

### ミドルウェアの追加

app/Http/Kernel.php の api 　ミドルウェアグループに下記の内容を追加 or コメントアウト

```bash
\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class
```

## 開発環境の軌道

すべての設定が完了したら以下のコマンドを実行

```bash
docker compose up -d
```

これで以下の URL でアプリケーションにアクセスできます：

Laravel: http://localhost
React: http://localhost:3000

## 開発環境の使用方法

### Laravel コマンドの実行

#### Artisan コマンドの実行

```bash
docker compose exec app php artisan <command>
```

#### 例: マイグレーション

```bash
docker compose exec app php artisan migrate
```

#### 例: シーダーの実行

```bash
docker compose exec app php artisan db:seed
```

### npm コマンドの実行

#### npm コマンドの実行

```bash
docker compose exec node npm <command>
```

#### 例: パッケージのインストール

```bash
docker compose exec node npm install <package-name>
```

#### 例: ビルド

```bash
docker compose exec node npm run build
```

### データベースへのアクセス

```bash
docker compose exec db mysql -u laravel -p

```

### キャッシュの最適化

```bash
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

## [番外編] API の疎通確認

### バックエンド API の作成

#### コントローラの作成

```bash
docker compose run --rm app php artisan make:controller API/TaskController --api
```

backend/app/Http/Controllers/API/TaskController.php を編集

```php
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * タスク一覧を取得
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = [
            ['id' => 1, 'title' => 'タスク1', 'completed' => false],
            ['id' => 2, 'title' => 'タスク2', 'completed' => true],
            ['id' => 3, 'title' => 'タスク3', 'completed' => false],
        ];

        return response()->json($tasks);
    }
}
```

#### API ルートの設定

backend/routes/api.php を編集

```bash
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TaskController;

// ユーザー情報取得のルート
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// タスク一覧取得のルート
Route::get('/tasks', [TaskController::class, 'index']);
```

### フロントエンドでの API の利用

#### axios のインストール

```bash
docker compose run --rm node npm install axios
```

### API からデータを取得するコンポーネントの作成

frontend/src/App.tsx を編集

```typescript
import React, { useEffect, useState } from "react";
import axios from "axios";
import "./App.css";

interface Task {
  id: number;
  title: string;
  completed: boolean;
}

function App() {
  const [tasks, setTasks] = useState<Task[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    // APIからタスク一覧を取得
    const fetchTasks = async () => {
      try {
        const response = await axios.get("/api/tasks");
        setTasks(response.data);
        setLoading(false);
      } catch (err) {
        setError("タスクの取得に失敗しました");
        setLoading(false);
      }
    };

    fetchTasks();
  }, []);

  if (loading) return <div>読み込み中...</div>;
  if (error) return <div>{error}</div>;

  return (
    <div className="App">
      <header className="App-header">
        <h1>タスク一覧</h1>
      </header>
      <main>
        <ul>
          {tasks.map((task) => (
            <li
              key={task.id}
              style={{
                textDecoration: task.completed ? "line-through" : "none",
              }}
            >
              {task.title}
            </li>
          ))}
        </ul>
      </main>
    </div>
  );
}

export default App;
```
