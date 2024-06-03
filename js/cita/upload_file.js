var data_citados = [];

const form = document.querySelector("form");
const dropArea = document.querySelector(".drag-area");
fileInput = document.querySelector(".file-input"),
    progressArea = document.querySelector(".progress-area"),
    uploadedArea = document.querySelector(".uploaded-area");
//const allowed_EXT = /(\.jpg|\.jpeg|\.bmp|\.gif|\.png|\.zip|\.rar|\.tar|\.txt|\.mp4|\.mp3|\.7z|\.doc|\.docx|\.xls)$/i;
const allowed_EXT = /(\.csv)$/i;

const files_name_upload = [];

dragForm = document.getElementById('drag-form');
dragText = document.getElementById('drag_text');
dragCloud = document.getElementById('drag-cloud');
dragInput = document.getElementById('file-input');
dragZone = document.getElementById('drag-area');
dragWarper = document.getElementById('drag-warper');

function showToast(s, c) {
    console.log('test');
    var x = document.getElementById("snackbar");
    var text = document.createTextNode(s);
    x.style.backgroundColor = c;
    x.textContent = '';
    x.appendChild(text);
    x.className = "show";
    setTimeout(function () { x.className = x.className.replace("show", ""); }, 3000);
}


// form click event
form.addEventListener("click", () => {
    fileInput.click();
});

fileInput.onchange = ({ target }) => {
    // Check for how much files
    let file = target.files;
    if (file.length === 1) {
        // let fileName = file[0].name;
        if (!allowed_EXT.exec(file[0].name)) {
            showToast('Por razones de seguridad, esta extensión está prohibida.', 'red');
        }
        else {
            if (!files_name_upload.includes(file[0].name)) {
                files_name_upload.push(file[0].name);
                uploadFile(file[0].name);
            }
        }
    } else {
        showToast('Por razones de seguridad, la carga de múltiples archivos está prohibida.', 'blue');
    }
}

dropArea.addEventListener("dragover", (event) => {
    event.preventDefault();
    dragText.textContent = "Suelta para cargar el archivo.";
    dragCloud.style.color = "#a366ff";
    dragForm.style.borderColor = "#a366ff";
    // dragWarper.style.width = "550px";
    dragText.style.fontSize = "24px";
    dragText.style.color = "#a366ff";
});


dropArea.addEventListener("dragleave", () => {
    dragText.textContent = "Haz clic o arrastra y suelta el archivo para cargarlo.";
    dragCloud.style.color = "#6990F2";
    dragForm.style.borderColor = "#6990F2";
    // dragWarper.style.width = "485px";
    dragText.style.fontSize = "18px";
    dragText.style.color = "#6990F2";

});


//If user drop File on DropArea
dropArea.addEventListener("drop", (event) => {
    event.preventDefault();
    var all_drop_files = event.dataTransfer.files;

    
    if (all_drop_files.length === 1) {
        if (!allowed_EXT.exec(all_drop_files[0].name)) {
            showToast('Por razones de seguridad, esta extensión está prohibida.', 'red');
        }
        else {
            if (!files_name_upload.includes(all_drop_files[0].name)) {
                files_name_upload.push(all_drop_files[0].name);
                drop_Upload(all_drop_files[0]);
            }
        }
    } else {
        showToast('Por razones de seguridad, la carga de múltiples archivos está prohibida.', 'blue');
    }
    dragText.textContent = "Haz clic o arrastra y suelta el archivo para subirlo.";
    dragCloud.style.color = "#6990F2";
    dragForm.style.borderColor = "#6990F2";
    // dragWarper.style.width = "485px";
    dragText.style.fontSize = "18px";
    dragText.style.color = "#6990F2";
});


async function drop_Upload(drop_files) {
    let form_data = new FormData();
    form_data.append("file-csv", drop_files);

    try {
        const response = await axios.post('../ajax/upload_csv.php?op=get_data_citados', form_data, {
            onUploadProgress: ({ loaded, total }) => {
                let fileLoaded = Math.floor((loaded / total) * 100);
                let fileTotal = Math.floor(total / 1000);
                let fileSize;
                (fileTotal < 1024) ? fileSize = fileTotal + " KB" : fileSize = (loaded / (1024 * 1024)).toFixed(2) + " MB";
                let progressHTML = `<li class="row">
                                    <i class="fas fa-file-alt"></i>
                                    <div class="content">
                                        <div class="details">
                                            <span class="name">${drop_files.name} • subiendo...</span>
                                            <span class="percent">${fileLoaded}%</span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress" style="width: ${fileLoaded}%"></div>
                                        </div>
                                    </div>
                                </li>`;
                uploadedArea.classList.add("onprogress");
                progressArea.innerHTML = progressHTML;

                if (loaded === total) {
                    progressArea.innerHTML = "";
                    let uploadedHTML = `<li class="row">
                                        <div class="content upload col-md-8">
                                            <i class="fas fa-file-alt"></i>
                                            <div class="details">
                                                <span class="name">${drop_files.name} • cargado</span>
                                                <span class="size">${fileSize}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex justify-content-end">
                                            <i class="fas fa-check mr-3" style="cursor:pointer" onclick="procesarCSV(this)"></i>
                                            <i class="fas fa-times" style="cursor:pointer" onclick="removeItemFile(this)"></i>
                                        </div>
                                    </li>`;
                    uploadedArea.classList.remove("onprogress");
                    uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML);
                }
            },
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        console.log(response);
        data_citados = response.data;
        showItemsCSV();
    } catch (error) {
        console.error('Error uploading file:', error);
    }
}

function uploadFile(name) {
    const data = new FormData(form);

    axios.post('../ajax/upload_csv.php?op=get_data_citados', data, {
        onUploadProgress: function (progressEvent) {
            const loaded = progressEvent.loaded;
            const total = progressEvent.total;
            let fileLoaded = Math.floor((loaded / total) * 100);
            let fileTotal = Math.floor(total / 1000);
            let fileSize;
            (fileTotal < 1024) ? fileSize = fileTotal + " KB" : fileSize = (loaded / (1024 * 1024)).toFixed(2) + " MB";

            let progressHTML = `<li class="row">
                              <i class="fas fa-file-alt"></i>
                              <div class="content">
                                <div class="details">
                                  <span class="name">${name} • subiendo...</span>
                                  <span class="percent">${fileLoaded}%</span>
                                </div>
                                <div class="progress-bar">
                                  <div class="progress" style="width: ${fileLoaded}%"></div>
                                </div>
                              </div>
                            </li>`;
            uploadedArea.classList.add("onprogress");
            progressArea.innerHTML = progressHTML;
            if (loaded === total) {
                progressArea.innerHTML = "";
                let uploadedHTML = `<li class="row">
                                <div class="content upload">
                                  <i class="fas fa-file-alt"></i>
                                  <div class="details">
                                    <span class="name">${name} • cargado</span>
                                    <span class="size">${fileSize}</span>
                                  </div>
                                </div>
                                <i class="fas fa-check"></i>
                              </li>`;
                uploadedArea.classList.remove("onprogress");
                uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML);
            }
        },
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            // Handle the response from the server if needed
            data_citados = response.data;
            showItemsCSV();
        })
        .catch(error => {
            // Handle error if needed
            console.error(error);
        });
}

function showItemsCSV() {
    let rows_content_table = document.getElementById('body-table-content');
    rows_content_table.innerHTML = '';
    if(data_citados.length === 0){
        return;
    }
    let data = data_citados;

    data.forEach(element => {
        let row = `
            <tr>
                <td>${element.contador}</td>
                <td>${element.id}</td>
                <td>${element.paciente}</td>
                <td>${element.dui}</td>
                <td>${element.edad}</td>
                <td>${element.telefono}</td>
                <td>${element.genero}</td>
                <td>${element.ocupacion}</td>
                <td>${element.departamento}</td>
                <td>${element.municipio}</td>
                <td>${element.tipo_paciente}</td>
                <td>${element.fecha}</td>
                <td>${element.hora}</td>
                <td>${element.telefono2}</td>
                <td>${element.institucion}</td>
                <td>${element.sucursal}</td>
                <td>${element.sector}</td>
            </tr>
        `;
        rows_content_table.innerHTML += row;
    });
}


function removeItemFile(element){
    data_citados = [];
    form.reset();
    uploadedArea.innerHTML = '';
    showItemsCSV();
}

function procesarCSV(){
    if(data_citados.length > 0){
        let citados_form = new FormData();
        citados_form.append('data', JSON.stringify(data_citados));
        axios.post('../ajax/upload_csv.php?op=procesar_csv', citados_form)
        .then((result) => {
            if(result.data.status === "success"){
                data_citados = [];
                uploadedArea.innerHTML = '';
                showItemsCSV();
                Swal.fire({
                    title: "Éxito",
                    text: "Los datos han sido importados exitosamente.",
                    icon: "success"
                  });
            }else{
                Swal.fire({
                    title: "Error",
                    text: "Ha ocurrido un error al importar los datos.",
                    icon: "error"
                  });
            }
            console.log(result);
        }).catch((err) => {
            console.log(err);
        });
    }else{
        console.log('Error sin datos');
    }
}