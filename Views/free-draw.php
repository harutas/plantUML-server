<?php
require_once("../Models/UMLConverter.php");
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link rel="stylesheet" data-name="vs/editor/editor.main" href="../node_modules/monaco-editor/min/vs/editor/editor.main.css" />
  <title>PlantUML Server</title>
</head>

<body>
  <div class="d-flex justify-content-between">
    <div class="mt-2 px-5">
      <h1 class="fs-2 fw-bolder">PlantUML Server</h1>
    </div>
    <div class="d-flex align-items-center px-5">
      <div class="d-flex align-items-center gap-1">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="format" id="png" value="png" checked>
          <label class="form-check-label" for="png">
            PNG
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="format" id="svg" value="svg">
          <label class=" form-check-label" for="svg">
            SVG
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="format" id="txt" value="txt">
          <label class="form-check-label" for="txt">
            TXT
          </label>
        </div>
        <button id="download-btn" type="submit" class="btn btn-outline-secondary">Download</button>
      </div>
    </div>
  </div>

  <div class="row px-5">
    <div id="editor-container" class="col-6 px-0" style="height:600px;border:1px solid grey"></div>
    <div id="preview-container" class="col-6 overflow-auto" style="height:600px;border:1px solid grey"></div>
  </div>
  <div class="px-5 my-3">
    <button type="button" class="btn btn-secondary" onclick="location.href='problems.php'">戻る</button>
  </div>
  </div>

  <script>
    var require = {
      paths: {
        vs: '../node_modules/monaco-editor/min/vs'
      }
    };
  </script>
  <script src="../node_modules/monaco-editor/min/vs/loader.js"></script>
  <script src="../node_modules/monaco-editor/min/vs/editor/editor.main.nls.js"></script>
  <script src="../node_modules/monaco-editor/min/vs/editor/editor.main.js"></script>
  <script>
    const editorContainer = document.getElementById('editor-container')
    const previewContainer = document.getElementById('preview-container')

    const downloadBtn = document.getElementById('download-btn')

    const defaultCode =
      "Type code..."

    const editor = monaco.editor.create(editorContainer, {
      value: defaultCode,
      language: 'markdown',
      minimap: {
        enabled: false,
      },
      lineDecorationsWidth: 5,
      automaticLayout: true
    });

    let editTimeout
    editor.onDidChangeModelContent(() => {
      clearTimeout(editTimeout)

      editTimeout = setTimeout(() => {
        renderUML(editor.getValue(), previewContainer)
      }, 500)
    })

    const renderUML = (code, element) => {
      const id = new Date().getTime().toString();
      const data = {
        "id": id,
        "code": code
      }

      fetch("../api/generate.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(data)
        }).then((res) => {
          return res.json()
        })
        .then((data) => {
          if (data.status === "ok") {
            const images = data.images
            element.innerHTML = ""
            for (let i = 0; i < images.length; i++) {
              const imageElement = new Image()
              imageElement.src = "data:image/png;base64," + images[i]
              element.appendChild(imageElement)
            }
          } else if (data.status === "failed") {
            console.log(data.message)
            element.innerHTML = "<p>No UML</p>"
          }
        })
    }

    const downloadFile = (code) => {
      const id = new Date().getTime().toString();
      // ラジオボタン要素を取得
      const radioButtons = document.getElementsByName("format");

      // 選択されたラジオボタンの値を取得
      let selectedValue;
      for (const radioButton of radioButtons) {
        if (radioButton.checked) {
          selectedValue = radioButton.value;
          break; // 最初に選択されたラジオボタンが見つかったらループを終了
        }
      }

      const data = {
        "id": id,
        "code": code,
        "extension": selectedValue
      }

      fetch("../api/download.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(data)
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('HTTPステータスコード: ' + response.status);
          }
          return response.blob()
        })
        .then(blob => {
          const url = window.URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = url;
          a.download = `ConvertUML.${selectedValue}`;
          document.body.appendChild(a);
          a.click();
          window.URL.revokeObjectURL(url);
        })
        .catch(error => console.error('ダウンロードエラー:', error));
    }

    downloadBtn.addEventListener("click", () => {
      downloadFile(editor.getValue())
    })

    renderUML(editor.getValue(), previewContainer)
  </script>
</body>

</html>