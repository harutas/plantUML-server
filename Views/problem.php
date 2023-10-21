<!DOCTYPE html>
<html lang="en">

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
    <div id="editor-container" class="col-4 px-0" style="height:600px;border:1px solid grey"></div>
    <div id="preview-container" class="col-4 overflow-auto" style="height:600px;border:1px solid grey"></div>
    <div id="practice-container" class="col-4 overflow-auto" style="height:600px;border:1px solid grey">
      <div>
        <button id="answer-uml-btn" class="btn btn-secondary btn-sm my-1">Answer UML</button>
        <button id="answer-code-btn" class="btn btn-secondary btn-sm my-1">Answer Code</button>
      </div>
      <div id="answer-uml" class="w-100 d-block overflow-auto">Answer UML</div>
      <div id="answer-code" class="w-100 d-none overflow-auto">Answer Code</div>
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

    const answerUml = document.getElementById('answer-uml')
    const answerCode = document.getElementById('answer-code')

    const answerUmlBtn = document.getElementById('answer-uml-btn')
    const answerCodeBtn = document.getElementById('answer-code-btn')

    let isAnswerCode = false

    const defaultCode =
      "@startuml\nClass01 <|-- Class02\nClass03 *-- Class04\nClass05 o-- Class06\nClass07 .. Class08\nClass09 -- Class10\n@enduml"

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
        renderPreview()
      }, 500)
    })

    const renderPreview = () => {
      const id = new Date().getTime().toString();
      const editorValue = editor.getValue()
      const data = {
        "id": id,
        "code": editorValue
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
            previewContainer.innerHTML = ""
            for (let i = 0; i < images.length; i++) {
              const imageElement = new Image()
              imageElement.src = "data:image/png;base64," + images[i]
              previewContainer.appendChild(imageElement)
            }
          } else if (data.status === "failed") {
            console.log(data.message)
            previewContainer.innerHTML = "<p>No UML</p>"
          }
        })
    }

    const showAnswerUML = () => {
      isAnswerCode = !isAnswerCode
      answerUml.classList.remove("d-none")
      answerUml.classList.add("d-block")

      answerCode.classList.remove("d-block")
      answerCode.classList.add("d-none")
    }

    const showAnswerCode = () => {
      isAnswerCode = !isAnswerCode
      answerUml.classList.remove("d-block")
      answerUml.classList.add("d-none")

      answerCode.classList.remove("d-none")
      answerCode.classList.add("d-block")
    }

    answerUmlBtn.addEventListener("click", showAnswerUML)
    answerCodeBtn.addEventListener("click", showAnswerCode)

    renderPreview()
    showAnswerUML()
  </script>
</body>

</html>