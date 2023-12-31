# plantUML-server

## 機能要件
- PlantUML の構文を通じてユースケース図、クラス図、アクティビティ図、状態図、マインドマップ、ガント図などを作成します。
- ユーザーはコードエディタで PlantUML を記述します。
- ユーザーが図を書く際に、その UML 図をリアルタイムで確認できるフィードバックオプションを提供します。
- ユーザーが .png、.svg、.txt ファイル形式で図をダウンロードできるようにします。
- ユーザーが現在の図表の構文を理解するためのチートシートを提供します。
- プラクティスプロンプトジェネレータを提供します。この機能は、ユーザーに特定の図表作成の課題（プロンプト）を出題し、その正解例となる図表も自動的に生成するものです。ユーザーは、自らの回答と正解例を比較することで、図表がどのように構築されるかを学べます。

## 技術スタック
- Client
  - HTML
  - CSS
  - JavaScript

- Server
  - PHP

- Database
  - LocalStorage on Server

- FW
  - monaco editor（https://microsoft.github.io/monaco-editor/）


### ロジック
1. クライアントのテキストをサーバへ送信する
2. phpのexec関数を使用してjavaでplantUMLのjarファイルを実行する
  ```java -jar plantuml.jar sequenceDiagram.txt```
3. sequenceDiagram.pngが生成されるのでクライアントへレスポンスを返す。

### 実行環境要件
- Java 8
- GraphViz