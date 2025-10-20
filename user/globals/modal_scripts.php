<!-- <script>
    $(document).on('click', '#view_files_btn', function() {
        
        $('#upload').attr('data-id', case_id);
        fetch(`./handlers/get_files.php?case_id=${case_id}&division=${division}`)
            .then(res => res.json())
            .then(data => {
                console.log(data);
                var text = ""
                if (data.length == 0) {
                    text += `<div class="col-lg-12 mx-auto text-center mb-2  text-white d-flex align-items-center justify-content-center">
                                            <h3 class="w-100 bg-danger text-white p-2">NO FILES</h3>
                        </div>`
                }
                data.forEach(
                    item => {

                        text += `<div class="col-lg-5 mx-1  mb-2  bg-white d-flex align-items-center justify-content-center flex-column">
                                            <div class="container mt-2 mb-2">
                            <div class="card folder-card">
                                <div class="folder-tab"><i class="bi bi-file-earmark-text"></i></div>
                                <div class="card-body">
                                    <h5 class="card-title text-dark text-sm">${item.url}</h5>
                                    <div class="buttons w-100 text-end mt-3">
                                                <a class=" mx-1 h3" style="color: var(--bs-primary)" title="Download" href="handlers/${item.url}" target="_blank"><i class="bi bi-download"></i></a>
                                                <a class=" h3 text-danger" data-id="${item.id}" title="Delete" data-url="${item.url}" id="delete_file_btn"><i class="bi bi-trash"></i></a>
                                            </div>
                                    
                                </div>
                            </div>
                        </div>
                                            
                                            
                        </div>`
                    }
                )
                $('#files_row').html(text);
            })
    })
</script> -->
<script>
    function uploadFile(file, case_id) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('department', case_id);
        formData.append('area', <?php echo "'" . $div . "'"; ?>);
        var division = <?php echo "'" . $div . "'"; ?>;

        fetch('./handlers/upload_file.php', {
                method: 'POST',
                body: formData
            })
            .then(response =>
                response.json()
            )
            .then(data => {

                if (data.success) {

                    $('#upload').html(`Upload`);
                    console.log('File uploaded successfully:', data.success);
                    alert("File successfully uploaded");

                } else {
                    console.error('File upload failed:', data.error);
                    $('#upload').html(`Upload`);
                    alert("Upload Failed")
                }
            })
            .catch(error => console.error('Error uploading file:', error));
    }

    document.getElementById('upload').addEventListener('click', function(event) {
        event.preventDefault();
        alert("Hello")
        console.log($("#upload").html())
        $('#upload').html(`<div class="spinner-border text-white" role="status">
  <span class="sr-only">Loading...</span>
</div>`);
        const fileInput = document.getElementById('file');
        const file = fileInput.files[0];

        if (file) {
            const fileSizeLimit = 20 * 1024 * 1024;
            if (file.type !== 'application/pdf') {
                $('#upload').html(`Upload`);
                alert('Please select a PDF file.');

                return;
            }
            if (file.size > fileSizeLimit) {
                $('#upload').html(`Upload`);
                alert('File size exceeds 20MB limit.');

                return;
            }

            uploadFile(file, id);
        } else {
            $('#upload').html(`Upload`);
            alert('Please select a file.');

        }
    });
</script>
<script>
    $(document).on('click', '#delete_file_btn', function() {
        var id = $(this).attr("data-id")
        var url = $(this).attr("data-url")
        fetch(`./handlers/delete_file.php?id=${id}&url=${url}`)
            .then(res => res.json())
            .then(data => {
                if (data.success == 1) {
                    alert("File deleted successfully");
                    window.location.reload()
                } else {
                    alert("Failed to delete file");

                }
            })
    })
</script>