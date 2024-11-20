const dropArea = document.getElementById("drop-area");
const fileInput = document.getElementById("file");
const fileSelector = document.getElementById("file-selector");
const fileList = document.getElementById("file-list");
const filesContainer = document.getElementById("files-container");

fileInput.onchange = () => {
    [...fileInput.files].forEach((file) => {
        if (typeValidation(file.type)) {
            uploadFile(file);
        }
    });
};

dropArea.ondragover = (e) => {
    e.preventDefault();
    [...e.dataTransfer.items].forEach((item) => {
        if (typeValidation(item.type)) {
            dropArea.classList.add("drag-over-effect");
        }
    });
};

dropArea.ondragleave = () => {
    dropArea.classList.remove("drag-over-effect");
};

dropArea.ondrop = (e) => {
    e.preventDefault();
    dropArea.classList.remove("drag-over-effect");

    if (e.dataTransfer.items) {
        [...e.dataTransfer.items].forEach((item) => {
            if (item.kind === "file") {
                const file = item.getAsFile();
                if (typeValidation(file.type)) {
                    uploadFile(file);
                }
            }
        });
    } else {
        [...e.dataTransfer.files].forEach((file) => {
            if (typeValidation(file.type)) {
                uploadFile(file);
            }
        });
    }
};

window.onload = function () {
    // JavaScript to trigger file deletion when the page loads
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "sender.php?deleteFiles=true", true);
    xhr.send();
};

function typeValidation(type) {
    var splitType = type.split("/")[0];
    if (type == "text/plain") {
        return true;
    } else {
        alert("Invalid file type. Please upload only TXT files.");
    }
}

function formatFileSize(bytes) {
    const kilobytes = bytes / 1024;
    if (kilobytes < 1000) {
        return kilobytes.toFixed(1) + " KB";
    } else {
        const megabytes = kilobytes / 1024;
        return megabytes.toFixed(2) + " MB";
    }
}

function uploadFile(file) {
    fileList.style.display = "flex";
    var div = document.createElement("div");
    div.classList.add("in-prog");
    div.classList.add("file");
    div.innerHTML = `
        <div class="file-name">
            <img src="/images/file.png" alt="File icon" width="25px" height="25px">
            <p>${truncateFileName(file.name)}</p>
        </div>
        <div class="file-size">
            <p>${formatFileSize(file.size)}</p>
            <form method="post" action="" style="display:inline;">
                <input type="hidden" name="file" value="${truncateFileName(file.name)}">
                <input type="hidden" name="conversionType" value="txt2pdf">
                <input type="hidden" name="download" value="true">
                <button id="download" type="submit" name="download" onclick="return confirm('Are you sure you want to download this file?')">
                    <span class="material-symbols-outlined">
                        download
                    </span>
                </button>
            </form>
        </div>
    `;
    filesContainer.appendChild(div);
    var http = new XMLHttpRequest();
    var data = new FormData();
    data.append("file", file);

    var form = div.querySelector("form");
    form.addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent the default form submission
        // Trigger the download by navigating to the download script
        window.location.href =
            "sender.php?download=true&file=" +
            encodeURIComponent(truncateFileName(file.name)) +
            "&conversionType=txt2pdf";
    });
    http.onload = () => {
        div.classList.add("complete");
        div.classList.remove("in-prog");
    };
    http.open("POST", "sender.php", true);
    http.send(data);
    http.onabort = () => div.remove();
}

function truncateFileName(fileName) {
    // Check if the file name ends with ".txt"
    if (fileName.endsWith(".txt")) {
        // Remove ".txt" and append ".pdf"
        fileName = fileName.substring(0, fileName.length - 4) + ".pdf";
    }
    return fileName;
}
