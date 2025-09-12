<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Multi Barcode Scanner</title>
  <script src="https://unpkg.com/@zxing/library@latest"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      padding: 20px;
    }
    video {
      width: 90%;
      max-width: 600px;
      border: 2px solid gray;
    }
    #scanned-list {
      margin-top: 20px;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      text-align: left;
      background: #f9f9f9;
      padding: 10px;
      border: 1px solid #ccc;
    }
    #scanned-list li {
      margin: 5px 0;
      font-size: 18px;
    }
    button {
      padding: 10px 20px;
      margin: 10px;
      font-size: 16px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <h2>Multiple Barcode Scanner</h2>
  <video id="video" muted></video>

  <div>
    <button id="startButton">Start Scanner</button>
    <button id="stopButton">Stop Scanner</button>
  </div>

  <h3>Scanned Barcodes:</h3>
  <ul id="scanned-list"></ul>

  <script>
    const codeReader = new ZXing.BrowserMultiFormatReader();
    let scannedSet = new Set(); // to prevent duplicates
    let scanning = false;

    const video = document.getElementById('video');
    const list = document.getElementById('scanned-list');

    document.getElementById('startButton').addEventListener('click', () => {
      scannedSet.clear();
      list.innerHTML = '';
      scanning = true;

      codeReader.getVideoInputDevices().then(devices => {
        const deviceId = devices[0].deviceId;

        codeReader.decodeFromVideoDevice(deviceId, video, (result, err) => {
          if (result && scanning) {
            const code = result.text;
            if (!scannedSet.has(code)) {
              scannedSet.add(code);
              const li = document.createElement('li');
              li.textContent = code;
              list.appendChild(li);
              console.log('Scanned:', code);
            }
          }

          if (err && !(err instanceof ZXing.NotFoundException)) {
            console.error(err);
          }
        });
      }).catch(err => {
        console.error('Camera error:', err);
      });
    });

    document.getElementById('stopButton').addEventListener('click', () => {
      scanning = false;
      codeReader.reset();
      console.log('Scanner stopped.');
    });
  </script>
</body>
</html>
