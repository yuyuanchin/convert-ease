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
    xhr.open('GET', 'sender.php?deleteFiles=true', true);
    xhr.send();
};

// document.getElementById("convertButton").addEventListener("click", function() {
//     // Your PHP file that handles the conversion logic
//     var phpScript = "sender.php";
//
//     // Data to be sent to the server, including the hidden input
//     var data = new FormData();
//     data.append("conversionType", "pdf2txt");
//
//     // Make an asynchronous request to the server using the Fetch API
//     fetch(phpScript, {
//         method: "POST",
//         body: data
//     })
//         .then(response => response.text())
//         .then(result => {
//             // Handle the result if needed
//             console.log(result);
//         })
//         .catch(error => {
//             // Handle errors
//             console.error("Error:", error);
//         });
// });

document.getElementById("convertButton").addEventListener("click", function (event) {
    event.preventDefault(); // Prevent the default click behavior

    // Your additional logic here, if needed

    // Optionally, you can use AJAX to handle the click event without navigating away from the page
    // For example:
    var formData = new FormData(document.getElementById("conversionForm"));
    fetch("sender.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.text())
        .then(data => console.log(data))
        .catch(error => console.error(error));
});

// document.getElementById('convertButton').addEventListener('click', function () {
//     // Example: Trigger pdf2txt conversion
//     triggerConversion('pdf2txt');
// });
//
// function triggerConversion(conversionType) {
//     var xhr = new XMLHttpRequest();
//     var formData = new FormData(document.getElementById('conversionForm'));
//
//     // Append the conversion type to the form data
//     formData.append('conversionType', conversionType);
//
//     xhr.open('POST', 'sender.php', true);
//
//     xhr.onload = function () {
//         if (xhr.status == 200) {
//             console.log('Conversion successful:', xhr.responseText);
//         } else {
//             console.error('Conversion failed:', xhr.statusText);
//         }
//     };
//
//     xhr.onerror = function () {
//         console.error('Network error during conversion');
//     };
//
//     xhr.send(formData);
// }

// document.getElementById("conversionForm").addEventListener("submit", function (event) {
//     event.preventDefault(); // Prevent the default form submission behavior
//
//     // Your additional logic here, if needed
//
//     // Optionally, you can use AJAX to handle the form submission without navigating away from the page
//     // For example:
//     var formData = new FormData(document.getElementById("conversionForm"));
//     fetch("sender.php", {
//         method: "POST",
//         body: formData
//     })
//     .then(response => response.text())
//     .then(data => console.log(data))
//     .catch(error => console.error(error));
// });

// var retForm = document.getElementById("conversionForm");
// var retOp = document.getElementById("retOp");
// retForm.addEventListener("submit", function (event) {
//     event.preventDefault(); // Prevent the default form submission behavior
//
//     // Your additional logic here, if needed
//
//     // Create a FormData object from the form
//     var formData = new FormData(retForm);
//
//     // Make a fetch request to sender.php
//     fetch("sender.php", {
//         method: "POST",
//         body: formData,
//     })
//         .then(response => response.text())
//         .then(data => {
//             // Handle the response data, if needed
//             console.log(data);
//
//             // Assuming "data" contains information about the conversion status
//             // You can update the UI or display a success message accordingly
//             retForm.style.display = "none";
//             retOp.innerHTML = "<b>Form submit successful</b>";
//         })
//         .catch(error => {
//             // Handle errors, if any
//             console.error(error);
//
//             // Display an error message in the UI
//             retOp.innerHTML = "<b>Error occurred during form submission</b>";
//         });
// });

// function submitConversionForm(event) {
//     retForm.style.display = "none";
//     retOp.innerHTML = "<b>Form submit successful</b>";
// }

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

// function submitConversionForm(event) {
//     event.preventDefault(); // Prevent the default form submission behavior
//
//     // Your additional logic here, if needed
//
//     // Optionally, you can use AJAX to handle the form submission without navigating away from the page
//     // For example:
//     var formData = new FormData(document.getElementById("conversionForm"));
//     fetch("sender.php", {
//         method: "POST",
//         body: formData
//     })
//     .then(response => response.text())
//     .then(data => console.log(data))
//     .catch(error => console.error(error));
// }

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