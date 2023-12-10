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

function typeValidation(type) {
    var splitType = type.split("/")[0];
    if (type == "application/pdf") {
        return true;
    } else {
        alert("Invalid file type. Please upload only PDF files.");
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

function uploadFiles(file) {
    // Assuming you have a server endpoint for file upload
    var uploadEndpoint = "upload.php";

    // Create a FormData object and append the file
    var formData = new FormData();
    formData.append("file", file);

    // Make a fetch request to upload the file
    fetch(uploadEndpoint, {
        method: "POST",
        body: formData,
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("File upload failed");
            }
            // Handle successful upload
            console.log("File uploaded successfully");
        })
        .catch((error) => {
            // Handle upload error
            console.error("Error uploading file:", error.message);
        });
}

function uploadFile(file) {
    fileList.style.display = "flex";
    var div = document.createElement("div");
    div.classList.add("in-prog");
    div.classList.add("file");
    div.innerHTML = `
    <div class="file-name">
      <img src="/images/file.png" alt="File icon" width="25px" height="25px">
      <p>${file.name}</p>
    </div>
    <div class="file-size">
      <p>${formatFileSize(file.size)}</p>
      <form method="post" action="" style="display:inline;">
        <input type="hidden" name="file" value="${truncateFileName(file.name)}">
        <input type="hidden" name="conversionType" value="pdf2txt">
        <input type="hidden" name="download" value="true">
        <button id="download" type="submit" name="download">
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

    // var form = div.querySelector("form");
    // form.addEventListener("submit", function (e) {
    //     e.preventDefault(); // Prevent the default form submission
    //     // Handle form submission here
    //     // For example, you can trigger the download via Ajax or other methods
    //     // var formData = new FormData(form);
    //     // formData.append("download", "true"); // You might need to adjust this based on your server-side code
    //     // fetch("senders.php", {
    //     //     method: "POST",
    //     //     body: formData,
    //     // })
    //     //     .then((response) => {
    //     //         if (!response.ok) {
    //     //             throw new Error("Download request failed");
    //     //         }
    //     //         // Handle successful download
    //     //         console.log("File downloaded successfully");
    //     //     })
    //     //     .catch((error) => {
    //     //         // Handle download error
    //     //         console.error("Error downloading file:", error.message);
    //     //     });
    // });
    http.onload = () => {
        div.classList.add("complete");
        div.classList.remove("in-prog");
    };
    // http.upload.onprogress = (e) => {
    //   var percent_complete = (e.loaded / e.total) * 100;
    //   div.querySelectorAll("span")[0].innerHTML =
    //     Math.round(percent_complete) + "%";
    //   div.querySelectorAll("span")[1].style.width = percent_complete + "%";
    // };
    http.open("POST", "sender.php", true);
    http.send(data);
    // div.querySelector(".cross").onclick = () => http.abort();
    http.onabort = () => div.remove();
}

function truncateFileName(fileName) {
    // Check if the file name ends with ".pdf"
    if (fileName.toLowerCase().endsWith(".pdf")) {
        // Remove ".pdf" and append ".txt"
        fileName = fileName.substring(0, fileName.length - 4) + ".txt";
    }
    return fileName;
}

// function handleFiles(files) {
//   console.log(files);
//   if (files.length > 0) {
//     const allowedTypes = ["application/pdf"];
//     const isValidFileType = Array.from(files).every((file) =>
//       allowedTypes.includes(file.type)
//     );

//     if (isValidFileType) {
//       const filesArray = Array.from(files);
//       fileList.style.display = "flex";

//       filesArray.forEach((file) => {
//         console.log("passed");
//         const fileContainer = document.createElement("div");
//         fileContainer.classList.add("file");
//         const fileNameContainer = document.createElement("div");
//         fileNameContainer.classList.add("file-name");
//         const fileIcon = document.createElement("img");
//         fileIcon.src = "images/file.png";
//         fileIcon.alt = "File icon";
//         fileIcon.width = 25;
//         fileNameContainer.appendChild(fileIcon);
//         const fileName = document.createElement("p");
//         fileName.textContent = file.name;
//         fileNameContainer.appendChild(fileName);
//         fileContainer.appendChild(fileNameContainer);
//         const fileSizeContainer = document.createElement("div");
//         fileSizeContainer.classList.add("file-size");
//         const fileSize = document.createElement("p");
//         fileSize.textContent = formatFileSize(file.size);
//         const deleteIcon = document.createElement("span");
//         deleteIcon.classList.add("material-symbols-outlined");
//         deleteIcon.textContent = "cancel";
//         fileSizeContainer.appendChild(fileSize);
//         fileSizeContainer.appendChild(deleteIcon);
//         fileContainer.appendChild(fileSizeContainer);
//         filesContainer.appendChild(fileContainer);
//       });
//     } else {
//       alert("Invalid file type. Please upload only PDF files.");
//     }
//   }
// }