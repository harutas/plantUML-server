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
  <div class="mt-2 px-5">
    <h1 class="fs-2 fw-bolder">PlantUML Server</h1>
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

    renderUML(editor.getValue(), previewContainer)
    renderUML(`<?php echo $problem->uml ?>`, answerUml)
  </script>
</body>

</html>