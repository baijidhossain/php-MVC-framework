(function () {
    let layoutTriggerBtn = __("[data-target-layout]");

    // layout change
    layoutTriggerBtn.forEach(function (btn) {
        btn.addEventListener("click", function () {
            let targetLayout = btn.getAttribute("data-target-layout");
            _(".right-column").setAttribute("data-layout", targetLayout);
            Array.from(layoutTriggerBtn).forEach((el) => el.classList.remove("selected-bg"));
            this.classList.add("selected-bg");
        });
    });

    // context menu
    const contextMenu = _("#context-menu");

    document.addEventListener("contextmenu", (e) => {
        if (e.target.closest(".files-table_body")) {
            e.preventDefault();

            let item = e.target.closest(".file-item");

            if (item.dataset.selected != "true") {
                toggleSelectedItem(item);
            }

            let rows = selectedItem();

            let contextType = e.target
                .closest(".file-item[data-item-type]")
                .dataset.itemType;

            let x = e.pageX;
            let y = e.pageY;

            let totalHeight = window.innerHeight;
            let totalWidth = window.innerWidth;

            x = x + 190 > totalWidth ? totalWidth - (190 + 10) : x;

            contextMenu.style.left = `${x}px`;

            if (y + 200 > totalHeight) {
                let overHeight = (y + 205) - totalHeight;
                contextMenu.style.top = `${y - overHeight}px`;
            } else {
                contextMenu.style.top = `${y}px`;
            }

            contextMenu.classList.remove("context-menu--active", "context-type-file", "context-type-folder");
            contextMenu.classList.add("context-menu--active");

            // add context type
            if (rows.length > 1) {
                contextMenu.classList.add("context-type-multiple");
            } else {

                contextMenu.classList.add(`context-type-${contextType}`);
            }

        } else {
            contextMenu.classList.remove("context-menu--active", "context-type-file", "context-type-folder", "context-type-multiple");
        }
    });

    document.addEventListener("click", (e) => {
        contextMenu.classList.remove("context-menu--active", "context-type-file", "context-type-folder");
    });

    // close modal
    __("[data-close=modal]").forEach(function (element) {
        element.addEventListener("click", () => closeModal());
    });

    // file upload on select
    _("#fileinput").addEventListener("change", (e) => {
        let files = e.target.files;

        if (files.length > 0) {
            Array.from(files).forEach((file) => uploadFile(file));
        }
    });

    // double-click on file
    addOnListener(document, "dblclick", (e) => {
        let item = e.target.closest(".file-item");
        let fileName = item.dataset.filename;

        fileName = CURRENT_DIR + "/" + fileName;

        if (window.opener?.hasOwnProperty('CKEDITOR')) {
            let funcNum = getUrlParam('CKEditorFuncNum');
            //todo file url change krte hobe
            let fileUrl = window.location.origin + '/public/images' + fileName;
            window.opener.CKEDITOR.tools.callFunction(funcNum, fileUrl);
            window.close();
        } else {
            toggleSelectedItem(item);
            download();
        }

    }, ".file-item[data-item-type=file]");

    // double-click on folder
    addOnListener(document, "dblclick", (e) => {
        let folderName = e.target.closest(".file-item").dataset.filename;

        changeDirectory(folderName);

    }, ".file-item[data-item-type=folder]");

    // breadcrumb click
    addOnListener(document, "click", (e) => {
        let folderPath = e.target.dataset.filename;

        changeBreadCrumbDirectory(folderPath);

    }, "#breadcrumb [data-filename]");

    // select multiple items
    addOnListener(
        document,
        "click",
        function (e) {
            let item = e.target.closest(".files-table_body .file-item");

            if (e.ctrlKey) {
                toggleSelectedItem(item);
            }


            if (e.button === 0) {
                if (!e.ctrlKey && !e.shiftKey) {
                    var reToggle = false;
                    if (item.dataset.selected === 'true' && selectedItem().length == 1) reToggle = true;

                    clearSelectedItem();

                    if (!reToggle) {
                        toggleSelectedItem(item);
                    }
                }

                if (e.shiftKey) {
                    selectRowsBetweenIndexes([lastSelectedRow.dataset.index, item.dataset.index]);
                }
            }


        },
        ".files-table_body .file-item",
    );

    document.addEventListener("click", (e) => {
        let item1 = e.target.closest(".files-table_body .file-item");
        let item2 = e.target.closest("#context-menu");

        if (!item1 && !item2) {
            clearSelectedItem();
        }
    });
})();

const modal = document.getElementById("modal");

function _(selector) {
    return document.querySelector(selector);
}

function __(selector) {
    return document.querySelectorAll(selector);
}

// get all paramters from url
function getUrlParams() {
    var params = {};
    window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (str, key, value) {
        params[key] = value;
    });
    return params;
}


function addOnListener(el, eventName, eventHandler, selector) {
    if (selector) {
        const wrappedHandler = (e) => {
            if (e.target && e.target.closest(selector)) {
                eventHandler(e);
            }
        };
        el.addEventListener(eventName, wrappedHandler);
        return wrappedHandler;
    } else {
        el.addEventListener(eventName, eventHandler);
        return eventHandler;
    }
}

async function request(url = '', method = "GET", params = {}, data = {}, onSuccess = null) {

    if (Object.keys(params).length) {
        let urlParams = getUrlParams();
        let searchParams = new URLSearchParams({ ...urlParams, ...params });
        url += "?" + searchParams.toString();
    }

    let formData = new FormData();

    if (Object.keys(data).length) {
        Object.keys(data).forEach((key) => {
            formData.append(key, data[key]);
        });
    }

    let req = await fetch(url, {
        method: method,
        body: formData,
    });

    if (req.status === 200) {
        if (typeof onSuccess == 'function') {
            if (onSuccess.length) {
                onSuccess(req);
            } else {
                onSuccess();
            }
        }
    } else {
        alert("Something went wrong, please refresh the page and try again.");
    }

}

function selectedItem() {
    return Array.from(__(".file-item[data-selected=true]"));
}

let lastSelectedRow;
function toggleSelectedItem(target) {
    if (target.dataset.selected == "true") {
        target.dataset.selected = "false";
    } else {
        target.dataset.selected = "true";
    }

    lastSelectedRow = target;
}

function selectRowsBetweenIndexes(indexes) {

    let min = Math.min(...indexes);
    let max = Math.max(...indexes);

    let rows = Array.from(__(".files-table_body .file-item"));

    rows.forEach((row) => {
            if (row.dataset.index >= min && row.dataset.index <= max) {
                row.dataset.selected = "true";
            }
        }

    );
}

function clearSelectedItem() {
    __(".file-item[data-selected=true]").forEach((item) => (item.dataset.selected = "false"));
}

function rename() {
    let selected = selectedItem()[0] || null;
    if (selected) {
        let fileName = selected.dataset.filename;
        modal.querySelector(".modal-title").innerHTML = "Rename";
        modal.querySelector(".modal-body").innerHTML = `<div class="mb-4"><label class="form-label">New Name</label><input name="rename" required value="${fileName}" autofocus></div>`;

        openModal().then(async () => {
            showLoader();
            closeModal();

            let newName = modal.querySelector('input[name="rename"]').value;

            await request(
                "",
                "POST",
                {
                    m: 'ajax',
                    task: 'rename'
                },
                {
                    currentPath: CURRENT_DIR,
                    oldName: fileName,
                    newName: newName,
                },
                async function (req) {
                    let resp = await req.text();

                    if (resp !== 'OK') {
                        showError(resp);
                    }

                    refreshUI();
                }
            );

        });
    }
}

function createFolder() {
    modal.querySelector(".modal-title").innerHTML = "Create Folder";
    modal.querySelector(".modal-body").innerHTML = `<div class="mb-4"><label class="form-label">Folder Name</label><input name="name" required><input type="hidden" name="path" value="${CURRENT_DIR == "" ? "" : "/" + CURRENT_DIR}"/></div>`;

    openModal().then(async () => {
        showLoader();

        closeModal();

        let folderName = modal.querySelector('input[name="name"]').value;

        await request(
            "",
            "POST",
            {
                m: 'ajax',
                task: 'createFolder'
            },
            {
                path: CURRENT_DIR,
                folderName: folderName,
            },
            async function (req) {
                let resp = await req.text();

                if (resp !== 'OK') {
                    showError(resp);
                }

                refreshUI();
            }
        );
    });
}

function download() {
    let selected = selectedItem()[0] ?? null;

    if (selected) {
        let fileName = CURRENT_DIR + "/" + selected.dataset.filename;
        let link = document.getElementById("download-link");
        link.setAttribute("href", `?m=ajax&download=${fileName}`);
        link.click();
    }
}

function copy() {
    let rows = selectedItem();
    if (rows.length) {
        // remove double slash from CURRENT_DIR
        CURRENT_DIR = CURRENT_DIR.replace(/\/\//g, "/");

        modal.querySelector(".modal-title").innerHTML = "Copy";
        modal.querySelector(".modal-body").innerHTML = `<div class="mb-4"><label class="form-label">Enter the path</label><input type="text" name="path" required value="${CURRENT_DIR}"></div>`;

        openModal().then(async () => {
            showLoader();
            closeModal();

            let items = [];
            rows.forEach((row) => items.push(row.dataset.filename) );

            let copyPath = modal.querySelector('input[name="path"]').value;

            let formData = {
                currentPath: CURRENT_DIR,
                copyPath: copyPath,
            };


            items.forEach((item, index) => { formData[`files[${index}]`] = item; });

            await request(
                "",
                "POST",
                {
                    m: 'ajax',
                    task: 'copy'
                },
                formData,
                async function (req) {
                    let resp = await req.text();

                    if (resp !== 'OK') {
                        showError(resp);
                    }

                    refreshUI();
                }
            );

        });
    }
}

function move() {
    let rows = selectedItem();
    if (rows.length) {
        // remove double slash from CURRENT_DIR
        CURRENT_DIR = CURRENT_DIR.replace(/\/\//g, "/");

        modal.querySelector(".modal-title").innerHTML = "Move";
        modal.querySelector(".modal-body").innerHTML = `<div class="mb-4"><label class="form-label">Enter the path</label><input type="text" name="path" required value="${CURRENT_DIR}"></div>`;

        openModal().then(async () => {
            showLoader();
            closeModal();

            let items = [];
            rows.forEach((row) => items.push(row.dataset.filename));

            let movePath = modal.querySelector('input[name="path"]').value;

            let formData = {
                currentPath: CURRENT_DIR,
                movePath: movePath,
            };

            items.forEach((item, index) => { formData[`files[${index}]`] = item; });

            await request(
                "",
                "POST",
                {
                    m: 'ajax',
                    task: 'move'
                },
                formData,
                async function (req) {
                    let resp = await req.text();

                    if (resp !== 'OK') {
                        showError(resp);
                    }

                    refreshUI();
                }
            );

        });
    }
}

function deleteItem() {
    let rows = selectedItem();
    if (rows.length) {

        modal.querySelector(".modal-title").innerHTML = "Delete";
        modal.querySelector(".modal-body").innerHTML = `<div class="mb-4">Are you sure you want to delete this item?</div>`;

        openModal().then(async function () {

            showLoader();
            closeModal();

            let items = [];
            rows.forEach((row) => items.push(row.dataset.filename));

            let formData = {
                currentPath: CURRENT_DIR
            };

            items.forEach((item, index) => { formData[`files[${index}]`] = item; });

            await request(
                "",
                "POST",
                {
                    m: 'ajax',
                    task: 'delete'
                },
                formData,
                refreshUI
            );


        });
    }
}

async function changeDirectory(folderName) {
    showLoader();

    let newPath = CURRENT_DIR + "/" + folderName;

    await request(
        "",
        "POST",
        {
            m: 'ajax',
            task: 'changeDir'
        },
        {
            chdir: newPath,
        },
        () => {
            CURRENT_DIR = CURRENT_DIR + "/" + folderName;
            refreshUI();
        }
    );

}

async function changeBreadCrumbDirectory(folderPath) {
    showLoader();

    request(
        "",
        "POST",
        {
            m: 'ajax',
            task: 'changeDir'
        },
        {
            chdir: folderPath,
        },
        () => {
            CURRENT_DIR = folderPath;
            refreshUI();
        }
    );
}

/* Upload handling */
function uploadFile(fileItem) {
    let formData = new FormData();
    formData.append("fileItem", fileItem);
    formData.append("uploadPath", CURRENT_DIR);

    let uploadBox = _('.upload-box-header');

    if (uploadBox.classList.contains('d-none')) {
        uploadBox.classList.remove('d-none');
    }

    let prog_elm = document.createElement("div");
    prog_elm.className = "u-progress-bar py-1 px-3";
    prog_elm.innerHTML = `<p class="my-1">${fileItem.name}</p><progress class="progressBar" value="0" max="100" style="width:100%;height:20px;"></progress><h5 class="status">0% Uploaded</h5>`;

    _("#upload-progress-boxes .upload-box-body").appendChild(prog_elm);

    let abortbutton = document.createElement("a");
    abortbutton.className = "btn-close";
    abortbutton.innerHTML = '<i class="fa fa-times"></i>';
    prog_elm.appendChild(abortbutton);

    let request = new XMLHttpRequest();
    request.upload.addEventListener("progress", uploadProgressHandler, false);
    request.upload.pElement = prog_elm;
    request.addEventListener("load", uploadCompleteHandler, false);
    request.addEventListener("error", uploadFailedHandler, false);
    request.addEventListener("abort", uploadCanceledHandler, false);
    request.open("POST", "?m=ajax&task=upload");
    request.send(formData);

    abortbutton.addEventListener("click", () => {
        request.abort();
        _("#upload-progress-boxes .upload-box-body").removeChild(prog_elm);

        if (_("#upload-progress-boxes .upload-box-body").children.length == 0) {
            closeUploadBox();
        }
    });
}

function uploadProgressHandler(event) {
    let percent = (event.loaded / event.total) * 100;
    this.pElement.querySelector(".progressBar").value = Math.round(percent);
}

function uploadCompleteHandler(event) {
    let upresult = this.responseText;
    if (upresult == "OK") {
        this.upload.pElement.querySelector("p").classList.add("text-success");

        refreshUI();
    } else {
        this.upload.pElement.querySelector("p").classList.add("text-danger");
        showError(upresult);
    }
}

function uploadFailedHandler(event) {
    this.upload.pElement.querySelector("p").classList.add("text-danger");
}

function uploadCanceledHandler(event) {
    this.upload.pElement.querySelector("p").classList.add("text-danger");
}

function closeModal() {
    fadeOut(modal);
}

function openModal() {
    fadeIn(modal);

    return new Promise((resolve, reject) => {
        modal
            .querySelector("[data-confirm-btn]")
            .addEventListener("click", () => resolve(1));
        modal
            .querySelector('[data-close="modal"]')
            .addEventListener("click", () => reject(0));
    });
}

function updateUI(htmlContent) {
    const uiContent = _('.right-column');

    uiContent.innerHTML = htmlContent;
}

function refreshUI() {
    showLoader();

    fetch(`?m=ajax&chdir=${CURRENT_DIR}`).then(async function (req) {
            if (req.status === 200) {
                let data = await req.text();
                updateUI(data);
            } else {
                alert("Something went wrong, please refresh the page and try again.");
            }
        }
    );
}

function showLoader() {
    _(".files-table").innerHTML = `<div class="flex items-center" style="gap: 10px;padding: 10px;margin: 15px;border: 1px solid #dadce0;display: inline-flex;border-radius: 3px;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i><span>Fetching directory contents...</span></div>`;
}

function fadeOut(element) {
    element.style.display = "none";
}

function fadeIn(element) {
    element.style.display = "block";
}

function toggleUploadBox() {
    _('.upload-box-body').classList.toggle('d-none');
    let icon = _('.upload-box-angle').querySelector('i');

    if (icon.classList.contains('fa-angle-down')) {
        icon.classList.remove('fa-angle-down');
        icon.classList.add('fa-angle-up');
    } else {
        icon.classList.remove('fa-angle-up');
        icon.classList.add('fa-angle-down');
    }
}

function closeUploadBox() {
    _('.upload-box-header').classList.add('d-none');

    Array.from(__('.u-progress-bar')).forEach((elm) => {
        elm.querySelector('.btn-close').click();
        elm.remove();
    });
}

function showError(message) {
    modal.querySelector(".modal-title").innerHTML = "Error";
    modal.querySelector(".modal-body").innerHTML = `<p>${message}</p>`;

    openModal().then(() => {
        closeModal();
    });
}

// Helper function to get parameters from the query string.
function getUrlParam(paramName) {
    let reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
    let match = window.location.search.match(reParam);

    return (match && match.length > 1) ? match[1] : null;
}
