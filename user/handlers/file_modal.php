<div class="modal fade" tabindex="-1" id="files_modal" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-light">
                <h3 class="text-dark"><?php echo $div; ?>Files</h3>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row w-100">
                    <div class="col-lg-5 mx-auto   p-3">
                        <div class="row">

                        </div>
                        <div class="row" id="files_row"></div>
                    </div>

                    <div class="col-lg-5  mx-auto p-3 ">
                        <form id="uploadForm">
                            <div class="row border p-3 shadow">
                                <div class="col-lg-12 w-100 mx-auto">
                                    <strong class="mb-2 h4">File Uploader</strong>
                                </div>
                                <hr>
                                <div class="col-lg-12 mx-auto">
                                    <div class="form-group">
                                        <label class="mb-2" for="">File (Only PDF files are allowed)</label>
                                        <input type="file" name="file" id="file" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 mx-auto">
                                    <div class="form-group">
                                        <label class="mb-2" for="">Section</label>
                                        <select name="section" id="section" class="form-control">
                                            <option value="">Select Section</option>
                                            <option value="section1">Section 1</option>
                                            <option value="section2">Section 2</option>
                                            <option value="section3">Section 3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12 mx-auto mt-3">
                                    <div class="progress">
                                        <div id="uploadProgress" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mx-auto mt-3 text-end">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary w-100" style="background: var(--bs-info)" id="upload">Upload</button>
                                        <div class="buttons w-100 text-end">
                                            <button class="btn btn-danger w-100 mt-2" data-bs-dismiss="modal">Close Modal</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <style>
                                .folder-card {
                                    position: relative;
                                    border: 1px solid #ccc;



                                }

                                .folder-tab {
                                    position: absolute;
                                    top: -20px;
                                    left: 20px;
                                    background-color: var(--bs-cyan);
                                    color: #fff;
                                    padding: 2px 10px;


                                }
                            </style>
                        </form>
                        <hr>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>