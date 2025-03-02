  document.getElementById('uploadButton').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });

        document.getElementById('fileInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                console.log('Selected file:', file.name);
                // You can add your own logic to handle the selected file here
            }
        });