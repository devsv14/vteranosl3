try {
    const btnImportCSV = document.getElementById('btnImportCSV');
    if (btnImportCSV) {
        btnImportCSV.addEventListener('click', (e) => {
            $("#modal-import-csv").modal('show');
        })
    }
} catch (err) {
    console.log(err)
}
