const Toast = Swal.mixin({
    toast: true,
    position: "center",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    }
});

var data_citados = [];

const form = document.querySelector("form");
const dropArea = document.querySelector(".drag-area");
fileInput = document.querySelector(".file-input"),
    progressArea = document.querySelector(".progress-area"),
    uploadedArea = document.querySelector(".uploaded-area");
//const allowed_EXT = /(\.jpg|\.jpeg|\.bmp|\.gif|\.png|\.zip|\.rar|\.tar|\.txt|\.mp4|\.mp3|\.7z|\.doc|\.docx|\.xls)$/i;
const allowed_EXT = /(\.csv)$/i;

var files_name_upload = [];

dragForm = document.getElementById('drag-form');
dragText = document.getElementById('drag_text');
dragCloud = document.getElementById('drag-cloud');
dragInput = document.getElementById('file-input');
dragZone = document.getElementById('drag-area');
dragWarper = document.getElementById('drag-warper');

function showToast(s, c) {
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
            Toast.fire({
                icon: "warning",
                title: "Por razones de seguridad, esta extensión está prohibida."
              });
        }
        else {
            if (!files_name_upload.includes(file[0].name) && files_name_upload.length === 0) {
                files_name_upload.push(file[0].name);
                uploadFile(file[0].name);
            }else{
                Toast.fire({
                    icon: "warning",
                    title: "La carga de múltiples archivos no está permitida."
                  });
            }
        }
    } else {
        Toast.fire({
            icon: "warning",
            title: "Por razones de seguridad, la carga de múltiples archivos está prohibida."
          });
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
            Toast.fire({
                icon: "warning",
                title: "Por razones de seguridad, esta extensión está prohibida."
              });
        }else {
            if (!files_name_upload.includes(all_drop_files[0].name) && files_name_upload.length === 0) {
                files_name_upload.push(all_drop_files[0].name);
                drop_Upload(all_drop_files[0]);
            }else{
                Toast.fire({
                    icon: "warning",
                    title: "La carga de múltiples archivos no está permitida."
                  });
            }
        }
    } else {
        Toast.fire({
            icon: "warning",
            title: "Por razones de seguridad, la carga de múltiples archivos está prohibida."
          });
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
                                    <i class="fas fa-file-alt" style="font-size: 25px;"></i>
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
                                            <i class="fas fa-file-alt" style="font-size: 25px;"></i>
                                            <div class="details">
                                                <span class="name">${drop_files.name} • cargado</span>
                                                <span class="size">${fileSize}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex justify-content-end">
                                            <i title="Importar datos del csv" class="fas fa-upload mr-4" style="cursor:pointer;font-size:22px" onclick="procesarCSV(this)"></i>
                                            <i title="Remover csv" class="fas fa-times" style="cursor:pointer;font-size:22px" onclick="removeItemFile(this)"></i>
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

        if(response.data.status === "success"){
            data_citados = response.data.result;
            showItemsCSV();
        }else{
            Swal.fire({
                title: "Error",
                text: "Ha ocurrido un error al importar los datos.",
                icon: "error"
            });
        }
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
                                <div class="content upload col-md-8">
                                  <i class="fas fa-file-alt" style="font-size: 25px;"></i>
                                  <div class="details">
                                    <span class="name">${name} • cargado</span>
                                    <span class="size">${fileSize}</span>
                                  </div>
                                </div>
                                <div class="col-md-4 d-flex justify-content-end">
                                    <i title="Importar datos del csv" class="fas fa-upload mr-4" style="cursor:pointer;font-size:22px" onclick="procesarCSV(this)"></i>
                                    <i title="Remover csv" class="fas fa-times" style="cursor:pointer;font-size:22px" onclick="removeItemFile(this)"></i>
                                </div>
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
            if(response.data.status === "success"){
                data_citados = response.data.result;
                showItemsCSV();
            }else{
                Swal.fire({
                    title: "Error",
                    text: "Ha ocurrido un error al importar los datos.",
                    icon: "error"
                });
            }
        })
        .catch(error => {
            // Handle error if needed
            console.error(error);
        });
}

function showItemsCSV() {
    let rows_content_table = document.getElementById('contador_citados');
    rows_content_table.innerHTML = '';
    if(data_citados.length === 0){
        return;
    }
    let contador = data_citados.length;
    rows_content_table.innerHTML = `
        <div class="col-lg-12 col-12">

            <div class="small-box bg-info">
                <div class="inner">
                <h3>${contador}<sup style="font-size: 20px"></sup></h3>
                <p>Cantidad de citados</p>
                </div>
            <div class="icon">
            <i class="fas fa-users"></i>
        </div>
    `
    let data = data_citados;
    /* data.forEach(element => {
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
    }); */
}


function removeItemFile(element){
    data_citados = [];
    form.reset();
    uploadedArea.innerHTML = '';
    showItemsCSV();
    //vacias array files
    files_name_upload = [];
}

function procesarCSV(){
    if(data_citados.length > 0){
        Swal.fire({
            title: "¿Estás seguro de importar estos datos?",
            text: "Esta acción permitirá agregar datos a citados.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, importar!",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                let citados_form = new FormData();
                citados_form.append('data', JSON.stringify(data_citados));
                //Loader
                let loader = document.getElementById('loader_upload_file');
                loader.classList.add('show');

                axios.post('../ajax/upload_csv.php?op=procesar_csv', citados_form)
                .then((result) => {
                    if(result.data.status === "success"){
                        loader.classList.remove('show');
                        //vacias array files
                        files_name_upload = [];
                        data_citados = [];
                        
                        uploadedArea.innerHTML = '';
                        showItemsCSV();
                        $("#dt_citados_csv").DataTable().ajax.reload(null,false);

                        Swal.fire({
                            title: "<strong>Datos importados exitosamente</strong>",
                            icon: "success",
                            html: `
                                <div>
                                    <span class="text-success"><i class="fas fa-check"></i> Cantidad ingresada: ${result.data.result.cont_insertados}</span></p>
                                    <p><span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Cantidad existente: ${result.data.result.cont_existe}</span>
                                </div>
                            `,
                            showCloseButton: true,
                            focusConfirm: false,
                            confirmButtonText: '<i class="fas fa-check-circle"></i> Aceptar',
                            customClass: {
                                popup: 'animated fadeInDown'
                            }
                        });
                    }else{
                        loader.classList.remove('show');
                        Swal.fire({
                            title: "Error",
                            text: "Ha ocurrido un error al importar los datos.",
                            icon: "error"
                        });
                    }
                }).catch((err) => {
                    console.log(err);
                });
            }
        });
    }else{
        Swal.fire({
            title: "Error",
            text: "Sin datos.",
            icon: "error"
        });
    }
}