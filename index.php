<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link rel="stylesheet" data-name="vs/editor/editor.main" href="./node_modules/monaco-editor/min/vs/editor/editor.main.css" />
  <title>PlantUML Server</title>
</head>

<body>
  <div id="editor-container" class="w-50 px-0" style="height:600px;border:1px solid grey"></div>
  <script>
    var require = {
      paths: {
        vs: './node_modules/monaco-editor/min/vs'
      }
    };
  </script>
  <script src="./node_modules/monaco-editor/min/vs/loader.js"></script>
  <script src="./node_modules/monaco-editor/min/vs/editor/editor.main.nls.js"></script>
  <script src="./node_modules/monaco-editor/min/vs/editor/editor.main.js"></script>
  <script>
    let mode = "preview" // preview or html
    let highlight = true

    const editorContainer = document.getElementById('editor-container')
    const previewContainer = document.getElementById('preview-container')

    const markdownPreviewBtn = document.getElementById('markdown-preview-btn')
    const htmlPreviewBtn = document.getElementById('html-preview-btn')
    const highlightBtn = document.getElementById('highlight-btn')
    const downloadBtn = document.getElementById('download-btn')

    const defaultCode =
      "# Type sentences\n\n[Recursion](https://recursionist.io)\n\n```\nfunction hello(){\n  return 'hello';\n}\n```"

    const editor = monaco.editor.create(editorContainer, {
      value: defaultCode,
      language: 'markdown',
      minimap: {
        enabled: false,
      },
      lineDecorationsWidth: 5,
      automaticLayout: true
    });
  </script>
</body>

</html>